<?php

use Modules\Portal\Controllers\MemberPortalController;

$router->group(['prefix' => 'membro', 'middleware' => ['csrf', 'auth', 'organization', 'plan:premium']], function($router) {
    $router->get('/', [MemberPortalController::class, 'index']);
    $router->get('/biblia', [MemberPortalController::class, 'bible']);
    $router->get('/planos-leitura', [MemberPortalController::class, 'readingPlans']);
    $router->get('/ministracoes', [MemberPortalController::class, 'ministrations']);
    $router->get('/cursos', [MemberPortalController::class, 'courses']);
    $router->get('/eventos', [MemberPortalController::class, 'events']);
    $router->get('/pedidos', [MemberPortalController::class, 'requests']);
    $router->get('/ofertas', [MemberPortalController::class, 'offerings']);
    $router->get('/configuracoes', [MemberPortalController::class, 'settings']);
});
