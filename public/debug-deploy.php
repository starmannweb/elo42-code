<?php
// Temporary file to verify Render deployment status
echo json_encode([
    'deployed_at' => '2025-04-02T11:40:00-03:00',
    'commit' => 'latest',
    'php_version' => PHP_VERSION,
    'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'unknown',
]);
