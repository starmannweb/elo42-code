<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use App\Models\Product;
use App\Models\Service;
use App\Models\Benefit;
use App\Models\Subscription;
use App\Models\Ticket;
use App\Models\PlatformSetting;

class AdminCatalogController extends Controller
{
    // ---- Products ----
    public function products(Request $req): void
    {
        Session::flash('warning', 'Produtos foi removido do Admin. Use Serviços para gerenciar o catálogo da plataforma.');
        redirect('/admin/servicos');
    }

    public function createProduct(Request $req): void
    {
        redirect('/admin/servicos/novo');
    }

    public function storeProduct(Request $req): void
    {
        redirect('/admin/servicos');
    }

    public function editProduct(Request $req): void
    {
        redirect('/admin/servicos');
    }

    public function updateProduct(Request $req): void
    {
        redirect('/admin/servicos');
    }

    public function storeProductCategory(Request $req): void
    {
        redirect('/admin/servicos');
    }

    // ---- Services ----
    public function services(Request $req): void
    {
        $this->view('admin/services/index', [
            'pageTitle' => 'Serviços — Admin', 'breadcrumb' => 'Serviços',
            'services' => Service::all('sort_order'),
        ]);
    }

    public function createService(Request $req): void
    {
        $this->view('admin/services/form', [
            'pageTitle' => 'Novo serviço', 'breadcrumb' => 'Serviços / Novo', 'item' => null,
        ]);
    }

    public function storeService(Request $req): void
    {
        $this->validate($req, ['name' => 'required', 'slug' => 'required']);
        Service::create($req->only(['name','slug','description','rules','price','recurrence','status','sort_order']));
        Session::flash('success', 'Serviço criado.');
        redirect('/admin/servicos');
    }

    public function editService(Request $req): void
    {
        $item = Service::find((int) $req->param('id'));
        if (!$item) { redirect('/admin/servicos'); }
        $this->view('admin/services/form', [
            'pageTitle' => 'Editar — ' . e($item['name']), 'breadcrumb' => 'Serviços / Editar', 'item' => $item,
        ]);
    }

    public function updateService(Request $req): void
    {
        $id = (int) $req->param('id');
        $this->validate($req, ['name' => 'required']);
        Service::update($id, $req->only(['name','slug','description','rules','price','recurrence','status','sort_order']));
        Session::flash('success', 'Serviço atualizado.');
        redirect('/admin/servicos');
    }

    // ---- Benefits ----
    public function benefits(Request $req): void
    {
        $this->view('admin/benefits/index', [
            'pageTitle' => 'Cortesias — Admin', 'breadcrumb' => 'Cortesias',
            'benefits' => Benefit::allWithUsageCount(),
        ]);
    }

    public function createBenefit(Request $req): void
    {
        $this->view('admin/benefits/form', [
            'pageTitle' => 'Nova cortesia', 'breadcrumb' => 'Cortesias / Nova', 'item' => null,
        ]);
    }

    public function storeBenefit(Request $req): void
    {
        $this->validate($req, ['name' => 'required', 'slug' => 'required']);
        Benefit::create($req->only(['name','slug','description','requirements','status','max_usage','valid_until']));
        Session::flash('success', 'Cortesia criada.');
        redirect('/admin/cortesias');
    }

    public function editBenefit(Request $req): void
    {
        $item = Benefit::find((int) $req->param('id'));
        if (!$item) { redirect('/admin/cortesias'); }
        $this->view('admin/benefits/form', [
            'pageTitle' => 'Editar — ' . e($item['name']), 'breadcrumb' => 'Cortesias / Editar', 'item' => $item,
        ]);
    }

    public function updateBenefit(Request $req): void
    {
        $id = (int) $req->param('id');
        $this->validate($req, ['name' => 'required']);
        Benefit::update($id, $req->only(['name','slug','description','requirements','status','max_usage','valid_until']));
        Session::flash('success', 'Cortesia atualizada.');
        redirect('/admin/cortesias');
    }

    // ---- Subscriptions ----
    public function subscriptions(Request $req): void
    {
        $filters = ['status' => $req->input('status', '')];
        $this->view('admin/subscriptions/index', [
            'pageTitle'     => 'Assinaturas — Admin', 'breadcrumb' => 'Assinaturas',
            'subscriptions' => Subscription::allWithOrg($filters), 'filters' => $filters,
        ]);
    }

    public function showSubscription(Request $req): void
    {
        $sub = Subscription::find((int) $req->param('id'));
        if (!$sub) { redirect('/admin/assinaturas'); }
        $pdo = Database::connection();
        $org = $pdo->prepare("SELECT * FROM organizations WHERE id = :id"); $org->execute(['id' => $sub['organization_id']]);
        $this->view('admin/subscriptions/show', [
            'pageTitle' => 'Assinatura #' . $sub['id'], 'breadcrumb' => 'Assinaturas / Detalhe',
            'sub' => $sub, 'org' => $org->fetch(), 'history' => Subscription::getHistory((int)$sub['id']),
        ]);
    }

    public function updateSubscription(Request $req): void
    {
        $id = (int) $req->param('id');
        $sub = Subscription::find($id);
        if (!$sub) { redirect('/admin/assinaturas'); }
        $data = $req->only(['plan_name','plan_slug','price','billing_cycle','status','trial_ends_at','starts_at','expires_at']);
        Subscription::update($id, $data);

        // Log history
        $pdo = Database::connection();
        $pdo->prepare("INSERT INTO subscription_history (subscription_id, action, old_plan, new_plan, created_by) VALUES (:sid, :act, :old, :new, :uid)")
            ->execute(['sid' => $id, 'act' => 'upgraded', 'old' => $sub['plan_name'], 'new' => $data['plan_name'] ?? $sub['plan_name'], 'uid' => Session::user()['id']]);
        Session::flash('success', 'Assinatura atualizada.');
        redirect('/admin/assinaturas/' . $id);
    }

    // ---- Tickets ----
    public function tickets(Request $req): void
    {
        $filters = ['status' => $req->input('status', ''), 'priority' => $req->input('priority', '')];
        $this->view('admin/tickets/index', [
            'pageTitle' => 'Tickets — Admin', 'breadcrumb' => 'Tickets',
            'tickets' => Ticket::allAdmin($filters), 'filters' => $filters,
        ]);
    }

    public function showTicket(Request $req): void
    {
        $ticket = Ticket::find((int) $req->param('id'));
        if (!$ticket) { redirect('/admin/tickets'); }
        $this->view('admin/tickets/show', [
            'pageTitle' => 'Ticket #' . $ticket['id'], 'breadcrumb' => 'Tickets / #' . $ticket['id'],
            'ticket' => $ticket, 'replies' => Ticket::getReplies((int)$ticket['id']),
        ]);
    }

    public function replyTicket(Request $req): void
    {
        $id = (int) $req->param('id');
        $this->validate($req, ['message' => 'required']);
        $pdo = Database::connection();
        $pdo->prepare("INSERT INTO ticket_replies (ticket_id, user_id, message, is_admin) VALUES (:tid, :uid, :msg, 1)")
            ->execute(['tid' => $id, 'uid' => Session::user()['id'], 'msg' => $req->input('message')]);
        Session::flash('success', 'Resposta enviada.');
        redirect('/admin/tickets/' . $id);
    }

    public function updateTicketStatus(Request $req): void
    {
        $id = (int) $req->param('id');
        $data = ['status' => $req->input('status')];
        if ($req->input('status') === 'resolved') { $data['resolved_at'] = date('Y-m-d H:i:s'); }
        if ($req->input('status') === 'closed') { $data['closed_at'] = date('Y-m-d H:i:s'); }
        Ticket::update($id, $data);
        Session::flash('success', 'Status atualizado.');
        redirect('/admin/tickets/' . $id);
    }

    // ---- Reports ----
    public function reports(Request $req): void
    {
        $pdo = Database::connection();
        $startDate = $req->input('start_date', date('Y-m-01'));
        $endDate = $req->input('end_date', date('Y-m-t'));

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE created_at >= :s AND created_at <= :e");
        $stmt->execute(['s' => $startDate, 'e' => $endDate . ' 23:59:59']);
        $newUsers = (int) $stmt->fetchColumn();

        $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM organizations WHERE created_at >= :s AND created_at <= :e");
        $stmt2->execute(['s' => $startDate, 'e' => $endDate . ' 23:59:59']);
        $newOrgs = (int) $stmt2->fetchColumn();

        $this->view('admin/reports/index', [
            'pageTitle' => 'Relatórios — Admin', 'breadcrumb' => 'Relatórios',
            'totalUsers' => (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'totalOrgs' => (int) $pdo->query("SELECT COUNT(*) FROM organizations")->fetchColumn(),
            'newUsers' => $newUsers, 'newOrgs' => $newOrgs,
            'activeSubs' => Subscription::countByStatus('active'),
            'openTickets' => Ticket::countOpen(),
            'filters' => ['start_date' => $startDate, 'end_date' => $endDate],
        ]);
    }

    // ---- Logs ----
    public function logs(Request $req): void
    {
        $pdo = Database::connection();
        $search = $req->input('search', '');
        $module = $req->input('module', '');

        $where = '1=1'; $params = [];
        if ($search) { $where .= " AND (u.name LIKE :s OR al.action LIKE :s)"; $params['s'] = "%{$search}%"; }
        if ($module) { $where .= " AND al.module = :m"; $params['m'] = $module; }

        $stmt = $pdo->prepare("SELECT al.*, u.name as user_name, u.email as user_email FROM audit_logs al LEFT JOIN users u ON al.user_id = u.id WHERE {$where} ORDER BY al.created_at DESC LIMIT 100");
        $stmt->execute($params);

        $this->view('admin/logs/index', [
            'pageTitle' => 'Logs — Admin', 'breadcrumb' => 'Logs',
            'logs' => $stmt->fetchAll(),
            'filters' => ['search' => $search, 'module' => $module],
        ]);
    }

    // ---- Settings ----
    public function settings(Request $req): void
    {
        $this->view('admin/settings/index', [
            'pageTitle' => 'Configurações — Admin', 'breadcrumb' => 'Configurações',
            'settings' => PlatformSetting::byGroup(),
        ]);
    }

    public function updateSettings(Request $req): void
    {
        $settings = $req->input('settings', []);
        if (is_array($settings)) {
            foreach ($settings as $key => $value) {
                PlatformSetting::set($key, $value, (int) Session::user()['id']);
            }
        }
        Session::flash('success', 'Configurações atualizadas.');
        redirect('/admin/configuracoes');
    }
}
