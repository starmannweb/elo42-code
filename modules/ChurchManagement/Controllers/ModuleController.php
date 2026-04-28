<?php

declare(strict_types=1);

namespace Modules\ChurchManagement\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Donation;
use App\Models\FinancialTransaction;
use App\Models\Member;

class ModuleController extends Controller
{
    private function orgId(): int
    {
        $org = Session::get('organization');
        if (is_array($org) && !empty($org['id'])) {
            return (int) $org['id'];
        }
        return 0;
    }

    private function renderModule(string $title, string $slug, string $description, string $icon, array $extra = []): void
    {
        $this->view('management/modules/placeholder', array_merge([
            'pageTitle'  => $title . ' — Gestão',
            'breadcrumb' => $title,
            'activeMenu' => $slug,
            'moduleTitle' => $title,
            'moduleDescription' => $description,
            'moduleIcon' => $icon,
        ], $extra));
    }

    // â”€â”€ Pessoas â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    public function visitors(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $search = $request->input('search', '');
            $status = $request->input('status', '');
            $month = $request->input('month', date('Y-m'));
            
            $visitors = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $sql = "SELECT * FROM visitors WHERE organization_id = :org_id";
                    $params = ['org_id' => $orgId];
                    
                    if (!empty($search)) {
                        $sql .= " AND (name LIKE :search OR phone LIKE :search OR email LIKE :search)";
                        $params['search'] = '%' . $search . '%';
                    }
                    
                    if (!empty($status)) {
                        $sql .= " AND status = :status";
                        $params['status'] = $status;
                    }
                    
                    if (!empty($month)) {
                        $sql .= " AND DATE_FORMAT(visit_date, '%Y-%m') = :month";
                        $params['month'] = $month;
                    }
                    
                    $sql .= " ORDER BY visit_date DESC";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    $visitors = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching visitors: ' . $e->getMessage());
                }
            }
            
            $this->view('management/modules/visitors', [
                'pageTitle' => 'Visitantes — Gestão',
                'breadcrumb' => 'Visitantes',
                'activeMenu' => 'visitantes',
                'visitors' => $visitors,
                'search' => $search,
                'status' => $status,
                'month' => $month,
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar visitantes.');
            redirect('/gestao');
        }
    }

    public function newConverts(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $search = $request->input('search', '');
            $status = $request->input('status', '');
            $month = $request->input('month', date('Y-m'));
            
            $converts = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $sql = "SELECT *, conversion_date as decision_date FROM converts WHERE organization_id = :org_id";
                    $params = ['org_id' => $orgId];
                    
                    if (!empty($search)) {
                        $sql .= " AND (name LIKE :search OR phone LIKE :search)";
                        $params['search'] = '%' . $search . '%';
                    }
                    
                    if (!empty($status)) {
                        $sql .= " AND status = :status";
                        $params['status'] = $status;
                    }
                    
                    if (!empty($month)) {
                        $sql .= " AND DATE_FORMAT(conversion_date, '%Y-%m') = :month";
                        $params['month'] = $month;
                    }
                    
                    $sql .= " ORDER BY conversion_date DESC";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    $converts = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching new converts: ' . $e->getMessage());
                }
            }
            
            $this->view('management/modules/new-converts', [
                'pageTitle' => 'Novos Convertidos — Gestão',
                'breadcrumb' => 'Novos Convertidos',
                'activeMenu' => 'novos-convertidos',
                'converts' => $converts,
                'search' => $search,
                'status' => $status,
                'month' => $month,
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar novos convertidos.');
            redirect('/gestao');
        }
    }

    public function birthdays(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $members = [];
            if ($orgId > 0) {
                try {
                    $allMembers = Member::where('organization_id', $orgId);
                    $currentMonth = (int) date('m');
                    $members = array_filter($allMembers, function($m) use ($currentMonth) {
                        if (empty($m['birth_date'])) return false;
                        return (int) date('m', strtotime($m['birth_date'])) === $currentMonth;
                    });
                    usort($members, function($a, $b) {
                        $dayA = (int) date('d', strtotime($a['birth_date']));
                        $dayB = (int) date('d', strtotime($b['birth_date']));
                        return $dayA - $dayB;
                    });
                } catch (\Throwable $e) {}
            }

            $this->view('management/modules/birthdays', [
                'pageTitle'  => 'Aniversários — Gestão',
                'breadcrumb' => 'Aniversários',
                'activeMenu' => 'aniversarios',
                'members'    => $members,
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar aniversarios.');
            redirect('/gestao');
        }
    }

    // â”€â”€ Grupos & Ministerios â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    public function smallGroups(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            
            $groups = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $stmt = $pdo->prepare("SELECT * FROM small_groups WHERE organization_id = :org_id ORDER BY name ASC");
                    $stmt->execute(['org_id' => $orgId]);
                    $groups = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching small groups: ' . $e->getMessage());
                }
            }
            
            $this->view('management/modules/small-groups', [
                'pageTitle' => 'Grupos Pequenos — Gestão',
                'breadcrumb' => 'Grupos Pequenos',
                'activeMenu' => 'celulas',
                'groups' => $groups,
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar grupos pequenos.');
            redirect('/gestao');
        }
    }

    public function journeys(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            
            $journeys = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $this->ensureSpiritualJourneysTable($pdo);
                    $stmt = $pdo->prepare("
                        SELECT *
                        FROM spiritual_journeys
                        WHERE organization_id = :org_id
                        ORDER BY created_at DESC
                    ");
                    $stmt->execute(['org_id' => $orgId]);
                    $journeys = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching journeys: ' . $e->getMessage());
                }
            }
            
            $this->view('management/modules/journeys', [
                'pageTitle' => 'Jornadas — Gestão',
                'breadcrumb' => 'Jornadas',
                'activeMenu' => 'jornadas',
                'journeys' => $journeys,
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar jornadas.');
            redirect('/gestao');
        }
    }

    public function storeJourney(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            if ($orgId <= 0) {
                Session::flash('error', 'Organização não encontrada.');
                redirect('/gestao/jornadas');
            }

            $title = trim((string) $request->input('title'));
            if ($title === '') {
                Session::flash('error', 'Informe o título da jornada.');
                redirect('/gestao/jornadas');
            }

            $status = (string) $request->input('status', 'draft');
            if (!in_array($status, ['draft', 'active', 'archived'], true)) {
                $status = 'draft';
            }
            $durationDays = trim((string) $request->input('duration_days'));

            $pdo = \App\Core\Database::connection();
            $this->ensureSpiritualJourneysTable($pdo);
            $stmt = $pdo->prepare("
                INSERT INTO spiritual_journeys (
                    organization_id, title, description, duration_days, status, created_at, updated_at
                ) VALUES (
                    :organization_id, :title, :description, :duration_days, :status, NOW(), NOW()
                )
            ");
            $stmt->execute([
                'organization_id' => $orgId,
                'title' => $title,
                'description' => trim((string) $request->input('description')),
                'duration_days' => $durationDays !== '' ? (int) $durationDays : null,
                'status' => $status,
            ]);

            Session::flash('success', 'Jornada criada com sucesso.');
            redirect('/gestao/jornadas');
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao salvar jornada: ' . $e->getMessage());
            redirect('/gestao/jornadas');
        }
    }

    public function removeJourney(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $id = (int) $request->param('id');
            if ($orgId <= 0 || $id <= 0) {
                Session::flash('error', 'Jornada inválida.');
                redirect('/gestao/jornadas');
            }

            $pdo = \App\Core\Database::connection();
            $this->ensureSpiritualJourneysTable($pdo);
            $stmt = $pdo->prepare('DELETE FROM spiritual_journeys WHERE id = :id AND organization_id = :organization_id');
            $stmt->execute(['id' => $id, 'organization_id' => $orgId]);

            Session::flash('success', 'Jornada removida com sucesso.');
            redirect('/gestao/jornadas');
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao remover jornada: ' . $e->getMessage());
            redirect('/gestao/jornadas');
        }
    }

    public function history(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $search = $request->input('search', '');
            $type = $request->input('type', '');
            $startDate = $request->input('start_date', date('Y-m-01'));
            $endDate = $request->input('end_date', date('Y-m-d'));
            
            $activities = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $sql = "SELECT * FROM activity_history WHERE organization_id = :org_id";
                    $params = ['org_id' => $orgId];
                    
                    if (!empty($search)) {
                        $sql .= " AND (title LIKE :search OR description LIKE :search OR user_name LIKE :search)";
                        $params['search'] = '%' . $search . '%';
                    }
                    
                    if (!empty($type)) {
                        $sql .= " AND type = :type";
                        $params['type'] = $type;
                    }
                    
                    if (!empty($startDate)) {
                        $sql .= " AND DATE(created_at) >= :start_date";
                        $params['start_date'] = $startDate;
                    }
                    
                    if (!empty($endDate)) {
                        $sql .= " AND DATE(created_at) <= :end_date";
                        $params['end_date'] = $endDate;
                    }
                    
                    $sql .= " ORDER BY created_at DESC LIMIT 100";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    $activities = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching activity history: ' . $e->getMessage());
                }
            }
            
            $this->view('management/modules/history', [
                'pageTitle' => 'Histórico — Gestão',
                'breadcrumb' => 'Histórico',
                'activeMenu' => 'historico',
                'activities' => $activities,
                'search' => $search,
                'type' => $type,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar historico.');
            redirect('/gestao');
        }
    }

    // â”€â”€ Financeiro â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    public function tithesOfferings(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $org = Session::get('organization');
            $orgName = is_array($org) ? ($org['name'] ?? 'Igreja') : 'Igreja';

            $donations = [];
            $summary = ['total' => 0, 'tithe' => 0, 'offering' => 0, 'donors' => 0];
            $pixKey = '';
            $pixWarning = true;
            $members = [];
            $units = [];

            if ($orgId > 0) {
                try {
                    $filters = [
                        'start_date' => date('Y-m-01'),
                        'end_date'   => date('Y-m-t'),
                    ];
                    $result = Donation::byOrg($orgId, $filters, 1, 30);
                    $donations = $result['data'] ?? [];
                    $summaryData = Donation::summaryByType($orgId, $filters['start_date'], $filters['end_date']);
                    foreach (is_array($summaryData) ? $summaryData : [] as $row) {
                        $type = (string) ($row['type'] ?? '');
                        $amount = (float) ($row['total'] ?? 0);
                        $summary['total'] += $amount;
                        if ($type === 'tithe') {
                            $summary['tithe'] += $amount;
                        } elseif ($type === 'offering') {
                            $summary['offering'] += $amount;
                        }
                        $summary['donors'] += (int) ($row['count'] ?? 0);
                    }
                    $members = Member::byOrg($orgId, [], 1, 500)['data'] ?? [];
                    $units = $this->churchUnits();
                } catch (\Throwable $e) {}

                try {
                    $pdo = \App\Core\Database::connection();
                    $stmt = $pdo->prepare("SELECT value FROM settings WHERE organization_id = :oid AND `key` = 'pix_key' LIMIT 1");
                    $stmt->execute(['oid' => $orgId]);
                    $row = $stmt->fetch();
                    if ($row && !empty($row['value'])) {
                        $pixKey = $row['value'];
                        $pixWarning = false;
                    }
                } catch (\Throwable $e) {}
            }

            $this->view('management/modules/tithes', [
                'pageTitle'  => 'Dízimos & Ofertas — Gestão',
                'breadcrumb' => 'Dízimos & Ofertas',
                'activeMenu' => 'dizimos-ofertas',
                'donations'  => $donations,
                'summary'    => $summary,
                'pixKey'     => $pixKey,
                'pixWarning' => $pixWarning,
                'orgName'    => $orgName,
                'members'    => $members,
                'units'      => $units,
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar dizimos & ofertas.');
            redirect('/gestao');
        }
    }

    public function expenses(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $page = max(1, (int) ($request->input('page', '1') ?: 1));
            $filters = [
                'type' => 'expense',
                'start_date' => $request->input('start_date', date('Y-m-01')),
                'end_date' => $request->input('end_date', date('Y-m-t')),
            ];

            $result = FinancialTransaction::byOrg($orgId, $filters, $page);
            $summary = FinancialTransaction::summary($orgId, $filters['start_date'], $filters['end_date']);

            $this->view('management/financial/expenses', [
                'pageTitle' => 'Despesas - Gestão',
                'breadcrumb' => 'Despesas',
                'activeMenu' => 'despesas',
                'transactions' => $result['data'],
                'pagination' => $result,
                'summary' => $summary,
                'filters' => $filters,
                'categories' => FinancialTransaction::getCategories($orgId),
                'units' => $this->churchUnits(),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar despesas.');
            redirect('/gestao');
        }
    }

    private function legacyExpenses(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $search = $request->input('search', '');
            $month = $request->input('month', date('Y-m'));
            
            $expenses = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $sql = "SELECT * FROM expenses WHERE organization_id = :org_id";
                    $params = ['org_id' => $orgId];
                    
                    if (!empty($search)) {
                        $sql .= " AND (description LIKE :search OR category LIKE :search)";
                        $params['search'] = '%' . $search . '%';
                    }
                    
                    if (!empty($month)) {
                        $sql .= " AND DATE_FORMAT(expense_date, '%Y-%m') = :month";
                        $params['month'] = $month;
                    }
                    
                    $sql .= " ORDER BY expense_date DESC";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    $expenses = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching expenses: ' . $e->getMessage());
                }
            }
            
            $this->view('management/financial/index', [
                'pageTitle' => 'Despesas — Gestão',
                'breadcrumb' => 'Despesas',
                'activeMenu' => 'despesas',
                'expenses' => $expenses,
                'search' => $search,
                'month' => $month,
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar despesas.');
            redirect('/gestao');
        }
    }

    public function auditing(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $search = $request->input('search', '');
            $type = $request->input('type', '');
            $startDate = $request->input('start_date', date('Y-m-01'));
            $endDate = $request->input('end_date', date('Y-m-d'));
            
            $transactions = [];
            $totalRevenue = 0;
            $totalExpenses = 0;
            
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $sql = "SELECT * FROM financial_audit_log WHERE organization_id = :org_id";
                    $params = ['org_id' => $orgId];
                    
                    if (!empty($search)) {
                        $sql .= " AND (description LIKE :search OR user_name LIKE :search)";
                        $params['search'] = '%' . $search . '%';
                    }
                    
                    if (!empty($type)) {
                        $sql .= " AND type = :type";
                        $params['type'] = $type;
                    }
                    
                    if (!empty($startDate)) {
                        $sql .= " AND DATE(created_at) >= :start_date";
                        $params['start_date'] = $startDate;
                    }
                    
                    if (!empty($endDate)) {
                        $sql .= " AND DATE(created_at) <= :end_date";
                        $params['end_date'] = $endDate;
                    }
                    
                    $sql .= " ORDER BY created_at DESC";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    $transactions = $stmt->fetchAll();
                    
                    foreach ($transactions as $tx) {
                        if (($tx['type'] ?? '') === 'receita') {
                            $totalRevenue += (float)($tx['amount'] ?? 0);
                        } elseif (($tx['type'] ?? '') === 'despesa') {
                            $totalExpenses += (float)($tx['amount'] ?? 0);
                        }
                    }
                } catch (\Throwable $e) {
                    error_log('Error fetching audit data: ' . $e->getMessage());
                }
            }
            
            $this->view('management/modules/auditing', [
                'pageTitle' => 'Auditoria — Gestão',
                'breadcrumb' => 'Auditoria',
                'activeMenu' => 'auditoria',
                'transactions' => $transactions,
                'totalRevenue' => $totalRevenue,
                'totalExpenses' => $totalExpenses,
                'search' => $search,
                'type' => $type,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar auditoria.');
            redirect('/gestao');
        }
    }

    public function accounts(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            
            $accounts = [];
            $totalBalance = 0;
            
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $stmt = $pdo->prepare("SELECT * FROM financial_accounts WHERE organization_id = :org_id ORDER BY name ASC");
                    $stmt->execute(['org_id' => $orgId]);
                    $accounts = $stmt->fetchAll();
                    
                    foreach ($accounts as $account) {
                        if (($account['status'] ?? '') === 'active') {
                            $totalBalance += (float)($account['balance'] ?? 0);
                        }
                    }
                } catch (\Throwable $e) {
                    error_log('Error fetching accounts: ' . $e->getMessage());
                }
            }
            
            $this->view('management/modules/accounts', [
                'pageTitle' => 'Contas e Caixa — Gestão',
                'breadcrumb' => 'Contas e Caixa',
                'activeMenu' => 'contas',
                'accounts' => $accounts,
                'totalBalance' => $totalBalance,
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar contas.');
            redirect('/gestao');
        }
    }

    public function financialCategories(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            
            $categories = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $stmt = $pdo->prepare("SELECT * FROM financial_categories WHERE organization_id = :org_id ORDER BY name ASC");
                    $stmt->execute(['org_id' => $orgId]);
                    $categories = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching financial categories: ' . $e->getMessage());
                }
            }
            
            $this->view('management/financial/categories', [
                'pageTitle' => 'Categorias Financeiras — Gestão',
                'breadcrumb' => 'Categorias',
                'activeMenu' => 'categorias-financeiras',
                'categories' => $categories,
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar categorias.');
            redirect('/gestao');
        }
    }

    // â”€â”€ Comunicacao â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    public function campaigns(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            
            $campaigns = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $stmt = $pdo->prepare("
                        SELECT c.*, u.name AS unit_name
                        FROM campaigns c
                        LEFT JOIN church_units u ON u.id = c.church_unit_id
                        WHERE c.organization_id = :org_id
                        ORDER BY c.created_at DESC
                    ");
                    $stmt->execute(['org_id' => $orgId]);
                    $campaigns = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching campaigns: ' . $e->getMessage());
                }
            }
            
            $this->view('management/modules/campaigns', [
                'pageTitle' => 'Campanhas de Arrecadação — Gestão',
                'breadcrumb' => 'Campanhas',
                'activeMenu' => 'campanhas',
                'campaigns' => $campaigns,
                'units' => $this->churchUnits(),
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function storeCampaign(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            if ($orgId <= 0) {
                Session::flash('error', 'Organização não encontrada.');
                redirect('/gestao/campanhas');
            }

            $title = trim((string) $request->input('title'));
            if ($title === '') {
                Session::flash('error', 'Informe o título da campanha.');
                redirect('/gestao/campanhas');
            }

            $status = (string) $request->input('status', 'draft');
            if (!in_array($status, ['draft', 'active', 'published', 'completed', 'archived'], true)) {
                $status = 'draft';
            }

            $goalAmount = (float) str_replace(',', '.', (string) $request->input('goal_amount', '0'));
            $pdo = \App\Core\Database::connection();
            $stmt = $pdo->prepare(
                'INSERT INTO campaigns (organization_id, church_unit_id, title, description, goal_amount, raised_amount, designation, end_date, status, created_at, updated_at)
                 VALUES (:organization_id, :church_unit_id, :title, :description, :goal_amount, 0, :designation, :end_date, :status, :created_at, :updated_at)'
            );
            $stmt->execute([
                'organization_id' => $orgId,
                'church_unit_id' => (int) $request->input('church_unit_id', 0) ?: null,
                'title' => $title,
                'description' => trim((string) $request->input('description')),
                'goal_amount' => max(0, $goalAmount),
                'designation' => trim((string) $request->input('designation')) ?: 'Campanha da igreja',
                'end_date' => $request->input('end_date') ?: null,
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            Session::flash('success', 'Campanha criada com sucesso.');
            redirect('/gestao/campanhas');
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao salvar campanha: ' . $e->getMessage());
            redirect('/gestao/campanhas');
        }
    }

    public function removeCampaign(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $id = (int) $request->param('id');
            if ($orgId <= 0 || $id <= 0) {
                Session::flash('error', 'Campanha inválida.');
                redirect('/gestao/campanhas');
            }

            $stmt = \App\Core\Database::connection()->prepare('DELETE FROM campaigns WHERE id = :id AND organization_id = :organization_id');
            $stmt->execute(['id' => $id, 'organization_id' => $orgId]);

            Session::flash('success', 'Campanha removida com sucesso.');
            redirect('/gestao/campanhas');
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao remover campanha: ' . $e->getMessage());
            redirect('/gestao/campanhas');
        }
    }

    public function readingPlan(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            
            $plans = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $stmt = $pdo->prepare("SELECT * FROM reading_plans WHERE organization_id = :org_id ORDER BY created_at DESC");
                    $stmt->execute(['org_id' => $orgId]);
                    $plans = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching reading plans: ' . $e->getMessage());
                }
            }
            
            $this->view('management/modules/reading-plan', [
                'pageTitle' => 'Planos de Leitura — Gestão',
                'breadcrumb' => 'Planos de Leitura',
                'activeMenu' => 'plano-leitura',
                'plans' => $plans,
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function expensesApprovals(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $search = $request->input('search', '');
            $status = $request->input('status', '');
            $month = $request->input('month', date('Y-m'));
            
            $expenses = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $sql = "SELECT * FROM expense_approvals WHERE organization_id = :org_id";
                    $params = ['org_id' => $orgId];
                    
                    if (!empty($search)) {
                        $sql .= " AND (description LIKE :search OR supplier LIKE :search)";
                        $params['search'] = '%' . $search . '%';
                    }
                    
                    if (!empty($status)) {
                        $sql .= " AND status = :status";
                        $params['status'] = $status;
                    }
                    
                    if (!empty($month)) {
                        $sql .= " AND DATE_FORMAT(expense_date, '%Y-%m') = :month";
                        $params['month'] = $month;
                    }
                    
                    $sql .= " ORDER BY expense_date DESC";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    $expenses = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching expenses: ' . $e->getMessage());
                }
            }
            
            $this->view('management/modules/expenses', [
                'pageTitle' => 'Aprovações de Despesas — Gestão',
                'breadcrumb' => 'Aprovações de Despesas',
                'activeMenu' => 'aprovacoes-despesas',
                'expenses' => $expenses,
                'search' => $search,
                'status' => $status,
                'month' => $month,
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function banners(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            
            $banners = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $stmt = $pdo->prepare("SELECT * FROM banners WHERE organization_id = :org_id ORDER BY display_order ASC, created_at DESC");
                    $stmt->execute(['org_id' => $orgId]);
                    $banners = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching banners: ' . $e->getMessage());
                }
            }
            
            $this->view('management/modules/banners', [
                'pageTitle' => 'Banners — Gestão',
                'breadcrumb' => 'Banners',
                'activeMenu' => 'banners',
                'banners' => $banners,
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function courses(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            
            $courses = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $this->ensureCourseMediaColumns($pdo);
                    $stmt = $pdo->prepare("SELECT * FROM courses WHERE organization_id = :org_id ORDER BY created_at DESC");
                    $stmt->execute(['org_id' => $orgId]);
                    $courses = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching courses: ' . $e->getMessage());
                }
            }
            
            $this->view('management/modules/courses', [
                'pageTitle' => 'Cursos — Gestão',
                'breadcrumb' => 'Cursos',
                'activeMenu' => 'cursos',
                'courses' => $courses,
                'units' => $this->churchUnits(),
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function storeCourse(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            if ($orgId <= 0) {
                Session::flash('error', 'Organização não encontrada.');
                redirect('/gestao/cursos');
            }

            $title = trim((string) $request->input('title'));
            if ($title === '') {
                Session::flash('error', 'Informe o título do curso.');
                redirect('/gestao/cursos');
            }

            $status = (string) $request->input('status', 'draft');
            if (!in_array($status, ['draft', 'published', 'ongoing', 'completed', 'cancelled'], true)) {
                $status = 'draft';
            }

            $pdo = \App\Core\Database::connection();
            $this->ensureCourseMediaColumns($pdo);
            $pdfUrl = $this->storeCoursePdf($request, $orgId);
            $videoUrl = trim((string) $request->input('video_url'));
            $durationHours = trim((string) $request->input('duration_hours'));

            $stmt = $pdo->prepare("
                INSERT INTO courses (
                    organization_id, church_unit_id, title, description, instructor, duration_hours,
                    start_date, end_date, status, pdf_file_url, video_url, created_at, updated_at
                ) VALUES (
                    :organization_id, :church_unit_id, :title, :description, :instructor, :duration_hours,
                    :start_date, :end_date, :status, :pdf_file_url, :video_url, NOW(), NOW()
                )
            ");
            $stmt->execute([
                'organization_id' => $orgId,
                'church_unit_id'  => (int) $request->input('church_unit_id', 0) ?: null,
                'title'           => $title,
                'description'     => trim((string) $request->input('description')),
                'instructor'      => trim((string) $request->input('instructor')),
                'duration_hours'  => $durationHours !== '' ? (int) $durationHours : null,
                'start_date'      => $request->input('start_date') ?: null,
                'end_date'        => $request->input('end_date') ?: null,
                'status'          => $status,
                'pdf_file_url'    => $pdfUrl,
                'video_url'       => $videoUrl !== '' ? $videoUrl : null,
            ]);

            Session::flash('success', 'Curso criado com sucesso.');
            redirect('/gestao/cursos');
        } catch (\InvalidArgumentException $e) {
            Session::flash('error', $e->getMessage());
            redirect('/gestao/cursos');
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao salvar curso: ' . $e->getMessage());
            redirect('/gestao/cursos');
        }
    }

    public function removeCourse(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $id = (int) $request->param('id');
            if ($orgId <= 0 || $id <= 0) {
                Session::flash('error', 'Curso inválido.');
                redirect('/gestao/cursos');
            }

            $pdo = \App\Core\Database::connection();
            $pdo->prepare('DELETE FROM course_enrollments WHERE course_id = :id')->execute(['id' => $id]);
            $pdo->prepare('DELETE FROM course_modules WHERE course_id = :id')->execute(['id' => $id]);
            $stmt = $pdo->prepare('DELETE FROM courses WHERE id = :id AND organization_id = :organization_id');
            $stmt->execute(['id' => $id, 'organization_id' => $orgId]);

            Session::flash('success', 'Curso removido com sucesso.');
            redirect('/gestao/cursos');
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao remover curso: ' . $e->getMessage());
            redirect('/gestao/cursos');
        }
    }

    private function ensureCourseMediaColumns(\PDO $pdo): void
    {
        $columns = $this->tableColumns($pdo, 'courses');
        $definitions = [
            'pdf_file_url' => 'VARCHAR(500) NULL',
            'video_url' => 'VARCHAR(500) NULL',
            'church_unit_id' => 'INT NULL',
        ];

        foreach ($definitions as $column => $definition) {
            if (in_array($column, $columns, true)) {
                continue;
            }
            $pdo->exec("ALTER TABLE courses ADD COLUMN {$column} {$definition}");
        }
    }

    private function churchUnits(): array
    {
        try {
            $stmt = \App\Core\Database::connection()->prepare('SELECT * FROM church_units WHERE organization_id = :organization_id ORDER BY name ASC');
            $stmt->execute(['organization_id' => $this->orgId()]);
            return $stmt->fetchAll();
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function ensureSpiritualJourneysTable(\PDO $pdo): void
    {
        $driver = (string) $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
        if ($driver === 'sqlite') {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS spiritual_journeys (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    organization_id INTEGER NOT NULL,
                    title TEXT NOT NULL,
                    description TEXT NULL,
                    duration_days INTEGER NULL,
                    status TEXT DEFAULT 'draft',
                    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                    updated_at TEXT DEFAULT CURRENT_TIMESTAMP
                )
            ");
            return;
        }

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS spiritual_journeys (
                id INT AUTO_INCREMENT PRIMARY KEY,
                organization_id INT NOT NULL,
                title VARCHAR(180) NOT NULL,
                description TEXT NULL,
                duration_days INT NULL,
                status ENUM('draft','active','archived') DEFAULT 'draft',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_spiritual_journeys_org (organization_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    private function tableColumns(\PDO $pdo, string $table): array
    {
        $driver = (string) $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $stmt = $pdo->query("PRAGMA table_info({$table})");
            return array_map(static fn (array $row): string => (string) $row['name'], $stmt->fetchAll());
        }

        if ($driver === 'pgsql') {
            $stmt = $pdo->prepare('SELECT column_name FROM information_schema.columns WHERE table_name = :table');
            $stmt->execute(['table' => $table]);
            return $stmt->fetchAll(\PDO::FETCH_COLUMN) ?: [];
        }

        $stmt = $pdo->query("SHOW COLUMNS FROM {$table}");
        return array_map(static fn (array $row): string => (string) ($row['Field'] ?? ''), $stmt->fetchAll());
    }

    private function storeCoursePdf(Request $request, int $orgId): ?string
    {
        $file = $request->file('material_pdf');
        if (!is_array($file) || (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ((int) ($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new \InvalidArgumentException('Não foi possível enviar o PDF. Tente novamente.');
        }

        $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
        if ($extension !== 'pdf') {
            throw new \InvalidArgumentException('Envie apenas arquivos PDF nos materiais do curso.');
        }

        $baseDir = BASE_PATH . '/public/uploads/courses/' . $orgId;
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0775, true);
        }

        $fileName = 'curso-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.pdf';
        $target = $baseDir . '/' . $fileName;

        if (!move_uploaded_file((string) $file['tmp_name'], $target)) {
            throw new \InvalidArgumentException('Não foi possível salvar o PDF enviado.');
        }

        return '/uploads/courses/' . $orgId . '/' . $fileName;
    }

    public function achievements(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            
            $achievements = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $stmt = $pdo->prepare("SELECT * FROM achievements WHERE organization_id = :org_id ORDER BY created_at DESC");
                    $stmt->execute(['org_id' => $orgId]);
                    $achievements = $stmt->fetchAll();
                } catch (\Throwable $e) {
                    error_log('Error fetching achievements: ' . $e->getMessage());
                }
            }
            
            $this->view('management/modules/achievements', [
                'pageTitle' => 'Conquistas — Gestão',
                'breadcrumb' => 'Conquistas',
                'activeMenu' => 'conquistas',
                'achievements' => $achievements,
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function storeReadingPlan(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            if ($orgId <= 0) {
                Session::flash('error', 'Organização não encontrada.');
                redirect('/gestao/plano-leitura');
            }

            $title = trim((string) $request->input('title'));
            if ($title === '') {
                Session::flash('error', 'Informe o título do plano.');
                redirect('/gestao/plano-leitura');
            }

            $status = (string) $request->input('status', 'draft');
            if (!in_array($status, ['draft', 'active', 'archived'], true)) {
                $status = 'draft';
            }

            $stmt = \App\Core\Database::connection()->prepare(
                'INSERT INTO reading_plans (organization_id, title, description, duration_days, book_range, participants_count, status, created_at, updated_at)
                 VALUES (:organization_id, :title, :description, :duration_days, :book_range, 0, :status, :created_at, :updated_at)'
            );
            $stmt->execute([
                'organization_id' => $orgId,
                'title' => $title,
                'description' => trim((string) $request->input('description')),
                'duration_days' => max(1, (int) $request->input('duration_days', 30)),
                'book_range' => trim((string) $request->input('book_range')) ?: 'Bíblia',
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            Session::flash('success', 'Plano de leitura criado com sucesso.');
            redirect('/gestao/plano-leitura');
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao salvar plano de leitura: ' . $e->getMessage());
            redirect('/gestao/plano-leitura');
        }
    }

    public function removeReadingPlan(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $id = (int) $request->param('id');
            if ($orgId <= 0 || $id <= 0) {
                Session::flash('error', 'Plano inválido.');
                redirect('/gestao/plano-leitura');
            }

            $stmt = \App\Core\Database::connection()->prepare('DELETE FROM reading_plans WHERE id = :id AND organization_id = :organization_id');
            $stmt->execute(['id' => $id, 'organization_id' => $orgId]);

            Session::flash('success', 'Plano de leitura removido com sucesso.');
            redirect('/gestao/plano-leitura');
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao remover plano de leitura: ' . $e->getMessage());
            redirect('/gestao/plano-leitura');
        }
    }

    public function storeAchievement(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            if ($orgId <= 0) {
                Session::flash('error', 'Organização não encontrada.');
                redirect('/gestao/conquistas');
            }

            $title = trim((string) $request->input('title'));
            if ($title === '') {
                Session::flash('error', 'Informe o título da conquista.');
                redirect('/gestao/conquistas');
            }

            $pdo = \App\Core\Database::connection();
            $stmt = $pdo->prepare(
                'INSERT INTO achievements (organization_id, title, description, icon, points, criteria_type, status, created_at)
                 VALUES (:organization_id, :title, :description, :icon, :points, :criteria_type, :status, :created_at)'
            );
            $stmt->execute([
                'organization_id' => $orgId,
                'title'           => $title,
                'description'     => trim((string) $request->input('description')),
                'icon'            => trim((string) $request->input('icon')) ?: '🏆',
                'points'          => max(0, (int) $request->input('points', 10)),
                'criteria_type'   => trim((string) $request->input('type')) ?: 'growth',
                'status'          => 'active',
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            Session::flash('success', 'Conquista criada com sucesso.');
            redirect('/gestao/conquistas');
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao salvar conquista: ' . $e->getMessage());
            redirect('/gestao/conquistas');
        }
    }

    public function removeAchievement(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $id = (int) $request->param('id');
            if ($orgId <= 0 || $id <= 0) {
                Session::flash('error', 'Conquista inválida.');
                redirect('/gestao/conquistas');
            }

            $pdo = \App\Core\Database::connection();
            $pdo->prepare('DELETE FROM member_achievements WHERE achievement_id = :id')->execute(['id' => $id]);
            $stmt = $pdo->prepare('DELETE FROM achievements WHERE id = :id AND organization_id = :organization_id');
            $stmt->execute(['id' => $id, 'organization_id' => $orgId]);

            Session::flash('success', 'Conquista removida com sucesso.');
            redirect('/gestao/conquistas');
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao remover conquista: ' . $e->getMessage());
            redirect('/gestao/conquistas');
        }
    }

    protected function handleError(\Throwable $e): void
    {
        Session::flash('error', 'Ocorreu um erro ao carregar a página: ' . $e->getMessage());
        redirect('/gestao');
    }
}

