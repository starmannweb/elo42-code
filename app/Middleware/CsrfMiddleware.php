<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Session;

class CsrfMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next, string ...$params): void
    {
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
            $token = $request->input('_csrf_token') ?? $request->header('X-CSRF-Token');
            $sessionToken = Session::get('_csrf_token');

            if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
                http_response_code(419);
                echo '<h1>419 - Token expirado</h1>';
                echo '<p>Sessão expirada. Por favor, recarregue a página e tente novamente.</p>';
                exit;
            }
        }

        if (!Session::has('_csrf_token')) {
            Session::set('_csrf_token', bin2hex(random_bytes(32)));
        }

        $next();
    }
}
