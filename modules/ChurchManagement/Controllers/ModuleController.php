<?php

declare(strict_types=1);

namespace Modules\ChurchManagement\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use Modules\ChurchManagement\Models\Member;
use Modules\ChurchManagement\Models\Donation;

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
            'pageTitle'  => $title . ' — Gestao',
            'breadcrumb' => $title,
            'activeMenu' => $slug,
            'moduleTitle' => $title,
            'moduleDescription' => $description,
            'moduleIcon' => $icon,
        ], $extra));
    }

    // ── Pessoas ──────────────────────────────────────────────

    public function visitors(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $search = $request->query('search', '');
            $status = $request->query('status', '');
            $month = $request->query('month', date('Y-m'));
            
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
            $search = $request->query('search', '');
            $status = $request->query('status', '');
            $month = $request->query('month', date('Y-m'));
            
            $converts = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $sql = "SELECT * FROM new_converts WHERE organization_id = :org_id";
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
                        $sql .= " AND DATE_FORMAT(decision_date, '%Y-%m') = :month";
                        $params['month'] = $month;
                    }
                    
                    $sql .= " ORDER BY decision_date DESC";
                    
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
                'pageTitle'  => 'Aniversarios — Gestao',
                'breadcrumb' => 'Aniversarios',
                'activeMenu' => 'aniversarios',
                'members'    => $members,
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar aniversarios.');
            redirect('/gestao');
        }
    }

    // ── Grupos & Ministerios ─────────────────────────────────

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
                    $stmt = $pdo->prepare("SELECT * FROM journeys WHERE organization_id = :org_id ORDER BY created_at DESC");
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

    public function history(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $search = $request->query('search', '');
            $type = $request->query('type', '');
            $startDate = $request->query('start_date', date('Y-m-01'));
            $endDate = $request->query('end_date', date('Y-m-d'));
            
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

    // ── Financeiro ───────────────────────────────────────────

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

            if ($orgId > 0) {
                try {
                    $filters = [
                        'start_date' => date('Y-m-01'),
                        'end_date'   => date('Y-m-t'),
                    ];
                    $result = Donation::byOrg($orgId, $filters, 1, 30);
                    $donations = $result['data'] ?? [];
                    $summaryData = Donation::summaryByType($orgId, $filters['start_date'], $filters['end_date']);
                    $summary = is_array($summaryData) ? $summaryData : $summary;
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
                'pageTitle'  => 'Dizimos & Ofertas — Gestao',
                'breadcrumb' => 'Dizimos & Ofertas',
                'activeMenu' => 'dizimos-ofertas',
                'donations'  => $donations,
                'summary'    => $summary,
                'pixKey'     => $pixKey,
                'pixWarning' => $pixWarning,
                'orgName'    => $orgName,
            ]);
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar dizimos & ofertas.');
            redirect('/gestao');
        }
    }

    public function expenses(Request $request): void
    {
        try {
            $this->renderModule(
                'Aprovacoes de Despesas',
                'despesas',
                'Controle e aprove despesas da igreja com fluxo de aprovacao e historico completo.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 14l6-6"></path><circle cx="9.5" cy="8.5" r="1.5"></circle><circle cx="14.5" cy="13.5" r="1.5"></circle><rect x="2" y="2" width="20" height="20" rx="2.5"></rect></svg>'
            );
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar despesas.');
            redirect('/gestao');
        }
    }

    public function auditing(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            $search = $request->query('search', '');
            $type = $request->query('type', '');
            $startDate = $request->query('start_date', date('Y-m-01'));
            $endDate = $request->query('end_date', date('Y-m-d'));
            
            $transactions = [];
            $totalRevenue = 0;
            $totalExpenses = 0;
            
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $sql = "SELECT * FROM financial_audit WHERE organization_id = :org_id";
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
            $this->renderModule(
                'Categorias Financeiras',
                'categorias-financeiras',
                'Organize receitas e despesas em categorias para melhor controle e relatorios.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>'
            );
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar categorias.');
            redirect('/gestao');
    // ── Comunicacao ──────────────────────────────────────────

    public function campaigns(Request $request): void
    {
        try {
            $orgId = $this->orgId();
            
            $campaigns = [];
            if ($orgId > 0) {
                try {
                    $pdo = \App\Core\Database::connection();
                    $stmt = $pdo->prepare("SELECT * FROM campaigns WHERE organization_id = :org_id ORDER BY created_at DESC");
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
                'csrf' => Session::get('csrf_token', ''),
            ]);
        } catch (\Throwable $e) {
            $this->handleError($e);
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
            $search = $request->query('search', '');
            $status = $request->query('status', '');
            $month = $request->query('month', date('Y-m'));
            
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

    protected function handleError(\Throwable $e): void
    {
        Session::flash('error', 'Ocorreu um erro ao carregar a página: ' . $e->getMessage());
        redirect('/gestao');
    }
}

