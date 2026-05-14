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
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $page = (int) ($request->input('page', '1'));
        $perPage = 20;

        $users = [];
        $total = 0;
        $degraded = false;

        try {
            $pdo = Database::connection();
            $where = '1=1';
            $params = [];
            if ($search) { $where .= " AND (u.name LIKE :s OR u.email LIKE :s)"; $params['s'] = "%{$search}%"; }
            if ($status) { $where .= " AND u.status = :st"; $params['st'] = $status; }

            $countStmt = $pdo->prepare("SELECT COUNT(*) FROM users u WHERE {$where}");
            $countStmt->execute($params);
            $total = (int) $countStmt->fetchColumn();

            $offset = ($page - 1) * $perPage;
            $stmt = $pdo->prepare("SELECT u.*, (SELECT COUNT(*) FROM organization_users ou WHERE ou.user_id = u.id) as org_count FROM users u WHERE {$where} ORDER BY u.created_at DESC LIMIT {$perPage} OFFSET {$offset}");
            $stmt->execute($params);
            $users = $stmt->fetchAll();
        } catch (\Throwable $e) {
            $degraded = true;
            error_log('[ADMIN_USERS] ' . $e->getMessage());
        }

        if ($total === 0 && $search === '' && $status === '') {
            $fallback = $this->currentSessionUserRow();
            if (!empty($fallback)) {
                $users = [$fallback];
                $total = 1;
            }
        }

        $this->view('admin/users/index', [
            'pageTitle'  => 'Usuários — Admin',
            'breadcrumb' => 'Usuários',
            'users'      => $users,
            'pagination' => ['total' => $total, 'page' => $page, 'perPage' => $perPage, 'totalPages' => (int) ceil(max(1, $total) / $perPage)],
            'filters'    => ['search' => $search, 'status' => $status],
            'degraded'   => $degraded,
        ]);
    }

    private function currentSessionUserRow(): array
    {
        $user = Session::user();
        if (!is_array($user) || empty($user['id'])) {
            return [];
        }

        $organization = Session::get('organization');
        $organization = is_array($organization) ? $organization : [];

        return [
            'id' => (int) $user['id'],
            'name' => (string) ($user['name'] ?? 'Usuario atual'),
            'email' => (string) ($user['email'] ?? ''),
            'status' => (string) ($user['status'] ?? 'active'),
            'last_login_at' => $user['last_login_at'] ?? null,
            'org_count' => !empty($organization['id']) ? 1 : 0,
            'is_session_fallback' => true,
        ];
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

    public function create(Request $request): void
    {
        $this->view('admin/users/create', [
            'pageTitle' => 'Novo Usuário',
            'breadcrumb' => 'Usuários / Novo',
        ]);
    }

    public function store(Request $request): void
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $email = $request->input('email');
        if (User::findByEmail($email)) {
            Session::flash('error', 'Este e-mail já está em uso.');
            redirect('/admin/usuarios/novo');
        }

        $userId = User::createAccount([
            'name' => $request->input('name'),
            'email' => $email,
            'password' => $request->input('password'),
            'phone' => $request->input('phone', ''),
            'status' => $request->input('status', 'active'),
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);

        Session::flash('success', 'Usuário criado com sucesso.');
        redirect('/admin/usuarios/' . $userId . '/editar');
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
        redirect('/admin/usuarios/' . $id . '/editar');
    }

    public function resetPassword(Request $request): void
    {
        $id = (int) $request->param('id');
        $user = User::find($id);

        if (!$user) {
            Session::flash('error', 'Usuário não encontrado.');
            redirect('/admin/usuarios');
        }

        $newPassword = $request->input('password');
        if (empty($newPassword) || strlen($newPassword) < 6) {
            Session::flash('error', 'A nova senha deve ter pelo menos 6 caracteres.');
            redirect('/admin/usuarios/' . $id . '/editar');
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $pdo = Database::connection();
        $stmt = $pdo->prepare("UPDATE users SET password = :pass, updated_at = NOW() WHERE id = :id");
        $stmt->execute(['pass' => $hashedPassword, 'id' => $id]);

        Session::flash('success', 'Senha redefinida com sucesso.');
        redirect('/admin/usuarios/' . $id . '/editar');
    }

    public function destroy(Request $request): void
    {
        $id = (int) $request->param('id');
        $user = User::find($id);

        if (!$user) {
            Session::flash('error', 'Usuário não encontrado.');
            redirect('/admin/usuarios');
        }

        if ($user['id'] === (Session::user()['id'] ?? null)) {
            Session::flash('error', 'Você não pode excluir a si mesmo.');
            redirect('/admin/usuarios');
        }

        User::delete($id);
        
        // Remove also from organization_users
        $pdo = Database::connection();
        $stmt = $pdo->prepare("DELETE FROM organization_users WHERE user_id = :uid");
        $stmt->execute(['uid' => $id]);

        Session::flash('success', 'Usuário removido com sucesso.');
        redirect('/admin/usuarios');
    }
}
