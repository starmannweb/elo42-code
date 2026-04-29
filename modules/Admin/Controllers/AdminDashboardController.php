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
        $startDate = (string) $request->input('start_date', date('Y-m-01'));
        $endDate = (string) $request->input('end_date', date('Y-m-t'));
        $reportType = (string) $request->input('report_type', 'overview');

        $totalUsers = 0;
        $totalOrgs = 0;
        $activeSubs = 0;
        $trialSubs = 0;
        $openTickets = 0;
        $activeBenefits = 0;
        $activeServices = 0;
        $recentUsers = [];
        $recentOrgs = [];
        $report = $this->emptyReport();
        $degraded = false;

        try {
            $pdo = Database::connection();
            $safeInt = static function (\Closure $resolver): int {
                try {
                    return (int) $resolver();
                } catch (\Throwable $e) {
                    return 0;
                }
            };
            $safeArray = static function (\Closure $resolver): array {
                try {
                    return $resolver() ?: [];
                } catch (\Throwable $e) {
                    return [];
                }
            };

            $totalUsers = $safeInt(static fn () => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn());
            $totalOrgs = $safeInt(static fn () => $pdo->query("SELECT COUNT(*) FROM organizations")->fetchColumn());
            $activeSubs = $safeInt(static fn () => Subscription::countByStatus('active'));
            $trialSubs = $safeInt(static fn () => Subscription::countByStatus('trial'));
            $openTickets = $safeInt(static fn () => Ticket::countOpen());
            $activeBenefits = $safeInt(static fn () => $pdo->query("SELECT COUNT(*) FROM benefits WHERE status = 'active'")->fetchColumn());
            $activeServices = $safeInt(static fn () => $pdo->query("SELECT COUNT(*) FROM services WHERE status = 'active'")->fetchColumn());
            $recentUsers = $safeArray(static fn () => $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll());
            $recentOrgs = $safeArray(static fn () => $pdo->query("SELECT * FROM organizations ORDER BY created_at DESC LIMIT 5")->fetchAll());
            $report = $this->buildReport($pdo, $startDate, $endDate);
        } catch (\Throwable $e) {
            error_log('[AdminDashboard] ' . $e->getMessage());
            $degraded = true;
        }

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
            'degraded'       => $degraded,
        ]);
    }

    private function emptyReport(): array
    {
        return [
            'total_users' => 0,
            'new_users' => 0,
            'total_orgs' => 0,
            'new_orgs' => 0,
            'active_subscriptions' => 0,
            'trial_subscriptions' => 0,
            'open_tickets' => 0,
            'active_products' => 0,
            'active_benefits' => 0,
        ];
    }

    private function buildReport(\PDO $pdo, string $startDate, string $endDate): array
    {
        $periodEnd = $endDate . ' 23:59:59';
        $countBetween = static function (string $table) use ($pdo, $startDate, $periodEnd): int {
            try {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE created_at >= :start AND created_at <= :end");
                $stmt->execute(['start' => $startDate, 'end' => $periodEnd]);
                return (int) $stmt->fetchColumn();
            } catch (\Throwable $e) {
                return 0;
            }
        };
        $countAll = static function (string $sql) use ($pdo): int {
            try {
                return (int) $pdo->query($sql)->fetchColumn();
            } catch (\Throwable $e) {
                return 0;
            }
        };
        $countStatus = static function (string $status): int {
            try {
                return Subscription::countByStatus($status);
            } catch (\Throwable $e) {
                return 0;
            }
        };

        return [
            'total_users' => $countAll('SELECT COUNT(*) FROM users'),
            'new_users' => $countBetween('users'),
            'total_orgs' => $countAll('SELECT COUNT(*) FROM organizations'),
            'new_orgs' => $countBetween('organizations'),
            'active_subscriptions' => $countStatus('active'),
            'trial_subscriptions' => $countStatus('trial'),
            'open_tickets' => (function () { try { return Ticket::countOpen(); } catch (\Throwable $e) { return 0; } })(),
            'active_products' => $countAll("SELECT COUNT(*) FROM services WHERE status = 'active'"),
            'active_benefits' => $countAll("SELECT COUNT(*) FROM benefits WHERE status = 'active'"),
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
