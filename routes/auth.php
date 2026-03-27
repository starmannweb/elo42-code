<?php

use Modules\Auth\Controllers\AuthController;

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/cadastro', [AuthController::class, 'showRegister']);
$router->post('/cadastro', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/esqueci-senha', [AuthController::class, 'showForgotPassword']);
$router->post('/esqueci-senha', [AuthController::class, 'forgotPassword']);
$router->get('/redefinir-senha/{token}', [AuthController::class, 'showResetPassword']);
$router->post('/redefinir-senha', [AuthController::class, 'resetPassword']);
$router->get('/verificar-email/{token}', [AuthController::class, 'verifyEmail']);

$router->get('/onboarding/organizacao', [AuthController::class, 'showOnboardingOrganization']);
$router->post('/onboarding/organizacao', [AuthController::class, 'storeOrganization']);
