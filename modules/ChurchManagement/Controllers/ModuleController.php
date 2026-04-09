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
                'Acompanhe os novos convertidos, registre decisoes de fe e gerencie o processo de discipulado.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s-8-4.5-8-11a4 4 0 0 1 7-2.6A4 4 0 0 1 18 10c0 6.5-6 11-6 11z"></path></svg>'
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
        }
    }

    // ── Comunicacao ──────────────────────────────────────────

    public function banners(Request $request): void
    {
        try {
            $this->renderModule(
                'Gerenciador de Banners',
                'banners',
                'Crie e gerencie banners para comunicacao visual da igreja, avisos e campanhas.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>'
            );
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar banners.');
            redirect('/gestao');
        }
    }

    public function courses(Request $request): void
    {
        try {
            $this->renderModule(
                'Cursos',
                'cursos',
                'Crie e gerencie cursos e classes para formacao de membros e lideranca.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M4 4.5A2.5 2.5 0 0 1 6.5 7H20"></path><path d="M6.5 7v10"></path></svg>'
            );
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar cursos.');
            redirect('/gestao');
        }
    }

    public function achievements(Request $request): void
    {
        try {
            $this->renderModule(
                'Conquistas',
                'conquistas',
                'Gerencie conquistas e marcos importantes dos membros da sua igreja.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>'
            );
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar conquistas.');
            redirect('/gestao');
        }
    }

    // ── Administracao ────────────────────────────────────────

    public function campaigns(Request $request): void
    {
        try {
            $this->renderModule(
                'Campanhas',
                'campanhas',
                'Gerencie as campanhas da sua igreja, acompanhe metas e engajamento.'
            );
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar campanhas.');
            redirect('/gestao');
        }
    }

    public function readingPlan(Request $request): void
    {
        try {
            $this->renderModule(
                'Plano de Leitura',
                'plano-leitura',
                'Crie e acompanhe planos de leitura bíblica para engajar seus membros.'
            );
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar plano de leitura.');
            redirect('/gestao');
        }
    }

    public function expensesApprovals(Request $request): void
    {
        try {
            $this->renderModule(
                'Aprovações de Despesas',
                'aprovacoes-despesas',
                'Revise e aprove as despesas lançadas pela equipe financeira.'
            );
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar aprovações de despesas.');
            redirect('/gestao');
        }
    }

    public function ai(Request $request): void
    {
        try {
            $this->renderModule(
                'Inteligencia Artificial',
                'ia',
                'Utilize IA para gerar insights, sermoes, estudos biblicos e auxiliar na gestao pastoral.',
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1a7 7 0 0 1-7 7h-2a7 7 0 0 1-7-7H3a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2z"></path><circle cx="10" cy="13" r="1"></circle><circle cx="14" cy="13" r="1"></circle></svg>'
            );
        } catch (\Throwable $e) {
            Session::flash('error', 'Erro ao carregar IA.');
            redirect('/gestao');
        }
    }
}
