<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Session;

class AdminMiddleware
{
    public function handle(): void
    {
        if (!Session::isAuthenticated()) {
            Session::set('intended_url', $_SERVER['REQUEST_URI'] ?? '/');
            redirect('/login');
            exit;
        }

        $permissions = Session::get('permissions') ?? [];
        $allowedPerms = ['admin.access', 'admin.users', 'admin.organizations'];
        $isAdmin = false;

        foreach ($allowedPerms as $perm) {
            if (in_array($perm, $permissions)) {
                $isAdmin = true;
                break;
            }
        }

        // Also check role slug
        $org = Session::get('organization');
        $roleSlug = $org['role_slug'] ?? '';
        if (in_array($roleSlug, ['super-admin', 'admin-elo42'])) {
            $isAdmin = true;
        }

        if (!$isAdmin) {
            Session::flash('error', 'Acesso restrito à administração.');
            redirect('/hub');
            exit;
        }
    }
}
