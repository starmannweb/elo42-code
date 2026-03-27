<?php

use Modules\Site\Controllers\SiteController;

$router->get('/', [SiteController::class, 'home']);
$router->get('/sobre', [SiteController::class, 'about']);
$router->get('/solucoes', [SiteController::class, 'solutions']);
$router->get('/plataforma', [SiteController::class, 'platform']);
$router->get('/beneficios', [SiteController::class, 'benefits']);
$router->get('/consultoria', [SiteController::class, 'consulting']);
$router->get('/funcionalidades', [SiteController::class, 'features']);
$router->get('/faq', [SiteController::class, 'faq']);
$router->get('/contato', [SiteController::class, 'contact']);
$router->post('/contato', [SiteController::class, 'contactSubmit']);
