<?php

namespace App\Requests;

use InvalidArgumentException;

class UpdateTransactionRequest
{
    public string $referencesId;
    public string $status;

    /**
     * @param string $referencesId
     * @param string $status
     */
    public function __construct(string $referencesId, string $status)
    {
        $this->validate($referencesId, $status);

        // Assign values after validation
        $this->referencesId = htmlspecialchars($referencesId, ENT_QUOTES, 'UTF-8');
        $this->status = $status;
    }

    public function getReferencesId(): string
    {
        return $this->referencesId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Validate input data and throw specific exceptions for each field if invalid.
     *
     * @param string $referencesId
     * @param string $status
     * @throws InvalidArgumentException
     */
    private function validate(string $referencesId, string $status): void
    {
        // Validate references_id
        if (empty($referencesId)) {
            throw new InvalidArgumentException('Field "references_id" is required.');
        }
        // Validate status
        if (empty($status)) {
            throw new InvalidArgumentException('Field "status" is required.');
        }
        if (!in_array($status, ['pending', 'paid', 'failed', 'expired'], true)) {
            throw new InvalidArgumentException('Field "status" must be either "pending", "paid", "failed", or "expired".');
        }
    }
}