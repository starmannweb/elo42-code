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
            $isTopDonors = str_ends_with($path, '/top-ofertantes') || str_ends_with($path, '/ranking');

            $topDonors = [];
            if ($isTopDonors) {
                try {
                    $pdo = Database::connection();
                    $currentYear = date('Y');
                    $stmt = $pdo->prepare("
                        SELECT
                            COALESCE(m.name, d.donor_name, 'Anônimo') AS name,
                            COUNT(d.id) AS donations_count,
                            SUM(d.amount) AS total_amount
                        FROM donations d
                        LEFT JOIN members m ON d.member_id = m.id
                        WHERE d.organization_id = :org_id
                          AND d.type IN ('tithe', 'offering')
                          AND d.donation_date LIKE :year_pattern
                        GROUP BY d.member_id, d.donor_name, m.name
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
                'pageTitle'   => ($isTopDonors ? 'Ranking de Ofertantes' : 'Membros') . ' - Gestão',
                'breadcrumb'  => $isTopDonors ? 'Ranking de Ofertantes' : 'Membros',
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

    public function map(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            if ($orgId <= 0) {
                Session::flash('warning', 'Complete o cadastro da organizacao para acessar o mapa de membros.');
                redirect('/onboarding/organizacao');
            }

            $members = [];
            try {
                $stmt = Database::connection()->prepare("
                    SELECT id, name, email, phone, address, city, state, status, photo, latitude, longitude
                    FROM members
                    WHERE organization_id = :org_id
                      AND latitude IS NOT NULL
                      AND longitude IS NOT NULL
                    ORDER BY name ASC
                ");
                $stmt->execute(['org_id' => $orgId]);
                $members = $stmt->fetchAll() ?: [];
            } catch (\Throwable $e) {
                error_log('Error fetching member map: ' . $e->getMessage());
            }

            $this->view('management/members/map', [
                'pageTitle'      => 'Mapa de Membros - Gestão',
                'breadcrumb'     => 'Administração / Mapa de Membros',
                'members'        => $members,
                'totalMembers'   => Member::countByOrg($orgId),
                'locatedMembers' => count($members),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar mapa de membros: ' . $e->getMessage());
            redirect('/gestao/membros');
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
                'address','city','state','zip_code','latitude','longitude','membership_date','baptism_date','status','notes'
            ]);
            $data['church_unit_id'] = (int) ($data['church_unit_id'] ?? 0) ?: null;
            $this->normalizeMemberCoordinates($data);
            $data = $this->applyMemberPhoto($request, $data, $orgId);

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
                'address','city','state','zip_code','latitude','longitude','membership_date','baptism_date','status','notes'
            ]);
            $data['church_unit_id'] = (int) ($data['church_unit_id'] ?? 0) ?: null;
            $this->normalizeMemberCoordinates($data);
            $data = $this->applyMemberPhoto($request, $data, $this->orgId());
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

    private function normalizeMemberCoordinates(array &$data): void
    {
        foreach (['latitude', 'longitude'] as $key) {
            if (!array_key_exists($key, $data)) {
                continue;
            }

            $value = str_replace(',', '.', trim((string) $data[$key]));
            if ($value === '' || !is_numeric($value)) {
                $data[$key] = null;
                continue;
            }

            $float = (float) $value;
            if ($key === 'latitude' && ($float < -90 || $float > 90)) {
                $data[$key] = null;
                continue;
            }
            if ($key === 'longitude' && ($float < -180 || $float > 180)) {
                $data[$key] = null;
                continue;
            }

            $data[$key] = number_format($float, 7, '.', '');
        }
    }

    private function applyMemberPhoto(Request $request, array $data, int $orgId): array
    {
        $photoData = trim((string) $request->input('photo_cropped', ''));
        if ($photoData === '') {
            return $data;
        }

        $data['photo'] = $this->storeMemberPhotoData($photoData, $orgId);
        return $data;
    }

    private function storeMemberPhotoData(string $photoData, int $orgId): string
    {
        if (!preg_match('/^data:image\/(png|jpeg|jpg|webp);base64,([A-Za-z0-9+\/=]+)$/', $photoData, $matches)) {
            throw new \RuntimeException('Imagem de membro invalida.');
        }

        $binary = base64_decode($matches[2], true);
        if ($binary === false || strlen($binary) > 6 * 1024 * 1024) {
            throw new \RuntimeException('Imagem de membro invalida ou muito grande.');
        }

        $relativeDir = '/uploads/members/' . max(0, $orgId);
        $targetDir = BASE_PATH . '/public' . $relativeDir;
        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            throw new \RuntimeException('Nao foi possivel preparar a pasta de fotos.');
        }

        $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
        $fileName = 'member-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
        $target = $targetDir . '/' . $fileName;

        if (file_put_contents($target, $binary) === false) {
            throw new \RuntimeException('Nao foi possivel salvar a foto do membro.');
        }

        return $relativeDir . '/' . $fileName;
    }
}
