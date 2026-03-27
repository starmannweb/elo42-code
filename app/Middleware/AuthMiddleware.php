<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Session;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next, string ...$params): void
    {
        if (!Session::isAuthenticated()) {
            Session::flash('error', 'Você precisa estar logado para acessar esta página.');
            Session::flash('intended_url', $request->uri());
            redirect('/login');
        }

        $next();
    }
}
