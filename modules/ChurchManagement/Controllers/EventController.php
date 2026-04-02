<?php

declare(strict_types=1);

namespace Modules\ChurchManagement\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Event;
use App\Models\User;

class EventController extends Controller
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
        $status = $request->input('status');
        $this->view('management/events/index', [
            'pageTitle'  => 'Eventos — Gestão',
            'breadcrumb' => 'Eventos',
            'events'     => Event::byOrg($this->orgId(), $status),
            'filter_status' => $status,
        ]);
    }

    public function create(Request $request): void
    {
        $this->view('management/events/form', [
            'pageTitle'  => 'Novo evento — Gestão',
            'breadcrumb' => 'Eventos / Novo',
            'event'      => null,
        ]);
    }

    public function store(Request $request): void
    {
        $this->validate($request, ['title' => 'required|min:3', 'start_date' => 'required']);
        Event::create(array_merge($request->only(['title','description','location','start_date','end_date','max_registrations','status']), [
            'organization_id' => $this->orgId(),
            'created_by' => Session::user()['id'],
        ]));
        Session::flash('success', 'Evento criado.');
        redirect('/gestao/eventos');
    }

    public function show(Request $request): void
    {
        try {
            $event = Event::find((int) $request->param('id'));
        } catch (\Throwable $e) {
            Session::flash('error', 'Nao foi possivel carregar evento agora.');
            redirect('/gestao/eventos');
        }

        if (!$event || (int)$event['organization_id'] !== $this->orgId()) { redirect('/gestao/eventos'); }
        $this->view('management/events/show', [
            'pageTitle'     => e($event['title']) . ' — Gestão',
            'breadcrumb'    => 'Eventos / ' . $event['title'],
            'event'         => $event,
            'registrations' => Event::getRegistrations((int) $event['id']),
        ]);
    }

    public function edit(Request $request): void
    {
        try {
            $event = Event::find((int) $request->param('id'));
        } catch (\Throwable $e) {
            Session::flash('error', 'Nao foi possivel carregar evento agora.');
            redirect('/gestao/eventos');
        }

        if (!$event || (int)$event['organization_id'] !== $this->orgId()) { redirect('/gestao/eventos'); }
        $this->view('management/events/form', [
            'pageTitle'  => 'Editar — ' . e($event['title']),
            'breadcrumb' => 'Eventos / Editar',
            'event'      => $event,
        ]);
    }

    public function update(Request $request): void
    {
        $id = (int) $request->param('id');

        try {
            $event = Event::find($id);
        } catch (\Throwable $e) {
            Session::flash('error', 'Nao foi possivel atualizar evento agora.');
            redirect('/gestao/eventos');
        }

        if (!$event || (int)$event['organization_id'] !== $this->orgId()) { redirect('/gestao/eventos'); }
        $this->validate($request, ['title' => 'required|min:3']);
        Event::update($id, $request->only(['title','description','location','start_date','end_date','max_registrations','status']));
        Session::flash('success', 'Evento atualizado.');
        redirect('/gestao/eventos');
    }

    public function agenda(Request $request): void
    {
        $this->view('management/agenda/index', [
            'pageTitle'  => 'Agenda — Gestão',
            'breadcrumb' => 'Agenda',
            'events'     => Event::byOrg($this->orgId()),
        ]);
    }
}
