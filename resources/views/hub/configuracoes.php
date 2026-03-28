<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<section class="hub-page">
    <header class="hub-page__header">
        <h1 class="hub-page__title">Configurações</h1>
        <p class="hub-page__subtitle">
            Ajuste dados da conta, visualize assinatura e acompanhe o consumo de créditos.
        </p>
    </header>

    <div class="hub-panel">
        <div class="hub-cards-grid">
            <article class="hub-mini-card">
                <h3 class="hub-mini-card__title">Conta</h3>
                <p class="hub-mini-card__text">
                    Nome: <strong><?= e((string) ($user['name'] ?? '')) ?></strong><br>
                    E-mail: <strong><?= e((string) ($user['email'] ?? '')) ?></strong>
                </p>
            </article>

            <article class="hub-mini-card">
                <h3 class="hub-mini-card__title">Assinatura para sites</h3>
                <p class="hub-mini-card__text">
                    Plano: <strong><?= e((string) ($siteBuilderAccess['plan_name'] ?? 'Sem assinatura')) ?></strong><br>
                    Status: <strong><?= e((string) ($siteBuilderAccess['status_label'] ?? 'Inativo')) ?></strong><br>
                    Mensalidade: <strong><?= e((string) ($siteBuilderAccess['monthly_fee_label'] ?? 'Consulte valores')) ?></strong>
                </p>
                <?php if (empty($siteBuilderAccess['can_publish'])): ?>
                    <a href="<?= url('/contato') ?>" class="btn btn--outline">Ativar mensalidade</a>
                <?php endif; ?>
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
</section>

<?php $__view->endSection(); ?>
