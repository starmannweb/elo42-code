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
        // Always ensure a CSRF token exists BEFORE anything else.
        // This guarantees forms rendered via GET already have a valid token in the session.
        if (!Session::has('_csrf_token')) {
            Session::set('_csrf_token', bin2hex(random_bytes(32)));
        }

        if (in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
            $token = $request->input('_csrf_token') ?? $request->header('X-CSRF-Token');
            $sessionToken = Session::get('_csrf_token');

            if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
                // Token mismatch — regenerate a new token so the next page load works
                Session::set('_csrf_token', bin2hex(random_bytes(32)));

                // For AJAX requests, return JSON
                if ($request->isAjax()) {
                    http_response_code(419);
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => 'Token CSRF expirado. Recarregue a página e tente novamente.',
                    ]);
                    exit;
                }

                // For form submissions, redirect back with flash error
                $referer = $_SERVER['HTTP_REFERER'] ?? '/';
                Session::flash('error', 'Sessão expirada. Por favor, tente novamente.');
                if (!headers_sent()) {
                    header('Location: ' . $referer, true, 302);
                }
                exit;
            }
        }

        $next();
    }
}
