<?php

namespace App\Services;

use App\Requests\CreateTransactionRequest;
use App\Requests\GetTransactionStatusRequest;
use App\Requests\UpdateTransactionRequest;
use App\Responses\CreateTransactionResponse;
use App\Responses\TransactionStatusResponse;

interface TransactionServiceInterface
{
    public function createTransaction(CreateTransactionRequest $request): CreateTransactionResponse;

    public function getTransactionStatus(GetTransactionStatusRequest $request): TransactionStatusResponse;
    public function updateTransactionStatus(UpdateTransactionRequest $request): TransactionStatusResponse;
}