<?php

namespace App\Controllers;

require_once __DIR__ . '/../Exceptions/DuplicateDataException.php';
require_once __DIR__ . '/../Exceptions/RecordNotFoundException.php';
require_once __DIR__ . '/../Requests/CreateTransactionRequest.php';
require_once __DIR__ . '/../Requests/GetTransactionStatusRequest.php';
require_once __DIR__ . '/../Responses/BaseResponse.php';
require_once __DIR__ . '/../Services/TransactionServiceInterface.php';

use App\Exceptions\DuplicateDataException;
use App\Exceptions\RecordNotFoundException;
use App\Requests\CreateTransactionRequest;
use App\Requests\GetTransactionStatusRequest;
use App\Responses\BaseResponse;
use App\Services\TransactionServiceInterface;
use Exception;
use InvalidArgumentException;

class TransactionController
{
    private TransactionServiceInterface $transactionService;

    /**
     * @param TransactionServiceInterface $transactionService
     */
    public function __construct(TransactionServiceInterface $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Create a new transaction.
     *
     * @param array $data Request data.
     * @return BaseResponse
     */
    public function createTransaction(array $data): BaseResponse
    {
        try {
            $request = new CreateTransactionRequest($data);
            $response = $this->transactionService->createTransaction($request);

            return new BaseResponse(
                code: 201,
                message: 'Transaction created successfully',
                errors: [],
                data: $response->toArray()
            );
        } catch (Exception $e) {
            error_log($e->getMessage(), 0);
            if ($e instanceof InvalidArgumentException) {
                return new BaseResponse(422, 'Invalid request parameters', [$e->getMessage()]);
            }
            if ($e instanceof DuplicateDataException) {
                return new BaseResponse(409, $e->getMessage(), [$e->getMessage()]);
            }
            return new BaseResponse(500, 'Transaction creation failed ', [$e->getMessage()]);
        }
    }

    /**
     * Check the status of a transaction.
     *
     * @return BaseResponse
     */
    public function checkTransactionStatus(): BaseResponse
    {
        $referencesId = $_GET['references_id'] ?? null;
        $merchantId = $_GET['merchant_id'] ?? null;
        try {
            $request = new GetTransactionStatusRequest($referencesId, $merchantId);
            $response = $this->transactionService->getTransactionStatus($request);
            return new BaseResponse(
                code: 200,
                message: 'Transaction status retrieved successfully',
                errors: [],
                data: $response->toArray()
            );
        } catch (Exception $e) {
            error_log($e->getMessage(), 0);
            if ($e instanceof InvalidArgumentException) {
                return new BaseResponse(422, 'Invalid request parameters', [$e->getMessage()]);
            }
            if ($e instanceof RecordNotFoundException) {
                return new BaseResponse(
                    code: 404,
                    message: 'Transaction not found',
                    errors: [$e->getMessage()],
                    data: null
                );
            }
            return new BaseResponse(
                code: 500,
                message: 'Transaction status retrieval failed',
                errors: [$e->getMessage()],
                data: null
            );
        }
    }
}