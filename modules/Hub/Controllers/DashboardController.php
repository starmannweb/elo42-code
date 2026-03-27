<?php

declare(strict_types=1);

namespace Modules\Hub\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $user = Session::user();
        $organization = Session::get('organization');

        $hasOrg = $organization !== null;

        if (!$hasOrg && !User::hasOrganization((int) $user['id'])) {
            redirect('/onboarding/organizacao');
        }

        $firstName = explode(' ', $user['name'])[0] ?? $user['name'];

        $greeting = match (true) {
            (int) date('H') < 12  => 'Bom dia',
            (int) date('H') < 18  => 'Boa tarde',
            default               => 'Boa noite',
        };

        $this->view('hub/dashboard', [
            'pageTitle'    => 'Dashboard — Elo 42',
            'user'         => $user,
            'organization' => $organization,
            'firstName'    => $firstName,
            'greeting'     => $greeting,
        ]);
    }
}
