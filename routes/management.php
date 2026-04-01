<?php

use Modules\ChurchManagement\Controllers\ManagementDashboardController;
use Modules\ChurchManagement\Controllers\MemberController;
use Modules\ChurchManagement\Controllers\MinistryController;
use Modules\ChurchManagement\Controllers\EventController;
use Modules\ChurchManagement\Controllers\FinancialController;
use Modules\ChurchManagement\Controllers\GeneralController;

$router->group(['prefix' => 'gestao', 'middleware' => ['csrf', 'auth']], function($router) {

    // Dashboard
    $router->get('/', [ManagementDashboardController::class, 'index']);

    // Members
    $router->get('/membros', [MemberController::class, 'index']);
    $router->get('/membros/novo', [MemberController::class, 'create']);
    $router->post('/membros', [MemberController::class, 'store']);
    $router->get('/membros/{id}', [MemberController::class, 'show']);
    $router->get('/membros/{id}/editar', [MemberController::class, 'edit']);
    $router->post('/membros/{id}/editar', [MemberController::class, 'update']);
    $router->post('/membros/{id}/excluir', [MemberController::class, 'destroy']);

    // Ministries
    $router->get('/ministerios', [MinistryController::class, 'index']);
    $router->get('/ministerios/novo', [MinistryController::class, 'create']);
    $router->post('/ministerios', [MinistryController::class, 'store']);
    $router->get('/ministerios/{id}/editar', [MinistryController::class, 'edit']);
    $router->post('/ministerios/{id}/editar', [MinistryController::class, 'update']);

    // Agenda (Calendar)
    $router->get('/agenda', [EventController::class, 'agenda']);

    // Events
    $router->get('/eventos', [EventController::class, 'index']);
    $router->get('/eventos/novo', [EventController::class, 'create']);
    $router->post('/eventos', [EventController::class, 'store']);
    $router->get('/eventos/{id}', [EventController::class, 'show']);
    $router->get('/eventos/{id}/editar', [EventController::class, 'edit']);
    $router->post('/eventos/{id}/editar', [EventController::class, 'update']);

    // Financial
    $router->get('/financeiro', [FinancialController::class, 'index']);
    $router->get('/financeiro/novo', [FinancialController::class, 'create']);
    $router->post('/financeiro', [FinancialController::class, 'store']);
    $router->post('/financeiro/categoria', [FinancialController::class, 'createCategory']);

    // Requests
    $router->get('/solicitacoes', [GeneralController::class, 'requests']);
    $router->get('/solicitacoes/nova', [GeneralController::class, 'createRequest']);
    $router->post('/solicitacoes', [GeneralController::class, 'storeRequest']);
    $router->post('/solicitacoes/{id}/status', [GeneralController::class, 'updateRequestStatus']);

    // Visits
    $router->get('/visitas', [GeneralController::class, 'visits']);
    $router->get('/visitas/nova', [GeneralController::class, 'createVisit']);
    $router->post('/visitas', [GeneralController::class, 'storeVisit']);
    $router->post('/visitas/{id}/followup', [GeneralController::class, 'updateVisitFollowUp']);

    // Counseling
    $router->get('/aconselhamento', [GeneralController::class, 'counseling']);
    $router->get('/aconselhamento/novo', [GeneralController::class, 'createCounseling']);
    $router->post('/aconselhamento', [GeneralController::class, 'storeCounseling']);

    // Sermons
    $router->get('/sermoes', [GeneralController::class, 'sermons']);
    $router->get('/sermoes/novo', [GeneralController::class, 'createSermon']);
    $router->post('/sermoes', [GeneralController::class, 'storeSermon']);

    // Action Plans
    $router->get('/planos', [GeneralController::class, 'plans']);
    $router->get('/planos/novo', [GeneralController::class, 'createPlan']);
    $router->post('/planos', [GeneralController::class, 'storePlan']);
    $router->get('/planos/{id}', [GeneralController::class, 'showPlan']);
    $router->post('/planos/{id}/objetivo', [GeneralController::class, 'storeObjective']);
    $router->post('/planos/{id}/objetivo/{objective_id}/tarefa', [GeneralController::class, 'storeTask']);
    $router->post('/planos/{id}/tarefa/{task_id}/status', [GeneralController::class, 'updateTaskStatus']);

    // Donations
    $router->get('/doacoes', [GeneralController::class, 'donations']);
    $router->get('/doacoes/nova', [GeneralController::class, 'createDonation']);
    $router->post('/doacoes', [GeneralController::class, 'storeDonation']);

    // Reports
    $router->get('/relatorios', [GeneralController::class, 'reports']);

    // Users
    $router->get('/usuarios', [GeneralController::class, 'users']);
    $router->get('/usuarios/novo', [GeneralController::class, 'createUser']);
    $router->post('/usuarios', [GeneralController::class, 'storeUser']);
    $router->post('/usuarios/{id}/excluir', [GeneralController::class, 'destroyUser']);

    // Settings
    $router->get('/configuracoes', [GeneralController::class, 'settings']);
});
