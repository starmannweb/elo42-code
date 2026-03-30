<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<?php
$financial = is_array($financialSummary ?? null) ? $financialSummary : [];
$steps = is_array($setupSteps ?? null) ? $setupSteps : [];
$accessProfiles = is_array($accessProfiles ?? null) ? $accessProfiles : [];
$currentAccessMode = (string) ($currentAccessMode ?? 'client');
$pendingSteps = array_values(array_filter($steps, static fn ($step) => empty($step['done'])));
$nextPendingStep = $pendingSteps[0] ?? null;
?>

<section class="hub-page">
    <header class="hub-page__header">
        <h1 class="hub-page__title">Configurações</h1>
        <p class="hub-page__subtitle">Edite conta e organização, acompanhe o financeiro e ajuste níveis de acesso.</p>
    </header>

    <div class="hub-cards-grid">
        <article class="hub-mini-card">
            <h2 class="hub-mini-card__title">Conta</h2>
            <form method="POST" action="<?= url('/hub/configuracoes/conta') ?>" data-loading>
                <?= csrf_field() ?>
                <div class="form-group">
                    <label class="form-label" for="cfg-name">Nome</label>
                    <input id="cfg-name" name="name" class="form-input" type="text" value="<?= e((string) ($user['name'] ?? '')) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="cfg-email">E-mail</label>
                    <input id="cfg-email" class="form-input" type="email" value="<?= e((string) ($user['email'] ?? '')) ?>" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label" for="cfg-phone">Telefone</label>
                    <input id="cfg-phone" name="phone" class="form-input" type="text" value="<?= e((string) ($user['phone'] ?? '')) ?>" placeholder="(13) 97800-8047">
                </div>
                <button type="submit" class="btn btn--primary" style="width:100%;">Salvar conta</button>
            </form>
        </article>

        <article class="hub-mini-card">
            <h2 class="hub-mini-card__title">Organização</h2>
            <?php if (!empty($organization['id'])): ?>
                <form method="POST" action="<?= url('/hub/configuracoes/organizacao') ?>" data-loading>
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label class="form-label" for="cfg-org-name">Nome da organização</label>
                        <input id="cfg-org-name" name="org_name" class="form-input" type="text" value="<?= e((string) ($organization['name'] ?? '')) ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="cfg-org-type">Tipo</label>
                        <input id="cfg-org-type" name="org_type" class="form-input" type="text" value="<?= e((string) ($organization['type'] ?? '')) ?>" placeholder="Igreja, ONG, ministério...">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="cfg-org-phone">Telefone</label>
                        <input id="cfg-org-phone" name="org_phone" class="form-input" type="text" value="<?= e((string) ($organization['phone'] ?? '')) ?>" placeholder="(13) 97800-8047">
                    </div>
                    <button type="submit" class="btn btn--primary" style="width:100%;">Salvar organização</button>
                </form>
            <?php else: ?>
                <p class="hub-mini-card__text">Você ainda não cadastrou organização.</p>
                <a href="<?= url('/onboarding/organizacao') ?>" class="btn btn--gold">Cadastrar organização</a>
            <?php endif; ?>
        </article>

        <article class="hub-mini-card">
            <h2 class="hub-mini-card__title">Perfil de acesso</h2>
            <form method="POST" action="<?= url('/hub/configuracoes/perfil-acesso') ?>" data-loading>
                <?= csrf_field() ?>
                <?php foreach ($accessProfiles as $profile): ?>
                    <label class="access-option">
                        <input type="radio" name="access_mode" value="<?= e((string) ($profile['value'] ?? 'client')) ?>" <?= ($currentAccessMode === ($profile['value'] ?? '')) ? 'checked' : '' ?>>
                        <span>
                            <strong><?= e((string) ($profile['title'] ?? 'Perfil')) ?></strong>
                            <small><?= e((string) ($profile['description'] ?? '')) ?></small>
                        </span>
                    </label>
                <?php endforeach; ?>
                <button type="submit" class="btn btn--primary" style="width:100%; margin-top:12px;">Salvar perfil</button>
            </form>
        </article>
    </div>

    <div class="hub-panel">
        <h2 class="hub-panel__title">Financeiro e publicação</h2>
        <div class="hub-cards-grid">
            <article class="hub-mini-card">
                <h3 class="hub-mini-card__title">Assinatura de sites</h3>
                <p class="hub-mini-card__text">
                    Status: <strong><?= e((string) ($financial['subscription_status'] ?? 'Sem assinatura')) ?></strong><br>
                    Valor: <strong><?= e((string) ($financial['subscription_value'] ?? 'Consulte valores')) ?></strong><br>
                    Ciclo: <strong><?= e((string) ($financial['billing_cycle'] ?? 'Mensal')) ?></strong><br>
                    Publicação: <strong><?= e((string) ($financial['publish_status'] ?? 'Bloqueado sem mensalidade')) ?></strong>
                </p>
                <a href="<?= url('/contato') ?>" class="btn btn--outline">Ativar mensalidade</a>
            </article>

            <article class="hub-mini-card">
                <h3 class="hub-mini-card__title">Expositor IA</h3>
                <p class="hub-mini-card__text">
                    Saldo: <strong><?= e((string) ($iaCredits ?? 0)) ?> crédito(s)</strong><br>
                    Consumo: <strong>1 crédito por geração</strong>
                </p>
                <a href="<?= url('/hub/creditos') ?>" class="btn btn--gold">Comprar créditos</a>
            </article>
        </div>
    </div>

    <div class="hub-panel">
        <h2 class="hub-panel__title">Etapas de configuração pendentes</h2>
        <?php if (!empty($nextPendingStep)): ?>
            <div class="church-pending-banner">
                <div>
                    <p class="church-pending-banner__eyebrow">Pendencia principal</p>
                    <h3 class="church-pending-banner__title"><?= e((string) ($nextPendingStep['title'] ?? 'Finalizar configuracao')) ?></h3>
                    <p class="church-pending-banner__text"><?= e((string) ($nextPendingStep['description'] ?? 'Conclua esta etapa para liberar todos os recursos da conta.')) ?></p>
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
                            <a href="<?= e((string) $step['action']) ?>" class="church-step-item__action"><?= e((string) ($step['action_text'] ?? 'Resolver')) ?></a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <p class="hub-mini-card__text">Nenhuma etapa pendente.</p>
        <?php endif; ?>
    </div>
</section>

<?php $__view->endSection(); ?>
