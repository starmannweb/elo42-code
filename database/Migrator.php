<?php

declare(strict_types=1);

namespace Database;

use App\Core\Database;

class Migrator
{
    private \PDO $pdo;
    private string $migrationsPath;
    private string $driver;

    public function __construct()
    {
        $this->pdo = Database::connection();
        $this->driver = (string) $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
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
                $this->executeSql($migration['up']);
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
                $this->executeSql($migration['down']);
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
        if ($this->driver === 'sqlite') {
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS migrations (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    migration TEXT NOT NULL,
                    batch INTEGER NOT NULL,
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP
                )
            ");
            return;
        }

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

    private function executeSql(string $sql): void
    {
        if ($this->driver !== 'sqlite') {
            foreach ($this->splitSqlStatements($sql) as $statement) {
                $statement = $this->translateGenericStatement($statement);
                if ($statement === null || trim($statement) === '') {
                    continue;
                }
                $this->pdo->exec($statement);
            }
            return;
        }

        foreach ($this->splitSqlStatements($this->translateSqliteSql($sql)) as $statement) {
            $statement = $this->translateSqliteStatement($statement);
            if ($statement === null || trim($statement) === '') {
                continue;
            }

            $this->pdo->exec($statement);
        }
    }

    private function translateGenericStatement(string $statement): ?string
    {
        $statement = trim($statement);
        if ($statement === '') {
            return null;
        }

        if (preg_match('/^ALTER\s+TABLE\s+([A-Za-z0-9_]+)\s+ADD\s+COLUMN\s+IF\s+NOT\s+EXISTS\s+([A-Za-z0-9_]+)\s+(.+)$/is', $statement, $matches)) {
            if ($this->genericColumnExists($matches[1], $matches[2])) {
                return null;
            }

            $definition = trim($matches[3]);
            return 'ALTER TABLE ' . $matches[1] . ' ADD COLUMN ' . $matches[2] . ' ' . $definition;
        }

        return $statement;
    }

    private function genericColumnExists(string $table, string $column): bool
    {
        try {
            if ($this->driver === 'pgsql') {
                $stmt = $this->pdo->prepare('SELECT column_name FROM information_schema.columns WHERE table_name = :table AND column_name = :column LIMIT 1');
                $stmt->execute(['table' => $table, 'column' => $column]);
                return (bool) $stmt->fetchColumn();
            }

            $stmt = $this->pdo->prepare('SHOW COLUMNS FROM ' . $table . ' LIKE :column');
            $stmt->execute(['column' => $column]);
            return (bool) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function translateSqliteSql(string $sql): string
    {
        $sql = preg_replace('/^\s*--.*$/m', '', $sql) ?? $sql;
        $sql = preg_replace('/\)\s*ENGINE\s*=\s*InnoDB\s+DEFAULT\s+CHARSET\s*=\s*utf8mb4\s+COLLATE\s*=\s*utf8mb4_unicode_ci/i', ')', $sql);
        $sql = preg_replace('/\s+ON\s+UPDATE\s+CURRENT_TIMESTAMP/i', '', $sql);
        $sql = preg_replace('/\b(BIGINT|INT)\s+UNSIGNED\s+AUTO_INCREMENT\s+PRIMARY\s+KEY\b/i', 'INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
        $sql = preg_replace('/\b(BIGINT|INT)\s+AUTO_INCREMENT\s+PRIMARY\s+KEY\b/i', 'INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
        $sql = preg_replace('/\b(BIGINT|INT)\s+UNSIGNED\b/i', 'INTEGER', $sql);
        $sql = preg_replace('/\bTINYINT\s*\(\s*1\s*\)/i', 'INTEGER', $sql);
        $sql = preg_replace('/\bJSON\b/i', 'TEXT', $sql);
        $sql = preg_replace('/\bENUM\s*\([^)]+\)/i', 'TEXT', $sql);
        $sql = preg_replace('/`([^`]+)`/', '"$1"', $sql);
        $sql = preg_replace('/\bINSERT\s+IGNORE\b/i', 'INSERT OR IGNORE', $sql);
        $sql = $this->translateCreateTableConstraints($sql);

        return $sql;
    }

    private function translateCreateTableConstraints(string $sql): string
    {
        return preg_replace_callback('/CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS\s+([A-Za-z0-9_]+)\s*\((.*?)\)\s*(?=;|$)/is', function (array $matches): string {
            $parts = $this->splitCommaSeparated($matches[2]);
            $kept = [];

            foreach ($parts as $part) {
                $trimmed = trim($part);
                if ($trimmed === '') {
                    continue;
                }

                if (preg_match('/^(INDEX|KEY)\s+/i', $trimmed)) {
                    continue;
                }

                if (preg_match('/^UNIQUE\s+KEY\s+[A-Za-z0-9_]+\s*\((.+)\)$/i', $trimmed, $unique)) {
                    $kept[] = 'UNIQUE (' . $unique[1] . ')';
                    continue;
                }

                $kept[] = $trimmed;
            }

            return 'CREATE TABLE IF NOT EXISTS ' . $matches[1] . " (\n            " . implode(",\n            ", $kept) . "\n        )";
        }, $sql) ?? $sql;
    }

    private function translateSqliteStatement(string $statement): ?string
    {
        $statement = trim($statement);
        if ($statement === '') {
            return null;
        }

        if (preg_match('/^ALTER\s+TABLE\s+([A-Za-z0-9_]+)\s+ADD\s+COLUMN\s+IF\s+NOT\s+EXISTS\s+([A-Za-z0-9_]+)\s+(.+)$/is', $statement, $matches)) {
            if ($this->sqliteColumnExists($matches[1], $matches[2])) {
                return null;
            }

            $definition = preg_replace('/\s+AFTER\s+[A-Za-z0-9_]+/i', '', trim($matches[3]));
            return 'ALTER TABLE ' . $matches[1] . ' ADD COLUMN ' . $matches[2] . ' ' . $definition;
        }

        $statement = preg_replace('/\s+AFTER\s+[A-Za-z0-9_]+/i', '', $statement);

        return $statement;
    }

    private function sqliteColumnExists(string $table, string $column): bool
    {
        $stmt = $this->pdo->query("PRAGMA table_info({$table})");
        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            if (strcasecmp((string) $row['name'], $column) === 0) {
                return true;
            }
        }

        return false;
    }

    private function splitSqlStatements(string $sql): array
    {
        $statements = [];
        $buffer = '';
        $quote = null;
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];
            $buffer .= $char;

            if (($char === "'" || $char === '"') && ($i === 0 || $sql[$i - 1] !== '\\')) {
                $quote = $quote === $char ? null : ($quote ?? $char);
                continue;
            }

            if ($char === ';' && $quote === null) {
                $statements[] = rtrim(substr($buffer, 0, -1));
                $buffer = '';
            }
        }

        if (trim($buffer) !== '') {
            $statements[] = $buffer;
        }

        return $statements;
    }

    private function splitCommaSeparated(string $input): array
    {
        $parts = [];
        $buffer = '';
        $depth = 0;
        $quote = null;
        $length = strlen($input);

        for ($i = 0; $i < $length; $i++) {
            $char = $input[$i];

            if (($char === "'" || $char === '"') && ($i === 0 || $input[$i - 1] !== '\\')) {
                $quote = $quote === $char ? null : ($quote ?? $char);
            } elseif ($quote === null) {
                if ($char === '(') {
                    $depth++;
                } elseif ($char === ')') {
                    $depth--;
                } elseif ($char === ',' && $depth === 0) {
                    $parts[] = $buffer;
                    $buffer = '';
                    continue;
                }
            }

            $buffer .= $char;
        }

        if (trim($buffer) !== '') {
            $parts[] = $buffer;
        }

        return $parts;
    }
}
