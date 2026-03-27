<?php

declare(strict_types=1);

namespace Modules\ChurchManagement\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Member;

class MemberController extends Controller
{
    private function orgId(): int { return (int) Session::get('organization')['id']; }

    public function index(Request $request): void
    {
        $page = (int) ($request->input('page', '1'));
        $filters = [
            'search' => $request->input('search', ''),
            'status' => $request->input('status', ''),
        ];
        $result = Member::byOrg($this->orgId(), $filters, $page);

        $this->view('management/members/index', [
            'pageTitle'   => 'Membros — Gestão',
            'breadcrumb'  => 'Membros',
            'members'     => $result['data'],
            'pagination'  => $result,
            'filters'     => $filters,
        ]);
    }

    public function create(Request $request): void
    {
        $this->view('management/members/form', [
            'pageTitle'  => 'Novo membro — Gestão',
            'breadcrumb' => 'Membros / Novo',
            'member'     => null,
        ]);
    }

    public function store(Request $request): void
    {
        $this->validate($request, [
            'name'  => 'required|min:3',
            'email' => 'email',
        ]);

        Member::create(array_merge($request->only([
            'name','email','phone','birth_date','gender','marital_status',
            'address','city','state','zip_code','membership_date','baptism_date','status','notes'
        ]), [
            'organization_id' => $this->orgId(),
            'created_by'      => Session::user()['id'],
        ]));

        Session::flash('success', 'Membro cadastrado com sucesso.');
        redirect('/gestao/membros');
    }

    public function show(Request $request): void
    {
        $member = Member::find((int) $request->param('id'));
        if (!$member || (int)$member['organization_id'] !== $this->orgId()) { redirect('/gestao/membros'); }

        $this->view('management/members/show', [
            'pageTitle'  => e($member['name']) . ' — Gestão',
            'breadcrumb' => 'Membros / ' . $member['name'],
            'member'     => $member,
        ]);
    }

    public function edit(Request $request): void
    {
        $member = Member::find((int) $request->param('id'));
        if (!$member || (int)$member['organization_id'] !== $this->orgId()) { redirect('/gestao/membros'); }

        $this->view('management/members/form', [
            'pageTitle'  => 'Editar — ' . e($member['name']),
            'breadcrumb' => 'Membros / Editar',
            'member'     => $member,
        ]);
    }

    public function update(Request $request): void
    {
        $id = (int) $request->param('id');
        $member = Member::find($id);
        if (!$member || (int)$member['organization_id'] !== $this->orgId()) { redirect('/gestao/membros'); }

        $this->validate($request, ['name' => 'required|min:3']);

        Member::update($id, $request->only([
            'name','email','phone','birth_date','gender','marital_status',
            'address','city','state','zip_code','membership_date','baptism_date','status','notes'
        ]));

        Session::flash('success', 'Membro atualizado com sucesso.');
        redirect('/gestao/membros/' . $id);
    }

    public function destroy(Request $request): void
    {
        $id = (int) $request->param('id');
        $member = Member::find($id);
        if (!$member || (int)$member['organization_id'] !== $this->orgId()) { redirect('/gestao/membros'); }

        Member::delete($id);
        Session::flash('success', 'Membro removido.');
        redirect('/gestao/membros');
    }
}
