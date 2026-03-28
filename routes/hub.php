<?php

use Modules\Hub\Controllers\DashboardController;

$router->group(['prefix' => 'hub', 'middleware' => ['csrf', 'auth']], function($router) {
    $router->get('/', [DashboardController::class, 'index']);
    $router->get('/vitrine', [DashboardController::class, 'vitrine']);
    $router->get('/sites', [DashboardController::class, 'sites']);
    $router->get('/expositor-ia', [DashboardController::class, 'expositorIa']);
    $router->get('/creditos', [DashboardController::class, 'creditos']);
    $router->get('/suporte', [DashboardController::class, 'suporte']);
    $router->get('/configuracoes', [DashboardController::class, 'configuracoes']);
});
