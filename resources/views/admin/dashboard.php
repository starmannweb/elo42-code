<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Painel Administrativo</h1>
        <p class="mgmt-header__subtitle">Visão geral da plataforma Elo 42</p>
    </div>
    <div class="mgmt-header__actions">
        <a href="<?= url('/admin/usuarios') ?>" class="btn btn--primary">Gestao de usuarios</a>
        <a href="<?= url('/admin/organizacoes') ?>" class="btn btn--outline">Organizacoes</a>
    </div>
</div>

<div class="mgmt-stats-grid">
    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--blue">👤</span>
        <div><div class="mgmt-stat__value"><?= $totalUsers ?></div><div class="mgmt-stat__label">Usuários</div></div>
    </div>
    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--gold">🏢</span>
        <div><div class="mgmt-stat__value"><?= $totalOrgs ?></div><div class="mgmt-stat__label">Organizações</div></div>
    </div>
    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--green">💳</span>
        <div><div class="mgmt-stat__value"><?= $activeSubs ?></div><div class="mgmt-stat__label">Assinaturas ativas</div></div>
    </div>
    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--teal">🧪</span>
        <div><div class="mgmt-stat__value"><?= $trialSubs ?></div><div class="mgmt-stat__label">Em trial</div></div>
    </div>
    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--red">🎫</span>
        <div><div class="mgmt-stat__value"><?= $openTickets ?></div><div class="mgmt-stat__label">Tickets abertos</div></div>
    </div>
    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--purple">📦</span>
        <div><div class="mgmt-stat__value"><?= $activeProducts ?></div><div class="mgmt-stat__label">Produtos ativos</div></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-6);margin-top:var(--space-6);">
    <div class="mgmt-info-card">
        <h3 class="mgmt-info-card__title">Últimos usuários</h3>
        <table class="mgmt-table"><thead><tr><th>Nome</th><th>E-mail</th><th>Cadastro</th></tr></thead><tbody>
            <?php foreach ($recentUsers as $u): ?>
            <tr>
                <td class="mgmt-table__name"><?= e($u['name']) ?></td>
                <td class="mgmt-table__sub"><?= e($u['email']) ?></td>
                <td><?= date('d/m H:i', strtotime($u['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody></table>
    </div>
    <div class="mgmt-info-card">
        <h3 class="mgmt-info-card__title">Últimas organizações</h3>
        <table class="mgmt-table"><thead><tr><th>Nome</th><th>Tipo</th><th>Cadastro</th></tr></thead><tbody>
            <?php foreach ($recentOrgs as $o): ?>
            <tr>
                <td class="mgmt-table__name"><?= e($o['name']) ?></td>
                <td><span class="badge badge--active"><?= e($o['type'] ?? '—') ?></span></td>
                <td><?= date('d/m H:i', strtotime($o['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody></table>
    </div>
</div>

<?php $__view->endSection(); ?>
