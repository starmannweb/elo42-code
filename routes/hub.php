<?php

use Modules\Hub\Controllers\DashboardController;

$router->group(['prefix' => 'hub', 'middleware' => ['csrf', 'auth']], function($router) {
    $router->get('/', [DashboardController::class, 'index']);
    $router->get('/vitrine', [DashboardController::class, 'vitrine']);
    $router->get('/sites', [DashboardController::class, 'sites']);
    $router->post('/sites/gerar', [DashboardController::class, 'gerarSite']);
    $router->get('/expositor-ia', [DashboardController::class, 'expositorIa']);
    $router->post('/expositor-ia/gerar', [DashboardController::class, 'gerarExpositorIa']);
    $router->get('/creditos', [DashboardController::class, 'creditos']);
    $router->post('/creditos/comprar', [DashboardController::class, 'comprarCreditos']);
    $router->get('/suporte', [DashboardController::class, 'suporte']);
    $router->post('/suporte/tickets', [DashboardController::class, 'criarTicketSuporte']);
    $router->get('/configuracoes', [DashboardController::class, 'configuracoes']);
    $router->post('/configuracoes/conta', [DashboardController::class, 'atualizarConta']);
    $router->post('/configuracoes/organizacao', [DashboardController::class, 'atualizarOrganizacao']);
    $router->post('/configuracoes/perfil-acesso', [DashboardController::class, 'atualizarPerfilAcesso']);
});
