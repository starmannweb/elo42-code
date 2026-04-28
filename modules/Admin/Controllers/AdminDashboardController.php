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
        $startDate = (string) $request->input('start_date', date('Y-m-01'));
        $endDate = (string) $request->input('end_date', date('Y-m-t'));
        $reportType = (string) $request->input('report_type', 'overview');

        $totalUsers = (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalOrgs = (int) $pdo->query("SELECT COUNT(*) FROM organizations")->fetchColumn();
        $activeSubs = Subscription::countByStatus('active');
        $trialSubs = Subscription::countByStatus('trial');
        $openTickets = Ticket::countOpen();
        $activeBenefits = (int) $pdo->query("SELECT COUNT(*) FROM benefits WHERE status = 'active'")->fetchColumn();
        $activeServices = (int) $pdo->query("SELECT COUNT(*) FROM services WHERE status = 'active'")->fetchColumn();

        $recentUsers = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();
        $recentOrgs = $pdo->query("SELECT * FROM organizations ORDER BY created_at DESC LIMIT 5")->fetchAll();
        $report = $this->buildReport($pdo, $startDate, $endDate);

        if ($request->input('export') === 'csv') {
            $this->exportCsv($report, $reportType, $startDate, $endDate);
            return;
        }

        $this->view('admin/dashboard', [
            'pageTitle'      => 'Admin — Elo 42',
            'breadcrumb'     => 'Dashboard',
            'totalUsers'     => $totalUsers,
            'totalOrgs'      => $totalOrgs,
            'activeSubs'     => $activeSubs,
            'trialSubs'      => $trialSubs,
            'openTickets'    => $openTickets,
            'activeBenefits' => $activeBenefits,
            'activeProducts' => $activeServices,
            'recentUsers'    => $recentUsers,
            'recentOrgs'     => $recentOrgs,
            'report'         => $report,
            'reportFilters'  => [
                'start_date'  => $startDate,
                'end_date'    => $endDate,
                'report_type' => $reportType,
            ],
        ]);
    }

    private function buildReport(\PDO $pdo, string $startDate, string $endDate): array
    {
        $periodEnd = $endDate . ' 23:59:59';
        $countBetween = static function (string $table) use ($pdo, $startDate, $periodEnd): int {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE created_at >= :start AND created_at <= :end");
            $stmt->execute(['start' => $startDate, 'end' => $periodEnd]);
            return (int) $stmt->fetchColumn();
        };

        return [
            'total_users' => (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
            'new_users' => $countBetween('users'),
            'total_orgs' => (int) $pdo->query('SELECT COUNT(*) FROM organizations')->fetchColumn(),
            'new_orgs' => $countBetween('organizations'),
            'active_subscriptions' => Subscription::countByStatus('active'),
            'trial_subscriptions' => Subscription::countByStatus('trial'),
            'open_tickets' => Ticket::countOpen(),
            'active_products' => (int) $pdo->query("SELECT COUNT(*) FROM services WHERE status = 'active'")->fetchColumn(),
            'active_benefits' => (int) $pdo->query("SELECT COUNT(*) FROM benefits WHERE status = 'active'")->fetchColumn(),
        ];
    }

    private function exportCsv(array $report, string $reportType, string $startDate, string $endDate): void
    {
        $labels = [
            'total_users' => 'Total de usuarios',
            'new_users' => 'Novos usuarios',
            'total_orgs' => 'Total de organizacoes',
            'new_orgs' => 'Novas organizacoes',
            'active_subscriptions' => 'Assinaturas ativas',
            'trial_subscriptions' => 'Assinaturas em teste',
            'open_tickets' => 'Tickets abertos',
            'active_products' => 'Servicos ativos',
            'active_benefits' => 'Cortesias ativas',
        ];

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="elo42-admin-' . preg_replace('/[^a-z0-9_-]/i', '-', $reportType) . '-' . date('Ymd-His') . '.csv"');
        echo "\xEF\xBB\xBF";
        echo "Relatorio;Periodo inicial;Periodo final;Indicador;Valor\n";

        foreach ($report as $key => $value) {
            echo implode(';', [
                $this->csvCell($reportType),
                $this->csvCell($startDate),
                $this->csvCell($endDate),
                $this->csvCell($labels[$key] ?? $key),
                (string) $value,
            ]) . "\n";
        }
    }

    private function csvCell(string $value): string
    {
        return '"' . str_replace('"', '""', $value) . '"';
    }
}
