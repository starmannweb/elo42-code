<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<?php
    $packages = is_array($packages ?? null) ? $packages : [];
    $starterPrice = (string) ($packages[0]['price_label'] ?? 'R$ 34,90');
?>

<section class="hub-page">
    <header class="hub-page__header">
        <h1 class="hub-page__title">Créditos do Expositor IA</h1>
        <p class="hub-page__subtitle">Use 3 gerações gratuitas por mês e compre créditos quando precisar gerar sermões, estudos, séries, EBD e materiais ministeriais.</p>
    </header>

    <div class="credits-hero">
        <div>
            <p class="credits-hero__label">Saldo disponível</p>
            <div class="credits-hero__value"><?= e((string) ($iaCredits ?? 0)) ?></div>
            <p class="hub-panel__text">Cada geração consome 1 crédito. O plano gratuito libera 3 gerações mensais e os pacotes começam em <strong class="credits-hero__price"><?= e($starterPrice) ?></strong>.</p>
        </div>
        <div class="hub-page__actions">
            <a href="<?= url('/hub/expositor-ia') ?>" class="btn btn--gold btn--lg">Abrir Expositor IA</a>
            <a href="#comprar-creditos" class="btn btn--outline btn--lg">Comprar créditos</a>
        </div>
    </div>

    <div class="hub-panel" id="comprar-creditos">
        <div>
            <h2 class="hub-panel__title">Planos e créditos</h2>
            <p class="hub-panel__text">Comece gratuitamente e amplie o saldo conforme a frequência de uso da sua equipe ministerial.</p>
        </div>

        <div class="credit-package-grid">
            <article class="credit-package-card credit-package-card--free">
                <span class="credit-package-card__badge">Grátis</span>
                <h3 class="hub-mini-card__title">Plano Gratuito</h3>
                <div class="credit-package-card__credits">3 gerações/mês</div>
                <div class="credit-package-card__price">R$ 0,00</div>
                <p class="hub-mini-card__text">Acesso inicial para testar o fluxo de sermões e estudos sem cartão.</p>
                <p class="credit-package-card__meta">Renova automaticamente a cada mês.</p>
                <a href="<?= url('/hub/expositor-ia') ?>" class="btn btn--outline" style="width:100%;">Começar gratuitamente</a>
            </article>

            <?php foreach ($packages as $package): ?>
                <article class="credit-package-card">
                    <?php if (!empty($package['badge'])): ?>
                        <span class="credit-package-card__badge"><?= e((string) $package['badge']) ?></span>
                    <?php endif; ?>
                    <h3 class="hub-mini-card__title"><?= e((string) ($package['name'] ?? 'Pacote')) ?></h3>
                    <div class="credit-package-card__credits"><?= e((string) ($package['credits'] ?? 0)) ?> créditos</div>
                    <div class="credit-package-card__price"><?= e((string) ($package['price_label'] ?? 'R$ 0,00')) ?></div>
                    <p class="hub-mini-card__text"><?= e((string) ($package['description'] ?? '')) ?></p>
                    <p class="credit-package-card__meta">Ideal para planejamento, sermões, discipulado e estudos bíblicos.</p>

                    <form method="POST" action="<?= url('/hub/creditos/comprar') ?>" data-loading>
                        <?= csrf_field() ?>
                        <input type="hidden" name="package_id" value="<?= e((string) ($package['id'] ?? '')) ?>">
                        <button type="submit" class="btn btn--primary" style="width:100%;">Comprar agora</button>
                    </form>
                </article>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="hub-panel">
        <h2 class="hub-panel__title">Histórico de créditos</h2>
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
