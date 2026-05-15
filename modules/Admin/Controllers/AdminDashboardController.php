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
                ['name' => 'Igreja Demo Esperanca', 'slug' => 'demo-esperanca', 'type' => 'church'],
                ['name' => 'Comunidade Demo Vida', 'slug' => 'demo-vida', 'type' => 'church'],
            ];

            $orgIds = [];
            foreach ($orgs as $org) {
                $existing = $pdo->prepare("SELECT id FROM organizations WHERE slug = :slug LIMIT 1");
                $existing->execute(['slug' => $org['slug']]);
                $orgId = (int) ($existing->fetchColumn() ?: 0);

                if ($orgId === 0) {
                    $stmt = $pdo->prepare("INSERT INTO organizations (name, slug, type, plan, status, created_at, updated_at) VALUES (:name, :slug, :type, 'premium', 'active', NOW(), NOW())");
                    $stmt->execute(['name' => $org['name'], 'slug' => $org['slug'], 'type' => $org['type']]);
                    $orgId = (int) $pdo->lastInsertId();
                } else {
                    $stmt = $pdo->prepare("UPDATE organizations SET name = :name, type = :type, plan = 'premium', status = 'active', updated_at = NOW() WHERE id = :id");
                    $stmt->execute(['name' => $org['name'], 'type' => $org['type'], 'id' => $orgId]);
                }
                $orgIds[] = $orgId;
            }

            $users = [
                ['name' => 'Pastor Demo', 'email' => 'pastor@' . self::DEMO_EMAIL_DOMAIN, 'role' => $managerRoleId, 'org' => $orgIds[0]],
                ['name' => 'Líder Demo', 'email' => 'lider@' . self::DEMO_EMAIL_DOMAIN, 'role' => $managerRoleId, 'org' => $orgIds[0]],
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
                } else {
                    $link = $pdo->prepare("UPDATE organization_users SET role_id = :r, status = 'active' WHERE organization_id = :org AND user_id = :u");
                    $link->execute(['org' => $u['org'], 'u' => $userId, 'r' => $u['role']]);
                }
            }

            $this->deleteDemoOrgData($pdo, $orgIds);
            $this->seedDemoChurch($pdo, $orgIds[0], [
                'name' => 'Igreja Demo Esperanca',
                'slug' => 'demo-esperanca',
                'city' => 'Jaguariuna',
                'state' => 'SP',
                'address' => 'Rua Alfredo Bueno, 1000, Centro',
                'phone' => '(19) 3838-1100',
                'email' => 'contato@demo-esperanca.local',
                'theme' => '#1455ff',
                'accent' => '#d6a646',
                'hero' => 'https://images.unsplash.com/photo-1438032005730-c779502df39b?auto=format&fit=crop&w=1800&q=80',
            ]);
            $this->seedDemoChurch($pdo, $orgIds[1], [
                'name' => 'Comunidade Demo Vida',
                'slug' => 'demo-vida',
                'city' => 'Campinas',
                'state' => 'SP',
                'address' => 'Avenida Brasil, 1440, Jardim Guanabara',
                'phone' => '(19) 3255-2200',
                'email' => 'contato@demo-vida.local',
                'theme' => '#0f766e',
                'accent' => '#f59e0b',
                'hero' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=80',
            ]);

            $pdo->commit();
            Session::flash('success', 'Dados de demo populados com painel da igreja, site publico, membros, mapa, financas, eventos e campanhas. Senha padrao: demo@2026');
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

            $orgIds = $pdo->query("SELECT id FROM organizations WHERE slug IN ('demo-esperanca', 'demo-vida')")->fetchAll(\PDO::FETCH_COLUMN) ?: [];
            $userIds = $pdo->query("SELECT id FROM users WHERE email LIKE '%@" . self::DEMO_EMAIL_DOMAIN . "'")->fetchAll(\PDO::FETCH_COLUMN) ?: [];

            $this->deleteDemoOrgData($pdo, array_map('intval', $orgIds));

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

    private function deleteDemoOrgData(\PDO $pdo, array $orgIds): void
    {
        $orgIds = array_values(array_filter(array_map('intval', $orgIds), static fn (int $id): bool => $id > 0));
        if (empty($orgIds)) {
            return;
        }

        $in = implode(',', array_fill(0, count($orgIds), '?'));

        foreach ([
            "DELETE mm FROM ministry_members mm JOIN ministries mi ON mi.id = mm.ministry_id WHERE mi.organization_id IN ({$in})",
            "DELETE er FROM event_registrations er JOIN events e ON e.id = er.event_id WHERE e.organization_id IN ({$in})",
            "DELETE sgm FROM small_group_members sgm JOIN small_groups sg ON sg.id = sgm.small_group_id WHERE sg.organization_id IN ({$in})",
            "DELETE ma FROM member_achievements ma JOIN members m ON m.id = ma.member_id WHERE m.organization_id IN ({$in})",
        ] as $sql) {
            try {
                $pdo->prepare($sql)->execute($orgIds);
            } catch (\Throwable $e) {
                // Optional demo tables may not exist in older installs.
            }
        }

        foreach ([
            'organization_sites',
            'settings',
            'financial_transactions',
            'donations',
            'financial_categories',
            'events',
            'ministries',
            'members',
            'church_units',
            'preachers',
            'sermons',
            'campaigns',
            'reading_plans',
            'small_groups',
            'banners',
            'visits',
            'church_requests',
            'counseling_sessions',
            'action_plans',
        ] as $table) {
            $this->deleteByOrganization($pdo, $table, $orgIds);
        }
    }

    private function deleteByOrganization(\PDO $pdo, string $table, array $orgIds): void
    {
        if (!$this->tableExists($pdo, $table)) {
            return;
        }

        $in = implode(',', array_fill(0, count($orgIds), '?'));
        try {
            $pdo->prepare("DELETE FROM {$table} WHERE organization_id IN ({$in})")->execute($orgIds);
        } catch (\Throwable $e) {
            // Keep demo seed resilient across partial schemas.
        }
    }

    private function tableExists(\PDO $pdo, string $table): bool
    {
        try {
            $driver = (string) $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
            if ($driver === 'sqlite') {
                $stmt = $pdo->prepare("SELECT name FROM sqlite_master WHERE type = 'table' AND name = :table LIMIT 1");
                $stmt->execute(['table' => $table]);
                return (bool) $stmt->fetchColumn();
            }

            $stmt = $pdo->prepare('SHOW TABLES LIKE :table');
            $stmt->execute(['table' => $table]);
            return (bool) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function seedDemoChurch(\PDO $pdo, int $orgId, array $profile): void
    {
        $city = (string) $profile['city'];
        $state = (string) $profile['state'];
        $isCampinas = $city === 'Campinas';
        $baseLat = $isCampinas ? -22.9056 : -22.7057;
        $baseLng = $isCampinas ? -47.0608 : -46.9854;

        $unitIds = [];
        if ($this->tableExists($pdo, 'church_units')) {
            $stmt = $pdo->prepare("INSERT INTO church_units (organization_id, name, code, address, city, state, phone, status, created_at, updated_at) VALUES (:org, :name, :code, :address, :city, :state, :phone, 'active', NOW(), NOW())");
            foreach ([
                ['name' => 'Sede', 'code' => 'SEDE', 'address' => $profile['address'], 'phone' => $profile['phone']],
                ['name' => 'Unidade Norte', 'code' => 'NORTE', 'address' => 'Rua das Flores, 220', 'phone' => '(19) 3000-2200'],
            ] as $unit) {
                $stmt->execute([
                    'org' => $orgId,
                    'name' => $unit['name'],
                    'code' => $unit['code'],
                    'address' => $unit['address'],
                    'city' => $city,
                    'state' => $state,
                    'phone' => $unit['phone'],
                ]);
                $unitIds[] = (int) $pdo->lastInsertId();
            }
        }

        $memberIds = [];
        $members = [
            ['Mateus Mendes Carvalho', 'mateus.mendes', '(19) 98386-9538', '1993-04-12', 'M', 'married', 0.0000, 0.0000],
            ['Ana Paula Ribeiro', 'ana.ribeiro', '(19) 99121-4410', '1988-09-23', 'F', 'married', 0.0040, -0.0027],
            ['Lucas Ferreira Santos', 'lucas.santos', '(19) 98844-2001', '2001-02-18', 'M', 'single', -0.0031, 0.0035],
            ['Mariana Costa Lima', 'mariana.lima', '(19) 98220-1187', '1996-11-05', 'F', 'single', 0.0022, 0.0041],
            ['Joao Pedro Almeida', 'joao.almeida', '(19) 99770-4301', '1979-07-30', 'M', 'married', -0.0054, -0.0042],
            ['Beatriz Nogueira', 'beatriz.nogueira', '(19) 98412-7200', '1990-01-16', 'F', 'married', 0.0063, 0.0018],
            ['Rafael Souza', 'rafael.souza', '(19) 98999-8801', '1984-05-09', 'M', 'single', -0.0027, -0.0063],
            ['Camila Martins', 'camila.martins', '(19) 98660-3355', '2003-12-02', 'F', 'single', 0.0071, -0.0035],
            ['Samuel Oliveira', 'samuel.oliveira', '(19) 98110-0044', '1972-03-21', 'M', 'widowed', -0.0061, 0.0058],
            ['Priscila Araujo', 'priscila.araujo', '(19) 99442-1500', '1998-08-14', 'F', 'single', 0.0036, 0.0070],
        ];

        $memberStmt = $pdo->prepare("
            INSERT INTO members (
                organization_id, church_unit_id, name, email, phone, birth_date, gender, marital_status,
                address, city, state, zip_code, latitude, longitude, membership_date, baptism_date, status, notes, created_at, updated_at
            ) VALUES (
                :org, :unit, :name, :email, :phone, :birth, :gender, :marital,
                :address, :city, :state, :zip, :lat, :lng, :membership, :baptism, :status, :notes, NOW(), NOW()
            )
        ");
        foreach ($members as $idx => $member) {
            $memberStmt->execute([
                'org' => $orgId,
                'unit' => $unitIds[$idx % max(1, count($unitIds))] ?? null,
                'name' => $member[0],
                'email' => $member[1] . '@' . $profile['slug'] . '.local',
                'phone' => $member[2],
                'birth' => $member[3],
                'gender' => $member[4],
                'marital' => $member[5],
                'address' => 'Rua Demo ' . (60 + ($idx * 7)) . ', Centro',
                'city' => $city,
                'state' => $state,
                'zip' => '13820-000',
                'lat' => number_format($baseLat + (float) $member[6], 7, '.', ''),
                'lng' => number_format($baseLng + (float) $member[7], 7, '.', ''),
                'membership' => date('Y-m-d', strtotime('-' . (30 + ($idx * 18)) . ' days')),
                'baptism' => $idx % 3 === 0 ? date('Y-m-d', strtotime('-' . (120 + ($idx * 11)) . ' days')) : null,
                'status' => $idx === 9 ? 'visitor' : 'active',
                'notes' => $idx === 0 ? 'Membro demo com localizacao definida para validar o mapa.' : null,
            ]);
            $memberIds[] = (int) $pdo->lastInsertId();
        }

        if ($this->tableExists($pdo, 'ministries')) {
            $ministryStmt = $pdo->prepare("INSERT INTO ministries (organization_id, church_unit_id, name, description, leader_member_id, color, status, created_at, updated_at) VALUES (:org, :unit, :name, :description, :leader, :color, 'active', NOW(), NOW())");
            $linkStmt = $this->tableExists($pdo, 'ministry_members') ? $pdo->prepare("INSERT INTO ministry_members (ministry_id, member_id, role, joined_at) VALUES (:ministry, :member, :role, NOW())") : null;
            foreach ([
                ['Louvor e Artes', 'Equipe de musica, celebracao e producao dos cultos.', '#1455ff'],
                ['Infantil', 'Cuidado e ensino biblico para criancas.', '#10b981'],
                ['Jovens', 'Encontros, discipulado e pequenos grupos de jovens.', '#f59e0b'],
                ['Acao Social', 'Projetos de apoio a familias e campanhas solidarias.', '#ef4444'],
            ] as $idx => $ministry) {
                $leaderId = $memberIds[$idx] ?? null;
                $ministryStmt->execute([
                    'org' => $orgId,
                    'unit' => $unitIds[$idx % max(1, count($unitIds))] ?? null,
                    'name' => $ministry[0],
                    'description' => $ministry[1],
                    'leader' => $leaderId,
                    'color' => $ministry[2],
                ]);
                $ministryId = (int) $pdo->lastInsertId();
                if ($linkStmt) {
                    foreach (array_slice($memberIds, $idx, 4) as $memberId) {
                        $linkStmt->execute(['ministry' => $ministryId, 'member' => $memberId, 'role' => $memberId === $leaderId ? 'leader' : 'member']);
                    }
                }
            }
        }

        if ($this->tableExists($pdo, 'financial_categories')) {
            $catStmt = $pdo->prepare("INSERT INTO financial_categories (organization_id, name, type, color, is_system, created_at) VALUES (:org, :name, :type, :color, 1, NOW())");
            $categoryIds = [];
            foreach ([
                ['Dizimos', 'income', '#1455ff'],
                ['Ofertas', 'income', '#10b981'],
                ['Missoes', 'income', '#f59e0b'],
                ['Aluguel e estrutura', 'expense', '#ef4444'],
                ['Acao social', 'expense', '#8b5cf6'],
            ] as $category) {
                $catStmt->execute(['org' => $orgId, 'name' => $category[0], 'type' => $category[1], 'color' => $category[2]]);
                $categoryIds[$category[0]] = (int) $pdo->lastInsertId();
            }

            $txStmt = $pdo->prepare("INSERT INTO financial_transactions (organization_id, church_unit_id, category_id, type, description, amount, transaction_date, reference, member_id, status, notes, created_at, updated_at) VALUES (:org, :unit, :cat, :type, :description, :amount, :date, :reference, :member, 'confirmed', :notes, NOW(), NOW())");
            foreach ([
                ['income', 'Dizimos', 'Dizimo mensal', 850.00, '-2 days', 0],
                ['income', 'Ofertas', 'Oferta culto de domingo', 640.00, '-5 days', 1],
                ['income', 'Missoes', 'Campanha missionaria', 1200.00, '-8 days', 2],
                ['expense', 'Aluguel e estrutura', 'Manutencao predial', 430.00, '-4 days', null],
                ['expense', 'Acao social', 'Cestas basicas', 780.00, '-10 days', null],
            ] as $tx) {
                $txStmt->execute([
                    'org' => $orgId,
                    'unit' => $unitIds[0] ?? null,
                    'cat' => $categoryIds[$tx[1]] ?? null,
                    'type' => $tx[0],
                    'description' => $tx[2],
                    'amount' => $tx[3],
                    'date' => date('Y-m-d', strtotime($tx[4])),
                    'reference' => 'DEMO-' . strtoupper(substr(md5((string) $tx[2]), 0, 6)),
                    'member' => $tx[5] !== null ? ($memberIds[(int) $tx[5]] ?? null) : null,
                    'notes' => 'Lancamento demo',
                ]);
            }
        }

        if ($this->tableExists($pdo, 'donations')) {
            $donationStmt = $pdo->prepare("INSERT INTO donations (organization_id, church_unit_id, member_id, donor_name, type, amount, donation_date, payment_method, reference, notes, created_at) VALUES (:org, :unit, :member, :donor, :type, :amount, :date, :method, :reference, :notes, NOW())");
            foreach (array_slice($members, 0, 8) as $idx => $member) {
                foreach ([['tithe', 180 + ($idx * 35)], ['offering', 60 + ($idx * 12)]] as $entry) {
                    $donationStmt->execute([
                        'org' => $orgId,
                        'unit' => $unitIds[$idx % max(1, count($unitIds))] ?? null,
                        'member' => $memberIds[$idx] ?? null,
                        'donor' => $member[0],
                        'type' => $entry[0],
                        'amount' => $entry[1],
                        'date' => date('Y-m-d', strtotime('-' . (1 + $idx) . ' days')),
                        'method' => $idx % 2 === 0 ? 'pix' : 'cash',
                        'reference' => 'DOA-DEMO-' . ($idx + 1),
                        'notes' => 'Doacao demo para ranking e dashboard',
                    ]);
                }
            }
        }

        if ($this->tableExists($pdo, 'events')) {
            $eventStmt = $pdo->prepare("INSERT INTO events (organization_id, church_unit_id, title, description, location, start_date, end_date, max_registrations, status, created_at, updated_at) VALUES (:org, :unit, :title, :description, :location, :start, :end, :max, 'published', NOW(), NOW())");
            foreach ([
                ['Culto de Celebracao', 'Domingo com louvor, palavra e comunhao.', 'Templo sede', '+4 days 19:00', '+4 days 21:00', 250],
                ['Encontro de Jovens', 'Noite de comunhao, devocional e pequenos grupos.', 'Sala multiuso', '+8 days 20:00', '+8 days 22:00', 90],
                ['Cafe com Voluntarios', 'Alinhamento de escalas e cuidado com equipes.', 'Espaco cafe', '+12 days 09:00', '+12 days 11:00', 60],
            ] as $event) {
                $eventStmt->execute([
                    'org' => $orgId,
                    'unit' => $unitIds[0] ?? null,
                    'title' => $event[0],
                    'description' => $event[1],
                    'location' => $event[2],
                    'start' => date('Y-m-d H:i:s', strtotime($event[3])),
                    'end' => date('Y-m-d H:i:s', strtotime($event[4])),
                    'max' => $event[5],
                ]);
            }
        }

        if ($this->tableExists($pdo, 'sermons')) {
            $sermonStmt = $pdo->prepare("INSERT INTO sermons (organization_id, church_unit_id, title, preacher, sermon_date, bible_reference, summary, series_name, tags, status, created_at, updated_at) VALUES (:org, :unit, :title, :preacher, :date, :reference, :summary, :series, :tags, 'published', NOW(), NOW())");
            foreach ([
                ['Chamados para Servir', 'Pastor Demo', '-3 days', 'Marcos 10:45', 'Uma mensagem sobre servico cristao no cotidiano.', 'Vida em Missao', 'servico,missao'],
                ['Comunidade que Cuida', 'Lider Demo', '-10 days', 'Atos 2:42-47', 'Praticas de comunhao e cuidado mutuo.', 'Vida em Missao', 'comunidade,cuidado'],
                ['Esperanca em Tempos Dificeis', 'Pastor Demo', '-17 days', 'Romanos 15:13', 'A esperanca biblica como fundamento para perseverar.', 'Fundamentos', 'esperanca,fe'],
            ] as $sermon) {
                $sermonStmt->execute([
                    'org' => $orgId,
                    'unit' => $unitIds[0] ?? null,
                    'title' => $sermon[0],
                    'preacher' => $sermon[1],
                    'date' => date('Y-m-d', strtotime($sermon[2])),
                    'reference' => $sermon[3],
                    'summary' => $sermon[4],
                    'series' => $sermon[5],
                    'tags' => $sermon[6],
                ]);
            }
        }

        if ($this->tableExists($pdo, 'campaigns')) {
            $campaignStmt = $pdo->prepare("INSERT INTO campaigns (organization_id, church_unit_id, title, description, goal_amount, raised_amount, designation, end_date, status, created_at, updated_at) VALUES (:org, :unit, :title, :description, :goal, :raised, :designation, :end_date, 'active', NOW(), NOW())");
            foreach ([
                ['Cestas de Amor', 'Campanha para apoiar familias da cidade com alimentos e acolhimento.', 12000, 7450, 'Acao social'],
                ['Reforma da Sala Infantil', 'Adequacao de ambiente seguro e acolhedor para criancas.', 18000, 9800, 'Estrutura'],
            ] as $campaign) {
                $campaignStmt->execute([
                    'org' => $orgId,
                    'unit' => $unitIds[0] ?? null,
                    'title' => $campaign[0],
                    'description' => $campaign[1],
                    'goal' => $campaign[2],
                    'raised' => $campaign[3],
                    'designation' => $campaign[4],
                    'end_date' => date('Y-m-d', strtotime('+45 days')),
                ]);
            }
        }

        if ($this->tableExists($pdo, 'small_groups')) {
            $sgStmt = $pdo->prepare("INSERT INTO small_groups (organization_id, name, description, leader_member_id, meeting_day, meeting_time, location, max_members, status, created_at, updated_at) VALUES (:org, :name, :description, :leader, :day, :time, :location, :max, 'active', NOW(), NOW())");
            foreach ([
                ['PG Centro', 'Grupo pequeno para estudo e comunhao no centro.', 1, 'Quarta', '20:00:00', 'Casa da Ana Paula', 14],
                ['PG Jovens', 'Encontro semanal para jovens e universitarios.', 2, 'Sexta', '20:30:00', 'Sala multiuso', 20],
            ] as $group) {
                $sgStmt->execute([
                    'org' => $orgId,
                    'name' => $group[0],
                    'description' => $group[1],
                    'leader' => $memberIds[$group[2]] ?? null,
                    'day' => $group[3],
                    'time' => $group[4],
                    'location' => $group[5],
                    'max' => $group[6],
                ]);
            }
        }

        if ($this->tableExists($pdo, 'banners')) {
            $bannerStmt = $pdo->prepare("INSERT INTO banners (organization_id, title, image_url, link_url, position, start_date, end_date, sort_order, status, created_at, updated_at) VALUES (:org, :title, :image, :link, 'home_top', CURDATE(), :end_date, :sort, 'active', NOW(), NOW())");
            foreach ([
                ['Culto de Celebracao', $profile['hero'], '#eventos', 10],
                ['Campanha Cestas de Amor', 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=1200&q=80', '#campanhas', 8],
            ] as $banner) {
                $bannerStmt->execute([
                    'org' => $orgId,
                    'title' => $banner[0],
                    'image' => $banner[1],
                    'link' => $banner[2],
                    'end_date' => date('Y-m-d', strtotime('+60 days')),
                    'sort' => $banner[3],
                ]);
            }
        }

        if ($this->tableExists($pdo, 'organization_sites')) {
            $siteStmt = $pdo->prepare("
                INSERT INTO organization_sites (
                    organization_id, template, status, site_title, slug, domain, theme_color, hero_image, logo_image,
                    site_description, about_text, contact_email, contact_phone, whatsapp_url, instagram_url, facebook_url,
                    youtube_url, address_line, city, state, cta_label, cta_url, published_url, service_times, gallery_images,
                    published_at, created_at, updated_at
                ) VALUES (
                    :org, 'Institucional Classico', 'published', :title, :slug, NULL, :theme, :hero, '/assets/img/logo-color-new.png',
                    :description, :about, :email, :phone, :whatsapp, :instagram, :facebook,
                    :youtube, :address, :city, :state, 'Planejar visita', :cta, :published_url, :service_times, :gallery,
                    NOW(), NOW(), NOW()
                )
            ");
            $siteStmt->execute([
                'org' => $orgId,
                'title' => $profile['name'],
                'slug' => $profile['slug'],
                'theme' => $profile['theme'],
                'hero' => $profile['hero'],
                'description' => 'Uma comunidade crista demo com agenda, ministerios, campanhas, grupos e canais de contato preenchidos para validar o site publico.',
                'about' => 'Somos uma igreja demo criada para demonstrar o painel da Elo 42 com dados completos: membros, eventos, financas, campanhas, ministerios, sermoes e mapa de membros.',
                'email' => $profile['email'],
                'phone' => $profile['phone'],
                'whatsapp' => 'https://wa.me/5519999990000',
                'instagram' => 'https://instagram.com/elo42',
                'facebook' => 'https://facebook.com/elo42',
                'youtube' => 'https://youtube.com/@elo42',
                'address' => $profile['address'],
                'city' => $city,
                'state' => $state,
                'cta' => 'https://wa.me/5519999990000',
                'published_url' => '/site/' . $profile['slug'],
                'service_times' => "Domingo 10h e 19h\nQuarta 20h",
                'gallery' => json_encode([
                    $profile['hero'],
                    'https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1200&q=80',
                ], JSON_UNESCAPED_SLASHES),
            ]);
        }

        if ($this->tableExists($pdo, 'settings')) {
            $this->insertDemoSettings($pdo, $orgId, [
                'appearance_primary' => $profile['theme'],
                'appearance_accent' => $profile['accent'],
                'appearance_background' => '#f4f7fd',
                'appearance_text' => '#06183a',
                'service_times' => "Domingo 10h e 19h\nQuarta 20h",
                'social_instagram' => 'https://instagram.com/elo42',
                'social_facebook' => 'https://facebook.com/elo42',
                'social_youtube' => 'https://youtube.com/@elo42',
                'social_whatsapp' => 'https://wa.me/5519999990000',
                'public_registration_active' => '1',
                'public_registration_slug' => 'cadastro-' . $profile['slug'],
                'public_registration_welcome' => 'Preencha seus dados. Nossa equipe entrara em contato para acolher voce.',
                'pix_type' => 'email',
                'pix_key' => $profile['email'],
                'pix_name' => $profile['name'],
            ]);
        }
    }

    private function insertDemoSettings(\PDO $pdo, int $orgId, array $settings): void
    {
        $stmt = $pdo->prepare(
            'INSERT INTO settings (`group`, `key`, value, type, organization_id, created_at, updated_at)
             VALUES (:group_name, :setting_key, :setting_value, :setting_type, :org_id, NOW(), NOW())'
        );

        foreach ($settings as $key => $value) {
            $stmt->execute([
                'group_name' => 'church',
                'setting_key' => (string) $key,
                'setting_value' => (string) $value,
                'setting_type' => 'string',
                'org_id' => $orgId,
            ]);
        }
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
