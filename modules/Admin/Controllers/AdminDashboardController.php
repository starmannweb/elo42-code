<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Database;
use App\Models\Subscription;
use App\Models\Ticket;

class AdminDashboardController extends Controller
{
    public function index(Request $request): void
    {
        $pdo = Database::connection();

        $totalUsers = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalOrgs = (int) $pdo->query("SELECT COUNT(*) FROM organizations")->fetchColumn();
        $activeSubs = Subscription::countByStatus('active');
        $trialSubs = Subscription::countByStatus('trial');
        $openTickets = Ticket::countOpen();
        $activeBenefits = (int) $pdo->query("SELECT COUNT(*) FROM benefits WHERE status = 'active'")->fetchColumn();
        $activeProducts = (int) $pdo->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetchColumn();

        $recentUsers = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();
        $recentOrgs = $pdo->query("SELECT * FROM organizations ORDER BY created_at DESC LIMIT 5")->fetchAll();

        $this->view('admin/dashboard', [
            'pageTitle'      => 'Admin — Elo 42',
            'breadcrumb'     => 'Dashboard',
            'totalUsers'     => $totalUsers,
            'totalOrgs'      => $totalOrgs,
            'activeSubs'     => $activeSubs,
            'trialSubs'      => $trialSubs,
            'openTickets'    => $openTickets,
            'activeBenefits' => $activeBenefits,
            'activeProducts' => $activeProducts,
            'recentUsers'    => $recentUsers,
            'recentOrgs'     => $recentOrgs,
        ]);
    }
}
