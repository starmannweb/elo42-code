<?php

declare(strict_types=1);

namespace Database;

use App\Core\Database;

class Migrator
{
    private \PDO $pdo;
    private string $migrationsPath;

    public function __construct()
    {
        $this->pdo = Database::connection();
        $this->migrationsPath = BASE_PATH . '/database/migrations';
        $this->ensureMigrationsTable();
    }

    public function run(): void
    {
        $files = $this->getPendingMigrations();

        if (empty($files)) {
            echo "Nothing to migrate.\n";
            return;
        }

        foreach ($files as $file) {
            echo "Migrating: {$file}...\n";

            $migration = require $this->migrationsPath . '/' . $file;

            try {
                $this->pdo->exec($migration['up']);
                $this->logMigration($file);
                echo "Migrated:  {$file}\n";
            } catch (\PDOException $e) {
                echo "Error in {$file}: " . $e->getMessage() . "\n";
                exit(1);
            }
        }

        echo "\nAll migrations completed.\n";
    }

    public function rollback(): void
    {
        $last = $this->getLastBatch();

        if (empty($last)) {
            echo "Nothing to rollback.\n";
            return;
        }

        foreach (array_reverse($last) as $file) {
            echo "Rolling back: {$file}...\n";

            $migration = require $this->migrationsPath . '/' . $file;

            try {
                $this->pdo->exec($migration['down']);
                $this->removeMigration($file);
                echo "Rolled back: {$file}\n";
            } catch (\PDOException $e) {
                echo "Error rolling back {$file}: " . $e->getMessage() . "\n";
                exit(1);
            }
        }
    }

    public function status(): void
    {
        $ran = $this->getRanMigrations();
        $all = $this->getAllMigrationFiles();

        echo str_pad("Migration", 50) . "Status\n";
        echo str_repeat('-', 60) . "\n";

        foreach ($all as $file) {
            $status = in_array($file, $ran) ? 'Ran' : 'Pending';
            echo str_pad($file, 50) . $status . "\n";
        }
    }

    private function ensureMigrationsTable(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    private function getPendingMigrations(): array
    {
        $ran = $this->getRanMigrations();
        $all = $this->getAllMigrationFiles();

        return array_diff($all, $ran);
    }

    private function getRanMigrations(): array
    {
        $stmt = $this->pdo->query("SELECT migration FROM migrations ORDER BY id");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function getAllMigrationFiles(): array
    {
        $files = glob($this->migrationsPath . '/*.php');
        return array_map('basename', $files);
    }

    private function getNextBatch(): int
    {
        $stmt = $this->pdo->query("SELECT MAX(batch) FROM migrations");
        return ((int) $stmt->fetchColumn()) + 1;
    }

    private function getLastBatch(): array
    {
        $stmt = $this->pdo->query("SELECT migration FROM migrations WHERE batch = (SELECT MAX(batch) FROM migrations) ORDER BY id");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function logMigration(string $file): void
    {
        $batch = $this->getNextBatch();
        $stmt = $this->pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (:migration, :batch)");
        $stmt->execute(['migration' => $file, 'batch' => $batch]);
    }

    private function removeMigration(string $file): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM migrations WHERE migration = :migration");
        $stmt->execute(['migration' => $file]);
    }
}
