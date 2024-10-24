<?php

namespace App\Requests;

use InvalidArgumentException;

class CreateTransactionRequest
{
    private string $invoiceId;
    private string $itemName;
    private float $amount;
    private string $paymentType;
    private string $customerName;
    private string $merchantId;

    public function __construct(array $data)
    {
        $this->validate($data);

        // Assign values after validation
        $this->invoiceId = htmlspecialchars($data['invoice_id'], ENT_QUOTES, 'UTF-8');
        $this->itemName = htmlspecialchars($data['item_name'] ?? '', ENT_QUOTES, 'UTF-8');
        $this->amount = (float)$data['amount'];
        $this->paymentType = $data['payment_type'];
        $this->customerName = htmlspecialchars($data['customer_name'], ENT_QUOTES, 'UTF-8');
        $this->merchantId = htmlspecialchars($data['merchant_id'], ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate input data and throw specific exceptions for each field if invalid.
     *
     * @param array $data
     * @throws InvalidArgumentException
     */
    private function validate(array $data): void
    {
        // Validate invoice_id
        if (empty($data['invoice_id'])) {
            throw new InvalidArgumentException('Field "invoice_id" is required.');
        }

        // Validate amount
        if (!isset($data['amount'])) {
            throw new InvalidArgumentException('Field "amount" is required.');
        }
        if (!is_numeric($data['amount']) || $data['amount'] <= 0) {
            throw new InvalidArgumentException('Field "amount" must be a positive number.');
        }

        // Validate payment_type
        if (!isset($data['payment_type'])) {
            throw new InvalidArgumentException('Field "payment_type" is required.');
        }
        if (!in_array($data['payment_type'], ['credit_card', 'virtual_account'], true)) {
            throw new InvalidArgumentException('Field "payment_type" must be either "credit_card" or "virtual_account".');
        }

        // Validate customer_name
        if (empty($data['customer_name'])) {
            throw new InvalidArgumentException('Field "customer_name" is required.');
        }

        // Validate merchant_id
        if (empty($data['merchant_id'])) {
            throw new InvalidArgumentException('Field "merchant_id" is required.');
        }
    }

    public function getInvoiceId(): string
    {
        return $this->invoiceId;
    }

    public function getItemName(): string
    {
        return $this->itemName;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }
}