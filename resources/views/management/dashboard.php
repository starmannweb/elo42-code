<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>

<?php
$income = (float) ($financial['income'] ?? 0);
$expense = (float) ($financial['expense'] ?? 0);
$balance = (float) ($financial['balance'] ?? 0);
?>

<section class="mgmt-dashboard">
    <header class="mgmt-header">
        <div>
            <h1 class="mgmt-header__title">Dashboard</h1>
            <p class="mgmt-header__subtitle">Acompanhe os principais indicadores da operação da sua igreja.</p>
        </div>
    </header>

    <section class="mgmt-kpi-grid" aria-label="Indicadores">
        <article class="mgmt-kpi-card">
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path></svg>
            </div>
            <div>
                <p class="mgmt-kpi-card__label">Membros</p>
                <p class="mgmt-kpi-card__value"><?= e((string) $totalMembers) ?></p>
            </div>
        </article>

        <article class="mgmt-kpi-card">
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--indigo" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg>
            </div>
            <div>
                <p class="mgmt-kpi-card__label">Eventos</p>
                <p class="mgmt-kpi-card__value"><?= e((string) $activeEvents) ?></p>
            </div>
        </article>

        <article class="mgmt-kpi-card">
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--gold" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 21h8"></path><path d="M12 17v4"></path><path d="M5 4h14"></path><path d="M17 4v5a5 5 0 0 1-10 0V4"></path></svg>
            </div>
            <div>
                <p class="mgmt-kpi-card__label">Pedidos pendentes</p>
                <p class="mgmt-kpi-card__value"><?= e((string) $openRequests) ?></p>
            </div>
        </article>

        <article class="mgmt-kpi-card">
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2.5 8A2.5 2.5 0 0 1 5 5.5h14A2.5 2.5 0 0 1 21.5 8v8A2.5 2.5 0 0 1 19 18.5H5A2.5 2.5 0 0 1 2.5 16z"></path><path d="M15 12h.01"></path><path d="M2.5 9.5h19"></path></svg>
            </div>
            <div>
                <p class="mgmt-kpi-card__label">Receita total</p>
                <p class="mgmt-kpi-card__value">R$ <?= e(number_format($income, 2, ',', '.')) ?></p>
            </div>
        </article>
    </section>

    <section class="mgmt-dashboard-grid">
        <article class="mgmt-dashboard-card">
            <header class="mgmt-dashboard-card__header">
                <h2>Crescimento de membros</h2>
            </header>
            <div class="mgmt-chart-placeholder">
                <div class="mgmt-chart-placeholder__line"></div>
                <div class="mgmt-chart-placeholder__axis">
                    <span>Out</span><span>Nov</span><span>Dez</span><span>Jan</span><span>Fev</span><span>Mar</span>
                </div>
            </div>
        </article>

        <article class="mgmt-dashboard-card">
            <header class="mgmt-dashboard-card__header">
                <h2>Receitas vs despesas</h2>
            </header>
            <div class="mgmt-balance-cards">
                <div>
                    <span>Receitas</span>
                    <strong>R$ <?= e(number_format($income, 2, ',', '.')) ?></strong>
                </div>
                <div>
                    <span>Despesas</span>
                    <strong>R$ <?= e(number_format($expense, 2, ',', '.')) ?></strong>
                </div>
                <div>
                    <span>Saldo</span>
                    <strong class="<?= $balance >= 0 ? 'is-positive' : 'is-negative' ?>">R$ <?= e(number_format($balance, 2, ',', '.')) ?></strong>
                </div>
            </div>
        </article>
    </section>

    <section class="mgmt-dashboard-grid">
        <article class="mgmt-dashboard-card">
            <header class="mgmt-dashboard-card__header">
                <h2>Progresso das campanhas</h2>
            </header>
            <div class="mgmt-progress-list">
                <div class="mgmt-progress-item">
                    <div class="mgmt-progress-item__head"><span>Reforma do templo</span><strong>58%</strong></div>
                    <div class="progress-bar"><div class="progress-bar__fill" style="width:58%"></div></div>
                </div>
                <div class="mgmt-progress-item">
                    <div class="mgmt-progress-item__head"><span>Missões 2026</span><strong>64%</strong></div>
                    <div class="progress-bar"><div class="progress-bar__fill progress-bar__fill--green" style="width:64%"></div></div>
                </div>
                <div class="mgmt-progress-item">
                    <div class="mgmt-progress-item__head"><span>Cesta solidária</span><strong>85%</strong></div>
                    <div class="progress-bar"><div class="progress-bar__fill progress-bar__fill--gold" style="width:85%"></div></div>
                </div>
            </div>
        </article>

        <article class="mgmt-dashboard-card">
            <header class="mgmt-dashboard-card__header">
                <h2>Atividade recente</h2>
            </header>
            <ul class="mgmt-activity-list">
                <li><strong><?= e((string) ($newMembers ?? 0)) ?> novo(s) membro(s)</strong><span>Atualizado no mês atual.</span></li>
                <li><strong><?= e((string) ($activeMinistries ?? 0)) ?> ministério(s) ativo(s)</strong><span>Acompanhamento pastoral em dia.</span></li>
                <li><strong><?= e((string) ($pendingTasks ?? 0)) ?> tarefa(s) pendente(s)</strong><span>Plano de ação com itens para conclusão.</span></li>
                <li><strong>Doações do mês: R$ <?= e(number_format((float) ($donationsMonth ?? 0), 2, ',', '.')) ?></strong><span>Captação em andamento.</span></li>
            </ul>
        </article>
    </section>
</section>

<?php $__view->endSection(); ?>
