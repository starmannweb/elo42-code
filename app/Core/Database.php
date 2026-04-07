<?php

declare(strict_types=1);

namespace App\Core;

class Database
{
    private static ?Database $instance = null;
    private ?\PDO $pdo = null;
    private static bool $connectionFailed = false;

    private function __construct()
    {
        // Support DATABASE_URL (used by many PaaS providers like Render, Heroku, Railway)
        $databaseUrl = env('DATABASE_URL', '');

        if (is_string($databaseUrl) && $databaseUrl !== '') {
            $parsed = parse_url($databaseUrl);
            $host = $parsed['host'] ?? '127.0.0.1';
            $port = $parsed['port'] ?? 3306;
            $database = ltrim($parsed['path'] ?? '/elo42_platform', '/');
            $username = $parsed['user'] ?? 'root';
            $password = $parsed['pass'] ?? '';
            $scheme = $parsed['scheme'] ?? 'mysql';

            // Support both mysql:// and postgres:// URLs
            $driver = match ($scheme) {
                'postgres', 'postgresql' => 'pgsql',
                default => 'mysql',
            };
        } else {
            $host = env('DB_HOST', '127.0.0.1');
            $port = env('DB_PORT', '3306');
            $database = env('DB_DATABASE', 'elo42_platform');
            $username = env('DB_USERNAME', 'root');
            $password = env('DB_PASSWORD', '');
            $driver = env('DB_DRIVER', 'mysql');
        }

        if ($driver === 'pgsql') {
            $dsn = "pgsql:host={$host};port={$port};dbname={$database}";
            $options = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];
        } else {
            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
            $options = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            ];
        }

        try {
            $this->pdo = new \PDO($dsn, (string) $username, (string) $password, $options);
            self::$connectionFailed = false;
        } catch (\PDOException $e) {
            self::$connectionFailed = true;

            // Log to error_log so it shows in container output
            error_log("[DATABASE] Connection failed: " . $e->getMessage());

            throw $e;
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): \PDO
    {
        if ($this->pdo === null) {
            throw new \RuntimeException('Database connection not available.');
        }
        return $this->pdo;
    }

    public static function connection(): \PDO
    {
        return self::getInstance()->getConnection();
    }

    /**
     * Check if database is available without throwing.
     */
    public static function isAvailable(): bool
    {
        try {
            self::connection();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Returns true if the last connection attempt failed.
     */
    public static function hasConnectionFailed(): bool
    {
        return self::$connectionFailed;
    }

    private function __clone() {}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}
