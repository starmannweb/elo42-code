<?php

declare(strict_types=1);

namespace Modules\ChurchManagement\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Ministry;
use App\Models\Member;
use App\Models\User;

class MinistryController extends Controller
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

    public function index(Request $request): void
    {
        $this->view('management/ministries/index', [
            'pageTitle'   => 'Ministérios — Gestão',
            'breadcrumb'  => 'Ministérios',
            'ministries'  => Ministry::byOrg($this->orgId()),
        ]);
    }

    public function create(Request $request): void
    {
        $this->view('management/ministries/form', [
            'pageTitle'  => 'Novo ministério — Gestão',
            'breadcrumb' => 'Ministérios / Novo',
            'ministry'   => null,
            'members'    => Member::byOrg($this->orgId(), [], 1, 500)['data'],
        ]);
    }

    public function store(Request $request): void
    {
        $this->validate($request, ['name' => 'required|min:3']);
        $id = Ministry::create(array_merge($request->only(['name','description','leader_member_id','color']), ['organization_id' => $this->orgId()]));
        $memberIds = $request->input('members', []);
        if (is_array($memberIds)) { Ministry::syncMembers((int)$id, array_map('intval', $memberIds)); }
        Session::flash('success', 'Ministério criado com sucesso.');
        redirect('/gestao/ministerios');
    }

    public function edit(Request $request): void
    {
        try {
            $ministry = Ministry::find((int) $request->param('id'));
        } catch (\Throwable $e) {
            Session::flash('error', 'Nao foi possivel carregar ministerio agora.');
            redirect('/gestao/ministerios');
        }

        if (!$ministry || (int)$ministry['organization_id'] !== $this->orgId()) { redirect('/gestao/ministerios'); }
        $this->view('management/ministries/form', [
            'pageTitle'  => 'Editar — ' . e($ministry['name']),
            'breadcrumb' => 'Ministérios / Editar',
            'ministry'   => $ministry,
            'members'    => Member::byOrg($this->orgId(), [], 1, 500)['data'],
            'current_members' => Ministry::getMembers((int) $ministry['id']),
        ]);
    }

    public function update(Request $request): void
    {
        $id = (int) $request->param('id');

        try {
            $ministry = Ministry::find($id);
        } catch (\Throwable $e) {
            Session::flash('error', 'Nao foi possivel atualizar ministerio agora.');
            redirect('/gestao/ministerios');
        }

        if (!$ministry || (int)$ministry['organization_id'] !== $this->orgId()) { redirect('/gestao/ministerios'); }
        $this->validate($request, ['name' => 'required|min:3']);
        Ministry::update($id, $request->only(['name','description','leader_member_id','color','status']));
        $memberIds = $request->input('members', []);
        if (is_array($memberIds)) { Ministry::syncMembers($id, array_map('intval', $memberIds)); }
        Session::flash('success', 'Ministério atualizado.');
        redirect('/gestao/ministerios');
    }
}
