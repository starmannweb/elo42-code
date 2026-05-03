<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header mgmt-header--reports mgmt-header--stack">
    <div class="mgmt-header__intro">
        <h1 class="mgmt-header__title">Relatórios</h1>
        <p class="mgmt-header__subtitle">Análises detalhadas e métricas de desempenho da igreja</p>
    </div>
    <form method="GET" action="<?= url('/gestao/relatorios') ?>" class="mgmt-report-filter" data-auto-submit>
        <?php $selectedReport = (string) ($filters['type'] ?? 'overview'); ?>
        <?php
            $reportTypes = [
                'overview' => 'Visão geral',
                'financial' => 'Financeiro',
                'members' => 'Membros',
                'events' => 'Eventos',
            ];
        ?>
        <label class="mgmt-report-filter__field">
            <span>Relatório</span>
            <select name="type" class="form-select" aria-label="Tipo de relatório">
                <?php foreach ($reportTypes as $typeValue => $typeLabel): ?>
                    <option value="<?= e($typeValue) ?>" <?= $selectedReport === $typeValue ? 'selected' : '' ?>><?= e($typeLabel) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label class="mgmt-report-filter__field">
            <span>Início</span>
            <input type="date" name="start_date" class="form-control" value="<?= e((string) ($filters['start_date'] ?? date('Y-m-01'))) ?>">
        </label>
        <label class="mgmt-report-filter__field">
            <span>Fim</span>
            <input type="date" name="end_date" class="form-control" value="<?= e((string) ($filters['end_date'] ?? date('Y-m-t'))) ?>">
        </label>
        <div class="mgmt-report-filter__actions">
            <button type="submit" name="export" value="pdf" class="btn btn--primary" formtarget="_blank">Exportar PDF</button>
        </div>
    </form>
</div>

<?php
$taxaCativante = $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100) : 0;
$reportType = $selectedReport ?? 'overview';
$showFinancial = in_array($reportType, ['overview', 'financial'], true);
$showMembers   = in_array($reportType, ['overview', 'members'], true);
$showEvents    = in_array($reportType, ['overview', 'events'], true);
?>

<div class="mgmt-report-banner" style="background: rgba(10,77,255,.05); border:1px solid rgba(10,77,255,.18); border-radius:12px; padding: 12px 16px; margin-bottom: var(--space-5); font-size: 13px; color: var(--color-text-primary);">
    <strong><?= e($reportTypes[$reportType] ?? 'Relatório') ?></strong>
    · <?= e(date('d/m/Y', strtotime((string) ($filters['start_date'] ?? date('Y-m-01'))))) ?>
    a <?= e(date('d/m/Y', strtotime((string) ($filters['end_date'] ?? date('Y-m-t'))))) ?>
</div>

<div class="mgmt-kpi-grid" style="grid-template-columns: repeat(4, 1fr);">
    <?php if ($showMembers): ?>
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">TOTAL MEMBROS</div>
            <div class="mgmt-kpi-card__value"><?= $totalMembers ?></div>
            <div style="font-size: 11px; color: #10b981; margin-top: 2px;"><?= (int) ($newMembers ?? 0) ?> novo(s) no per&iacute;odo</div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path></svg>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($showFinancial): ?>
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">RECEITAS TOTAIS</div>
            <div class="mgmt-kpi-card__value" style="color: #10b981;">R$ <?= number_format($financial['income'] ?? 0, 2, ',', '.') ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">No per&iacute;odo selecionado</div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
        </div>
    </div>
    <div class="mgmt-kpi-card" style="justify-content:space-between; border-color: rgba(239, 68, 68, 0.2);">
        <div>
            <div class="mgmt-kpi-card__label">DESPESAS TOTAIS</div>
            <div class="mgmt-kpi-card__value" style="color: #ef4444;">R$ <?= number_format($financial['expense'] ?? 0, 2, ',', '.') ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">No per&iacute;odo selecionado</div>
        </div>
        <div class="mgmt-kpi-card__icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($showEvents): ?>
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">EVENTOS ATIVOS</div>
            <div class="mgmt-kpi-card__value"><?= (int) ($activeEvents ?? 0) ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Agenda e celebra&ccedil;&otilde;es</div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--indigo">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($showMembers): ?>
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">TAXA DE ATIVOS</div>
            <div class="mgmt-kpi-card__value"><?= $taxaCativante ?>%</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Membros ativos no total</div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--indigo">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="mgmt-dashboard-grid" style="margin-top: var(--space-6);">
    <?php if ($showFinancial): ?>
    <article class="mgmt-dashboard-card">
        <header class="mgmt-dashboard-card__header" style="display:flex;flex-direction:column;align-items:flex-start;gap:4px;">
            <h2 style="margin:0;">Receitas vs Despesas</h2>
            <span style="font-size: 12px; color: var(--text-muted);">Comparativo financeiro mensal</span>
        </header>
        <div style="display: flex; align-items: flex-end; justify-content: space-around; height: 200px; padding: var(--space-4) 0;">
            <?php
            $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'];
            $receitas = [15000, 18000, 22000, 19000, 25000, 21000];
            $despesas = [12000, 14000, 16000, 15000, 18000, 17000];
            $maxVal = max(max($receitas), max($despesas));
            foreach ($meses as $i => $mes):
                $hRec = ($receitas[$i] / $maxVal) * 150;
                $hDesp = ($despesas[$i] / $maxVal) * 150;
            ?>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                <div style="display: flex; gap: 4px; align-items: flex-end;">
                    <div style="width: 20px; height: <?= $hRec ?>px; background: #10b981; border-radius: 4px 4px 0 0;"></div>
                    <div style="width: 20px; height: <?= $hDesp ?>px; background: #ef4444; border-radius: 4px 4px 0 0;"></div>
                </div>
                <span style="font-size: 11px; color: var(--text-muted);"><?= $mes ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="display: flex; justify-content: center; gap: var(--space-6); font-size: 12px; color: var(--text-muted);">
            <span style="display: flex; align-items: center; gap: 4px;"><span style="width: 10px; height: 10px; background: #10b981; border-radius: 2px;"></span> Receitas (R$)</span>
            <span style="display: flex; align-items: center; gap: 4px;"><span style="width: 10px; height: 10px; background: #ef4444; border-radius: 2px;"></span> Despesas (R$)</span>
        </div>
    </article>
    <?php endif; ?>

    <?php if ($showMembers || $showEvents): ?>
    <article class="mgmt-dashboard-card">
        <header class="mgmt-dashboard-card__header">
            <h2 style="display:flex;align-items:center;gap:8px;"><?= $showEvents && !$showMembers ? 'Frequência em eventos' : 'Taxa de Adesão & Visitantes' ?></h2>
            <span style="font-size: 12px; color: var(--text-muted);"><?= $showEvents && !$showMembers ? 'Inscrições e check-ins' : 'Pessoas conectadas nos cultos' ?></span>
        </header>
        <div style="display: flex; align-items: flex-end; justify-content: space-around; height: 200px; padding: var(--space-4) 0;">
            <?php 
            $semanas = ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4', 'Sem 5'];
            $membrosAtivos = [320, 340, 350, 360, 380];
            $visitantes = [20, 35, 25, 40, 30];
            foreach ($semanas as $i => $sem): 
            ?>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                <div style="width: 40px; height: <?= ($membrosAtivos[$i] / 400) * 150 ?>px; background: linear-gradient(180deg, #3b82f6 0%, #1e3a8a 100%); border-radius: 4px 4px 0 0; position: relative;">
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: <?= ($visitantes[$i] / 400) * 150 ?>px; background: #d6a646; border-radius: 0 0 4px 4px;"></div>
                </div>
                <span style="font-size: 11px; color: var(--text-muted);"><?= $sem ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="display: flex; justify-content: center; gap: var(--space-6); font-size: 12px; color: var(--text-muted);">
            <span style="display: flex; align-items: center; gap: 4px;"><span style="width: 10px; height: 10px; background: #3b82f6; border-radius: 2px;"></span> Membros Ativos</span>
            <span style="display: flex; align-items: center; gap: 4px;"><span style="width: 10px; height: 10px; background: #d6a646; border-radius: 2px;"></span> Novos Visitantes</span>
        </div>
    </article>
    <?php endif; ?>
</div>

<?php if ($showMembers): ?>
<article class="mgmt-dashboard-card" style="margin-top: var(--space-6);">
    <header class="mgmt-dashboard-card__header">
        <h2 style="display:flex;align-items:center;gap:8px;">Status de Membresia</h2>
    </header>
    <div style="display: flex; gap: var(--space-8); padding: var(--space-4);">
        <div style="flex: 1;">
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: var(--space-4);">Avalie o engajamento da igreja de maneira global e acompanhe o percentual de visitantes que está se convertendo em novos membros ativos mensalmente.</p>
            <div style="display: flex; flex-direction: column; gap: var(--space-3);">
                <div style="display: flex; align-items: center; gap: var(--space-3);">
                    <span style="width: 12px; height: 12px; background: #10b981; border-radius: 50%;"></span>
                    <span style="font-weight: 600;">Ativos</span>
                    <span style="margin-left: auto; font-weight: 700;">70%</span>
                </div>
                <div style="display: flex; align-items: center; gap: var(--space-3);">
                    <span style="width: 12px; height: 12px; background: #ef4444; border-radius: 50%;"></span>
                    <span style="font-weight: 600;">Inativos</span>
                    <span style="margin-left: auto; font-weight: 700;">15%</span>
                </div>
                <div style="display: flex; align-items: center; gap: var(--space-3);">
                    <span style="width: 12px; height: 12px; background: #d6a646; border-radius: 50%;"></span>
                    <span style="font-weight: 600;">Visitantes</span>
                    <span style="margin-left: auto; font-weight: 700;">15%</span>
                </div>
            </div>
        </div>
        <div style="width: 200px; height: 200px; position: relative;">
            <svg viewBox="0 0 36 36" style="width: 100%; height: 100%; transform: rotate(-90deg);">
                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#10b981" stroke-width="3.8" stroke-dasharray="70 30" stroke-dashoffset="0"></circle>
                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#ef4444" stroke-width="3.8" stroke-dasharray="15 85" stroke-dashoffset="-70"></circle>
                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#d6a646" stroke-width="3.8" stroke-dasharray="15 85" stroke-dashoffset="-85"></circle>
            </svg>
        </div>
    </div>
</article>
<?php endif; ?>

<?php if ($showFinancial && $reportType === 'financial'): ?>
<article class="mgmt-dashboard-card" style="margin-top: var(--space-6);">
    <header class="mgmt-dashboard-card__header">
        <h2 style="display:flex;align-items:center;gap:8px;">Receitas por categoria</h2>
        <span style="font-size:12px;color:var(--text-muted);">Distribuição das entradas no período</span>
    </header>
    <div style="padding: var(--space-3) 0; display:grid; gap: var(--space-3);">
        <?php
            $donationSummary = is_array($donationSummary ?? null) ? $donationSummary : [];
            if (empty($donationSummary)):
        ?>
            <div class="mgmt-dashboard-empty">
                <span class="mgmt-empty-circle"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="7" x2="20" y2="7"></line><line x1="4" y1="12" x2="20" y2="12"></line><line x1="4" y1="17" x2="20" y2="17"></line></svg></span>
                <strong>Sem categorias movimentadas</strong>
                <span>Registre receitas para visualizar a quebra por categoria.</span>
            </div>
        <?php else:
            $totalIncome = max(1, array_sum(array_map(static fn($r) => (float) ($r['total'] ?? 0), $donationSummary)));
            foreach ($donationSummary as $row):
                $value = (float) ($row['total'] ?? 0);
                $pct = round(($value / $totalIncome) * 100);
        ?>
            <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;"><strong><?= e((string) ($row['type'] ?? 'Categoria')) ?></strong><span>R$ <?= number_format($value, 2, ',', '.') ?> · <?= $pct ?>%</span></div>
                <div style="height:8px;background:rgba(10,77,255,.08);border-radius:999px;overflow:hidden;"><span style="display:block;height:100%;width:<?= $pct ?>%;background:linear-gradient(90deg,#0a4dff,#10b981);"></span></div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</article>
<?php endif; ?>
<?php $__view->endSection(); ?>
