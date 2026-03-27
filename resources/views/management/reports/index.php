<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div><h1 class="mgmt-header__title">Relatórios</h1><p class="mgmt-header__subtitle">Visão consolidada da organização</p></div>
</div>

<form method="GET" action="<?= url('/gestao/relatorios') ?>" class="mgmt-filters">
    <input type="date" name="start_date" class="form-input" value="<?= e($filters['start_date']) ?>">
    <input type="date" name="end_date" class="form-input" value="<?= e($filters['end_date']) ?>">
    <button type="submit" class="btn btn--ghost">Filtrar período</button>
</form>

<div class="mgmt-stats-grid">
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--blue">👥</span><div><div class="mgmt-stat__value"><?= $totalMembers ?></div><div class="mgmt-stat__label">Total de membros</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--green">✅</span><div><div class="mgmt-stat__value"><?= $activeMembers ?></div><div class="mgmt-stat__label">Membros ativos</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--teal">🆕</span><div><div class="mgmt-stat__value"><?= $newMembers ?></div><div class="mgmt-stat__label">Novos este mês</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--purple">📅</span><div><div class="mgmt-stat__value"><?= $activeEvents ?></div><div class="mgmt-stat__label">Eventos ativos</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--red">📝</span><div><div class="mgmt-stat__value"><?= $openRequests ?></div><div class="mgmt-stat__label">Solicitações abertas</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--gold">🎯</span><div><div class="mgmt-stat__value"><?= $pendingTasks ?></div><div class="mgmt-stat__label">Tarefas pendentes</div></div></div>
</div>

<h2 class="mgmt-header__title" style="margin:var(--space-6) 0 var(--space-4)">Financeiro do período</h2>
<div class="financial-summary">
    <div class="financial-summary__card"><div class="financial-summary__label">Entradas</div><div class="financial-summary__value financial-summary__value--income">R$ <?= number_format($financial['income'], 2, ',', '.') ?></div></div>
    <div class="financial-summary__card"><div class="financial-summary__label">Saídas</div><div class="financial-summary__value financial-summary__value--expense">R$ <?= number_format($financial['expense'], 2, ',', '.') ?></div></div>
    <div class="financial-summary__card"><div class="financial-summary__label">Saldo</div><div class="financial-summary__value financial-summary__value--balance">R$ <?= number_format($financial['balance'], 2, ',', '.') ?></div></div>
</div>

<?php if (!empty($donationSummary)): ?>
<h2 class="mgmt-header__title" style="margin:var(--space-6) 0 var(--space-4)">Doações por tipo</h2>
<div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Tipo</th><th>Quantidade</th><th>Total</th></tr></thead><tbody>
    <?php foreach ($donationSummary as $ds): ?><tr>
        <td class="mgmt-table__name"><?= e(match($ds['type']) { 'tithe'=>'Dízimos','offering'=>'Ofertas','special'=>'Especial','campaign'=>'Campanha', default=>ucfirst($ds['type']) }) ?></td>
        <td><?= $ds['count'] ?></td>
        <td class="font-bold text-success">R$ <?= number_format((float)$ds['total'], 2, ',', '.') ?></td>
    </tr><?php endforeach; ?>
</tbody></table></div>
<?php endif; ?>

<div class="text-center text-muted" style="padding:var(--space-8);font-size:var(--text-sm)">
    <p>📊 Exportação em PDF/Excel será disponibilizada em uma atualização futura.</p>
</div>
<?php $__view->endSection(); ?>
