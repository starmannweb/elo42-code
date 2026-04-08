<?php

return [
    'aliases' => [
        'auth'         => \App\Middleware\AuthMiddleware::class,
        'permission'   => \App\Middleware\PermissionMiddleware::class,
        'csrf'         => \App\Middleware\CsrfMiddleware::class,
        'admin'        => \App\Middleware\AdminMiddleware::class,
        'organization' => \App\Middleware\RequireOrganizationMiddleware::class,
        'church_gestao'=> \App\Middleware\ChurchManagementMiddleware::class,
        'plan'         => \App\Middleware\PlanMiddleware::class,
    ],

    'groups' => [
        'web'   => ['csrf'],
        'auth'  => ['csrf', 'auth'],
        'admin' => ['csrf', 'auth', 'permission:admin'],
    ],
];
