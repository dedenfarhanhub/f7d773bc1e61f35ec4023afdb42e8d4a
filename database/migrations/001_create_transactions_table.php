<?php


class CreateTransactionsTable
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function up(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS transactions (
                id BIGINT PRIMARY KEY AUTO_INCREMENT,
                invoice_id VARCHAR(255) NOT NULL,
                merchant_id VARCHAR(255) NOT NULL,
                references_id VARCHAR(255) UNIQUE,
                amount DECIMAL(15, 2) NOT NULL,
                item_name VARCHAR(255) NOT NULL,
                customer_name VARCHAR(255) NOT NULL,
                number_va VARCHAR(50),
                payment_type TINYINT NOT NULL,
                status TINYINT NOT NULL DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );

            CREATE INDEX idx_transactions_invoice_id ON transactions (invoice_id);
            CREATE INDEX idx_transactions_merchant_id ON transactions (merchant_id);
            CREATE INDEX idx_transactions_references_id ON transactions (references_id);
            CREATE INDEX idx_transactions_payment_type ON transactions (payment_type);
            CREATE INDEX idx_transactions_status ON transactions (status);
        ";

        $this->db->exec($sql);
        echo "Table 'transactions' has been created.\n";
    }
}
