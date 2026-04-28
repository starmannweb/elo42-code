<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Session;

class AdminMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next, string ...$params): void
    {
        if (!Session::isAuthenticated()) {
            Session::flash('intended_url', $request->uri());
            redirect('/login');
        }

        $user = Session::user() ?? [];
        $isAdmin = strtolower((string) ($user['email'] ?? '')) === 'ricieri@starmannweb.com.br';

        if (!$isAdmin) {
            Session::flash('error', 'Acesso restrito à administração.');
            redirect('/hub');
        }

        $next();
    }
}
