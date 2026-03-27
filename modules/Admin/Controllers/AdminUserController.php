<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index(Request $request): void
    {
        $pdo = Database::connection();
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $page = (int) ($request->input('page', '1'));
        $perPage = 20;

        $where = '1=1';
        $params = [];
        if ($search) { $where .= " AND (u.name LIKE :s OR u.email LIKE :s)"; $params['s'] = "%{$search}%"; }
        if ($status) { $where .= " AND u.status = :st"; $params['st'] = $status; }

        $total = (int) $pdo->prepare("SELECT COUNT(*) FROM users u WHERE {$where}") and
            ($countStmt = $pdo->prepare("SELECT COUNT(*) FROM users u WHERE {$where}")) and $countStmt->execute($params) and ($total = (int) $countStmt->fetchColumn());

        $offset = ($page - 1) * $perPage;
        $stmt = $pdo->prepare("SELECT u.*, (SELECT COUNT(*) FROM organization_users ou WHERE ou.user_id = u.id) as org_count FROM users u WHERE {$where} ORDER BY u.created_at DESC LIMIT {$perPage} OFFSET {$offset}");
        $stmt->execute($params);

        $this->view('admin/users/index', [
            'pageTitle'  => 'Usuários — Admin',
            'breadcrumb' => 'Usuários',
            'users'      => $stmt->fetchAll(),
            'pagination' => ['total' => $total, 'page' => $page, 'perPage' => $perPage, 'totalPages' => (int) ceil($total / $perPage)],
            'filters'    => ['search' => $search, 'status' => $status],
        ]);
    }

    public function show(Request $request): void
    {
        $user = User::find((int) $request->param('id'));
        if (!$user) { redirect('/admin/usuarios'); }

        $pdo = Database::connection();
        $orgs = $pdo->prepare("SELECT o.*, ou.role_id, r.name as role_name, ou.status as membership_status FROM organizations o JOIN organization_users ou ON o.id = ou.organization_id LEFT JOIN roles r ON ou.role_id = r.id WHERE ou.user_id = :uid");
        $orgs->execute(['uid' => $user['id']]);

        $logs = $pdo->prepare("SELECT * FROM audit_logs WHERE user_id = :uid ORDER BY created_at DESC LIMIT 20");
        $logs->execute(['uid' => $user['id']]);

        $this->view('admin/users/show', [
            'pageTitle'     => e($user['name']) . ' — Admin',
            'breadcrumb'    => 'Usuários / ' . $user['name'],
            'user'          => $user,
            'organizations' => $orgs->fetchAll(),
            'logs'          => $logs->fetchAll(),
        ]);
    }

    public function edit(Request $request): void
    {
        $user = User::find((int) $request->param('id'));
        if (!$user) { redirect('/admin/usuarios'); }
        $this->view('admin/users/edit', [
            'pageTitle' => 'Editar — ' . e($user['name']),
            'breadcrumb' => 'Usuários / Editar',
            'user' => $user,
        ]);
    }

    public function update(Request $request): void
    {
        $id = (int) $request->param('id');
        $this->validate($request, ['name' => 'required|min:3']);
        User::update($id, $request->only(['name', 'email', 'phone', 'status']));
        Session::flash('success', 'Usuário atualizado.');
        redirect('/admin/usuarios/' . $id);
    }
}
