<?php

class SeedTransactionsTable
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function up(): void
    {
        // Insert dummy data
        $sql = "
            INSERT INTO transactions (invoice_id, item_name, amount, payment_type, customer_name, merchant_id, status, references_id, number_va) 
            VALUES 
                ('INV001', 'Product A', 100000.00, 1, 'John Doe', 'MERCHANT101', 1, 'REF001', 'VA12345678'),
                ('INV002', 'Product B', 200000.00, 2, 'Jane Doe', 'MERCHANT102', 2, 'REF002', NULL),
                ('INV003', 'Product C', 150000.00, 1, 'John Smith', 'MERCHANT103', 3, 'REF003', 'VA87654321');
        ";

        $this->db->exec($sql);
        echo "Dummy data has been inserted into 'transactions'.\n";
    }
}
