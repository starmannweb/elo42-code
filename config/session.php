<?php

return [
    'name'     => env('SESSION_NAME', 'elo42_session'),
    'lifetime' => (int) env('SESSION_LIFETIME', 120),
    'secure'   => (bool) env('SESSION_SECURE', false),
    'httponly'  => (bool) env('SESSION_HTTPONLY', true),
    'path'     => env('SESSION_PATH', BASE_PATH . '/storage/sessions'),
];
