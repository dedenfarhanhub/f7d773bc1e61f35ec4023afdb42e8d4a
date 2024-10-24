<?php

namespace App\Models;

class Transaction
{
    public int $id;
    public string $invoiceId;
    public string $itemName;
    public float $amount;
    public int $paymentType;
    public string $customerName;
    public string $merchantId;
    public int $status;
    public ?string $referencesId;
    public ?string $numberVa;

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

    public function getPaymentType(): int
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

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getReferencesId(): ?string
    {
        return $this->referencesId;
    }

    public function setReferencesId(string $referencesId): void
    {
        $this->referencesId = $referencesId;
    }

    public function getNumberVa(): ?string
    {
        return $this->numberVa;
    }

    public function setInvoiceId(string $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    public function setItemName(string $itemName): void
    {
        $this->itemName = $itemName;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function setPaymentType(int $paymentType): void
    {
        $this->paymentType = $paymentType;
    }

    public function setCustomerName(string $customerName): void
    {
        $this->customerName = $customerName;
    }

    public function setMerchantId(string $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function setNumberVa(?string $numberVa): void
    {
        $this->numberVa = $numberVa;
    }
}
