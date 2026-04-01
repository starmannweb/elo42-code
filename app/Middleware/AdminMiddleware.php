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
        $permissions = $user['permissions'] ?? [];
        $allowedPerms = ['admin.access', 'admin.users', 'admin.organizations'];
        $isAdmin = false;

        foreach ($allowedPerms as $perm) {
            if (in_array($perm, $permissions, true)) {
                $isAdmin = true;
                break;
            }
        }

        $org = Session::get('organization');
        $roleSlug = is_array($org) ? (string) ($org['role_slug'] ?? '') : '';
        if (in_array($roleSlug, ['super-admin', 'admin-elo42'], true)) {
            $isAdmin = true;
        }

        if (!$isAdmin) {
            Session::flash('error', 'Acesso restrito à administração.');
            redirect('/hub');
        }

        $next();
    }
}
