<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Dashboard da Gestão</h1>
        <p class="mgmt-header__subtitle">Visão geral da sua organização</p>
    </div>
</div>

<div class="mgmt-stats-grid">
    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--blue">👥</span>
        <div>
            <div class="mgmt-stat__value"><?= $totalMembers ?></div>
            <div class="mgmt-stat__label">Total de membros</div>
        </div>
    </div>

    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--green">🆕</span>
        <div>
            <div class="mgmt-stat__value"><?= $newMembers ?></div>
            <div class="mgmt-stat__label">Novos este mês</div>
        </div>
    </div>

    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--purple">📅</span>
        <div>
            <div class="mgmt-stat__value"><?= $activeEvents ?></div>
            <div class="mgmt-stat__label">Eventos ativos</div>
        </div>
    </div>

    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--gold">⛪</span>
        <div>
            <div class="mgmt-stat__value"><?= $activeMinistries ?></div>
            <div class="mgmt-stat__label">Ministérios ativos</div>
        </div>
    </div>

    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--red">📝</span>
        <div>
            <div class="mgmt-stat__value"><?= $openRequests ?></div>
            <div class="mgmt-stat__label">Solicitações abertas</div>
        </div>
    </div>

    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--teal">🎯</span>
        <div>
            <div class="mgmt-stat__value"><?= $pendingTasks ?></div>
            <div class="mgmt-stat__label">Tarefas pendentes</div>
        </div>
    </div>
</div>

<div class="financial-summary">
    <div class="financial-summary__card">
        <div class="financial-summary__label">Entradas do mês</div>
        <div class="financial-summary__value financial-summary__value--income">
            R$ <?= number_format($financial['income'], 2, ',', '.') ?>
        </div>
    </div>
    <div class="financial-summary__card">
        <div class="financial-summary__label">Saídas do mês</div>
        <div class="financial-summary__value financial-summary__value--expense">
            R$ <?= number_format($financial['expense'], 2, ',', '.') ?>
        </div>
    </div>
    <div class="financial-summary__card">
        <div class="financial-summary__label">Saldo do mês</div>
        <div class="financial-summary__value financial-summary__value--balance">
            R$ <?= number_format($financial['balance'], 2, ',', '.') ?>
        </div>
    </div>
</div>

<div class="mgmt-stat" style="margin-bottom: var(--space-6);">
    <span class="mgmt-stat__icon mgmt-stat__icon--gold">🤝</span>
    <div>
        <div class="mgmt-stat__value">R$ <?= number_format($donationsMonth, 2, ',', '.') ?></div>
        <div class="mgmt-stat__label">Doações recebidas este mês</div>
    </div>
</div>

<?php $__view->endSection(); ?>
