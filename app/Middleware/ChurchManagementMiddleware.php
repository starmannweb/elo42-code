<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Session;

class ChurchManagementMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next, string ...$params): void
    {
        $organization = Session::get('organization');
        $roleSlug = is_array($organization) ? (string) ($organization['role_slug'] ?? '') : '';

        // Se o usuário logado for apanas um membro da congregação (sem atributos de gestão), não pode acessar o painel de gestão.
        if ($roleSlug === 'member') {
            if ($request->isAjax() || str_starts_with($request->uri(), '/api/')) {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Acesso negado. A gestão é restrita aos líderes da instituição.']);
                exit;
            }

            Session::flash('error', 'Acesso negado: Você não tem permissão para acessar o painel de Gestão da Igreja.');
            redirect('/hub');
        }

        $next();
    }
}
