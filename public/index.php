<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../src/Controllers/TransactionController.php';
require_once __DIR__ . '/../src/Repositories/TransactionRepositoryImpl.php';
require_once __DIR__ . '/../src/Services/TransactionServiceImpl.php';

use App\Controllers\TransactionController;
use App\Repositories\TransactionRepositoryImpl;
use App\Services\TransactionServiceImpl;

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nos niff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

try {
    $database = new Database();
    $dbConnection = $database->getConnection();

    // Dependency Injection
    $transactionRepository = new TransactionRepositoryImpl($dbConnection);
    $transactionService = new TransactionServiceImpl($transactionRepository);
    $transactionController = new TransactionController($transactionService);

    // Routing
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    switch ($requestUri) {
        case '/api/transactions/status':
            if ($requestMethod === 'GET') {
                $response = $transactionController->checkTransactionStatus();
                http_response_code($response->code);
                echo json_encode($response);
            } else {
                http_response_code(405); // Method Not Allowed
                echo json_encode(['message' => 'Method Not Allowed']);
            }
            break;
        case '/api/transactions':
            if ($requestMethod === 'POST') {
                $requestBody = json_decode(file_get_contents("php://input"), true);
                $response = $transactionController->createTransaction($requestBody);
                http_response_code($response->code);
                echo json_encode($response);
            } else {
                http_response_code(405); // Method Not Allowed
                echo json_encode(['message' => 'Method Not Allowed']);
            }
            break;
        default:
            http_response_code(404); // Not Found
            echo json_encode(['message' => 'Not Found']);
            break;
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['message' => 'Internal Server Error']);
    exit();
}
