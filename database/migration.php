<?php

require_once __DIR__ . '/../config/Database.php';

class MigrationManager
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function run(): void
    {
        $this->createMigrationsTable();

        $migrations = $this->getPendingMigrations();
        foreach ($migrations as $migration) {
            require_once __DIR__ . '/migrations/' . $migration;
            $migrationClass = $this->getMigrationClassName($migration);
            (new $migrationClass($this->db))->up();
            $this->recordMigration($migration);
            echo "Migration $migration applied.\n";
        }
    }

    private function createMigrationsTable(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS migrations (
                id INT PRIMARY KEY AUTO_INCREMENT,
                migration VARCHAR(255) NOT NULL,
                applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ";
        $this->db->exec($sql);
    }

    private function getPendingMigrations(): array
    {
        $appliedMigrations = $this->db->query("SELECT migration FROM migrations")->fetchAll(PDO::FETCH_COLUMN);
        $files = array_diff(scandir(__DIR__ . '/migrations'), ['.', '..']);
        return array_diff($files, $appliedMigrations);
    }

    private function getMigrationClassName(string $migration): string
    {
        $className = pathinfo($migration, PATHINFO_FILENAME);
        $className = preg_replace('/^\d+_/', '', $className);
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $className)));
    }

    private function recordMigration(string $migration): void
    {
        $stmt = $this->db->prepare("INSERT INTO migrations (migration) VALUES (:migration)");
        $stmt->execute(['migration' => $migration]);
    }
}

try {
    $database = new Database();
    $dbConnection = $database->getConnection();

    if ($dbConnection) {
        $migrationManager = new MigrationManager($dbConnection);
        $migrationManager->run();
    }
} catch (PDOException|Exception $e) {
    echo "Error: " . $e->getMessage();
}
