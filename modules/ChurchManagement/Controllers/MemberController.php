<?php

declare(strict_types=1);

namespace Modules\ChurchManagement\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Member;
use App\Models\User;

class MemberController extends Controller
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
        $orgId = $this->orgId();
        if ($orgId <= 0) {
            Session::flash('warning', 'Complete o cadastro da organizacao para acessar os membros.');
            redirect('/onboarding/organizacao');
        }

        $page = (int) ($request->input('page', '1'));
        $filters = [
            'search' => $request->input('search', ''),
            'status' => $request->input('status', ''),
        ];

        $result = Member::byOrg($orgId, $filters, $page);
        if (($result['degraded'] ?? false) === true) {
            Session::flash('warning', 'Membros indisponivel no momento. Exibindo modo de contingencia.');
        }

        $this->view('management/members/index', [
            'pageTitle'   => 'Membros - Gestao',
            'breadcrumb'  => 'Membros',
            'members'     => $result['data'],
            'pagination'  => $result,
            'filters'     => $filters,
        ]);
    }

    public function create(Request $request): void
    {
        if ($this->orgId() <= 0) {
            Session::flash('warning', 'Complete o cadastro da organizacao para adicionar membros.');
            redirect('/onboarding/organizacao');
        }

        $this->view('management/members/form', [
            'pageTitle'  => 'Novo membro - Gestao',
            'breadcrumb' => 'Membros / Novo',
            'member'     => null,
        ]);
    }

    public function store(Request $request): void
    {
        $orgId = $this->orgId();
        if ($orgId <= 0) {
            Session::flash('warning', 'Complete o cadastro da organizacao para adicionar membros.');
            redirect('/onboarding/organizacao');
        }

        $this->validate($request, [
            'name'  => 'required|min:3',
            'email' => 'email',
        ]);

        try {
            Member::create(array_merge($request->only([
                'name','email','phone','birth_date','gender','marital_status',
                'address','city','state','zip_code','membership_date','baptism_date','status','notes'
            ]), [
                'organization_id' => $orgId,
                'created_by'      => Session::user()['id'],
            ]));
        } catch (\Throwable $e) {
            Session::flash('error', 'Nao foi possivel cadastrar membro agora. Tente novamente.');
            Session::setOld($request->all());
            redirect('/gestao/membros/novo');
        }

        Session::flash('success', 'Membro cadastrado com sucesso.');
        redirect('/gestao/membros');
    }

    public function show(Request $request): void
    {
        try {
            $member = Member::find((int) $request->param('id'));
        } catch (\Throwable $e) {
            Session::flash('error', 'Nao foi possivel carregar membro agora.');
            redirect('/gestao/membros');
        }

        if (!$member || (int) $member['organization_id'] !== $this->orgId()) {
            redirect('/gestao/membros');
        }

        $this->view('management/members/show', [
            'pageTitle'  => e($member['name']) . ' - Gestao',
            'breadcrumb' => 'Membros / ' . $member['name'],
            'member'     => $member,
        ]);
    }

    public function edit(Request $request): void
    {
        try {
            $member = Member::find((int) $request->param('id'));
        } catch (\Throwable $e) {
            Session::flash('error', 'Nao foi possivel carregar membro agora.');
            redirect('/gestao/membros');
        }

        if (!$member || (int) $member['organization_id'] !== $this->orgId()) {
            redirect('/gestao/membros');
        }

        $this->view('management/members/form', [
            'pageTitle'  => 'Editar - ' . e($member['name']),
            'breadcrumb' => 'Membros / Editar',
            'member'     => $member,
        ]);
    }

    public function update(Request $request): void
    {
        $id = (int) $request->param('id');

        try {
            $member = Member::find($id);
        } catch (\Throwable $e) {
            Session::flash('error', 'Nao foi possivel atualizar membro agora.');
            redirect('/gestao/membros');
        }

        if (!$member || (int) $member['organization_id'] !== $this->orgId()) {
            redirect('/gestao/membros');
        }

        $this->validate($request, ['name' => 'required|min:3']);

        try {
            Member::update($id, $request->only([
                'name','email','phone','birth_date','gender','marital_status',
                'address','city','state','zip_code','membership_date','baptism_date','status','notes'
            ]));
        } catch (\Throwable $e) {
            Session::flash('error', 'Nao foi possivel atualizar membro agora. Tente novamente.');
            Session::setOld($request->all());
            redirect('/gestao/membros/' . $id . '/editar');
        }

        Session::flash('success', 'Membro atualizado com sucesso.');
        redirect('/gestao/membros/' . $id);
    }

    public function destroy(Request $request): void
    {
        $id = (int) $request->param('id');

        try {
            $member = Member::find($id);
        } catch (\Throwable $e) {
            Session::flash('error', 'Nao foi possivel remover membro agora.');
            redirect('/gestao/membros');
        }

        if (!$member || (int) $member['organization_id'] !== $this->orgId()) {
            redirect('/gestao/membros');
        }

        try {
            Member::delete($id);
        } catch (\Throwable $e) {
            Session::flash('error', 'Nao foi possivel remover membro agora. Tente novamente.');
            redirect('/gestao/membros');
        }

        Session::flash('success', 'Membro removido.');
        redirect('/gestao/membros');
    }
}
