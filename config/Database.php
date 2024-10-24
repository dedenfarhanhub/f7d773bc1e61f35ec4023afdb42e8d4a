<?php

require_once __DIR__ . '/env.php';

class Database
{
    private ?PDO $conn = null;
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    private int $port;

    public function __construct()
    {
        $this->host = getEnvVar('DB_HOST');
        $this->db_name = getEnvVar('DB_NAME');
        $this->username = getEnvVar('DB_USER');
        $this->password = getEnvVar('DB_PASSWORD');
        $this->port = (int)getEnvVar('DB_PORT');
    }

    /**
     * @throws Exception
     */
    public function getConnection(): ?PDO
    {
        if ($this->conn === null) {
            try {
                $dsn = sprintf("mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4", $this->host, $this->port, $this->db_name);
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (PDOException $exception) {
                throw new Exception("Database connection failed: " . $exception->getMessage());
            }
        }
        return $this->conn;
    }
}
