<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
    $statSvg = static function (string $path): string {
        return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $path . '</svg>';
    };
?>

<?php if (!empty($degraded)): ?>
    <div class="alert alert--warning" role="alert" style="margin-bottom:var(--space-5);">
        Não foi possível carregar os relatórios agora. O serviço de dados está temporariamente indisponível — tente novamente em instantes.
    </div>
<?php endif; ?>

<div class="mgmt-header"><div><h1 class="mgmt-header__title">Relatórios</h1><p class="mgmt-header__subtitle">Visão consolidada da plataforma</p></div></div>
<form method="GET" action="<?= url('/admin/relatorios') ?>" class="mgmt-filters">
    <div class="form-group"><label class="form-label" style="font-size:var(--text-xs);">De</label><input type="date" name="start_date" class="form-input" value="<?= e($filters['start_date']) ?>"></div>
    <div class="form-group"><label class="form-label" style="font-size:var(--text-xs);">Até</label><input type="date" name="end_date" class="form-input" value="<?= e($filters['end_date']) ?>"></div>
    <button type="submit" class="btn btn--secondary">Filtrar</button>
</form>

<div class="mgmt-stats-grid" style="margin-top:var(--space-5);">
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--blue"><?= $statSvg('<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle>') ?></span><div><div class="mgmt-stat__value"><?= $totalUsers ?></div><div class="mgmt-stat__label">Total de usuários</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--green"><?= $statSvg('<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><path d="M20 8v6"></path><path d="M23 11h-6"></path>') ?></span><div><div class="mgmt-stat__value"><?= $newUsers ?></div><div class="mgmt-stat__label">Novos no período</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--gold"><?= $statSvg('<path d="M3 21V7l9-4 9 4v14"></path><path d="M9 21v-6h6v6"></path><path d="M3 21h18"></path>') ?></span><div><div class="mgmt-stat__value"><?= $totalOrgs ?></div><div class="mgmt-stat__label">Total de instituições</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--teal"><?= $statSvg('<path d="M3 21V7l9-4 9 4v14"></path><path d="M9 21v-6h6v6"></path><path d="M3 21h18"></path><circle cx="6" cy="10" r="0.5" fill="currentColor"></circle>') ?></span><div><div class="mgmt-stat__value"><?= $newOrgs ?></div><div class="mgmt-stat__label">Novas no período</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--purple"><?= $statSvg('<rect x="2.5" y="5" width="19" height="14" rx="2"></rect><path d="M16 12h.01"></path><path d="M2.5 9h19"></path>') ?></span><div><div class="mgmt-stat__value"><?= $activeSubs ?></div><div class="mgmt-stat__label">Assinaturas ativas</div></div></div>
    <div class="mgmt-stat"><span class="mgmt-stat__icon mgmt-stat__icon--red"><?= $statSvg('<path d="M3 9a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4z"></path><path d="M13 5v14"></path>') ?></span><div><div class="mgmt-stat__value"><?= $openTickets ?></div><div class="mgmt-stat__label">Tickets abertos</div></div></div>
</div>
<?php $__view->endSection(); ?>
