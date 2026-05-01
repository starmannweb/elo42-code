<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
    $report = is_array($report ?? null) ? $report : [];
    $reportFilters = is_array($reportFilters ?? null) ? $reportFilters : [];
    $reportType = (string) ($reportFilters['report_type'] ?? 'overview');
    $reportLabels = [
        'overview' => 'Visão geral',
        'users' => 'Usuários',
        'organizations' => 'Instituições',
        'catalog' => 'Serviços',
        'support' => 'Suporte',
    ];
    $typeLabels = ['church' => 'Igreja', 'association' => 'Associação', 'ministry' => 'Ministério', 'ong' => 'ONG', 'other' => 'Outro'];
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Painel Administrativo</h1>
        <p class="mgmt-header__subtitle">Visão geral da plataforma Elo 42</p>
    </div>
    <div class="mgmt-header__actions">
        <a href="<?= url('/admin/usuarios') ?>" class="btn btn--primary">Gestão de usuários</a>
        <a href="<?= url('/admin/organizacoes') ?>" class="btn btn--outline">Instituições</a>
    </div>
</div>

<div class="mgmt-info-card" style="margin-bottom:var(--space-5); border: 1px dashed rgba(214, 166, 70, 0.4); background: rgba(214, 166, 70, 0.06);">
    <div style="display:flex; flex-wrap:wrap; gap:var(--space-4); align-items:center; justify-content:space-between;">
        <div style="flex:1; min-width: 280px;">
            <h3 class="mgmt-info-card__title" style="margin:0 0 var(--space-1);">Dados de demonstração</h3>
            <p class="mgmt-header__subtitle" style="margin:0;">Popule o sistema com 2 organizações e 3 usuários para testes (senha padrão: <code>demo@2026</code>). Use "Remover" para limpar dados de demo sem afetar contas reais.</p>
        </div>
        <div style="display:flex; gap:var(--space-2); flex-wrap:wrap;">
            <form method="POST" action="<?= url('/admin/seed-demo') ?>" style="margin:0;" onsubmit="return confirm('Popular dados de demonstração?');">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn--gold">+ Popular demo</button>
            </form>
            <form method="POST" action="<?= url('/admin/unseed-demo') ?>" style="margin:0;" onsubmit="return confirm('Remover todos os dados de demonstração? Essa ação remove apenas registros marcados como demo.');">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn--outline btn--danger">Remover demo</button>
            </form>
        </div>
    </div>
</div>

<?php if (!empty($degraded)): ?>
    <div class="alert alert--warning" role="alert" style="margin-bottom:var(--space-5);">
        Painel exibindo dados parciais — o serviço de dados está temporariamente indisponível e novas leituras serão retomadas em instantes.
    </div>
<?php endif; ?>

<div class="mgmt-stats-grid">
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--blue">👤</span><div><div class="mgmt-stat__value"><?= $totalUsers ?></div><div class="mgmt-stat__label">Usuários</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--gold">🏢</span><div><div class="mgmt-stat__value"><?= $totalOrgs ?></div><div class="mgmt-stat__label">Instituições</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--green">💳</span><div><div class="mgmt-stat__value"><?= $activeSubs ?></div><div class="mgmt-stat__label">Assinaturas ativas</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--teal">🧪</span><div><div class="mgmt-stat__value"><?= $trialSubs ?></div><div class="mgmt-stat__label">Em teste</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--red">🎫</span><div><div class="mgmt-stat__value"><?= $openTickets ?></div><div class="mgmt-stat__label">Tickets abertos</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--purple">🔧</span><div><div class="mgmt-stat__value"><?= $activeProducts ?></div><div class="mgmt-stat__label">Serviços ativos</div></div></div>
</div>

<div class="mgmt-info-card" style="margin-top:var(--space-6);">
    <div class="mgmt-dashboard-card__header" style="align-items:flex-start;">
        <div>
            <h2 class="mgmt-info-card__title">Relatórios do painel</h2>
            <p class="mgmt-header__subtitle" style="margin:0;">Gere e exporte relatórios sem sair do dashboard administrativo.</p>
        </div>
    </div>
    <form method="GET" action="<?= url('/admin') ?>" class="mgmt-filter-form report-filter" style="margin-top:var(--space-4);grid-template-columns:1fr 160px 160px auto auto;">
        <select name="report_type" class="form-select">
            <?php foreach ($reportLabels as $value => $label): ?>
                <option value="<?= e($value) ?>" <?= $reportType === $value ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="start_date" class="form-control" value="<?= e((string) ($reportFilters['start_date'] ?? date('Y-m-01'))) ?>">
        <input type="date" name="end_date" class="form-control" value="<?= e((string) ($reportFilters['end_date'] ?? date('Y-m-t'))) ?>">
        <button type="submit" class="btn btn--outline">Gerar</button>
        <button type="submit" name="export" value="csv" class="btn btn--primary">Exportar CSV</button>
    </form>

    <div class="mgmt-kpi-grid" style="grid-template-columns:repeat(4,minmax(0,1fr));margin-top:var(--space-5);">
        <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Novos usuários</div><div class="mgmt-kpi-card__value"><?= (int) ($report['new_users'] ?? 0) ?></div></div></div>
        <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Novas instituições</div><div class="mgmt-kpi-card__value"><?= (int) ($report['new_orgs'] ?? 0) ?></div></div></div>
        <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Assinaturas ativas</div><div class="mgmt-kpi-card__value"><?= (int) ($report['active_subscriptions'] ?? 0) ?></div></div></div>
        <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Cortesias ativas</div><div class="mgmt-kpi-card__value"><?= (int) ($report['active_benefits'] ?? 0) ?></div></div></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-6);margin-top:var(--space-6);">
    <div class="mgmt-info-card">
        <h3 class="mgmt-info-card__title">Últimos usuários</h3>
        <table class="mgmt-table"><thead><tr><th>Nome</th><th>E-mail</th><th>Cadastro</th></tr></thead><tbody>
            <?php foreach ($recentUsers as $u): ?>
            <tr><td class="mgmt-table__name"><?= e($u['name']) ?></td><td class="mgmt-table__sub"><?= e($u['email']) ?></td><td><?= date('d/m H:i', strtotime($u['created_at'])) ?></td></tr>
            <?php endforeach; ?>
        </tbody></table>
    </div>
    <div class="mgmt-info-card">
        <h3 class="mgmt-info-card__title">Últimas instituições</h3>
        <table class="mgmt-table"><thead><tr><th>Nome</th><th>Tipo</th><th>Cadastro</th></tr></thead><tbody>
            <?php foreach ($recentOrgs as $o): ?>
            <tr><td class="mgmt-table__name"><?= e($o['name']) ?></td><td><span class="badge badge--active"><?= e($typeLabels[$o['type'] ?? ''] ?? ($o['type'] ?? '-')) ?></span></td><td><?= date('d/m H:i', strtotime($o['created_at'])) ?></td></tr>
            <?php endforeach; ?>
        </tbody></table>
    </div>
</div>

<?php $__view->endSection(); ?>
