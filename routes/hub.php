<?php

use Modules\Hub\Controllers\DashboardController;

$router->group(['prefix' => 'hub', 'middleware' => ['csrf', 'auth']], function($router) {
    // Rotas liberadas sem organização
    $router->get('/', [DashboardController::class, 'index']);
    $router->get('/suporte', [DashboardController::class, 'suporte']);
    $router->post('/suporte/tickets', [DashboardController::class, 'criarTicketSuporte']);
    $router->get('/configuracoes', [DashboardController::class, 'configuracoes']);
    $router->post('/configuracoes/conta', [DashboardController::class, 'atualizarConta']);
    $router->post('/configuracoes/organizacao', [DashboardController::class, 'atualizarOrganizacao']);

    // Funcionalidades restritas (exigem organização)
    $router->group(['middleware' => ['organization']], function($router) {
        $router->get('/vitrine', [DashboardController::class, 'vitrine']);
        $router->get('/sites', [DashboardController::class, 'sites']);
        $router->post('/sites/gerar', [DashboardController::class, 'gerarSite']);
        $router->group(['middleware' => ['plan:premium']], function($router) {
            $router->get('/expositor-ia', [DashboardController::class, 'expositorIa']);
            $router->post('/expositor-ia/gerar', [DashboardController::class, 'gerarExpositorIa']);
        });
        $router->get('/creditos', [DashboardController::class, 'creditos']);
        $router->post('/creditos/comprar', [DashboardController::class, 'comprarCreditos']);
        $router->post('/configuracoes/perfil-acesso', [DashboardController::class, 'atualizarPerfilAcesso']);

        // Gestão de Equipe (Usuários)
        $router->get('/usuarios', [DashboardController::class, 'usuarios']);
        $router->get('/usuarios/buscar', [DashboardController::class, 'buscarUsuarios']);
        $router->post('/usuarios/adicionar', [DashboardController::class, 'adicionarUsuario']);
        $router->post('/usuarios/editar/{id}', [DashboardController::class, 'editarUsuario']);
        $router->post('/usuarios/remover/{id}', [DashboardController::class, 'removerUsuario']);
    });
});
