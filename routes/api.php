<?php

$router->group(['prefix' => 'api'], function($router) {

    // --- Health check ---
    $router->get('/health', '{"status":"ok","version":"1.0.0"}');

    // --- Future API v1 routes ---
    // These routes will require API authentication (token-based).
    // $router->group(['prefix' => 'v1', 'middleware' => 'api.auth'], function($router) {
    //
    //     // Members
    //     $router->get('/members', 'Api\MemberController@index');
    //     $router->get('/members/{id}', 'Api\MemberController@show');
    //     $router->post('/members', 'Api\MemberController@store');
    //     $router->put('/members/{id}', 'Api\MemberController@update');
    //
    //     // Financial
    //     $router->get('/transactions', 'Api\TransactionController@index');
    //     $router->post('/transactions', 'Api\TransactionController@store');
    //
    //     // Events
    //     $router->get('/events', 'Api\EventController@index');
    //     $router->get('/events/{id}', 'Api\EventController@show');
    //
    //     // Donations
    //     $router->get('/donations', 'Api\DonationController@index');
    //     $router->post('/donations', 'Api\DonationController@store');
    //
    //     // Reports
    //     $router->get('/reports/members', 'Api\ReportController@members');
    //     $router->get('/reports/financial', 'Api\ReportController@financial');
    // });

    // --- Webhook endpoints (no auth, verified by signature) ---
    // $router->post('/webhooks/payment', 'Webhook\PaymentWebhookController@handle');
    // $router->post('/webhooks/whatsapp', 'Webhook\WhatsAppWebhookController@handle');
});
