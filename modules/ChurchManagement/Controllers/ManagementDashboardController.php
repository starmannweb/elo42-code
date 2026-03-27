<?php

declare(strict_types=1);

namespace Modules\ChurchManagement\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Member;
use App\Models\Ministry;
use App\Models\Event;
use App\Models\FinancialTransaction;
use App\Models\Donation;
use App\Models\ChurchRequest;
use App\Models\ActionPlan;

class ManagementDashboardController extends Controller
{
    private function getOrg(): array
    {
        $org = Session::get('organization');
        if (!$org) { redirect('/onboarding/organizacao'); }
        return $org;
    }

    public function index(Request $request): void
    {
        $org = $this->getOrg();
        $orgId = (int) $org['id'];
        $user = Session::user();
        $firstName = explode(' ', $user['name'])[0];

        $now = date('Y-m');
        $startOfMonth = $now . '-01';
        $endOfMonth = date('Y-m-t');

        $financial = FinancialTransaction::summary($orgId, $startOfMonth, $endOfMonth);

        $this->view('management/dashboard', [
            'pageTitle'      => 'Gestão — Elo 42',
            'breadcrumb'     => 'Gestão',
            'firstName'      => $firstName,
            'totalMembers'   => Member::countByOrg($orgId),
            'newMembers'     => Member::newThisMonth($orgId),
            'activeEvents'   => Event::countActive($orgId),
            'activeMinistries' => Ministry::countByOrg($orgId),
            'openRequests'   => ChurchRequest::countOpen($orgId),
            'pendingTasks'   => ActionPlan::pendingTasks($orgId),
            'donationsMonth' => Donation::totalByOrg($orgId),
            'financial'      => $financial,
        ]);
    }
}
