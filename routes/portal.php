<?php

use Modules\Portal\Controllers\MemberPortalController;

// Demo access (no full auth check — testing only)
$router->get('/demo/membro', [MemberPortalController::class, 'demoAccess']);

// Subscription-required page (auth + org only — NO plan:premium to avoid redirect loop)
$router->group(['middleware' => ['csrf', 'auth', 'organization']], function ($router) {
    $router->get('/membro/assine', [MemberPortalController::class, 'subscribeRequired']);
});

// Full member portal — requires active subscription (plan:premium)
$router->group(['prefix' => 'membro', 'middleware' => ['csrf', 'auth', 'organization', 'plan:premium']], function($router) {
    $router->get('/', [MemberPortalController::class, 'index']);
    $router->get('/biblia', [MemberPortalController::class, 'bible']);
    $router->get('/planos-leitura', [MemberPortalController::class, 'readingPlans']);
    $router->get('/eventos', [MemberPortalController::class, 'events']);
    $router->post('/eventos/{id}/inscricao', [MemberPortalController::class, 'rsvpEvent']);
    $router->get('/configuracoes', [MemberPortalController::class, 'settings']);
    $router->post('/configuracoes/salvar', [MemberPortalController::class, 'saveSettings']);
    $router->get('/ministracoes', [MemberPortalController::class, 'ministrations']);
    $router->get('/cursos', [MemberPortalController::class, 'courses']);
    $router->get('/pedidos', [MemberPortalController::class, 'requests']);
    $router->get('/solicitacoes', [MemberPortalController::class, 'requests']);
    $router->post('/solicitacoes', [MemberPortalController::class, 'storeRequest']);
    $router->get('/conquistas', [MemberPortalController::class, 'achievements']);
    $router->get('/ofertas', [MemberPortalController::class, 'offerings']);
});
