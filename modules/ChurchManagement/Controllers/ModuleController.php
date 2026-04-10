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
            $this->renderModule(
                'Visitantes',
                'visitantes',
                'Gerencie os visitantes da sua igreja, acompanhe frequencia e faca o acompanhamento pastoral.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg>'
            );
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar visitantes.');
            redirect('/gestao');
        }
    }

    public function newConverts(Request $request): void
    {
        try {
            $this->renderModule(
                'Novos Convertidos',
                'novos-convertidos',
                'Acompanhe os novos convertidos, registre decisões de fé e gerencie o processo de discipulado.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>'
            );
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
            $this->renderModule(
                'Grupos Pequenos',
                'celulas',
                'Gerencie os grupos pequenos (celulas) da sua igreja, lideres, membros e encontros semanais.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><circle cx="5" cy="7" r="2"></circle><circle cx="19" cy="7" r="2"></circle><circle cx="5" cy="17" r="2"></circle><circle cx="19" cy="17" r="2"></circle></svg>'
            );
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar grupos pequenos.');
            redirect('/gestao');
        }
    }

    public function journeys(Request $request): void
    {
        try {
            $this->renderModule(
                'Jornadas',
                'jornadas',
                'Crie e gerencie trilhas de crescimento espiritual para os membros da sua igreja.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>'
            );
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar jornadas.');
            redirect('/gestao');
        }
    }

    public function history(Request $request): void
    {
        try {
            $this->renderModule(
                'Historico',
                'historico',
                'Visualize o historico completo de atividades, eventos e movimentacoes da sua igreja.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>'
            );
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
            $this->renderModule(
                'Auditoria',
                'auditoria',
                'Acompanhe todas as movimentacoes financeiras com trilha de auditoria completa.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>'
            );
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar auditoria.');
            redirect('/gestao');
        }
    }

    public function accounts(Request $request): void
    {
        try {
            $this->renderModule(
                'Contas / Caixa',
                'contas',
                'Gerencie contas bancarias, caixas e saldos da organizacao.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2.5" y="5" width="19" height="14" rx="2"></rect><path d="M16 12h.01"></path><path d="M2.5 9h19"></path></svg>'
            );
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
            $this->renderModule(
                'Campanhas de Arrecadação',
                'campanhas',
                'Crie campanhas de arrecadação e acompanhe o progresso das doações.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>'
            );
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function readingPlan(Request $request): void
    {
        try {
            $this->renderModule(
                'Planos de Leitura',
                'plano-leitura',
                'Gerencie planos de leitura bíblica para engajar a igreja.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>'
            );
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function expensesApprovals(Request $request): void
    {
        try {
            $this->renderModule(
                'Aprovações de Despesas',
                'aprovacoes-despesas',
                'Gerencie as requisições de compra e aprovações de gastos da igreja.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>'
            );
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

