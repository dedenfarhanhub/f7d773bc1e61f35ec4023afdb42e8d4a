<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Repositories/TransactionRepositoryImpl.php';
require_once __DIR__ . '/../src/Requests/UpdateTransactionRequest.php';
require_once __DIR__ . '/../src/Services/TransactionServiceImpl.php';

use App\Repositories\TransactionRepositoryImpl;
use App\Requests\UpdateTransactionRequest;
use App\Services\TransactionServiceImpl;

$database = new Database();
$dbConnection = $database->getConnection();

if (!$dbConnection) {
    http_response_code(500);
    echo json_encode(['message' => 'Database connection failed']);
    exit;
}

// Dependency Injection
$transactionRepository = new TransactionRepositoryImpl($dbConnection);
$transactionService = new TransactionServiceImpl($transactionRepository);

function displayUsage(): void
{
    echo "Usage: php transaction-cli.php {references_id} {status}\n";
    echo "Status options: pending, paid, failed, expired\n";
}

if ($argc !== 3) {
    echo "Invalid number of arguments.\n";
    displayUsage();
    exit(1);
}

$referenceId = $argv[1];
$status = $argv[2];

try {
    $request = new UpdateTransactionRequest($referenceId, $status);
    $updatedTransaction = $transactionService->updateTransactionStatus($request);
    echo "Transaction updated: " . json_encode($updatedTransaction->toArray());
} catch (Exception $e) {
    echo "Error updating transaction status: " . $e->getMessage() . "\n";
}