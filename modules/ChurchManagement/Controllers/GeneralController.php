<?php

declare(strict_types=1);

namespace Modules\ChurchManagement\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use App\Models\ChurchRequest;
use App\Models\Visit;
use App\Models\CounselingSession;
use App\Models\Sermon;
use App\Models\ActionPlan;
use App\Models\Donation;
use App\Models\Member;
use App\Models\Event;
use App\Models\FinancialTransaction;
use App\Models\User;

class GeneralController extends Controller
{
    private function orgId(): int 
    { 
        $org = Session::get('organization');
        if (is_array($org) && !empty($org['id'])) {
            return (int) $org['id'];
        }

        $user = Session::user() ?? [];
        $userId = (int) ($user['id'] ?? 0);
        if ($userId > 0) {
            try {
                $dbOrg = User::getOrganization($userId);
                if ($dbOrg) {
                    Session::set('organization', [
                        'id'        => $dbOrg['id'],
                        'name'      => $dbOrg['name'],
                        'slug'      => $dbOrg['slug'] ?? '',
                        'type'      => $dbOrg['type'] ?? '',
                        'plan'      => $dbOrg['plan'] ?? 'trial',
                        'status'    => $dbOrg['status'] ?? 'trial',
                        'role_slug' => $dbOrg['role_slug'] ?? null,
                        'role_name' => $dbOrg['role_name'] ?? null,
                    ]);
                    return (int) $dbOrg['id'];
                }
            } catch (\Throwable $e) {}
        }

        return 0;
    }

    private function buildBaseContext(string $breadcrumb, string $activeMenu): array
    {
        return [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'organization' => Session::get('organization') ?: [],
            'user' => Session::user() ?: [],
        ];
    }

    private function hasPremiumAccess(): bool
    {
        $organization = Session::get('organization');
        $organization = is_array($organization) ? $organization : [];
        $user = Session::user() ?? [];
        $permissions = is_array($user['permissions'] ?? null) ? $user['permissions'] : [];
        $plan = strtolower((string) ($organization['plan'] ?? 'free'));
        $roleSlug = (string) ($organization['role_slug'] ?? '');
        $premiumPlans = ['premium', 'professional', 'enterprise'];

        if (in_array($plan, $premiumPlans, true)) {
            return true;
        }

        if (
            in_array($roleSlug, ['super-admin', 'admin-elo42'], true)
            || in_array('admin.access', $permissions, true)
            || strtolower((string) ($user['email'] ?? '')) === 'ricieri@starmannweb.com.br'
        ) {
            return true;
        }

        if ($plan === 'free' && !empty($user['created_at'])) {
            try {
                return new \DateTimeImmutable('now') < (new \DateTimeImmutable((string) $user['created_at']))->modify('+7 days');
            } catch (\Throwable $e) {
                return false;
            }
        }

        return false;
    }

    private function containsPremiumSetting(array $payload): bool
    {
        $exactKeys = [
            'pix_type',
            'pix_key',
            'pix_name',
            'pix_beneficiary',
            'pix_instruction',
            'openai_key',
            'model_analysis',
            'model_generation',
            'seo_title',
            'seo_desc',
            'seo_keywords',
            'pwa_name',
            'pwa_short_name',
            'pwa_desc',
            'theme_color',
            'background_color',
            'pwa_icon_192',
            'pwa_icon_512',
            'payment_gateway',
            'payment_public_key',
            'payment_secret_key',
            'whatsapp_provider',
            'email_provider',
            'webhook_url',
        ];

        $prefixes = [
            'appearance_',
            'social_',
        ];

        foreach (array_keys($payload) as $key) {
            $key = (string) $key;
            if (in_array($key, $exactKeys, true)) {
                return true;
            }

            foreach ($prefixes as $prefix) {
                if (str_starts_with($key, $prefix)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function orgUsers(): array
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("
                SELECT u.*, ou.role_id, r.name as role_name, r.slug as role_slug, ou.id as org_user_id
                FROM users u
                JOIN organization_users ou ON u.id = ou.user_id
                LEFT JOIN roles r ON ou.role_id = r.id
                WHERE ou.organization_id = :org_id AND ou.status = 'active'
                ORDER BY u.name ASC
            ");
            $stmt->execute(['org_id' => $this->orgId()]);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            Session::flash('warning', 'Usuarios indisponiveis no momento. Exibindo modo de contingencia.');
            return [];
        }
    }

    private function settingValues(array $keys = []): array
    {
        try {
            $pdo = Database::connection();
            $params = ['org_id' => $this->orgId()];
            $sql = "SELECT `key`, value FROM settings WHERE organization_id = :org_id";

            if (!empty($keys)) {
                $placeholders = [];
                foreach (array_values($keys) as $index => $key) {
                    $name = 'key_' . $index;
                    $placeholders[] = ':' . $name;
                    $params[$name] = (string) $key;
                }
                $sql .= ' AND `key` IN (' . implode(',', $placeholders) . ')';
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            $values = [];
            foreach ($stmt->fetchAll() as $row) {
                $values[(string) ($row['key'] ?? '')] = (string) ($row['value'] ?? '');
            }

            return $values;
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function saveSettingValues(array $values, string $group = 'church'): void
    {
        if (empty($values)) {
            return;
        }

        $pdo = Database::connection();
        $orgId = $this->orgId();
        $delete = $pdo->prepare('DELETE FROM settings WHERE organization_id = :org_id AND `key` = :setting_key');
        $insert = $pdo->prepare(
            'INSERT INTO settings (`group`, `key`, value, type, organization_id, created_at, updated_at)
             VALUES (:group_name, :setting_key, :setting_value, :setting_type, :org_id, NOW(), NOW())'
        );

        foreach ($values as $key => $value) {
            $key = (string) $key;
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $key)) {
                continue;
            }

            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $type = 'json';
            } elseif (is_bool($value)) {
                $value = $value ? '1' : '0';
                $type = 'boolean';
            } else {
                $value = trim((string) $value);
                $type = is_numeric($value) ? 'string' : 'string';
            }

            $params = [
                'group_name' => $group,
                'setting_key' => $key,
                'org_id' => $orgId,
            ];
            $delete->execute([
                'setting_key' => $key,
                'org_id' => $orgId,
            ]);
            $insert->execute($params + [
                'setting_value' => $value,
                'setting_type' => $type,
            ]);
        }
    }

    private function churchUnits(): array
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare('SELECT * FROM church_units WHERE organization_id = :org_id ORDER BY status ASC, name ASC');
            $stmt->execute(['org_id' => $this->orgId()]);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function preachersList(): array
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare(
                'SELECT p.*, u.name AS unit_name
                 FROM preachers p
                 LEFT JOIN church_units u ON u.id = p.church_unit_id
                 WHERE p.organization_id = :org_id
                 ORDER BY p.status ASC, p.name ASC'
            );
            $stmt->execute(['org_id' => $this->orgId()]);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    // --- Requests ---
    public function requests(Request $req): void
    {
        try {
            $this->view('management/requests/index', [
                'pageTitle' => 'Solicitações — Gestão', 'breadcrumb' => 'Solicitações',
                'requests' => ChurchRequest::byOrg($this->orgId()),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar solicitações: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function createRequest(Request $req): void
    {
        try {
            $this->view('management/requests/form', [
                'pageTitle' => 'Nova solicitação', 'breadcrumb' => 'Solicitações / Nova',
                'item' => null, 'members' => Member::byOrg($this->orgId(), [], 1, 500)['data'],
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar formulário: ' . $e->getMessage());
            redirect('/gestao/solicitacoes');
        }
    }

    public function storeRequest(Request $req): void
    {
        $this->validate($req, ['title' => 'required']);
        ChurchRequest::create(array_merge($req->only(['title','description','type','priority','member_id']), [
            'organization_id' => $this->orgId(), 'created_by' => Session::user()['id'],
        ]));
        Session::flash('success', 'Solicitação registrada.');
        redirect('/gestao/solicitacoes');
    }

    public function updateRequestStatus(Request $req): void
    {
        $id = (int) $req->param('id');
        $item = ChurchRequest::find($id);
        if (!$item || (int)$item['organization_id'] !== $this->orgId()) { redirect('/gestao/solicitacoes'); }
        $data = ['status' => $req->input('status')];
        if ($req->input('status') === 'resolved') { $data['resolved_at'] = date('Y-m-d H:i:s'); }
        ChurchRequest::update($id, $data);
        Session::flash('success', 'Status atualizado.');
        redirect('/gestao/solicitacoes');
    }

    // --- Visits ---
    public function visits(Request $req): void
    {
        try {
            $this->view('management/visits/index', [
                'pageTitle' => 'Visitas — Gestão', 'breadcrumb' => 'Visitas',
                'visits' => Visit::byOrg($this->orgId()),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar visitas: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function createVisit(Request $req): void
    {
        try {
            $this->view('management/visits/form', [
                'pageTitle' => 'Nova visita', 'breadcrumb' => 'Visitas / Nova', 'item' => null,
                'members' => Member::byOrg($this->orgId(), [], 1, 500)['data'],
                'units' => $this->churchUnits(),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar formulário: ' . $e->getMessage());
            redirect('/gestao/visitas');
        }
    }

    public function storeVisit(Request $req): void
    {
        $this->validate($req, ['visitor_name' => 'required', 'visit_date' => 'required']);

        $payload = $req->only(['visitor_name','phone','email','visit_date','source','notes','assigned_to']);

        foreach (['phone', 'email', 'notes'] as $optional) {
            if (isset($payload[$optional]) && trim((string) $payload[$optional]) === '') {
                $payload[$optional] = null;
            }
        }

        $assigned = $payload['assigned_to'] ?? null;
        $payload['assigned_to'] = ($assigned === '' || $assigned === null) ? null : (int) $assigned;

        try {
            Visit::create(array_merge($payload, [
                'organization_id' => $this->orgId(),
            ]));
            Session::flash('success', 'Visita registrada.');
        } catch (\Throwable $e) {
            Session::flash('error', 'Não foi possível registrar a visita: ' . $e->getMessage());
        }

        redirect('/gestao/visitas');
    }

    public function updateVisitFollowUp(Request $req): void
    {
        $id = (int) $req->param('id');
        $v = Visit::find($id);
        if (!$v || (int)$v['organization_id'] !== $this->orgId()) { redirect('/gestao/visitas'); }
        Visit::update($id, ['follow_up' => $req->input('follow_up')]);
        Session::flash('success', 'Status atualizado.');
        redirect('/gestao/visitas');
    }

    // --- Counseling ---
    public function counseling(Request $req): void
    {
        try {
            $allSessions = CounselingSession::byOrg($this->orgId());
            $statusFilter = (string) $req->input('status', 'pending');
            $allowedFilters = ['pending', 'in_progress', 'completed', 'all'];
            if (!in_array($statusFilter, $allowedFilters, true)) {
                $statusFilter = 'pending';
            }

            $statusGroup = static function (array $session): string {
                return match ((string) ($session['status'] ?? 'pending')) {
                    'scheduled', 'pending' => 'pending',
                    'in_progress' => 'in_progress',
                    'completed' => 'completed',
                    default => 'pending',
                };
            };

            $counts = ['pending' => 0, 'in_progress' => 0, 'completed' => 0];
            foreach ($allSessions as $session) {
                $counts[$statusGroup($session)]++;
            }

            $sessions = $statusFilter === 'all'
                ? $allSessions
                : array_values(array_filter($allSessions, static fn (array $session): bool => $statusGroup($session) === $statusFilter));

            $this->view('management/counseling/index', [
                'pageTitle' => 'Aconselhamento — Gestão', 'breadcrumb' => 'Aconselhamento',
                'sessions' => $sessions,
                'statusCounts' => $counts,
                'statusFilter' => $statusFilter,
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar aconselhamento: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function createCounseling(Request $req): void
    {
        try {
            $this->view('management/counseling/form', [
                'pageTitle' => 'Novo atendimento', 'breadcrumb' => 'Aconselhamento / Novo', 'item' => null,
                'members' => Member::byOrg($this->orgId(), [], 1, 500)['data'],
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar formulário: ' . $e->getMessage());
            redirect('/gestao/aconselhamento');
        }
    }

    public function storeCounseling(Request $req): void
    {
        $this->validate($req, ['subject' => 'required', 'session_date' => 'required']);
        CounselingSession::create(array_merge($req->only(['member_id','counselor_name','subject','session_date','status','notes','is_confidential']), [
            'organization_id' => $this->orgId(),
        ]));
        Session::flash('success', 'Sessão registrada.');
        redirect('/gestao/aconselhamento');
    }

    // --- Sermons ---
    public function sermons(Request $req): void
    {
        try {
            $this->view('management/sermons/index', [
                'pageTitle' => 'Sermões — Gestão', 'breadcrumb' => 'Sermões',
                'sermons' => Sermon::byOrg($this->orgId(), $req->input('search')),
                'preachers' => $this->preachersList(),
                'units' => $this->churchUnits(),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar sermões: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function createSermon(Request $req): void
    {
        try {
            $this->view('management/sermons/form', [
                'pageTitle' => 'Novo sermão', 'breadcrumb' => 'Sermões / Novo', 'item' => null,
                'preachers' => $this->preachersList(),
                'units' => $this->churchUnits(),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar formulário: ' . $e->getMessage());
            redirect('/gestao/sermoes');
        }
    }

    public function storeSermon(Request $req): void
    {
        $this->validate($req, ['title' => 'required']);
        $preacher = trim((string) $req->input('preacher'));
        $preacherId = (int) $req->input('preacher_id', 0);
        if ($preacher === '' && $preacherId > 0) {
            foreach ($this->preachersList() as $item) {
                if ((int) ($item['id'] ?? 0) === $preacherId) {
                    $preacher = (string) ($item['name'] ?? '');
                    break;
                }
            }
        }

        $data = $req->only(['title','sermon_date','bible_reference','summary','series_name','tags','status','church_unit_id']);
        $data['church_unit_id'] = (int) ($data['church_unit_id'] ?? 0) ?: null;

        Sermon::create(array_merge($data, [
            'organization_id' => $this->orgId(),
            'preacher' => $preacher !== '' ? $preacher : null,
        ]));
        Session::flash('success', 'Sermão registrado.');
        redirect('/gestao/sermoes');
    }

    // --- Action Plans ---
    public function plans(Request $req): void
    {
        try {
            $this->view('management/plans/index', [
                'pageTitle' => 'Plano de Ação — Gestão', 'breadcrumb' => 'Plano de Ação',
                'plans' => ActionPlan::byOrg($this->orgId()),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar planos: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function createPlan(Request $req): void
    {
        try {
            $this->view('management/plans/form', [
                'pageTitle' => 'Novo plano', 'breadcrumb' => 'Plano de Ação / Novo', 'item' => null,
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar formulário: ' . $e->getMessage());
            redirect('/gestao/planos');
        }
    }

    public function storePlan(Request $req): void
    {
        $this->validate($req, ['title' => 'required']);
        ActionPlan::create(array_merge($req->only(['title','description','start_date','end_date','status']), [
            'organization_id' => $this->orgId(), 'created_by' => Session::user()['id'],
        ]));
        Session::flash('success', 'Plano criado.');
        redirect('/gestao/planos');
    }

    public function showPlan(Request $req): void
    {
        try {
            $plan = ActionPlan::getWithDetails((int) $req->param('id'));
            if (!$plan) { redirect('/gestao/planos'); }
            $this->view('management/plans/show', [
                'pageTitle' => e($plan['title']) . ' — Gestão', 'breadcrumb' => 'Planos / ' . $plan['title'],
                'plan' => $plan, 'members' => Member::byOrg($this->orgId(), [], 1, 500)['data'],
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar plano: ' . $e->getMessage());
            redirect('/gestao/planos');
        }
    }

    public function storeObjective(Request $req): void
    {
        $planId = (int) $req->param('id');
        $this->validate($req, ['title' => 'required']);

        try {
            $pdo = Database::connection();
            $pdo->prepare("INSERT INTO action_plan_objectives (plan_id, title, description) VALUES (:pid, :title, :desc)")
                ->execute(['pid' => $planId, 'title' => $req->input('title'), 'desc' => $req->input('description')]);
            Session::flash('success', 'Objetivo adicionado.');
        } catch (\Throwable $e) {
            Session::flash('error', 'Nao foi possivel adicionar objetivo agora.');
        }

        redirect('/gestao/planos/' . $planId);
    }

    public function storeTask(Request $req): void
    {
        $objId = (int) $req->param('objective_id');
        $planId = (int) $req->param('id');
        $this->validate($req, ['title' => 'required']);
        $pdo = Database::connection();
        $pdo->prepare("INSERT INTO action_plan_tasks (objective_id, title, assigned_to, due_date) VALUES (:oid, :title, :assigned, :due)")
            ->execute(['oid' => $objId, 'title' => $req->input('title'), 'assigned' => $req->input('assigned_to') ?: null, 'due' => $req->input('due_date') ?: null]);
        Session::flash('success', 'Tarefa adicionada.');
        redirect('/gestao/planos/' . $planId);
    }

    public function updateTaskStatus(Request $req): void
    {
        $taskId = (int) $req->param('task_id');
        $planId = (int) $req->param('id');
        $pdo = Database::connection();
        $pdo->prepare("UPDATE action_plan_tasks SET status = :status WHERE id = :id")->execute([
            'status' => $req->input('status'), 'id' => $taskId,
        ]);
        Session::flash('success', 'Tarefa atualizada.');
        redirect('/gestao/planos/' . $planId);
    }

    // --- Donations ---
    public function donations(Request $req): void
    {
        try {
            $orgId = $this->orgId();
            $page = (int) ($req->input('page', '1'));
            $filters = [
                'type'       => $req->input('type', ''),
                'start_date' => $req->input('start_date', date('Y-m-01')),
                'end_date'   => $req->input('end_date', date('Y-m-t')),
            ];
            $result = Donation::byOrg($orgId, $filters, $page);
            $summary = Donation::summaryByType($orgId, $filters['start_date'], $filters['end_date']);

            if (($result['degraded'] ?? false) === true) {
                Session::flash('warning', 'Doacoes indisponiveis no momento. Exibindo modo de contingencia.');
            }

            $this->view('management/donations/index', [
                'pageTitle'  => 'Doações — Gestão', 'breadcrumb' => 'Doações',
                'donations'  => $result['data'], 'pagination' => $result,
                'summary'    => $summary, 'filters' => $filters,
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar doações: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function createDonation(Request $req): void
    {
        try {
            $this->view('management/donations/form', [
                'pageTitle' => 'Nova doação', 'breadcrumb' => 'Doações / Nova', 'item' => null,
                'members' => Member::byOrg($this->orgId(), [], 1, 500)['data'],
                'units' => $this->churchUnits(),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar formulário: ' . $e->getMessage());
            redirect('/gestao/doacoes');
        }
    }

    public function storeDonation(Request $req): void
    {
        $this->validate($req, ['amount' => 'required', 'donation_date' => 'required']);
        $data = $req->only(['member_id','donor_name','type','amount','donation_date','payment_method','reference','notes','church_unit_id']);
        $data['church_unit_id'] = (int) ($data['church_unit_id'] ?? 0) ?: null;

        Donation::create(array_merge($data, [
            'organization_id' => $this->orgId(),
        ]));
        Session::flash('success', 'Doação registrada.');
        redirect('/gestao/doacoes');
    }

    // --- Relatórios ---
    // --- Reports ---
    public function reports(Request $req): void
    {
        try {
            $orgId = $this->orgId();
            $startDate = $req->input('start_date', date('Y-m-01'));
            $endDate = $req->input('end_date', date('Y-m-t'));
            $reportType = $req->input('type', 'overview');
            $financial = FinancialTransaction::summary($orgId, $startDate, $endDate);

            if (($financial['degraded'] ?? false) === true) {
                Session::flash('warning', 'Relatorios com dados parciais no momento. Exibindo modo de contingencia.');
            }

            $data = [
                'pageTitle'      => 'Relatórios — Gestão', 'breadcrumb' => 'Relatórios',
                'totalMembers'   => Member::countByOrg($orgId),
                'activeMembers'  => Member::countByOrg($orgId, 'active'),
                'newMembers'     => Member::newThisMonth($orgId),
                'activeEvents'   => Event::countActive($orgId),
                'financial'      => $financial,
                'donationSummary' => Donation::summaryByType($orgId, $startDate, $endDate),
                'openRequests'   => ChurchRequest::countOpen($orgId),
                'pendingTasks'   => ActionPlan::pendingTasks($orgId),
                'filters'        => ['start_date' => $startDate, 'end_date' => $endDate, 'type' => $reportType],
            ];

            if ($req->input('export') === 'pdf') {
                $this->view('management/reports/print', $data);
                return;
            }

            $this->view('management/reports/index', $data);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar relatórios: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    // --- Users ---
    public function users(Request $req): void
    {
        $this->view('management/users/index', [
            'pageTitle' => 'Usuários — Gestão',
            'breadcrumb' => 'Usuários',
            'users' => $this->orgUsers(),
        ]);
        return;

        $users = [];

        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("
            SELECT u.*, ou.role_id, r.name as role_name, ou.id as org_user_id
            FROM users u 
            JOIN organization_users ou ON u.id = ou.user_id 
            LEFT JOIN roles r ON ou.role_id = r.id
            WHERE ou.organization_id = :org_id AND ou.status = 'active'
        ");
            $stmt->execute(['org_id' => $this->orgId()]);
            $users = $stmt->fetchAll();
        } catch (\Throwable $e) {
            Session::flash('warning', 'Usuarios indisponiveis no momento. Exibindo modo de contingencia.');
        }

        $this->view('management/users/index', [
            'pageTitle' => 'Usuários — Gestão', 'breadcrumb' => 'Usuários',
            'users' => $users,
        ]);
    }

    public function createUser(Request $req): void
    {
        $this->view('management/users/form', [
            'pageTitle' => 'Novo Usuário', 'breadcrumb' => 'Usuários / Novo',
        ]);
    }

    public function storeUser(Request $req): void
    {
        $this->validate($req, ['name' => 'required', 'email' => 'required', 'password' => 'required']);
        $pdo = Database::connection();

        $roleId = (int) $req->input('role_id');
        if ($roleId <= 0) {
            try {
                $stmt = $pdo->prepare("SELECT id FROM roles WHERE slug = 'org-member' LIMIT 1");
                $stmt->execute();
                $roleId = (int) ($stmt->fetch()['id'] ?? 3);
            } catch (\Throwable $e) {
                $roleId = 3;
            }
        }

        $pdo->beginTransaction();
        try {
            $user = \App\Models\User::findByEmail($req->input('email'));
            if (!$user) {
                $uid = \App\Models\User::createAccount([
                    'name' => $req->input('name'),
                    'email' => $req->input('email'),
                    'password' => $req->input('password')
                ]);
            } else {
                $uid = $user['id'];

                $check = $pdo->prepare("SELECT 1 FROM organization_users WHERE organization_id = :org_id AND user_id = :u_id LIMIT 1");
                $check->execute(['org_id' => $this->orgId(), 'u_id' => $uid]);
                if ($check->fetchColumn()) {
                    throw new \Exception('Esse usuário já está vinculado à organização.');
                }
            }

            $pdo->prepare("INSERT INTO organization_users (organization_id, user_id, role_id, status) VALUES (?, ?, ?, 'active')")
                ->execute([$this->orgId(), $uid, $roleId]);
            $pdo->commit();
            Session::flash('success', 'Usuário criado/vinculado com sucesso.');
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            Session::flash('error', 'Erro ao processar usuário: ' . $e->getMessage());
        }
        redirect('/gestao/configuracoes/usuarios');
    }

    public function destroyUser(Request $req): void
    {
        $id = (int) $req->param('id');
        $pdo = Database::connection();
        $pdo->prepare("DELETE FROM organization_users WHERE id = ? AND organization_id = ?")->execute([$id, $this->orgId()]);
        Session::flash('success', 'Usuário desvinculado.');
        redirect('/gestao/configuracoes/usuarios');
    }

    // --- Settings ---
    public function settings(Request $req): void
    {
        $this->settingsUsers($req);
        return;

        try {
            $context = $this->buildBaseContext('Configurações da Igreja', 'configuracoes');
            $this->view('management/settings/index', array_merge($context, [
                'pageTitle' => 'Configurações da Igreja — Gestão',
                'activeTab' => 'igreja'
            ]));
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar configurações: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function settingsUsers(Request $req): void
    {
        try {
            $context = $this->buildBaseContext('Configurações / Usuários', 'configuracoes');

            $currentUserId = (int) ($context['user']['id'] ?? 0);
            if ($currentUserId > 0) {
                \App\Models\Organization::ensureOwnerLink($this->orgId(), $currentUserId);
            }

            $availableRoles = [];
            try {
                $stmt = Database::connection()->prepare("SELECT id, name, slug FROM roles WHERE slug LIKE 'org-%' ORDER BY name ASC");
                $stmt->execute();
                $availableRoles = $stmt->fetchAll() ?: [];
            } catch (\Throwable $e) {
                $availableRoles = [];
            }

            $this->view('management/settings/users', array_merge($context, [
                'pageTitle' => 'Usuários e Permissões — Gestão',
                'activeTab' => 'usuarios',
                'users' => $this->orgUsers(),
                'availableRoles' => $availableRoles,
            ]));
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar usuários: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function settingsCategories(Request $req): void
    {
        redirect('/gestao/categorias-financeiras');
    }

    public function settingsUnits(Request $req): void
    {
        try {
            $context = $this->buildBaseContext('Configurações / Unidades', 'configuracoes/unidades');
            $this->view('management/settings/units', array_merge($context, [
                'pageTitle' => 'Unidades da Igreja — Gestão',
                'activeTab' => 'unidades',
                'units' => $this->churchUnits(),
            ]));
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar unidades: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function storeUnit(Request $req): void
    {
        $name = trim((string) $req->input('name'));
        if ($name === '') {
            Session::flash('error', 'Informe o nome da unidade.');
            redirect('/gestao/configuracoes/unidades');
        }

        try {
            $stmt = Database::connection()->prepare(
                'INSERT INTO church_units (organization_id, name, code, address, city, state, phone, status, created_at, updated_at)
                 VALUES (:organization_id, :name, :code, :address, :city, :state, :phone, :status, NOW(), NOW())'
            );
            $stmt->execute([
                'organization_id' => $this->orgId(),
                'name' => $name,
                'code' => trim((string) $req->input('code')) ?: null,
                'address' => trim((string) $req->input('address')) ?: null,
                'city' => trim((string) $req->input('city')) ?: null,
                'state' => strtoupper(substr(trim((string) $req->input('state')), 0, 2)) ?: null,
                'phone' => trim((string) $req->input('phone')) ?: null,
                'status' => $req->input('status') === 'inactive' ? 'inactive' : 'active',
            ]);
            Session::flash('success', 'Unidade cadastrada com sucesso.');
        } catch (\Throwable $e) {
            Session::flash('error', 'Não foi possível salvar a unidade: ' . $e->getMessage());
        }

        redirect('/gestao/configuracoes/unidades');
    }

    public function removeUnit(Request $req): void
    {
        try {
            $stmt = Database::connection()->prepare('DELETE FROM church_units WHERE id = :id AND organization_id = :organization_id');
            $stmt->execute(['id' => (int) $req->param('id'), 'organization_id' => $this->orgId()]);
            Session::flash('success', 'Unidade removida.');
        } catch (\Throwable $e) {
            Session::flash('error', 'Não foi possível remover a unidade.');
        }

        redirect('/gestao/configuracoes/unidades');
    }

    public function saveSettings(Request $req): void
    {
        $payload = $_POST;
        unset($payload['_token'], $payload['csrf_token'], $payload['redirect_to']);

        if ($this->containsPremiumSetting($payload) && !$this->hasPremiumAccess()) {
            Session::flash('warning', 'Este recurso é exclusivo do Plano Premium. Assine agora para desbloquear!');
            redirect('/gestao/assinatura');
        }

        try {
            $this->saveSettingValues($payload, 'church');
        } catch (\Throwable $e) {
            Session::flash('error', 'Não foi possível salvar as configurações: ' . $e->getMessage());
            redirect((string) $req->input('redirect_to', '/gestao/configuracoes'));
        }

        Session::flash('success', 'Configurações salvas.');
        redirect((string) $req->input('redirect_to', '/gestao/configuracoes'));
    }

    public function settingsPix(Request $req): void
    {
        try {
            $context = $this->buildBaseContext('Configurações PIX / Ofertas', 'configuracoes/pix');
            $this->view('management/settings/pix', array_merge($context, [
                'pageTitle' => 'PIX / Ofertas — Gestão',
                'activeTab' => 'pix',
                'settings' => $this->settingValues()
            ]));
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar configurações: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function settingsAi(Request $req): void
    {
        redirect('/gestao/configuracoes/integracoes');
    }

    public function settingsAppearance(Request $req): void
    {
        redirect('/hub/sites#aparencia-site');
    }

    public function settingsSeo(Request $req): void
    {
        try {
            $context = $this->buildBaseContext('SEO & Meta Tags', 'configuracoes/seo');
            $this->view('management/settings/seo', array_merge($context, [
                'pageTitle' => 'SEO — Gestão',
                'activeTab' => 'seo',
                'settings' => $this->settingValues()
            ]));
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar configurações: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function settingsPwa(Request $req): void
    {
        try {
            $context = $this->buildBaseContext('Configurações PWA', 'configuracoes/pwa');
            $this->view('management/settings/pwa', array_merge($context, [
                'pageTitle' => 'PWA — Gestão',
                'activeTab' => 'pwa',
                'settings' => $this->settingValues()
            ]));
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar configurações: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function settingsIntegrations(Request $req): void
    {
        try {
            $context = $this->buildBaseContext('Configurações / Integrações', 'configuracoes/integracoes');
            $this->view('management/settings/integrations', array_merge($context, [
                'pageTitle' => 'Integrações e IA — Gestão',
                'activeTab' => 'integracoes',
                'settings' => $this->settingValues()
            ]));
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar integrações: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function settingsSocial(Request $req): void
    {
        redirect('/hub/sites');
    }

    public function preachers(Request $req): void
    {
        try {
            $this->view('management/sermons/preachers', [
                'pageTitle' => 'Pregadores — Gestão',
                'breadcrumb' => 'Pregadores',
                'activeMenu' => 'pregadores',
                'preachers' => $this->preachersList(),
                'units' => $this->churchUnits(),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar pregadores: ' . $e->getMessage());
            redirect('/gestao/sermoes');
        }
    }

    public function storePreacher(Request $req): void
    {
        $name = trim((string) $req->input('name'));
        if ($name === '') {
            Session::flash('error', 'Informe o nome do pregador.');
            redirect('/gestao/pregadores');
        }

        try {
            $stmt = Database::connection()->prepare(
                'INSERT INTO preachers (organization_id, church_unit_id, name, email, phone, bio, status, created_at, updated_at)
                 VALUES (:organization_id, :church_unit_id, :name, :email, :phone, :bio, :status, NOW(), NOW())'
            );
            $stmt->execute([
                'organization_id' => $this->orgId(),
                'church_unit_id' => (int) $req->input('church_unit_id', 0) ?: null,
                'name' => $name,
                'email' => trim((string) $req->input('email')) ?: null,
                'phone' => trim((string) $req->input('phone')) ?: null,
                'bio' => trim((string) $req->input('bio')) ?: null,
                'status' => $req->input('status') === 'inactive' ? 'inactive' : 'active',
            ]);
            Session::flash('success', 'Pregador cadastrado com sucesso.');
        } catch (\Throwable $e) {
            Session::flash('error', 'Não foi possível salvar o pregador: ' . $e->getMessage());
        }

        redirect('/gestao/pregadores');
    }

    public function removePreacher(Request $req): void
    {
        try {
            $stmt = Database::connection()->prepare('DELETE FROM preachers WHERE id = :id AND organization_id = :organization_id');
            $stmt->execute(['id' => (int) $req->param('id'), 'organization_id' => $this->orgId()]);
            Session::flash('success', 'Pregador removido.');
        } catch (\Throwable $e) {
            Session::flash('error', 'Não foi possível remover o pregador.');
        }

        redirect('/gestao/pregadores');
    }

    public function ministrations(Request $req): void
    {
        try {
            $context = $this->buildBaseContext('Ministrações', 'ministracoes');
            $this->view('management/modules/generic', array_merge($context, [
                'pageTitle' => 'Ministrações',
                'breadcrumb' => 'Ministrações',
                'moduleName' => 'Ministrações',
                'moduleDescription' => 'Acompanhe as ministrações, equipes e datas na sua igreja.',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>'
            ]));
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar ministrações: ' . $e->getMessage());
            redirect('/gestao');
        }
    }
}
