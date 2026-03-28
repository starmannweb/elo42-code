<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<section class="hub-page">
    <header class="hub-page__header">
        <h1 class="hub-page__title">Suporte</h1>
        <p class="hub-page__subtitle">
            Fale com nossa equipe para atendimento técnico, implantação e orientações operacionais.
        </p>
    </header>

    <div class="hub-panel">
        <div class="hub-cards-grid">
            <article class="hub-mini-card">
                <h3 class="hub-mini-card__title">WhatsApp</h3>
                <p class="hub-mini-card__value"><?= e((string) ($supportWhatsapp ?? '(13) 97800-8047')) ?></p>
                <a href="<?= e((string) ($supportWhatsappUrl ?? 'https://wa.me/5513978008047')) ?>" target="_blank" rel="noopener noreferrer" class="btn btn--gold">
                    Conversar no WhatsApp
                </a>
            </article>

            <article class="hub-mini-card">
                <h3 class="hub-mini-card__title">E-mail</h3>
                <p class="hub-mini-card__value"><?= e((string) ($supportEmail ?? 'suporte@elo42.com.br')) ?></p>
                <a href="mailto:<?= e((string) ($supportEmail ?? 'suporte@elo42.com.br')) ?>" class="btn btn--outline">
                    Enviar e-mail
                </a>
            </article>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
