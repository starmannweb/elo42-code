<?php

use Modules\ChurchManagement\Controllers\ManagementDashboardController;
use Modules\ChurchManagement\Controllers\MemberController;
use Modules\ChurchManagement\Controllers\MinistryController;
use Modules\ChurchManagement\Controllers\EventController;
use Modules\ChurchManagement\Controllers\FinancialController;
use Modules\ChurchManagement\Controllers\GeneralController;
use Modules\ChurchManagement\Controllers\ModuleController;

$router->group(['prefix' => 'gestao', 'middleware' => ['csrf', 'auth', 'organization', 'church_gestao']], function($router) {

    // Dashboard
    $router->get('/', [ManagementDashboardController::class, 'index']);

    // ── Premium Features ─────────────────────────────────────
    $router->group(['middleware' => ['plan:premium']], function($router) {
        $router->get('/visitantes', [ModuleController::class, 'visitors']);
        $router->get('/novos-convertidos', [ModuleController::class, 'newConverts']);
        $router->get('/aniversarios', [ModuleController::class, 'birthdays']);
        $router->get('/celulas', [ModuleController::class, 'smallGroups']);
        $router->get('/jornadas', [ModuleController::class, 'journeys']);
        $router->get('/historico', [ModuleController::class, 'history']);
        
        $router->get('/despesas', [ModuleController::class, 'expenses']);
        $router->get('/auditoria', [ModuleController::class, 'auditing']);
        $router->get('/categorias-financeiras', [ModuleController::class, 'financialCategories']);
        $router->get('/financeiro', [FinancialController::class, 'index']);
        $router->get('/financeiro/novo', [FinancialController::class, 'create']);
        $router->post('/financeiro', [FinancialController::class, 'store']);
        $router->post('/financeiro/categoria', [FinancialController::class, 'createCategory']);
        $router->get('/doacoes', [GeneralController::class, 'donations']);
        $router->get('/doacoes/nova', [GeneralController::class, 'createDonation']);
        $router->post('/doacoes', [GeneralController::class, 'storeDonation']);

        $router->get('/banners', [ModuleController::class, 'banners']);
        $router->get('/agenda', [EventController::class, 'agenda']);
        $router->get('/eventos', [EventController::class, 'index']);
        $router->get('/eventos/novo', [EventController::class, 'create']);
        $router->post('/eventos', [EventController::class, 'store']);
        $router->get('/eventos/{id}', [EventController::class, 'show']);
        $router->get('/eventos/{id}/editar', [EventController::class, 'edit']);
        $router->post('/eventos/{id}/editar', [EventController::class, 'update']);
        $router->get('/cursos', [ModuleController::class, 'courses']);
        $router->get('/conquistas', [ModuleController::class, 'achievements']);
        
        $router->get('/planos', [GeneralController::class, 'plans']);
        $router->get('/planos/novo', [GeneralController::class, 'createPlan']);
        $router->post('/planos', [GeneralController::class, 'storePlan']);
        $router->get('/planos/{id}', [GeneralController::class, 'showPlan']);
        $router->post('/planos/{id}/objetivo', [GeneralController::class, 'storeObjective']);
        $router->post('/planos/{id}/objetivo/{objective_id}/tarefa', [GeneralController::class, 'storeTask']);
        $router->post('/planos/{id}/tarefa/{task_id}/status', [GeneralController::class, 'updateTaskStatus']);
    });

    // ── Free Features ────────────────────────────────────────
    $router->get('/membros', [MemberController::class, 'index']);
    $router->get('/membros/novo', [MemberController::class, 'create']);
    $router->post('/membros', [MemberController::class, 'store']);
    $router->get('/membros/{id}', [MemberController::class, 'show']);
    $router->get('/membros/{id}/editar', [MemberController::class, 'edit']);
    $router->post('/membros/{id}/editar', [MemberController::class, 'update']);
    $router->post('/membros/{id}/excluir', [MemberController::class, 'destroy']);

    $router->get('/ministerios', [MinistryController::class, 'index']);
    $router->get('/ministerios/novo', [MinistryController::class, 'create']);
    $router->post('/ministerios', [MinistryController::class, 'store']);
    $router->get('/ministerios/{id}/editar', [MinistryController::class, 'edit']);
    $router->post('/ministerios/{id}/editar', [MinistryController::class, 'update']);

    $router->get('/dizimos-ofertas', [ModuleController::class, 'tithesOfferings']);
    $router->get('/contas', [ModuleController::class, 'accounts']);
    
    $router->get('/sermoes', [GeneralController::class, 'sermons']);
    $router->get('/sermoes/novo', [GeneralController::class, 'createSermon']);
    $router->post('/sermoes', [GeneralController::class, 'storeSermon']);

    $router->get('/solicitacoes', [GeneralController::class, 'requests']);
    $router->get('/solicitacoes/nova', [GeneralController::class, 'createRequest']);
    $router->post('/solicitacoes', [GeneralController::class, 'storeRequest']);
    $router->post('/solicitacoes/{id}/status', [GeneralController::class, 'updateRequestStatus']);

    $router->get('/aconselhamento', [GeneralController::class, 'counseling']);
    $router->get('/aconselhamento/novo', [GeneralController::class, 'createCounseling']);
    $router->post('/aconselhamento', [GeneralController::class, 'storeCounseling']);

    $router->get('/relatorios', [GeneralController::class, 'reports']);

    $router->get('/usuarios', [GeneralController::class, 'users']);
    $router->get('/usuarios/novo', [GeneralController::class, 'createUser']);
    $router->post('/usuarios', [GeneralController::class, 'storeUser']);
    $router->post('/usuarios/{id}/excluir', [GeneralController::class, 'destroyUser']);

    $router->get('/configuracoes', [GeneralController::class, 'settings']);
    $router->get('/assinatura', [\Modules\ChurchManagement\Controllers\BillingController::class, 'upgradePage']);
});
