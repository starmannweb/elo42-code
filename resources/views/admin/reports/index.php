<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Relatórios</h1><p class="mgmt-header__subtitle">Visão consolidada da plataforma</p></div></div>
<form method="GET" action="<?= url('/admin/relatorios') ?>" class="mgmt-filters">
    <div class="form-group"><label class="form-label" style="font-size:var(--text-xs);">De</label><input type="date" name="start_date" class="form-input" value="<?= e($filters['start_date']) ?>"></div>
    <div class="form-group"><label class="form-label" style="font-size:var(--text-xs);">Até</label><input type="date" name="end_date" class="form-input" value="<?= e($filters['end_date']) ?>"></div>
    <button type="submit" class="btn btn--secondary">Filtrar</button>
</form>

<div class="mgmt-stats-grid" style="margin-top:var(--space-5);">
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--blue">👤</span><div><div class="mgmt-stat__value"><?= $totalUsers ?></div><div class="mgmt-stat__label">Total de usuários</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--green">👤</span><div><div class="mgmt-stat__value"><?= $newUsers ?></div><div class="mgmt-stat__label">Novos no período</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--gold">🏢</span><div><div class="mgmt-stat__value"><?= $totalOrgs ?></div><div class="mgmt-stat__label">Total de organizações</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--teal">🏢</span><div><div class="mgmt-stat__value"><?= $newOrgs ?></div><div class="mgmt-stat__label">Novas no período</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--purple">💳</span><div><div class="mgmt-stat__value"><?= $activeSubs ?></div><div class="mgmt-stat__label">Assinaturas ativas</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--red">🎫</span><div><div class="mgmt-stat__value"><?= $openTickets ?></div><div class="mgmt-stat__label">Tickets abertos</div></div></div>
</div>
<?php $__view->endSection(); ?>
