<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<section class="hub-page">
    <header class="hub-page__header">
        <h1 class="hub-page__title">Meus sites</h1>
        <p class="hub-page__subtitle">Seu site é gerado integralmente com os dados do cadastro da sua organização. A publicação no seu domínio só é liberada com a mensalidade ativa.</p>
    </header>

    <div class="hub-panel">
        <div class="hub-panel__row">
            <div>
                <h2 class="hub-panel__title">Status de publicação</h2>
                <p class="hub-panel__text">
                    Plano atual: <strong><?= e((string) ($siteBuilderAccess['plan_name'] ?? 'Sem assinatura')) ?></strong><br>
                    Status: <strong><?= e((string) ($siteBuilderAccess['status_label'] ?? 'Pendente de organização')) ?></strong><br>
                    Mensalidade: <strong><?= e((string) ($siteBuilderAccess['monthly_fee_label'] ?? 'Consulte valores')) ?></strong>
                </p>
            </div>
            <div class="hub-badge <?= !empty($siteBuilderAccess['can_publish']) ? 'hub-badge--success' : 'hub-badge--warning' ?>">
                <?= !empty($siteBuilderAccess['can_publish']) ? 'Publicação liberada' : 'Publicação bloqueada' ?>
            </div>
        </div>

        <?php if (empty($siteBuilderAccess['can_publish'])): ?>
            <div class="alert alert--warning" role="alert">
                <?= e((string) ($siteBuilderAccess['publish_requirement'] ?? 'Para publicar em domínio real, ative uma mensalidade.')) ?>
                <a href="<?= url('/contato') ?>" class="text-primary font-bold">Ativar mensalidade</a>
            </div>
        <?php endif; ?>

        <div class="hub-page__actions" style="align-items:center;">
            <form action="<?= url('/hub/sites/gerar') ?>" method="POST" style="margin: 0;">
                <input type="hidden" name="csrf_token" value="<?= e((string) csrf_token()) ?>">
                <button class="btn btn--gold btn--lg" type="submit" onclick="this.classList.add('btn--loading');">
                    Gerar meu site
                </button>
            </form>
            <a href="<?= url('/contato') ?>" class="btn btn--outline btn--lg">Ativar mensalidade</a>
        </div>
    </div>

    <div class="hub-panel">
        <h2 class="hub-panel__title">Modelos disponíveis</h2>
        <div class="hub-cards-grid">
            <?php foreach (($siteTemplates ?? []) as $template): ?>
                <article class="hub-mini-card">
                    <h3 class="hub-mini-card__title"><?= e((string) ($template['name'] ?? 'Modelo')) ?></h3>
                    <p class="hub-mini-card__text"><?= e((string) ($template['description'] ?? '')) ?></p>
                    <div class="hub-page__actions" style="margin-top:auto;">
                        <button type="button" class="btn btn--ghost">Ver preview</button>
                        <form action="<?= url('/hub/sites/gerar') ?>" method="POST" style="margin: 0;">
                            <input type="hidden" name="csrf_token" value="<?= e((string) csrf_token()) ?>">
                            <input type="hidden" name="template" value="<?= e((string) ($template['name'] ?? '')) ?>">
                            <button type="submit" class="btn btn--primary" onclick="this.classList.add('btn--loading');">Utilizar este modelo</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
