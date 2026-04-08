<?php

declare(strict_types=1);

namespace Modules\ChurchManagement\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\User;

class BillingController extends Controller
{
    public function upgradePage(Request $request): void
    {
        $org = Session::get('organization');
        if (!$org || empty($org['id'])) {
            redirect('/hub');
        }

        $user = Session::user() ?? [];
        $firstName = explode(' ', (string) ($user['name'] ?? 'Usuário'))[0] ?? 'Usuário';

        $this->view('management/billing/upgrade', [
            'pageTitle'  => 'Assinatura Elo 42 Premium',
            'breadcrumb' => 'Assinatura',
            'firstName'  => $firstName,
            'planName'   => $org['plan'] ?? 'free',
        ]);
    }
}
