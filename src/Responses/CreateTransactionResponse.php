<?php

namespace App\Responses;

class CreateTransactionResponse
{
    private string $referenceId;
    private ?string $numberVa;
    private string $status;

    public function __construct(string $referenceId, ?string $numberVa, string $status)
    {
        $this->referenceId = $referenceId;
        $this->numberVa = $numberVa;
        $this->status = $status;
    }

    public function toArray(): array
    {
        return [
            'reference_id' => $this->getReferenceId(),
            'number_va' => $this->getNumberVa(),
            'status' => $this->getStatus(),
        ];
    }

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    public function getNumberVa(): ?string
    {
        return $this->numberVa;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}