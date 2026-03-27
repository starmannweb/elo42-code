<?php

declare(strict_types=1);

namespace Modules\ChurchManagement\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Ministry;
use App\Models\Member;

class MinistryController extends Controller
{
    private function orgId(): int { return (int) Session::get('organization')['id']; }

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
        $ministry = Ministry::find((int) $request->param('id'));
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
        $ministry = Ministry::find($id);
        if (!$ministry || (int)$ministry['organization_id'] !== $this->orgId()) { redirect('/gestao/ministerios'); }
        $this->validate($request, ['name' => 'required|min:3']);
        Ministry::update($id, $request->only(['name','description','leader_member_id','color','status']));
        $memberIds = $request->input('members', []);
        if (is_array($memberIds)) { Ministry::syncMembers($id, array_map('intval', $memberIds)); }
        Session::flash('success', 'Ministério atualizado.');
        redirect('/gestao/ministerios');
    }
}
