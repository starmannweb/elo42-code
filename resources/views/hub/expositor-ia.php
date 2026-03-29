<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<?php $form = is_array($expositorForm ?? null) ? $expositorForm : []; ?>

<section class="hub-page">
    <header class="hub-page__header">
        <h1 class="hub-page__title">Expositor IA (Hokmah)</h1>
        <p class="hub-page__subtitle">Gere sermões e estudos bíblicos com profundidade. Cada geração consome <?= e((string) ($iaCreditCost ?? 1)) ?> crédito.</p>
    </header>

    <div class="hub-panel">
        <div class="hub-panel__row">
            <div>
                <h2 class="hub-panel__title">Saldo de créditos</h2>
                <p class="hub-panel__text">Você possui <strong><?= e((string) ($iaCredits ?? 0)) ?> crédito(s)</strong> disponível(is).</p>
            </div>
            <div class="hub-badge <?= !empty($canGenerateIa) ? 'hub-badge--success' : 'hub-badge--warning' ?>">
                <?= !empty($canGenerateIa) ? 'Geração liberada' : 'Sem créditos suficientes' ?>
            </div>
        </div>

        <?php if (empty($canGenerateIa)): ?>
            <div class="alert alert--warning" role="alert">
                O Expositor IA não é ilimitado. Compre créditos para continuar gerando conteúdo.
                <a href="<?= url('/hub/creditos') ?>" class="text-primary font-bold">Comprar créditos</a>
            </div>
        <?php endif; ?>

        <div class="expositor-layout">
            <form method="POST" action="<?= url('/hub/expositor-ia/gerar') ?>" class="hub-mini-card" data-loading>
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label" for="ia-passage">Passagem bíblica</label>
                    <input id="ia-passage" type="text" name="passage" class="form-input" value="<?= e((string) ($form['passage'] ?? '')) ?>" placeholder="Ex.: Efésios 2:1-10" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="ia-theme">Tema / Ênfase</label>
                    <input id="ia-theme" type="text" name="theme" class="form-input" value="<?= e((string) ($form['theme'] ?? '')) ?>" placeholder="Ex.: Salvos pela graça">
                </div>

                <div class="form-group">
                    <label class="form-label" for="ia-confessional">Camada confessional</label>
                    <select id="ia-confessional" name="confessional" class="form-select">
                        <?php foreach (($confessionalOptions ?? []) as $option): ?>
                            <option value="<?= e((string) ($option['value'] ?? '')) ?>" <?= (($form['confessional'] ?? '') === ($option['value'] ?? '')) ? 'selected' : '' ?>>
                                <?= e((string) ($option['label'] ?? '')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="ia-depth">Nível de profundidade</label>
                    <select id="ia-depth" name="depth" class="form-select">
                        <?php foreach (($depthOptions ?? []) as $option): ?>
                            <option value="<?= e((string) ($option['value'] ?? '')) ?>" <?= (($form['depth'] ?? '') === ($option['value'] ?? '')) ? 'selected' : '' ?>>
                                <?= e((string) ($option['label'] ?? '')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="hub-panel__row" style="align-items:center;">
                    <p class="hub-panel__text" style="margin:0;">
                        <strong>Custo:</strong> <?= e((string) ($iaCreditCost ?? 1)) ?> crédito por geração.
                    </p>
                    <button type="submit" class="btn btn--gold" <?= empty($canGenerateIa) ? 'disabled aria-disabled="true"' : '' ?>>
                        Gerar esboço
                    </button>
                </div>
            </form>

            <article class="hub-mini-card">
                <h2 class="hub-mini-card__title">Resultado</h2>
                <?php if (!empty($expositorLastResult)): ?>
                    <pre class="expositor-result"><?= e((string) $expositorLastResult) ?></pre>
                <?php else: ?>
                    <p class="hub-mini-card__text">Preencha os dados ao lado e clique em <strong>Gerar esboço</strong>.</p>
                <?php endif; ?>
                <div class="hub-page__actions" style="margin-top:auto;">
                    <a href="<?= url('/hub/creditos') ?>" class="btn btn--outline">Comprar créditos</a>
                    <a href="https://wa.me/5513978008047" target="_blank" rel="noopener noreferrer" class="btn btn--ghost">Suporte no WhatsApp</a>
                </div>
            </article>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
