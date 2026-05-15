<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <?php if (!empty($service['badge'])): ?>
                <span class="section__badge"><?= e($service['badge']) ?></span>
            <?php endif; ?>
            <h1 class="page-hero__title"><?= e($service['title']) ?></h1>
            <p class="page-hero__subtitle"><?= e($service['subtitle']) ?></p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="service-content">
            <div class="service-content__main">
                <p class="service-content__description"><?= e($service['description']) ?></p>

                <div class="service-features">
                    <h2 class="service-features__title">O que está incluso</h2>
                    <div class="service-features__grid">
                        <?php foreach ($service['features'] as $feature): ?>
                            <div class="service-feature-item">
                                <span class="service-feature-item__icon">✓</span>
                                <span><?= e($feature) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <aside class="service-content__sidebar">
                <div class="service-cta-card">
                    <h3 class="service-cta-card__title">Quer saber mais?</h3>
                    <p class="service-cta-card__text">Fale com nossa equipe e descubra como esse serviço pode ajudar sua organização.</p>
                    <a href="<?= url('/') ?>#serviços" class="btn btn--primary" style="width:100%;">Falar com especialista</a>
                    <a href="<?= url('/cadastro') ?>" class="btn btn--outline" style="width:100%; margin-top: var(--space-3);">Criar minha conta</a>
                </div>

                <div class="service-back">
                    <a href="<?= url('/') ?>#serviços" class="btn btn--ghost btn--sm">&larr; Voltar aos serviços</a>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
