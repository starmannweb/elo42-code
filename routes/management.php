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
        $router->get('/visitantes', [GeneralController::class, 'visits']);
        $router->get('/visitantes/nova', [GeneralController::class, 'createVisit']);
        $router->post('/visitantes', [GeneralController::class, 'storeVisit']);
        $router->post('/visitantes/{id}/status', [GeneralController::class, 'updateVisitFollowUp']);
        $router->get('/novos-convertidos', [ModuleController::class, 'newConverts']);
        $router->get('/aniversarios', [ModuleController::class, 'birthdays']);
        $router->get('/celulas', [ModuleController::class, 'smallGroups']);
        $router->get('/jornadas', [ModuleController::class, 'journeys']);
        $router->post('/jornadas/nova', [ModuleController::class, 'storeJourney']);
        $router->post('/jornadas/{id}/remover', [ModuleController::class, 'removeJourney']);
        $router->get('/historico', [ModuleController::class, 'history']);
        $router->get('/atendimento-pastoral', [GeneralController::class, 'counseling']);
        $router->get('/atendimento-pastoral/novo', [GeneralController::class, 'createCounseling']);
        $router->post('/atendimento-pastoral', [GeneralController::class, 'storeCounseling']);
        $router->get('/solicitacoes', [GeneralController::class, 'requests']);
        $router->get('/solicitacoes/nova', [GeneralController::class, 'createRequest']);
        $router->post('/solicitacoes', [GeneralController::class, 'storeRequest']);
        $router->post('/solicitacoes/{id}/status', [GeneralController::class, 'updateRequestStatus']);
        
        $router->get('/aprovacoes-despesas', [ModuleController::class, 'expensesApprovals']);
        $router->post('/aprovacoes-despesas/nova', [ModuleController::class, 'storeExpenseApproval']);
        $router->post('/aprovacoes-despesas/{id}/aprovar', [ModuleController::class, 'approveExpense']);
        $router->post('/aprovacoes-despesas/{id}/rejeitar', [ModuleController::class, 'rejectExpense']);
        $router->get('/auditoria', [ModuleController::class, 'auditing']);
        $router->get('/contas', [ModuleController::class, 'accounts']);

        $router->get('/banners', [ModuleController::class, 'banners']);
        $router->get('/cursos', [ModuleController::class, 'courses']);
        $router->post('/cursos/novo', [ModuleController::class, 'storeCourse']);
        $router->post('/cursos/{id}/remover', [ModuleController::class, 'removeCourse']);
        $router->get('/campanhas', [ModuleController::class, 'campaigns']);
        $router->post('/campanhas/nova', [ModuleController::class, 'storeCampaign']);
        $router->post('/campanhas/{id}/remover', [ModuleController::class, 'removeCampaign']);
        $router->get('/conquistas', [ModuleController::class, 'achievements']);
        $router->post('/conquistas/nova', [ModuleController::class, 'storeAchievement']);
        $router->post('/conquistas/{id}/remover', [ModuleController::class, 'removeAchievement']);
        $router->get('/pregadores', [GeneralController::class, 'preachers']);
        $router->post('/pregadores', [GeneralController::class, 'storePreacher']);
        $router->post('/pregadores/{id}/remover', [GeneralController::class, 'removePreacher']);
        $router->get('/sermoes', [GeneralController::class, 'sermons']);
        $router->get('/sermoes/novo', [GeneralController::class, 'createSermon']);
        $router->post('/sermoes', [GeneralController::class, 'storeSermon']);
        $router->get('/sermoes/expositor-ia', [\Modules\Hub\Controllers\DashboardController::class, 'expositorIa']);
        $router->get('/relatorios', [GeneralController::class, 'reports']);

        $router->get('/ministerios', [MinistryController::class, 'index']);
        $router->get('/ministerios/novo', [MinistryController::class, 'create']);
        $router->post('/ministerios', [MinistryController::class, 'store']);
        $router->get('/ministerios/{id}/editar', [MinistryController::class, 'edit']);
        $router->post('/ministerios/{id}/editar', [MinistryController::class, 'update']);

        $router->get('/configuracoes/pix', [GeneralController::class, 'settingsPix']);
        $router->get('/configuracoes/ia', [GeneralController::class, 'settingsAi']);
        $router->get('/configuracoes/aparencia', [GeneralController::class, 'settingsAppearance']);
        $router->get('/configuracoes/seo', [GeneralController::class, 'settingsSeo']);
        $router->get('/configuracoes/pwa', [GeneralController::class, 'settingsPwa']);
        $router->get('/configuracoes/integracoes', [GeneralController::class, 'settingsIntegrations']);
        $router->get('/configuracoes/redes-sociais', [GeneralController::class, 'settingsSocial']);
    });

    // ── Free Features ────────────────────────────────────────
    $router->get('/membros', [MemberController::class, 'index']);
    $router->get('/membros/novo', [MemberController::class, 'create']);
    $router->post('/membros', [MemberController::class, 'store']);
    $router->get('/membros/{id}', [MemberController::class, 'show']);
    $router->get('/membros/{id}/editar', [MemberController::class, 'edit']);
    $router->post('/membros/{id}/editar', [MemberController::class, 'update']);
    $router->post('/membros/{id}/excluir', [MemberController::class, 'destroy']);

    $router->get('/receitas', [ModuleController::class, 'tithesOfferings']);
    $router->get('/despesas', [ModuleController::class, 'expenses']);
    $router->get('/financeiro/novo', [FinancialController::class, 'create']);
    $router->post('/financeiro', [FinancialController::class, 'store']);
    $router->post('/financeiro/categoria', [FinancialController::class, 'createCategory']);
    $router->post('/financeiro/categoria/popular', [FinancialController::class, 'seedCategories']);
    $router->post('/financeiro/categoria/{id}/editar', [FinancialController::class, 'updateCategory']);
    $router->post('/financeiro/categoria/{id}/excluir', [FinancialController::class, 'deleteCategory']);
    $router->get('/categorias-financeiras', [FinancialController::class, 'categories']);
    $router->get('/doacoes', [GeneralController::class, 'donations']);
    $router->get('/doacoes/nova', [GeneralController::class, 'createDonation']);
    $router->post('/doacoes', [GeneralController::class, 'storeDonation']);
    
    $router->get('/agenda', [EventController::class, 'agenda']);
    $router->get('/eventos', [EventController::class, 'index']);
    $router->get('/eventos/novo', [EventController::class, 'create']);
    $router->post('/eventos', [EventController::class, 'store']);
    $router->get('/eventos/{id}', [EventController::class, 'show']);
    $router->get('/eventos/{id}/editar', [EventController::class, 'edit']);
    $router->post('/eventos/{id}/editar', [EventController::class, 'update']);

    $router->get('/plano-leitura', [ModuleController::class, 'readingPlan']);
    $router->post('/plano-leitura/novo', [ModuleController::class, 'storeReadingPlan']);
    $router->post('/plano-leitura/{id}/remover', [ModuleController::class, 'removeReadingPlan']);
    
    $router->get('/ministracoes', [GeneralController::class, 'ministrations']);

    $router->get('/configuracoes', [GeneralController::class, 'settings']);
    $router->get('/configuracoes/usuarios', [GeneralController::class, 'settingsUsers']);
    $router->get('/configuracoes/unidades', [GeneralController::class, 'settingsUnits']);
    $router->post('/configuracoes/unidades', [GeneralController::class, 'storeUnit']);
    $router->post('/configuracoes/unidades/{id}/remover', [GeneralController::class, 'removeUnit']);
    $router->get('/configuracoes/categorias', [GeneralController::class, 'settingsCategories']);
    $router->post('/configuracoes', [GeneralController::class, 'saveSettings']);
    $router->get('/usuarios', [GeneralController::class, 'settingsUsers']);
    $router->get('/usuarios/novo', [GeneralController::class, 'createUser']);
    $router->post('/usuarios', [GeneralController::class, 'storeUser']);
    $router->post('/usuarios/{id}/excluir', [GeneralController::class, 'destroyUser']);

    $router->get('/assinatura', [\Modules\ChurchManagement\Controllers\BillingController::class, 'upgradePage']);
});
