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

<div class="mgmt-info-card admin-demo-card">
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

<?php
    $statSvg = static function (string $path): string {
        return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $path . '</svg>';
    };
?>
<div class="mgmt-stats-grid admin-dashboard-stats">
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--blue"><?= $statSvg('<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>') ?></span><div><div class="mgmt-stat__value"><?= $totalUsers ?></div><div class="mgmt-stat__label">Usuários</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--gold"><?= $statSvg('<path d="M3 21V7l9-4 9 4v14"></path><path d="M9 21v-6h6v6"></path><path d="M3 21h18"></path>') ?></span><div><div class="mgmt-stat__value"><?= $totalOrgs ?></div><div class="mgmt-stat__label">Instituições</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--green"><?= $statSvg('<rect x="2.5" y="5" width="19" height="14" rx="2"></rect><path d="M16 12h.01"></path><path d="M2.5 9h19"></path>') ?></span><div><div class="mgmt-stat__value"><?= $activeSubs ?></div><div class="mgmt-stat__label">Assinaturas ativas</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--teal"><?= $statSvg('<path d="M9 2h6"></path><path d="M10 2v7l-5 9a2 2 0 0 0 1.7 3h10.6a2 2 0 0 0 1.7-3l-5-9V2"></path><path d="M7 14h10"></path>') ?></span><div><div class="mgmt-stat__value"><?= $trialSubs ?></div><div class="mgmt-stat__label">Em teste</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--red"><?= $statSvg('<path d="M3 9a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4z"></path><path d="M13 5v14"></path>') ?></span><div><div class="mgmt-stat__value"><?= $openTickets ?></div><div class="mgmt-stat__label">Tickets abertos</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--purple"><?= $statSvg('<path d="M14.7 6.3a4 4 0 0 1-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 1 5.4-5.4l-2.7 2.7-1.4-1.4 2.7-2.7z"></path>') ?></span><div><div class="mgmt-stat__value"><?= $activeProducts ?></div><div class="mgmt-stat__label">Serviços ativos</div></div></div>
</div>

<div class="mgmt-info-card" style="margin-top:var(--space-6);">
    <div class="mgmt-dashboard-card__header" style="align-items:flex-start;">
        <div>
            <h2 class="mgmt-info-card__title">Relatórios do painel</h2>
            <p class="mgmt-header__subtitle" style="margin:0;">Gere e exporte relatórios sem sair do dashboard administrativo.</p>
        </div>
    </div>
    <form method="GET" action="<?= url('/admin') ?>" class="mgmt-filter-form report-filter" data-auto-submit style="margin-top:var(--space-4);grid-template-columns:1fr 160px 160px auto;">
        <select name="report_type" class="form-select">
            <?php foreach ($reportLabels as $value => $label): ?>
                <option value="<?= e($value) ?>" <?= $reportType === $value ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="start_date" class="form-control" value="<?= e((string) ($reportFilters['start_date'] ?? date('Y-m-01'))) ?>">
        <input type="date" name="end_date" class="form-control" value="<?= e((string) ($reportFilters['end_date'] ?? date('Y-m-t'))) ?>">
        <button type="submit" name="export" value="csv" class="btn btn--primary">Exportar CSV</button>
    </form>
    <script>
    (function () {
        var form = document.querySelector('.report-filter[data-auto-submit]');
        if (!form) return;
        form.querySelectorAll('select, input[type="date"]').forEach(function (field) {
            field.addEventListener('change', function () {
                if (typeof form.requestSubmit === 'function') form.requestSubmit();
                else form.submit();
            });
        });
    })();
    </script>

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
