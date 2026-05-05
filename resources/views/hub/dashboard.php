<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<?php
$hubProducts = is_array($showcaseItems ?? null) ? $showcaseItems : [];
$iaCreditsCount = (int) ($iaCredits ?? 0);
$ticketsOpen = (int) ($ticketsCount ?? 0);
$churchAccess = is_array($churchManagementAccess ?? null) ? $churchManagementAccess : [];
$hasOrganization = !empty($organization['id']);
$churchMetrics = is_array($churchMetrics ?? null) ? $churchMetrics : [];
$adminActivity = is_array($dashboardActivity ?? null) ? $dashboardActivity : [];
$churchBalance = (float) ($churchMetrics['revenue_total'] ?? 0) - (float) ($churchMetrics['expenses_total'] ?? 0);
$formatMoney = static fn (float $value): string => 'R$ ' . number_format($value, 2, ',', '.');

$churchStatusLabel = !empty($churchAccess['can_access'])
    ? 'Liberada'
    : (($organizationDeadline['is_overdue'] ?? false) ? 'Bloqueada' : 'Pendente');
$churchStatusMeta = !empty($churchAccess['is_trial']) && isset($churchAccess['days_left'])
    ? 'Teste: ' . (int) $churchAccess['days_left'] . ' dia(s)'
    : ($hasOrganization ? (string) ($organization['name'] ?? 'Organização ativa') : 'Cadastre a organização');

$siteStatusLabel = !empty($siteBuilderAccess['can_publish'])
    ? 'Publicando'
    : (!empty($siteBuilderAccess['can_access']) ? 'Configurar' : 'Pendente');
$sitePlanLabel = (string) ($siteBuilderAccess['plan_name'] ?? 'Sem assinatura');
$siteStatusMeta = (string) ($siteBuilderAccess['status_label'] ?? 'Sem assinatura ativa');

$supportStatusLabel = $ticketsOpen > 0 ? $ticketsOpen . ' aberto(s)' : 'Em dia';
$supportStatusMeta = $ticketsOpen > 0 ? 'Acompanhe a central de suporte' : 'Nenhum ticket pendente';

$activityItems = [];
if (!$hasOrganization) {
    $activityItems[] = ['title' => 'Cadastrar organização', 'meta' => 'Libera a gestão da igreja, vínculo de equipe e configuração dos produtos do Hub.'];
}
if (empty($siteBuilderAccess['can_publish'])) {
    $activityItems[] = ['title' => 'Publicação do site ainda não liberada', 'meta' => (string) ($siteBuilderAccess['publish_requirement'] ?? 'Ative a mensalidade para publicar em domínio real.')];
}
if ($iaCreditsCount <= 0) {
    $activityItems[] = ['title' => 'Créditos de IA zerados', 'meta' => 'Compre créditos para usar o Expositor IA em sermões, estudos e apoio pastoral.'];
}
if ($ticketsOpen > 0) {
    $activityItems[] = ['title' => 'Tickets aguardando acompanhamento', 'meta' => $ticketsOpen . ' solicitação(ões) aberta(s) na central de suporte.'];
}
if (empty($activityItems)) {
    $activityItems[] = ['title' => 'Hub operacional', 'meta' => 'Gestão, suporte e recursos digitais estão sem pendências críticas.'];
}
$priorityItems = array_slice(array_merge($adminActivity, $activityItems), 0, 6);

$steps = is_array($setupSteps ?? null) ? $setupSteps : [];
$pendingSteps = array_values(array_filter($steps, static fn ($step) => empty($step['done'])));
$nextPendingStep = $pendingSteps[0] ?? null;
?>

<section class="church-dashboard">
    <header class="church-dashboard__header">
        <div>
            <h1 class="church-dashboard__title">Dashboard</h1>
            <p class="church-dashboard__subtitle">
                <?= $hasOrganization
                    ? 'Central do administrador da igreja para acesso, assinatura, site, IA e suporte.'
                    : 'Cadastre sua organização para liberar os produtos e serviços do Hub.' ?>
            </p>
        </div>
        <div class="church-dashboard__actions">
            <a href="<?= url('/gestao') ?>" class="btn btn--outline">Abrir gestão</a>
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
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18M5 10h14"></path><path d="M7 21v-8h10v8"></path></svg>
            </div>
            <div class="church-metric-card__body">
                <p class="church-metric-card__label">Membros cadastrados</p>
                <p class="church-metric-card__value"><?= e((string) (int) ($churchMetrics['members_total'] ?? 0)) ?></p>
                <p class="church-metric-card__meta"><?= e($churchStatusMeta) ?></p>
            </div>
        </article>

        <article class="church-metric-card">
            <div class="church-metric-card__icon church-metric-card__icon--indigo" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M3 12h18M12 3a14.5 14.5 0 0 1 0 18M12 3a14.5 14.5 0 0 0 0 18"></path></svg>
            </div>
            <div class="church-metric-card__body">
                <p class="church-metric-card__label">Eventos ativos</p>
                <p class="church-metric-card__value"><?= e((string) (int) ($churchMetrics['events_active'] ?? 0)) ?></p>
                <p class="church-metric-card__meta">Agenda e comunicação</p>
            </div>
        </article>

        <article class="church-metric-card">
            <div class="church-metric-card__icon church-metric-card__icon--gold" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
            </div>
            <div class="church-metric-card__body">
                <p class="church-metric-card__label">Solicitações abertas</p>
                <p class="church-metric-card__value"><?= e((string) (int) ($churchMetrics['pending_requests'] ?? 0)) ?></p>
                <p class="church-metric-card__meta">Demandas da comunidade</p>
            </div>
        </article>

        <article class="church-metric-card">
            <div class="church-metric-card__icon church-metric-card__icon--green" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            </div>
            <div class="church-metric-card__body">
                <p class="church-metric-card__label">Saldo do período</p>
                <p class="church-metric-card__value is-text"><?= e($formatMoney($churchBalance)) ?></p>
                <p class="church-metric-card__meta">Receitas menos despesas</p>
            </div>
        </article>
    </section>

    <section class="church-panels-grid">
        <article class="church-panel">
            <header class="church-panel__header">
                <h2 class="church-panel__title">Leituras do administrador</h2>
                <span class="church-panel__hint">Operação da igreja e pendências do Hub</span>
            </header>
            <ul class="church-activity-list">
                <?php foreach ($priorityItems as $item): ?>
                    <li class="church-activity-item">
                        <p class="church-activity-item__title"><?= e((string) ($item['title'] ?? 'Atualização')) ?></p>
                        <p class="church-activity-item__meta"><?= e((string) ($item['meta'] ?? '')) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </article>

        <article class="church-panel">
            <header class="church-panel__header">
                <h2 class="church-panel__title">Serviços conectados</h2>
                <span class="church-panel__hint"><?= e($sitePlanLabel) ?></span>
            </header>
            <div class="church-links-grid">
                <a href="<?= url('/gestao') ?>" class="church-link-card">Gestão da igreja</a>
                <a href="<?= url('/hub/sites') ?>" class="church-link-card">Site institucional</a>
                <a href="<?= url('/hub/expositor-ia') ?>" class="church-link-card">Expositor IA</a>
                <a href="<?= url('/hub/suporte') ?>" class="church-link-card">Suporte</a>
                <a href="<?= url('/hub/creditos') ?>" class="church-link-card">Créditos</a>
                <a href="<?= url('/hub/vitrine') ?>" class="church-link-card">Loja do Hub</a>
            </div>
        </article>
    </section>

    <section class="church-bottom-grid">
        <article class="church-panel">
            <header class="church-panel__header">
                <h2 class="church-panel__title">Etapas de implantação</h2>
            </header>
            <?php if (!empty($nextPendingStep)): ?>
                <div class="church-pending-banner">
                    <div>
                        <p class="church-pending-banner__eyebrow">Ação recomendada agora</p>
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
                <a href="<?= url('/gestao') ?>" class="church-link-card">Painel de gestão</a>
                <a href="<?= url('/gestao/agenda') ?>" class="church-link-card">Agenda da igreja</a>
                <a href="<?= url('/gestao/relatorios') ?>" class="church-link-card">Relatórios</a>
                <a href="<?= url('/hub/sites') ?>" class="church-link-card">Meu site</a>
                <a href="<?= url('/hub/expositor-ia') ?>" class="church-link-card">Expositor IA</a>
                <a href="<?= url('/hub/configuracoes') ?>" class="church-link-card">Configurações</a>
            </div>
        </article>
    </section>

    <?php if (!empty($hubProducts)): ?>
        <article class="church-panel">
            <header class="church-panel__header">
                <h2 class="church-panel__title">Oportunidades do Hub</h2>
                <span class="church-panel__hint">Serviços úteis para igrejas e organizações</span>
            </header>
            <div class="church-links-grid church-links-grid--wide">
                <?php foreach (array_slice($hubProducts, 0, 4) as $item): ?>
                    <a href="<?= e((string) ($item['url'] ?? '#')) ?>" class="church-link-card">
                        <?= e((string) ($item['title'] ?? 'Produto Hub')) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </article>
    <?php endif; ?>
</section>

<?php $__view->endSection(); ?>
