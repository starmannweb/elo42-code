<?php

declare(strict_types=1);

define('BASE_PATH', __DIR__);

require BASE_PATH . '/app/autoload.php';

// Load .env
$envFile = BASE_PATH . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"\'");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            putenv("{$key}={$value}");
        }
    }
}

$configPath = BASE_PATH . '/config';
foreach (glob($configPath . '/*.php') as $file) {
    $key = basename($file, '.php');
    $GLOBALS['__config'][$key] = require $file;
}

$migrator = new \Database\Migrator();

$action = $argv[1] ?? 'run';

match ($action) {
    'run'      => $migrator->run(),
    'rollback' => $migrator->rollback(),
    'status'   => $migrator->status(),
    default    => print("Usage: php migrate.php [run|rollback|status]\n"),
};
