<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<section class="hub-page">
    <header class="hub-page__header">
        <h1 class="hub-page__title">Créditos do Expositor IA</h1>
        <p class="hub-page__subtitle">
            O Expositor IA funciona por consumo de créditos. Escolha um pacote e continue gerando conteúdos.
        </p>
    </header>

    <div class="hub-panel">
        <div class="hub-panel__row">
            <div>
                <h2 class="hub-panel__title">Saldo atual</h2>
                <p class="hub-panel__text">Você possui <strong><?= e((string) ($iaCredits ?? 0)) ?> crédito(s)</strong>.</p>
            </div>
            <a href="<?= url('/hub/expositor-ia') ?>" class="btn btn--outline">Ir para o Expositor IA</a>
        </div>

        <div class="hub-cards-grid">
            <?php foreach (($packages ?? []) as $package): ?>
                <article class="hub-mini-card">
                    <?php if (!empty($package['badge'])): ?>
                        <span class="hub-mini-card__badge"><?= e((string) $package['badge']) ?></span>
                    <?php endif; ?>
                    <h3 class="hub-mini-card__title"><?= e((string) ($package['name'] ?? 'Pacote')) ?></h3>
                    <p class="hub-mini-card__value"><?= e((string) ($package['credits'] ?? 0)) ?> créditos</p>
                    <p class="hub-mini-card__price"><?= e((string) ($package['price'] ?? '')) ?></p>
                    <a href="<?= url('/contato') ?>" class="btn btn--gold">Solicitar compra</a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
