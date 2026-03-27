<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Session;

class PermissionMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next, string ...$params): void
    {
        $requiredPermission = $params[0] ?? null;

        if (!$requiredPermission) {
            $next();
            return;
        }

        $user = Session::user();
        if (!$user) {
            redirect('/login');
        }

        $userPermissions = $user['permissions'] ?? [];

        if (!in_array($requiredPermission, $userPermissions) && !in_array('*', $userPermissions)) {
            http_response_code(403);
            echo '<h1>403 - Acesso negado</h1>';
            echo '<p>Você não tem permissão para acessar esta página.</p>';
            exit;
        }

        $next();
    }
}
