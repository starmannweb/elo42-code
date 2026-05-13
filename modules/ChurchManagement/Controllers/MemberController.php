<?php

declare(strict_types=1);

namespace Modules\ChurchManagement\Controllers;

use App\Core\Controller;
use App\Core\Database;
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

    private function churchUnits(): array
    {
        try {
            $stmt = Database::connection()->prepare('SELECT * FROM church_units WHERE organization_id = :org_id ORDER BY status ASC, name ASC');
            $stmt->execute(['org_id' => $this->orgId()]);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function index(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            if ($orgId <= 0) {
                Session::flash('warning', 'Complete o cadastro da organizacao para acessar os membros.');
                redirect('/onboarding/organizacao');
            }

            $path = parse_url((string) $_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
            $isTopDonors = str_ends_with($path, '/ranking');

            $topDonors = [];
            if ($isTopDonors) {
                try {
                    $pdo = Database::connection();
                    $currentYear = date('Y');
                    $stmt = $pdo->prepare("
                        SELECT 
                            m.name, 
                            COUNT(ft.id) as donations_count, 
                            SUM(ft.amount) as total_amount
                        FROM financial_transactions ft
                        JOIN members m ON ft.member_id = m.id
                        WHERE ft.organization_id = :org_id 
                          AND (ft.type = 'tithe' OR ft.type = 'offering')
                          AND ft.status = 'confirmed'
                          AND ft.transaction_date LIKE :year_pattern
                        GROUP BY ft.member_id
                        ORDER BY total_amount DESC
                        LIMIT 50
                    ");
                    $stmt->execute([
                        'org_id' => $orgId,
                        'year_pattern' => $currentYear . '-%'
                    ]);
                    $topDonors = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching top donors: ' . $e->getMessage());
                }
            }

            $page = (int) ($request->input('page', '1'));
            $filters = [
                'search' => $request->input('search', ''),
                'status' => $request->input('status', ''),
            ];

            $result = Member::byOrg($orgId, $filters, $page);

            $this->view('management/members/index', [
                'pageTitle'   => ($isTopDonors ? 'Top Ofertantes' : 'Membros') . ' - Gestao',
                'breadcrumb'  => 'Membros',
                'members'     => $result['data'],
                'pagination'  => $result,
                'filters'     => $filters,
                'units'       => $this->churchUnits(),
                'isTopDonors' => $isTopDonors,
                'topDonors'   => $topDonors,
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar membros: ' . $e->getMessage());
            redirect('/gestao');
        }
    }

    public function create(Request $request): void
    {
        try {
            if ($this->orgId() <= 0) {
                Session::flash('warning', 'Complete o cadastro da organizacao para adicionar membros.');
                redirect('/onboarding/organizacao');
            }

            $this->view('management/members/form', [
                'pageTitle'  => 'Novo membro - Gestao',
                'breadcrumb' => 'Membros / Novo',
                'member'     => null,
                'units'      => $this->churchUnits(),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar formulário: ' . $e->getMessage());
            redirect('/gestao/membros');
        }
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
            $data = $request->only([
                'name','email','phone','birth_date','gender','marital_status','church_unit_id',
                'address','city','state','zip_code','membership_date','baptism_date','status','notes'
            ]);
            $data['church_unit_id'] = (int) ($data['church_unit_id'] ?? 0) ?: null;

            Member::create(array_merge($data, [
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
            if (!$member || (int) $member['organization_id'] !== $this->orgId()) {
                if ($request->isAjax()) {
                    http_response_code(404);
                    echo json_encode(['error' => 'Membro não encontrado.']);
                    exit;
                }
                redirect('/gestao/membros');
            }
            $member['unit_name'] = null;
            foreach ($this->churchUnits() as $unit) {
                if ((int) ($unit['id'] ?? 0) === (int) ($member['church_unit_id'] ?? 0)) {
                    $member['unit_name'] = (string) ($unit['name'] ?? '');
                    break;
                }
            }
            
            if ($request->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'member' => $member]);
                exit;
            }

            $this->view('management/members/show', [
                'pageTitle'  => e($member['name']) . ' - Gestao',
                'breadcrumb' => 'Membros / ' . $member['name'],
                'member'     => $member,
            ]);
        } catch (\Throwable $e) {
            if ($request->isAjax()) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
                exit;
            }
            Session::flash('error', 'Erro ao carregar membro: ' . $e->getMessage());
            redirect('/gestao/membros');
        }
    }

    public function edit(Request $request): void
    {
        try {
            $member = Member::find((int) $request->param('id'));
            if (!$member || (int) $member['organization_id'] !== $this->orgId()) {
                redirect('/gestao/membros');
            }
            $this->view('management/members/form', [
                'pageTitle'  => 'Editar - ' . e($member['name']),
                'breadcrumb' => 'Membros / Editar',
                'member'     => $member,
                'units'      => $this->churchUnits(),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar membro: ' . $e->getMessage());
            redirect('/gestao/membros');
        }
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
            $data = $request->only([
                'name','email','phone','birth_date','gender','marital_status','church_unit_id',
                'address','city','state','zip_code','membership_date','baptism_date','status','notes'
            ]);
            $data['church_unit_id'] = (int) ($data['church_unit_id'] ?? 0) ?: null;
            Member::update($id, $data);
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
