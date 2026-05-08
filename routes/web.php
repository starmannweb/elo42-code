<?php

use Modules\Site\Controllers\SiteController;

$router->group(['middleware' => ['csrf']], function ($router) {
    $router->get('/app-manifest', [SiteController::class, 'manifest']);
    $router->get('/app.webmanifest', [SiteController::class, 'manifest']);
    $router->get('/site/{slug}', [SiteController::class, 'generatedSite']);
    $router->get('/', [SiteController::class, 'home']);
    $router->get('/servico/{slug}', [SiteController::class, 'service']);
    $router->get('/sobre', [SiteController::class, 'about']);
    $router->get('/solucoes', [SiteController::class, 'solutions']);
    $router->get('/plataforma', [SiteController::class, 'platform']);
    $router->get('/beneficios', [SiteController::class, 'benefits']);
    $router->get('/consultoria', [SiteController::class, 'consulting']);
    $router->get('/funcionalidades', [SiteController::class, 'features']);
    $router->get('/faq', [SiteController::class, 'faq']);
    $router->get('/blog', [SiteController::class, 'blog']);
    $router->get('/blog/{slug}', [SiteController::class, 'blogArticle']);
    $router->get('/contato', [SiteController::class, 'contact']);
    $router->post('/contato', [SiteController::class, 'contactSubmit']);
    $router->get('/termos', [SiteController::class, 'terms']);
    $router->get('/privacidade', [SiteController::class, 'privacy']);
    $router->get('/politica-de-cookies', [SiteController::class, 'cookiePolicy']);
    $router->get('/ajuda', [SiteController::class, 'help']);
});
