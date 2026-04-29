<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header mgmt-header--reports">
    <div>
        <h1 class="mgmt-header__title">Relatórios</h1>
        <p class="mgmt-header__subtitle">Análises detalhadas e métricas de desempenho da igreja</p>
    </div>
    <form method="GET" action="<?= url('/gestao/relatorios') ?>" class="mgmt-header__actions mgmt-filter-form report-filter">
        <?php $selectedReport = (string) ($filters['type'] ?? 'overview'); ?>
        <?php
            $reportTypes = [
                'overview' => 'Visão geral',
                'financial' => 'Financeiro',
                'members' => 'Membros',
                'events' => 'Eventos',
            ];
        ?>
        <label class="report-filter__select">
            <span>Relatório</span>
            <select name="type" class="form-select" aria-label="Tipo de relatório">
                <?php foreach ($reportTypes as $typeValue => $typeLabel): ?>
                    <option value="<?= e($typeValue) ?>" <?= $selectedReport === $typeValue ? 'selected' : '' ?>><?= e($typeLabel) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label class="report-filter__field">
            <span>Início</span>
            <input type="date" name="start_date" class="form-control" value="<?= e((string) ($filters['start_date'] ?? date('Y-m-01'))) ?>">
        </label>
        <label class="report-filter__field">
            <span>Fim</span>
            <input type="date" name="end_date" class="form-control" value="<?= e((string) ($filters['end_date'] ?? date('Y-m-t'))) ?>">
        </label>
        <a href="<?= url('/gestao/relatorios') ?>" class="btn btn--outline">Limpar</a>
        <button type="submit" class="btn btn--secondary">Aplicar</button>
        <button type="submit" name="export" value="pdf" class="btn btn--primary" formtarget="_blank">Exportar PDF</button>
    </form>
</div>

<?php 
$taxaCativante = $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100) : 0;
?>

<div class="mgmt-kpi-grid" style="grid-template-columns: repeat(4, 1fr);">
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">TOTAL MEMBROS</div>
            <div class="mgmt-kpi-card__value"><?= $totalMembers ?></div>
            <div style="font-size: 11px; color: #10b981; margin-top: 2px;">+12% vs período anterior</div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path></svg>
        </div>
    </div>
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">RECEITAS TOTAIS</div>
            <div class="mgmt-kpi-card__value" style="color: #10b981;">R$ <?= number_format($financial['income'] ?? 0, 2, ',', '.') ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Acumulado do ano</div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
        </div>
    </div>
    <div class="mgmt-kpi-card" style="justify-content:space-between; border-color: rgba(239, 68, 68, 0.2);">
        <div>
            <div class="mgmt-kpi-card__label">DESPESAS TOTAIS</div>
            <div class="mgmt-kpi-card__value" style="color: #ef4444;">R$ <?= number_format($financial['expense'] ?? 0, 2, ',', '.') ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Acumulado do ano</div>
        </div>
        <div class="mgmt-kpi-card__icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
        </div>
    </div>
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">TAXA CATIVANTE</div>
            <div class="mgmt-kpi-card__value"><?= $taxaCativante ?>%</div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Frequência média em eventos</div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--indigo">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
    </div>
</div>

<div class="mgmt-dashboard-grid" style="margin-top: var(--space-6);">
    <article class="mgmt-dashboard-card">
        <header class="mgmt-dashboard-card__header">
            <h2 style="display:flex;align-items:center;gap:8px;">Receitas vs Despesas</h2>
            <span style="font-size: 12px; color: var(--text-muted);">Comparativo financeiro mensal</span>
            <button class="btn btn--outline btn--sm" style="margin-left: auto;">📄 PDF</button>
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

    <article class="mgmt-dashboard-card">
        <header class="mgmt-dashboard-card__header">
            <h2 style="display:flex;align-items:center;gap:8px;">Taxa de Adesão & Visitantes</h2>
            <span style="font-size: 12px; color: var(--text-muted);">Pessoas conectadas nos cultos</span>
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
</div>

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
<?php $__view->endSection(); ?>
