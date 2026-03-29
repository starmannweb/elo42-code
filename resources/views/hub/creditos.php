<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<section class="hub-page">
    <header class="hub-page__header">
        <h1 class="hub-page__title">Créditos do Expositor IA</h1>
        <p class="hub-page__subtitle">Gerencie seu saldo de créditos e compre novos pacotes para continuar gerando conteúdos.</p>
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
                    <p class="hub-mini-card__price"><?= e((string) ($package['price_label'] ?? 'R$ 0,00')) ?></p>
                    <p class="hub-mini-card__text"><?= e((string) ($package['description'] ?? '')) ?></p>

                    <form method="POST" action="<?= url('/hub/creditos/comprar') ?>" data-loading>
                        <?= csrf_field() ?>
                        <input type="hidden" name="package_id" value="<?= e((string) ($package['id'] ?? '')) ?>">
                        <button type="submit" class="btn btn--gold" style="width:100%;">Solicitar compra</button>
                    </form>
                </article>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="hub-panel">
        <h2 class="hub-panel__title">Histórico</h2>
        <?php if (!empty($history)): ?>
            <div class="table-responsive">
                <table class="table table--hub">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Tipo</th>
                            <th>Qtd</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (($history ?? []) as $item): ?>
                            <tr>
                                <td><?= e((string) ($item['date'] ?? '')) ?></td>
                                <td><?= e((string) ($item['description'] ?? '')) ?></td>
                                <td><?= e((string) ($item['type'] ?? '')) ?></td>
                                <td><?= e((string) ($item['qty'] ?? '0')) ?></td>
                                <td><?= e((string) ($item['price'] ?? '-')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="hub-mini-card__text">Nenhuma movimentação registrada até o momento.</p>
        <?php endif; ?>
    </div>
</section>

<?php $__view->endSection(); ?>
