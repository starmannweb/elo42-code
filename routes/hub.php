<?php

use Modules\Hub\Controllers\DashboardController;

$router->group(['prefix' => 'hub', 'middleware' => ['csrf', 'auth']], function($router) {
    $router->get('/', [DashboardController::class, 'index']);
});
