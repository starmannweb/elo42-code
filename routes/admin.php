<?php

use App\Core\Router;

$router->group(['middleware' => ['auth', 'csrf', 'admin']], function (Router $r) {

    // Dashboard
    $r->get('/admin', [\Modules\Admin\Controllers\AdminDashboardController::class, 'index']);

    // Users
    $r->get('/admin/usuarios', [\Modules\Admin\Controllers\AdminUserController::class, 'index']);
    $r->get('/admin/usuarios/novo', [\Modules\Admin\Controllers\AdminUserController::class, 'create']);
    $r->post('/admin/usuarios', [\Modules\Admin\Controllers\AdminUserController::class, 'store']);
    $r->get('/admin/usuarios/{id}', [\Modules\Admin\Controllers\AdminUserController::class, 'show']);
    $r->get('/admin/usuarios/{id}/editar', [\Modules\Admin\Controllers\AdminUserController::class, 'edit']);
    $r->post('/admin/usuarios/{id}/editar', [\Modules\Admin\Controllers\AdminUserController::class, 'update']);
    $r->post('/admin/usuarios/{id}/excluir', [\Modules\Admin\Controllers\AdminUserController::class, 'destroy']);

    // Organizations
    $r->get('/admin/organizacoes', [\Modules\Admin\Controllers\AdminOrganizationController::class, 'index']);
    $r->get('/admin/organizacoes/{id}', [\Modules\Admin\Controllers\AdminOrganizationController::class, 'show']);
    $r->get('/admin/organizacoes/{id}/editar', [\Modules\Admin\Controllers\AdminOrganizationController::class, 'edit']);
    $r->post('/admin/organizacoes/{id}/editar', [\Modules\Admin\Controllers\AdminOrganizationController::class, 'update']);

    // Products
    $r->get('/admin/produtos', [\Modules\Admin\Controllers\AdminCatalogController::class, 'products']);
    $r->get('/admin/produtos/novo', [\Modules\Admin\Controllers\AdminCatalogController::class, 'createProduct']);
    $r->post('/admin/produtos', [\Modules\Admin\Controllers\AdminCatalogController::class, 'storeProduct']);
    $r->get('/admin/produtos/{id}/editar', [\Modules\Admin\Controllers\AdminCatalogController::class, 'editProduct']);
    $r->post('/admin/produtos/{id}/editar', [\Modules\Admin\Controllers\AdminCatalogController::class, 'updateProduct']);
    $r->post('/admin/produtos/categoria', [\Modules\Admin\Controllers\AdminCatalogController::class, 'storeProductCategory']);

    // Services
    $r->get('/admin/servicos', [\Modules\Admin\Controllers\AdminCatalogController::class, 'services']);
    $r->get('/admin/servicos/novo', [\Modules\Admin\Controllers\AdminCatalogController::class, 'createService']);
    $r->post('/admin/servicos', [\Modules\Admin\Controllers\AdminCatalogController::class, 'storeService']);
    $r->get('/admin/servicos/{id}/editar', [\Modules\Admin\Controllers\AdminCatalogController::class, 'editService']);
    $r->post('/admin/servicos/{id}/editar', [\Modules\Admin\Controllers\AdminCatalogController::class, 'updateService']);

    // Benefits
    $r->get('/admin/beneficios', [\Modules\Admin\Controllers\AdminCatalogController::class, 'benefits']);
    $r->get('/admin/beneficios/novo', [\Modules\Admin\Controllers\AdminCatalogController::class, 'createBenefit']);
    $r->post('/admin/beneficios', [\Modules\Admin\Controllers\AdminCatalogController::class, 'storeBenefit']);
    $r->get('/admin/beneficios/{id}/editar', [\Modules\Admin\Controllers\AdminCatalogController::class, 'editBenefit']);
    $r->post('/admin/beneficios/{id}/editar', [\Modules\Admin\Controllers\AdminCatalogController::class, 'updateBenefit']);

    // Subscriptions
    $r->get('/admin/assinaturas', [\Modules\Admin\Controllers\AdminCatalogController::class, 'subscriptions']);
    $r->get('/admin/assinaturas/{id}', [\Modules\Admin\Controllers\AdminCatalogController::class, 'showSubscription']);
    $r->post('/admin/assinaturas/{id}', [\Modules\Admin\Controllers\AdminCatalogController::class, 'updateSubscription']);

    // Tickets
    $r->get('/admin/tickets', [\Modules\Admin\Controllers\AdminCatalogController::class, 'tickets']);
    $r->get('/admin/tickets/{id}', [\Modules\Admin\Controllers\AdminCatalogController::class, 'showTicket']);
    $r->post('/admin/tickets/{id}/responder', [\Modules\Admin\Controllers\AdminCatalogController::class, 'replyTicket']);
    $r->post('/admin/tickets/{id}/status', [\Modules\Admin\Controllers\AdminCatalogController::class, 'updateTicketStatus']);

    // Reports
    $r->get('/admin/relatorios', [\Modules\Admin\Controllers\AdminCatalogController::class, 'reports']);

    // Logs
    $r->get('/admin/logs', [\Modules\Admin\Controllers\AdminCatalogController::class, 'logs']);

    // Settings
    $r->get('/admin/configuracoes', [\Modules\Admin\Controllers\AdminCatalogController::class, 'settings']);
    $r->post('/admin/configuracoes', [\Modules\Admin\Controllers\AdminCatalogController::class, 'updateSettings']);
});
