<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Database;
use App\Core\Session;
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
        $publishedArticles = 0;
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
            $publishedArticles = $safeInt(static fn () => $pdo->query("SELECT COUNT(*) FROM blog_articles WHERE status = 'published'")->fetchColumn());
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
            'activeBenefits'     => $activeBenefits,
            'activeProducts'     => $activeServices,
            'publishedArticles'  => $publishedArticles,
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

    private const DEMO_EMAIL_DOMAIN = 'demo.elo42.test';

    public function seedDemo(Request $request): void
    {
        try {
            $pdo = Database::connection();
            $pdo->beginTransaction();

            $managerRoleId = (int) ($pdo->query("SELECT id FROM roles WHERE slug = 'org-manager' LIMIT 1")->fetchColumn() ?: 1);
            $memberRoleId = (int) ($pdo->query("SELECT id FROM roles WHERE slug = 'org-member' LIMIT 1")->fetchColumn() ?: 3);

            $orgs = [
                ['name' => 'Igreja Demo Esperança', 'slug' => 'demo-esperanca', 'type' => 'church'],
                ['name' => 'Comunidade Demo Vida', 'slug' => 'demo-vida', 'type' => 'church'],
            ];

            $orgIds = [];
            foreach ($orgs as $org) {
                $existing = $pdo->prepare("SELECT id FROM organizations WHERE slug = :slug LIMIT 1");
                $existing->execute(['slug' => $org['slug']]);
                $orgId = (int) ($existing->fetchColumn() ?: 0);

                if ($orgId === 0) {
                    $stmt = $pdo->prepare("INSERT INTO organizations (name, slug, type, plan, status, created_at, updated_at) VALUES (:name, :slug, :type, 'free', 'active', NOW(), NOW())");
                    $stmt->execute(['name' => $org['name'], 'slug' => $org['slug'], 'type' => $org['type']]);
                    $orgId = (int) $pdo->lastInsertId();
                }
                $orgIds[] = $orgId;
            }

            $users = [
                ['name' => 'Pastor Demo', 'email' => 'pastor@' . self::DEMO_EMAIL_DOMAIN, 'role' => $managerRoleId, 'org' => $orgIds[0]],
                ['name' => 'Líder Demo', 'email' => 'lider@' . self::DEMO_EMAIL_DOMAIN, 'role' => $memberRoleId, 'org' => $orgIds[0]],
                ['name' => 'Membro Demo', 'email' => 'membro@' . self::DEMO_EMAIL_DOMAIN, 'role' => $memberRoleId, 'org' => $orgIds[1]],
            ];

            $passwordHash = password_hash('demo@2026', PASSWORD_DEFAULT);
            foreach ($users as $u) {
                $existing = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
                $existing->execute(['email' => $u['email']]);
                $userId = (int) ($existing->fetchColumn() ?: 0);

                if ($userId === 0) {
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, status, created_at, updated_at) VALUES (:name, :email, :pwd, 'active', NOW(), NOW())");
                    $stmt->execute(['name' => $u['name'], 'email' => $u['email'], 'pwd' => $passwordHash]);
                    $userId = (int) $pdo->lastInsertId();
                }

                $check = $pdo->prepare("SELECT 1 FROM organization_users WHERE organization_id = :org AND user_id = :u LIMIT 1");
                $check->execute(['org' => $u['org'], 'u' => $userId]);
                if (!$check->fetchColumn()) {
                    $link = $pdo->prepare("INSERT INTO organization_users (organization_id, user_id, role_id, status, joined_at) VALUES (:org, :u, :r, 'active', NOW())");
                    $link->execute(['org' => $u['org'], 'u' => $userId, 'r' => $u['role']]);
                }
            }

            $pdo->commit();
            Session::flash('success', 'Dados de demo populados (2 organizações, 3 usuários). Senha padrão: demo@2026');
        } catch (\Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('[AdminDashboard.seedDemo] ' . $e->getMessage());
            Session::flash('error', $this->friendlyDatabaseError($e, 'popular os dados de demo'));
        }

        redirect('/admin');
    }

    public function unseedDemo(Request $request): void
    {
        try {
            $pdo = Database::connection();
            $pdo->beginTransaction();

            $userIds = $pdo->query("SELECT id FROM users WHERE email LIKE '%@" . self::DEMO_EMAIL_DOMAIN . "'")->fetchAll(\PDO::FETCH_COLUMN) ?: [];

            if (!empty($userIds)) {
                $placeholders = implode(',', array_fill(0, count($userIds), '?'));
                $pdo->prepare("DELETE FROM organization_users WHERE user_id IN ({$placeholders})")->execute($userIds);
                $pdo->prepare("DELETE FROM users WHERE id IN ({$placeholders})")->execute($userIds);
            }

            $pdo->prepare("DELETE FROM organizations WHERE slug IN ('demo-esperanca', 'demo-vida')")->execute();

            $pdo->commit();
            Session::flash('success', 'Dados de demo removidos.');
        } catch (\Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('[AdminDashboard.unseedDemo] ' . $e->getMessage());
            Session::flash('error', $this->friendlyDatabaseError($e, 'remover os dados de demo'));
        }

        redirect('/admin');
    }

    private function friendlyDatabaseError(\Throwable $e, string $action): string
    {
        $message = $e->getMessage();
        if (str_contains($message, 'SQLSTATE[HY000] [2002]') || stripos($message, 'Connection refused') !== false) {
            return 'Nao foi possivel ' . $action . ': o banco de dados recusou a conexao. Verifique se o MySQL esta ativo e se DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD ou DATABASE_URL estao corretos no ambiente.';
        }

        return 'Erro ao ' . $action . ': ' . $message;
    }
}
