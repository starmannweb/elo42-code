<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use App\Models\Organization;

class AdminOrganizationController extends Controller
{
    public function index(Request $request): void
    {
        $pdo = Database::connection();
        $search = $request->input('search', '');
        $status = $request->input('status', '');

        $where = '1=1'; $params = [];
        if ($search) { $where .= " AND (o.name LIKE :s OR o.cnpj LIKE :s)"; $params['s'] = "%{$search}%"; }
        if ($status) { $where .= " AND o.status = :st"; $params['st'] = $status; }

        $stmt = $pdo->prepare("
            SELECT o.*, (SELECT COUNT(*) FROM organization_users ou WHERE ou.organization_id = o.id) as user_count,
            (SELECT COUNT(*) FROM members m WHERE m.organization_id = o.id) as member_count
            FROM organizations o WHERE {$where} ORDER BY o.created_at DESC
        ");
        $stmt->execute($params);

        $this->view('admin/organizations/index', [
            'pageTitle'     => 'Organizações — Admin',
            'breadcrumb'    => 'Organizações',
            'organizations' => $stmt->fetchAll(),
            'filters'       => ['search' => $search, 'status' => $status],
        ]);
    }

    public function show(Request $request): void
    {
        $org = Organization::find((int) $request->param('id'));
        if (!$org) { redirect('/admin/organizacoes'); }

        $pdo = Database::connection();
        $users = $pdo->prepare("SELECT u.*, ou.role_id, r.name as role_name, ou.status as membership_status FROM users u JOIN organization_users ou ON u.id = ou.user_id LEFT JOIN roles r ON ou.role_id = r.id WHERE ou.organization_id = :oid");
        $users->execute(['oid' => $org['id']]);

        $sub = $pdo->prepare("SELECT * FROM subscriptions WHERE organization_id = :oid ORDER BY created_at DESC LIMIT 1");
        $sub->execute(['oid' => $org['id']]);

        $this->view('admin/organizations/show', [
            'pageTitle'    => e($org['name']) . ' — Admin',
            'breadcrumb'   => 'Organizações / ' . $org['name'],
            'org'          => $org,
            'users'        => $users->fetchAll(),
            'subscription' => $sub->fetch() ?: null,
        ]);
    }

    public function edit(Request $request): void
    {
        $org = Organization::find((int) $request->param('id'));
        if (!$org) { redirect('/admin/organizacoes'); }
        $this->view('admin/organizations/edit', [
            'pageTitle'  => 'Editar — ' . e($org['name']),
            'breadcrumb' => 'Organizações / Editar',
            'org'        => $org,
        ]);
    }

    public function update(Request $request): void
    {
        $id = (int) $request->param('id');
        $this->validate($request, ['name' => 'required|min:3']);
        Organization::update($id, $request->only(['name', 'legal_name', 'cnpj', 'phone', 'city', 'state', 'status', 'plan']));
        Session::flash('success', 'Organização atualizada.');
        redirect('/admin/organizacoes/' . $id);
    }
}
