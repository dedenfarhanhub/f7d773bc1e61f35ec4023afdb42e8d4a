<?php

namespace App\Repositories;

use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    public function createTransaction(Transaction $transaction): Transaction;

    public function getTransactionByReferenceIdMerchantId(string $referencesId, string $merchantId): ?Transaction;
    public function getTransactionByReferenceId(string $referencesId): ?Transaction;
    public function updateTransactionStatus(string $referencesId, int $status): Transaction;
}