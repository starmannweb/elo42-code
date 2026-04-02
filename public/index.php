<?php

declare(strict_types=1);

// Temporary: enable full error reporting for diagnosis
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('BASE_PATH', dirname(__DIR__));

// Catch fatal errors (parse errors, undefined functions, etc.)
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (str_contains($uri, '/gestao')) {
            if (!headers_sent()) {
                http_response_code(500);
                header('Content-Type: text/html; charset=utf-8');
            }
            echo '<!DOCTYPE html><html><body style="font-family:monospace;padding:2rem;background:#111;color:#0f0">';
            echo '<h2>FATAL ERROR on /gestao</h2>';
            echo '<p><b>Type:</b> ' . $error['type'] . '</p>';
            echo '<p><b>Message:</b> ' . htmlspecialchars($error['message']) . '</p>';
            echo '<p><b>File:</b> ' . htmlspecialchars($error['file']) . ':' . $error['line'] . '</p>';
            echo '</body></html>';
        }
    }
});

// Global exception handler
set_exception_handler(function (\Throwable $e) {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (str_contains($uri, '/gestao')) {
        while (ob_get_level() > 0) { ob_end_clean(); }
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html><body style="font-family:monospace;padding:2rem;background:#111;color:#0f0">';
        echo '<h2>EXCEPTION on /gestao</h2>';
        echo '<p><b>Message:</b> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><b>File:</b> ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        echo '</body></html>';
        exit;
    }
    http_response_code(500);
    echo 'Internal Server Error';
});

require BASE_PATH . '/app/autoload.php';

$app = new App\Core\App();
$app->run();
