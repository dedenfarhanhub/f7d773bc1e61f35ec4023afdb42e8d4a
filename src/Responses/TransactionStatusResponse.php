<?php

namespace App\Responses;

class TransactionStatusResponse
{
    private string $referenceId;
    private string $invoiceId;
    private string $status;

    public function __construct(string $referenceId, string $invoiceId, string $status)
    {
        $this->referenceId = $referenceId;
        $this->invoiceId = $invoiceId;
        $this->status = $status;
    }

    public function toArray(): array
    {
        return [
            'reference_id' => $this->getReferenceId(),
            'invoice_id' => $this->getInvoiceId(),
            'status' => $this->getStatus(),
        ];
    }

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    public function getInvoiceId(): ?string
    {
        return $this->invoiceId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}