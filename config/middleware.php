<?php

return [
    'aliases' => [
        'auth'       => \App\Middleware\AuthMiddleware::class,
        'permission' => \App\Middleware\PermissionMiddleware::class,
        'csrf'       => \App\Middleware\CsrfMiddleware::class,
        'admin'      => \App\Middleware\AdminMiddleware::class,
    ],

    'groups' => [
        'web'   => ['csrf'],
        'auth'  => ['csrf', 'auth'],
        'admin' => ['csrf', 'auth', 'permission:admin'],
    ],
];
