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
        if (!$org) {
            redirect('/onboarding/organizacao');
        }

        return is_array($org) ? $org : [];
    }

    public function index(Request $request): void
    {
        $org = $this->getOrg();
        $orgId = (int) ($org['id'] ?? 0);
        $user = Session::user() ?? [];
        $firstName = explode(' ', (string) ($user['name'] ?? 'Usuário'))[0] ?? 'Usuário';

        $safe = static function (callable $resolver, mixed $default) {
            try {
                return $resolver();
            } catch (\Throwable $e) {
                return $default;
            }
        };

        $now = date('Y-m');
        $startOfMonth = $now . '-01';
        $endOfMonth = date('Y-m-t');

        $financial = $safe(static function () use ($orgId, $startOfMonth, $endOfMonth): array {
            return FinancialTransaction::summary($orgId, $startOfMonth, $endOfMonth);
        }, ['income' => 0, 'expense' => 0, 'balance' => 0]);

        $this->view('management/dashboard', [
            'pageTitle'      => 'Gestão — Elo 42',
            'breadcrumb'     => 'Gestão',
            'firstName'      => $firstName,
            'totalMembers'   => $safe(static fn (): int => Member::countByOrg($orgId), 0),
            'newMembers'     => $safe(static fn (): int => Member::newThisMonth($orgId), 0),
            'activeEvents'   => $safe(static fn (): int => Event::countActive($orgId), 0),
            'activeMinistries' => $safe(static fn (): int => Ministry::countByOrg($orgId), 0),
            'openRequests'   => $safe(static fn (): int => ChurchRequest::countOpen($orgId), 0),
            'pendingTasks'   => $safe(static fn (): int => ActionPlan::pendingTasks($orgId), 0),
            'donationsMonth' => $safe(static fn (): float => Donation::totalByOrg($orgId), 0.0),
            'financial'      => $financial,
        ]);
    }
}
