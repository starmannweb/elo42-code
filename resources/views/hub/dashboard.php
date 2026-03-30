<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<?php
$metrics = is_array($churchMetrics ?? null) ? $churchMetrics : [];
$membersTotal = (int) ($metrics['members_total'] ?? 0);
$eventsActive = (int) ($metrics['events_active'] ?? 0);
$pendingRequests = (int) ($metrics['pending_requests'] ?? 0);
$revenueTotal = (float) ($metrics['revenue_total'] ?? 0.0);
$expensesTotal = (float) ($metrics['expenses_total'] ?? 0.0);
$balanceTotal = $revenueTotal - $expensesTotal;

$activityItems = is_array($dashboardActivity ?? null) ? $dashboardActivity : [];
$steps = is_array($setupSteps ?? null) ? $setupSteps : [];
$pendingSteps = array_values(array_filter($steps, static fn ($step) => empty($step['done'])));
$nextPendingStep = $pendingSteps[0] ?? null;
?>

<section class="church-dashboard">
    <header class="church-dashboard__header">
        <div>
            <h1 class="church-dashboard__title">Dashboard</h1>
            <p class="church-dashboard__subtitle">
                <?= !empty($organization['id'])
                    ? 'Visão geral da operação da ' . e((string) ($organization['name'] ?? 'organização')) . '.'
                    : 'Cadastre sua organização para liberar todos os módulos do sistema.' ?>
            </p>
        </div>
        <div class="church-dashboard__actions">
            <a href="<?= url('/hub/vitrine') ?>" class="btn btn--outline">Ver vitrine</a>
            <a href="<?= url('/hub/configuracoes') ?>" class="btn btn--primary">Configurações</a>
        </div>
    </header>

    <?php if (($organizationDeadline['is_required'] ?? false) && empty($organization['id'])): ?>
        <div class="alert <?= !empty($organizationDeadline['is_overdue']) ? 'alert--error' : 'alert--warning' ?>" role="alert">
            <?php if (!empty($organizationDeadline['is_overdue'])): ?>
                O prazo de 7 dias para cadastrar a organização foi atingido. Conclua o cadastro para continuar usando todos os módulos.
            <?php else: ?>
                Você tem <?= e((string) ($organizationDeadline['days_left'] ?? 0)) ?> dia(s) para cadastrar sua organização e liberar o Hub completo.
            <?php endif; ?>
            <a href="<?= url('/onboarding/organizacao') ?>" class="text-primary font-bold">Cadastrar organização</a>
        </div>
    <?php endif; ?>

    <section class="church-metrics-grid" aria-label="Indicadores principais">
        <article class="church-metric-card">
            <div class="church-metric-card__icon church-metric-card__icon--blue" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="church-metric-card__body">
                <p class="church-metric-card__label">Membros</p>
                <p class="church-metric-card__value"><?= e((string) $membersTotal) ?></p>
            </div>
        </article>

        <article class="church-metric-card">
            <div class="church-metric-card__icon church-metric-card__icon--indigo" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 11h18"></path></svg>
            </div>
            <div class="church-metric-card__body">
                <p class="church-metric-card__label">Eventos</p>
                <p class="church-metric-card__value"><?= e((string) $eventsActive) ?></p>
            </div>
        </article>

        <article class="church-metric-card">
            <div class="church-metric-card__icon church-metric-card__icon--gold" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4 7v6c0 4.5 3 7.4 8 8 5-0.6 8-3.5 8-8V7l-8-4z"></path><path d="m9 12 2 2 4-4"></path></svg>
            </div>
            <div class="church-metric-card__body">
                <p class="church-metric-card__label">Pedidos pendentes</p>
                <p class="church-metric-card__value"><?= e((string) $pendingRequests) ?></p>
            </div>
        </article>

        <article class="church-metric-card">
            <div class="church-metric-card__icon church-metric-card__icon--green" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 8A2.5 2.5 0 0 1 5 5.5h14A2.5 2.5 0 0 1 21.5 8v8A2.5 2.5 0 0 1 19 18.5H5A2.5 2.5 0 0 1 2.5 16z"></path><path d="M15 12h.01"></path><path d="M2.5 9.5h19"></path></svg>
            </div>
            <div class="church-metric-card__body">
                <p class="church-metric-card__label">Receita total</p>
                <p class="church-metric-card__value">R$ <?= e(number_format($revenueTotal, 2, ',', '.')) ?></p>
            </div>
        </article>
    </section>

    <section class="church-panels-grid">
        <article class="church-panel">
            <header class="church-panel__header">
                <h2 class="church-panel__title">Receitas vs despesas</h2>
                <span class="church-panel__hint">Resumo atual</span>
            </header>
            <div class="church-balance-grid">
                <div class="church-balance-item">
                    <span class="church-balance-item__label">Receitas</span>
                    <strong class="church-balance-item__value">R$ <?= e(number_format($revenueTotal, 2, ',', '.')) ?></strong>
                </div>
                <div class="church-balance-item">
                    <span class="church-balance-item__label">Despesas</span>
                    <strong class="church-balance-item__value">R$ <?= e(number_format($expensesTotal, 2, ',', '.')) ?></strong>
                </div>
                <div class="church-balance-item">
                    <span class="church-balance-item__label">Saldo</span>
                    <strong class="church-balance-item__value <?= $balanceTotal >= 0 ? 'is-positive' : 'is-negative' ?>">R$ <?= e(number_format($balanceTotal, 2, ',', '.')) ?></strong>
                </div>
            </div>
        </article>

        <article class="church-panel">
            <header class="church-panel__header">
                <h2 class="church-panel__title">Atividade recente</h2>
            </header>
            <?php if (!empty($activityItems)): ?>
                <ul class="church-activity-list">
                    <?php foreach ($activityItems as $item): ?>
                        <li class="church-activity-item">
                            <p class="church-activity-item__title"><?= e((string) ($item['title'] ?? 'Atualização')) ?></p>
                            <p class="church-activity-item__meta"><?= e((string) ($item['meta'] ?? '')) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="church-empty-state">Sem atividade no momento.</p>
            <?php endif; ?>
        </article>
    </section>

    <section class="church-bottom-grid">
        <article class="church-panel">
            <header class="church-panel__header">
                <h2 class="church-panel__title">Etapas pendentes</h2>
            </header>
            <?php if (!empty($nextPendingStep)): ?>
                <div class="church-pending-banner">
                    <div>
                        <p class="church-pending-banner__eyebrow">Acao recomendada agora</p>
                        <h3 class="church-pending-banner__title"><?= e((string) ($nextPendingStep['title'] ?? 'Concluir etapa pendente')) ?></h3>
                        <p class="church-pending-banner__text"><?= e((string) ($nextPendingStep['description'] ?? 'Finalize esta etapa para liberar melhor o ecossistema.')) ?></p>
                    </div>
                    <?php if (!empty($nextPendingStep['action'])): ?>
                        <a href="<?= e((string) $nextPendingStep['action']) ?>" class="btn btn--gold">
                            <?= e((string) ($nextPendingStep['action_text'] ?? 'Resolver agora')) ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($steps)): ?>
                <ol class="church-steps-list">
                    <?php foreach ($steps as $step): ?>
                        <li class="church-step-item <?= !empty($step['done']) ? 'is-done' : 'is-pending' ?>">
                            <span class="church-step-item__number"><?= e((string) ($step['number'] ?? '')) ?></span>
                            <div class="church-step-item__content">
                                <p class="church-step-item__title"><?= e((string) ($step['title'] ?? 'Etapa')) ?></p>
                                <p class="church-step-item__desc"><?= e((string) ($step['description'] ?? '')) ?></p>
                            </div>
                            <?php if (empty($step['done']) && !empty($step['action'])): ?>
                                <a href="<?= e((string) $step['action']) ?>" class="church-step-item__action">
                                    <?= e((string) ($step['action_text'] ?? 'Resolver')) ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php else: ?>
                <p class="church-empty-state">Nenhuma etapa pendente.</p>
            <?php endif; ?>
        </article>

        <article class="church-panel">
            <header class="church-panel__header">
                <h2 class="church-panel__title">Acessos rápidos</h2>
            </header>
            <div class="church-links-grid">
                <a href="<?= url('/hub/vitrine') ?>" class="church-link-card">Abrir vitrine</a>
                <a href="<?= url('/hub/sites') ?>" class="church-link-card">Meus sites</a>
                <a href="<?= url('/hub/expositor-ia') ?>" class="church-link-card">Expositor IA</a>
                <a href="<?= url('/hub/creditos') ?>" class="church-link-card">Créditos</a>
                <a href="<?= url('/hub/suporte') ?>" class="church-link-card">Suporte</a>
                <a href="<?= url('/hub/configuracoes') ?>" class="church-link-card">Configurações</a>
            </div>
        </article>
    </section>
</section>

<?php $__view->endSection(); ?>
