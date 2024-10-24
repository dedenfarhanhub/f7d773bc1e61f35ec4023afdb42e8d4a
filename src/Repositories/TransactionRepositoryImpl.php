<?php

namespace App\Repositories;

require_once __DIR__ . '/../Exceptions/DuplicateDataException.php';
require_once __DIR__ . '/TransactionRepositoryInterface.php';
require_once __DIR__ . '/../Models/Transaction.php';

use App\Exceptions\DuplicateDataException;
use App\Models\Transaction;
use Exception;

class TransactionRepositoryImpl implements TransactionRepositoryInterface
{

    private \PDO $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @throws Exception
     */
    #[\Override] public function createTransaction(Transaction $transaction): Transaction
    {
        // Start a transaction
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("SELECT 1 as one FROM transactions WHERE invoice_id = :invoiceId FOR UPDATE");
            $stmt->bindValue(':invoiceId', $transaction->getInvoiceId());
            $stmt->execute();
            if ($stmt->fetch()) {
                throw new DuplicateDataException("Invoice ID already exists.");
            }

            // Insert payment
            $stmt = $this->db->prepare("INSERT INTO transactions (invoice_id, references_id, merchant_id,
                        amount, item_name, customer_name, number_va, payment_type, status) 
                        VALUES (:invoiceId, :referencesId, :merchantId, :amount, :itemName, :customerName, :numberVa,
                                :paymentType, :status)");
            $stmt->bindValue(':invoiceId', $transaction->getInvoiceId());
            $stmt->bindValue(':referencesId', $transaction->getReferencesId());
            $stmt->bindValue(':merchantId', $transaction->getMerchantId());
            $stmt->bindValue(':amount', $transaction->getAmount());
            $stmt->bindValue(':itemName', $transaction->getItemName());
            $stmt->bindValue(':customerName', $transaction->getCustomerName());
            $stmt->bindValue(':numberVa', $transaction->getNumberVa());
            $stmt->bindValue(':paymentType', $transaction->getPaymentType());
            $stmt->bindValue(':status', $transaction->getStatus());
            $stmt->execute();

            // Commit the transaction
            $this->db->commit();

            return $transaction;
        } catch (DuplicateDataException $e) {
            $this->db->rollBack();
            throw $e;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new \PDOException('Failed to create transaction.');
        }
    }

    #[\Override] public function getTransactionByReferenceIdMerchantId(string $referencesId, string $merchantId): ?Transaction
    {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE references_id = :references_id AND merchant_id = :merchant_id");

        $stmt->bindValue(':references_id', $referencesId);
        $stmt->bindValue(':merchant_id', $merchantId);

        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            return $this->mapToTransaction($result);
        }

        return null;
    }

    private function mapToTransaction(object $data): Transaction
    {
        $transaction = new Transaction();
        $transaction->setInvoiceId($data->invoice_id);
        $transaction->setMerchantId($data->merchant_id);
        $transaction->setReferencesId($data->references_id);
        $transaction ->setAmount($data->amount);
        $transaction->setItemName($data->item_name);
        $transaction->setCustomerName($data->customer_name);
        $transaction->setNumberVa($data->number_va);
        $transaction->setPaymentType($data->payment_type);
        $transaction->setStatus($data->status);
        return $transaction;
    }

    #[\Override] public function getTransactionByReferenceId(string $referencesId): ?Transaction
    {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE references_id = :references_id");

        $stmt->bindValue(':references_id', $referencesId);

        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            return $this->mapToTransaction($result);
        }

        return null;
    }

    /**
     * @throws Exception
     */
    #[\Override] public function updateTransactionStatus(string $referencesId, int $status): Transaction
    {
        $stmt = $this->db->prepare("UPDATE transactions SET status = :status WHERE references_id = :references_id");
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':references_id', $referencesId);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update the transaction status.");
        }
        return $this->getTransactionByReferenceId($referencesId);
    }
}