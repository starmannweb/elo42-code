<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Session;
use App\Models\User;
use DateTimeImmutable;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next, string ...$params): void
    {
        if (!Session::isAuthenticated()) {
            Session::flash('error', 'Você precisa estar logado para acessar esta página.');
            Session::flash('intended_url', $request->uri());
            redirect('/login');
        }

        if ($this->mustCompleteOrganizationOnboarding($request)) {
            Session::flash('warning', 'Para continuar, cadastre sua organização. O prazo máximo de 7 dias foi atingido.');
            redirect('/onboarding/organizacao');
        }

        $next();
    }

    private function mustCompleteOrganizationOnboarding(Request $request): bool
    {
        $uri = $request->uri();
        if ($uri === '/onboarding/organizacao') {
            return false;
        }

        $user = Session::user() ?? [];
        $userId = (int) ($user['id'] ?? 0);
        if ($userId <= 0) {
            return false;
        }

        if ($this->hasOrganization($userId)) {
            return false;
        }

        $createdAt = (string) ($user['created_at'] ?? '');
        if ($createdAt === '') {
            return false;
        }

        try {
            $created = new DateTimeImmutable($createdAt);
            $deadline = $created->modify('+7 days');
            return new DateTimeImmutable('now') >= $deadline;
        } catch (\Throwable $e) {
            return false;
        }
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
