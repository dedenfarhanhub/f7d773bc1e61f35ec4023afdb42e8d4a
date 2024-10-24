<?php

namespace App\Requests;

use InvalidArgumentException;

class GetTransactionStatusRequest
{
    public string $referencesId;
    public string $merchantId;

    public function __construct(string $referencesId, string $merchantId)
    {
        $this->validate($referencesId, $merchantId);

        // Assign values after validation
        $this->referencesId = htmlspecialchars($referencesId, ENT_QUOTES, 'UTF-8');
        $this->merchantId = htmlspecialchars($merchantId, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate input data and throw specific exceptions for each field if invalid.
     *
     * @param string $referencesId
     * @param string $merchantId
     * @throws InvalidArgumentException
     */
    private function validate(string $referencesId, string $merchantId): void
    {
        // Validate references_id
        if (empty($referencesId)) {
            throw new InvalidArgumentException('Field "references_id" is required.');
        }
        // Validate merchant_id
        if (empty($merchantId)) {
            throw new InvalidArgumentException('Field "merchant_id" is required.');
        }
    }
}