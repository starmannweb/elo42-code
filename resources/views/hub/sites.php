<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<section class="hub-page">
    <header class="hub-page__header">
        <h1 class="hub-page__title">Meus sites</h1>
        <p class="hub-page__subtitle">
            Crie e gerencie os sites da sua organização no construtor Elo 42.
        </p>
    </header>

    <div class="hub-panel">
        <div class="hub-panel__row">
            <div>
                <h2 class="hub-panel__title">Status de publicação</h2>
                <p class="hub-panel__text">
                    Plano atual: <strong><?= e((string) ($siteBuilderAccess['plan_name'] ?? 'Sem assinatura')) ?></strong><br>
                    Status: <strong><?= e((string) ($siteBuilderAccess['status_label'] ?? 'Inativo')) ?></strong><br>
                    Mensalidade: <strong><?= e((string) ($siteBuilderAccess['monthly_fee_label'] ?? 'Consulte valores')) ?></strong>
                </p>
            </div>
            <div class="hub-badge <?= !empty($siteBuilderAccess['can_publish']) ? 'hub-badge--success' : 'hub-badge--warning' ?>">
                <?= !empty($siteBuilderAccess['can_publish']) ? 'Publicação liberada' : 'Publicação bloqueada' ?>
            </div>
        </div>

        <?php if (empty($siteBuilderAccess['can_publish'])): ?>
            <div class="alert alert--warning" role="alert">
                <?= e((string) ($siteBuilderAccess['publish_requirement'] ?? 'Para publicar um site no construtor, é necessário ter uma mensalidade ativa.')) ?>
                <a href="<?= url('/contato') ?>" class="text-primary font-bold">Falar com especialista</a>
            </div>
        <?php endif; ?>

        <div class="hub-page__actions">
            <button class="btn btn--gold btn--lg" type="button" <?= empty($siteBuilderAccess['can_publish']) ? 'disabled aria-disabled="true"' : '' ?>>
                Iniciar criação de site
            </button>
            <a href="<?= url('/contato') ?>" class="btn btn--outline btn--lg">Ativar mensalidade</a>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
