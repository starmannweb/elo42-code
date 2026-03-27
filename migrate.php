<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

define('BASE_PATH', __DIR__);

use Dotenv\Dotenv;

if (file_exists(BASE_PATH . '/.env')) {
    $dotenv = Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();
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
    default    => echo "Usage: php migrate.php [run|rollback|status]\n",
};
