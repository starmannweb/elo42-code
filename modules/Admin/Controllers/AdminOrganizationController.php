<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Models\Organization;

class AdminOrganizationController extends Controller
{
    public function index(Request $request): void
    {
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $organizations = [];
        $degraded = false;

        try {
            $pdo = Database::connection();
            $where = '1=1';
            $params = [];

            if ($search) {
                $where .= ' AND (o.name LIKE :s OR o.document LIKE :s)';
                $params['s'] = "%{$search}%";
            }

            if ($status) {
                $where .= ' AND o.status = :st';
                $params['st'] = $status;
            }

            $stmt = $pdo->prepare("
                SELECT o.*, o.document AS cnpj,
                    (SELECT COUNT(*) FROM organization_users ou WHERE ou.organization_id = o.id) as user_count,
                    (SELECT COUNT(*) FROM members m WHERE m.organization_id = o.id) as member_count
                FROM organizations o
                WHERE {$where}
                ORDER BY o.created_at DESC
            ");
            $stmt->execute($params);

            $organizations = array_map(
                fn (array $organization): array => $this->hydrateOrganization($organization),
                $stmt->fetchAll()
            );
        } catch (\Throwable $e) {
            $degraded = true;
            error_log('[ADMIN_ORGS] ' . $e->getMessage());
        }

        if (empty($organizations) && $search === '' && $status === '') {
            $fallback = $this->currentSessionOrganizationRow();
            if (!empty($fallback)) {
                $organizations = [$fallback];
            }
        }

        $this->view('admin/organizations/index', [
            'pageTitle'     => 'Instituições — Admin',
            'breadcrumb'    => 'Instituições',
            'organizations' => $organizations,
            'filters'       => ['search' => $search, 'status' => $status],
            'degraded'      => $degraded,
        ]);
    }

    private function currentSessionOrganizationRow(): array
    {
        $organization = Session::get('organization');
        $organization = is_array($organization) ? $organization : [];

        if (empty($organization['id']) && trim((string) ($organization['name'] ?? '')) === '') {
            return [];
        }

        return $this->hydrateOrganization([
            'id' => (int) ($organization['id'] ?? 0),
            'name' => (string) ($organization['name'] ?? 'Organizacao atual'),
            'slug' => (string) ($organization['slug'] ?? ''),
            'type' => (string) ($organization['type'] ?? 'church'),
            'document' => $organization['document'] ?? null,
            'plan' => (string) ($organization['plan'] ?? 'free'),
            'status' => (string) ($organization['status'] ?? 'active'),
            'created_at' => (string) ($organization['created_at'] ?? date('Y-m-d H:i:s')),
            'settings' => $organization['settings'] ?? null,
            'user_count' => 1,
            'member_count' => 0,
            'is_session_fallback' => true,
        ]);
    }

    public function show(Request $request): void
    {
        $id = (int) $request->param('id');
        $degraded = false;

        try {
            $org = Organization::find($id);
        } catch (\Throwable $e) {
            $degraded = true;
            error_log('[ADMIN_ORGS_SHOW_FIND] ' . $e->getMessage());
            $org = $this->fallbackOrganizationForId($id);
        }

        if (!$org) {
            Session::flash('warning', 'Instituicao nao encontrada.');
            redirect('/admin/organizacoes');
        }

        $org = $this->hydrateOrganization($org);
        $users = [];
        $subscription = null;

        try {
            $pdo = Database::connection();

            $usersStmt = $pdo->prepare('SELECT u.*, ou.role_id, r.name as role_name, ou.status as membership_status FROM users u JOIN organization_users ou ON u.id = ou.user_id LEFT JOIN roles r ON ou.role_id = r.id WHERE ou.organization_id = :oid');
            $usersStmt->execute(['oid' => $org['id']]);
            $users = $usersStmt->fetchAll() ?: [];

            $sub = $pdo->prepare('SELECT * FROM subscriptions WHERE organization_id = :oid ORDER BY created_at DESC LIMIT 1');
            $sub->execute(['oid' => $org['id']]);
            $subscription = $sub->fetch() ?: null;
        } catch (\Throwable $e) {
            $degraded = true;
            error_log('[ADMIN_ORGS_SHOW_DETAILS] ' . $e->getMessage());
            $users = $this->fallbackOrganizationUsers();
        }

        $this->view('admin/organizations/show', [
            'pageTitle'    => e($org['name']) . ' — Admin',
            'breadcrumb'   => 'Instituições / ' . $org['name'],
            'org'          => $org,
            'users'        => $users,
            'subscription' => $subscription,
            'degraded'     => $degraded,
        ]);
    }

    public function edit(Request $request): void
    {
        $id = (int) $request->param('id');
        $degraded = false;

        try {
            $org = Organization::find($id);
        } catch (\Throwable $e) {
            $degraded = true;
            error_log('[ADMIN_ORGS_EDIT_FIND] ' . $e->getMessage());
            $org = $this->fallbackOrganizationForId($id);
        }
        if (!$org) {
            redirect('/admin/organizacoes');
        }

        $this->view('admin/organizations/edit', [
            'pageTitle'  => 'Editar — ' . e($org['name']),
            'breadcrumb' => 'Instituições / Editar',
            'org'        => $this->hydrateOrganization($org),
            'degraded'   => $degraded,
        ]);
    }

    public function update(Request $request): void
    {
        $id = (int) $request->param('id');
        $redirectTo = $this->adminReturnTo($request, '/admin/organizacoes/' . $id);
        $this->validate($request, ['name' => 'required|min:3']);

        try {
            $org = Organization::find($id);
        } catch (\Throwable $e) {
            error_log('[ADMIN_ORGS_UPDATE_FIND] ' . $e->getMessage());
            $org = $this->fallbackOrganizationForId($id);
            if ($org) {
                $sessionOrg = Session::get('organization');
                $sessionOrg = is_array($sessionOrg) ? $sessionOrg : [];
                if ((int) ($sessionOrg['id'] ?? 0) === $id) {
                    $sessionOrg['name'] = $request->input('name');
                    $sessionOrg['status'] = $request->input('status');
                    $sessionOrg['plan'] = $this->normalizePlan((string) $request->input('plan', (string) ($org['plan'] ?? 'free')));
                    Session::set('organization', $sessionOrg);
                }
                Session::flash('warning', 'Banco indisponivel agora. Os dados foram mantidos na sessao e podem nao persistir no cadastro.');
                redirect($redirectTo);
            }
        }

        if (!$org) {
            redirect($redirectTo);
        }

        $settings = $this->decodeSettings($org['settings'] ?? null);
        $settings['legal_name'] = trim((string) $request->input('legal_name', '')) ?: null;

        try {
            Organization::update($id, [
                'name'     => $request->input('name'),
                'document' => trim((string) $request->input('cnpj', '')) ?: null,
                'phone'    => trim((string) $request->input('phone', '')) ?: null,
                'city'     => trim((string) $request->input('city', '')) ?: null,
                'state'    => trim((string) $request->input('state', '')) ?: null,
                'status'   => $request->input('status'),
                'plan'     => $this->normalizePlan((string) $request->input('plan', (string) ($org['plan'] ?? 'free'))),
                'settings' => json_encode($settings, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]);
        } catch (\Throwable $e) {
            error_log('[ADMIN_ORGS_UPDATE] ' . $e->getMessage());
            Session::flash('error', 'Nao foi possivel salvar a instituicao agora. Tente novamente em alguns instantes.');
            redirect($redirectTo);
        }

        Session::flash('success', 'Instituição atualizada.');
        redirect($redirectTo);
    }

    private function adminReturnTo(Request $request, string $fallback): string
    {
        $returnTo = trim((string) $request->input('return_to', ''));
        if ($returnTo !== ''
            && str_starts_with($returnTo, '/admin/')
            && !str_contains($returnTo, "\n")
            && !str_contains($returnTo, "\r")
        ) {
            return $returnTo;
        }

        return $fallback;
    }

    private function hydrateOrganization(array $organization): array
    {
        $settings = $this->decodeSettings($organization['settings'] ?? null);
        $organization['legal_name'] = $settings['legal_name'] ?? null;
        $organization['cnpj'] = $organization['document'] ?? ($organization['cnpj'] ?? null);

        return $organization;
    }

    private function fallbackOrganizationForId(int $id): ?array
    {
        $fallback = $this->currentSessionOrganizationRow();
        if (empty($fallback)) {
            return null;
        }

        return (int) ($fallback['id'] ?? 0) === $id ? $fallback : null;
    }

    private function fallbackOrganizationUsers(): array
    {
        $user = Session::user();
        if (!is_array($user) || empty($user)) {
            return [];
        }

        $user['role_name'] = $user['role_name'] ?? 'Administrador';
        $user['membership_status'] = $user['membership_status'] ?? 'active';

        return [$user];
    }

    private function decodeSettings(mixed $settings): array
    {
        if (!is_string($settings) || trim($settings) === '') {
            return [];
        }

        $decoded = json_decode($settings, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function normalizePlan(string $plan): string
    {
        return match ($plan) {
            'basic' => 'starter',
            'pro' => 'professional',
            'free', 'starter', 'professional', 'enterprise' => $plan,
            default => 'free',
        };
    }
}
