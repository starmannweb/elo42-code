<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<section class="hub-page">
    <header class="hub-page__header">
        <h1 class="hub-page__title">Expositor IA (Hokmah)</h1>
        <p class="hub-page__subtitle">
            Gere esboços e estudos bíblicos com custo de <?= e((string) ($iaCreditCost ?? 1)) ?> crédito por geração.
        </p>
    </header>

    <div class="hub-panel">
        <div class="hub-panel__row">
            <div>
                <h2 class="hub-panel__title">Saldo de créditos</h2>
                <p class="hub-panel__text">
                    Você possui <strong><?= e((string) ($iaCredits ?? 0)) ?> crédito(s)</strong> disponível(is) no momento.
                </p>
            </div>
            <div class="hub-badge <?= !empty($canGenerateIa) ? 'hub-badge--success' : 'hub-badge--warning' ?>">
                <?= !empty($canGenerateIa) ? 'Geração liberada' : 'Sem créditos suficientes' ?>
            </div>
        </div>

        <div class="hub-form-grid">
            <div class="form-group">
                <label class="form-label" for="ia_passage">Passagem bíblica</label>
                <input id="ia_passage" class="form-input" type="text" placeholder="Ex.: Efésios 2:1-10" <?= empty($canGenerateIa) ? 'disabled' : '' ?>>
            </div>
            <div class="form-group">
                <label class="form-label" for="ia_theme">Tema / ênfase</label>
                <input id="ia_theme" class="form-input" type="text" placeholder="Ex.: Salvos pela graça" <?= empty($canGenerateIa) ? 'disabled' : '' ?>>
            </div>
        </div>

        <?php if (empty($canGenerateIa)): ?>
            <div class="alert alert--warning" role="alert">
                O Expositor IA não é ilimitado. Para gerar novos conteúdos, compre mais créditos.
                <a href="<?= url('/hub/creditos') ?>" class="text-primary font-bold">Comprar créditos</a>
            </div>
        <?php endif; ?>

        <div class="hub-page__actions">
            <button class="btn btn--gold btn--lg" type="button" <?= empty($canGenerateIa) ? 'disabled aria-disabled="true"' : '' ?>>
                Gerar esboço
            </button>
            <a href="<?= url('/hub/creditos') ?>" class="btn btn--outline btn--lg">Comprar créditos</a>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
