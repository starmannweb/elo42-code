<?php

$sessionPath = env('SESSION_PATH', BASE_PATH . '/storage/sessions');

if (is_string($sessionPath) && $sessionPath !== '') {
    $isAbsoluteWindows = preg_match('/^[A-Za-z]:[\\\\\\/]/', $sessionPath) === 1;
    $isAbsoluteUnix = str_starts_with($sessionPath, DIRECTORY_SEPARATOR);

    if (!$isAbsoluteWindows && !$isAbsoluteUnix) {
        $normalized = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $sessionPath);
        $sessionPath = BASE_PATH . DIRECTORY_SEPARATOR . ltrim($normalized, DIRECTORY_SEPARATOR);
    }
}

return [
    'name'      => env('SESSION_NAME', 'elo42_session'),
    'lifetime'  => (int) env('SESSION_LIFETIME', 120),
    'secure'    => (bool) env('SESSION_SECURE', false),
    'httponly'  => (bool) env('SESSION_HTTPONLY', true),
    'path'      => $sessionPath,
];
