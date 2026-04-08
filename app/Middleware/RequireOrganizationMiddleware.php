<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Session;
use App\Models\User;

class RequireOrganizationMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next, string ...$params): void
    {
        $user = Session::user() ?? [];
        $userId = (int) ($user['id'] ?? 0);

        if ($userId > 0 && !$this->hasOrganization($userId)) {
            // Se for requisição AJAX, retornar JSON
            if ($request->isAjax() || str_starts_with($request->uri(), '/api/')) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Acesso negado. É necessário cadastrar uma organização primeiro.']);
                exit;
            }

            Session::flash('warning', 'Acesso bloqueado: Cadastre sua organização para liberar esta funcionalidade.');
            redirect('/onboarding/organizacao');
        }

        $next();
    }

    private function hasOrganization(int $userId): bool
    {
        $organization = Session::get('organization');
        if (is_array($organization) && !empty($organization['id'])) {
            return true;
        }

        try {
            return User::hasOrganization($userId);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
