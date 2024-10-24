<?php

namespace App\Services;

require_once __DIR__ . '/../Exceptions/RecordNotFoundException.php';
require_once __DIR__ . '/TransactionServiceInterface.php';
require_once __DIR__ . '/../Models/Transaction.php';
require_once __DIR__ . '/../Models/PaymentStatus.php';
require_once __DIR__ . '/../Models/PaymentType.php';
require_once __DIR__ . '/../Repositories/TransactionRepositoryInterface.php';
require_once __DIR__ . '/../Requests/CreateTransactionRequest.php';
require_once __DIR__ . '/../Requests/GetTransactionStatusRequest.php';
require_once __DIR__ . '/../Responses/CreateTransactionResponse.php';
require_once __DIR__ . '/../Responses/TransactionStatusResponse.php';

use App\Exceptions\RecordNotFoundException;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Models\Transaction;
use App\Repositories\TransactionRepositoryInterface;
use App\Requests\CreateTransactionRequest;
use App\Requests\GetTransactionStatusRequest;
use App\Requests\UpdateTransactionRequest;
use App\Responses\CreateTransactionResponse;
use App\Responses\TransactionStatusResponse;
use Exception;
use Random\RandomException;

class TransactionServiceImpl implements TransactionServiceInterface
{
    private TransactionRepositoryInterface $transactionRepository;

    /**
     * @param TransactionRepositoryInterface $transactionRepository
     */
    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @throws Exception
     */
    #[\Override] public function createTransaction(CreateTransactionRequest $request): CreateTransactionResponse
    {
        try {
            $transaction = $this->toTransactionModel($request);
            $createdTransaction = $this->transactionRepository->createTransaction($transaction);
            return new CreateTransactionResponse(
                $createdTransaction->getReferencesId(),
                $createdTransaction->getNumberVa(),
                PaymentStatus::toString($createdTransaction->getStatus())
            );
        } catch (\PDOException $e) {
            error_log($e->getMessage(), 0);
            throw new \PDOException('Error creating transaction');
        } catch (RandomException $e) {
            throw new \Exception('Failed generate random references_id');
        }
    }

    /**
     * Convert CreateTransactionRequest to Transaction model.
     *
     * @param CreateTransactionRequest $request
     * @return Transaction
     * @throws RandomException
     */
    private function toTransactionModel(CreateTransactionRequest $request): Transaction
    {
        // Generate references_id and number_va if needed
        $referencesId = $this->generateReferencesId();
        $numberVa = null;
        $paymentType = PaymentType::fromString($request->getPaymentType());

        if ($paymentType === PaymentType::VIRTUAL_ACCOUNT) {
            $numberVa = $this->generateVirtualAccountNumber();
        }

        $transaction = new Transaction();
        $transaction->invoiceId = $request->getInvoiceId();
        $transaction->merchantId = $request->getMerchantId();
        $transaction->referencesId = $referencesId;
        $transaction->amount = $request->getAmount();
        $transaction->itemName = $request->getItemName();
        $transaction->customerName = $request->getCustomerName();
        $transaction->numberVa = $numberVa;
        $transaction->paymentType = $paymentType;
        $transaction->status = PaymentStatus::PENDING;
        return $transaction;
    }

    /**
     * Generate a unique references_id.
     *
     * @return string
     * @throws RandomException
     */
    private function generateReferencesId(): string
    {
        return "REF_" . bin2hex(random_bytes(8));
    }

    /**
     * Generate a virtual account number.
     *
     * @return string
     */
    private function generateVirtualAccountNumber(): string
    {
        return (string)rand(1000000000, 9999999999);
    }

    /**
     * @throws Exception
     */
    #[\Override] public function getTransactionStatus(GetTransactionStatusRequest $request): TransactionStatusResponse
    {
        try {
            $transaction = $this->transactionRepository->getTransactionByReferenceIdMerchantId($request->referencesId, $request->merchantId);
            if (!$transaction) {
                throw new RecordNotFoundException('Transaction not found.');
            }

            return new TransactionStatusResponse(
                $transaction->getReferencesId(),
                $transaction->getInvoiceId(),
                PaymentStatus::toString($transaction->getStatus())
            );
        } catch (RecordNotFoundException $e) {
            throw new RecordNotFoundException($e->getMessage());
        } catch (\PDOException $e) {
            error_log($e->getMessage(), 0);
            throw new \PDOException('Error retrieving transaction status');
        }
    }

    /**
     * @throws RecordNotFoundException
     */
    #[\Override] public function updateTransactionStatus(UpdateTransactionRequest $request): TransactionStatusResponse
    {
        try {
            $transaction = $this->transactionRepository->getTransactionByReferenceId($request->referencesId);
            if (!$transaction) {
                throw new RecordNotFoundException('Transaction not found.');
            }

            $updatedTransaction = $this->transactionRepository->updateTransactionStatus($request->referencesId,
                PaymentStatus::fromString($request->getStatus()));
            return new TransactionStatusResponse(
                $updatedTransaction->getReferencesId(),
                $updatedTransaction->getInvoiceId(),
                PaymentStatus::toString($updatedTransaction->getStatus())
            );
        } catch (RecordNotFoundException $e) {
            throw new RecordNotFoundException($e->getMessage());
        } catch (\PDOException $e) {
            error_log($e->getMessage(), 0);
            throw new \PDOException('Error retrieving transaction status');
        }
    }
}