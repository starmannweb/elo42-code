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
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar formulário: ' . $e->getMessage());
            redirect('/gestao/visitas');
        }
    }

    public function storeVisit(Request $req): void
    {
        $this->validate($req, ['visitor_name' => 'required', 'visit_date' => 'required']);
        Visit::create(array_merge($req->only(['visitor_name','phone','email','visit_date','source','notes','assigned_to']), [
            'organization_id' => $this->orgId(),
        ]));
        Session::flash('success', 'Visita registrada.');
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
            $this->view('management/counseling/index', [
                'pageTitle' => 'Aconselhamento — Gestão', 'breadcrumb' => 'Aconselhamento',
                'sessions' => CounselingSession::byOrg($this->orgId()),
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
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar formulário: ' . $e->getMessage());
            redirect('/gestao/sermoes');
        }
    }

    public function storeSermon(Request $req): void
    {
        $this->validate($req, ['title' => 'required']);
        Sermon::create(array_merge($req->only(['title','preacher','sermon_date','bible_reference','summary','series_name','tags','status']), [
            'organization_id' => $this->orgId(),
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
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar formulário: ' . $e->getMessage());
            redirect('/gestao/doacoes');
        }
    }

    public function storeDonation(Request $req): void
    {
        $this->validate($req, ['amount' => 'required', 'donation_date' => 'required']);
        Donation::create(array_merge($req->only(['member_id','donor_name','type','amount','donation_date','payment_method','reference','notes']), [
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
            $financial = FinancialTransaction::summary($orgId, $startDate, $endDate);

            if (($financial['degraded'] ?? false) === true) {
                Session::flash('warning', 'Relatorios com dados parciais no momento. Exibindo modo de contingencia.');
            }

            $this->view('management/reports/index', [
                'pageTitle'      => 'Relatórios — Gestão', 'breadcrumb' => 'Relatórios',
                'totalMembers'   => Member::countByOrg($orgId),
                'activeMembers'  => Member::countByOrg($orgId, 'active'),
                'newMembers'     => Member::newThisMonth($orgId),
                'activeEvents'   => Event::countActive($orgId),
                'financial'      => $financial,
                'donationSummary' => Donation::summaryByType($orgId, $startDate, $endDate),
                'openRequests'   => ChurchRequest::countOpen($orgId),
                'pendingTasks'   => ActionPlan::pendingTasks($orgId),
                'filters'        => ['start_date' => $startDate, 'end_date' => $endDate],
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar relatórios: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    // --- Users ---
    public function users(Request $req): void
    {
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
            }
            $pdo->prepare("INSERT INTO organization_users (organization_id, user_id, role_id, status) VALUES (?, ?, 3, 'active')")
                ->execute([$this->orgId(), $uid]);
            $pdo->commit();
            Session::flash('success', 'Usuário criado/vinculado com sucesso.');
        } catch (\Exception $e) {
            $pdo->rollBack();
            Session::flash('error', 'Erro ao processar usuário.');
        }
        redirect('/gestao/usuarios');
    }

    public function destroyUser(Request $req): void
    {
        $id = (int) $req->param('id');
        $pdo = Database::connection();
        $pdo->prepare("DELETE FROM organization_users WHERE id = ? AND organization_id = ?")->execute([$id, $this->orgId()]);
        Session::flash('success', 'Usuário desvinculado.');
        redirect('/gestao/usuarios');
    }

    // --- Settings ---
    public function settings(Request $req): void
    {
        try {
            $this->view('management/settings/index', [
                'pageTitle' => 'Configurações — Gestão', 'breadcrumb' => 'Configurações',
                'categories' => FinancialTransaction::getCategories($this->orgId()),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar configurações: ' . $e->getMessage());
            redirect('/gestao');
        }
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
