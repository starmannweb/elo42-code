<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<section class="hub-page">
    <header class="hub-page__header">
        <h1 class="hub-page__title">Catálogo</h1>
        <p class="hub-page__subtitle">
            Soluções do ecossistema Elo 42 para gestão, implantação, benefícios, tráfego e operação contínua.
        </p>
    </header>

    <div class="dashboard-section__header">
        <h2 class="dashboard-section__title" style="margin-bottom:0;">Serviços</h2>
    </div>

    <div class="showcase-grid">
        <?php foreach (($showcaseItems ?? []) as $item): ?>
            <?php $isDisabled = !empty($item['is_disabled']); ?>
            <article class="showcase-card <?= $isDisabled ? 'is-disabled' : '' ?>">
                <div class="showcase-card__head">
                    <span class="showcase-card__icon showcase-card__icon--<?= e((string) ($item['icon'] ?? 'briefcase')) ?>" aria-hidden="true">
                        <?php if (($item['icon'] ?? '') === 'book'): ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                        <?php elseif (($item['icon'] ?? '') === 'monitor'): ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="14" rx="2"></rect><path d="M8 22h8M12 18v4"></path></svg>
                        <?php elseif (($item['icon'] ?? '') === 'gift'): ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="13" rx="2"></rect><path d="M12 8v13M3 12h18"></path><path d="M12 8c-1.8 0-3-1-3-2.4A2.4 2.4 0 0 1 11.4 3c1.3 0 2.2 1 2.6 2 .4-1 1.3-2 2.6-2A2.4 2.4 0 0 1 19 5.6C19 7 17.8 8 16 8z"></path></svg>
                        <?php elseif (($item['icon'] ?? '') === 'calendar'): ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg>
                        <?php elseif (($item['icon'] ?? '') === 'globe'): ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M3 12h18M12 3a14.5 14.5 0 0 1 0 18M12 3a14.5 14.5 0 0 0 0 18"></path></svg>
                        <?php elseif (($item['icon'] ?? '') === 'diagnostic'): ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V9M10 19V5M16 19v-7M22 19V3"></path></svg>
                        <?php elseif (($item['icon'] ?? '') === 'megaphone'): ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l18-5v12L3 13v-2z"></path><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"></path></svg>
                        <?php elseif (($item['icon'] ?? '') === 'hand'): ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M8 13V4a1.5 1.5 0 0 1 3 0v6"></path><path d="M11 10V3.5a1.5 1.5 0 0 1 3 0V10"></path><path d="M14 10V5a1.5 1.5 0 0 1 3 0v7"></path><path d="M5 14.5c0-1 .8-1.8 1.8-1.8H8v.3a8 8 0 0 0 8 8h.4a3.6 3.6 0 0 0 3.6-3.6V13"></path></svg>
                        <?php else: ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"></path></svg>
                        <?php endif; ?>
                    </span>

                    <?php if (!empty($item['badge'])): ?>
                        <span class="showcase-card__badge showcase-card__badge--<?= e((string) (($item['badge_type'] ?? '') ?: 'new')) ?>">
                            <?= e((string) $item['badge']) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <h3 class="showcase-card__title"><?= e((string) ($item['title'] ?? '')) ?></h3>
                <p class="showcase-card__description"><?= e((string) ($item['description'] ?? '')) ?></p>

                <div class="showcase-card__footer">
                    <strong class="showcase-card__price"><?= e((string) ($item['price'] ?? '')) ?></strong>
                    <?php if ($isDisabled): ?>
                        <span class="showcase-card__button is-disabled" aria-disabled="true"><?= e((string) ($item['cta'] ?? 'Desativado')) ?></span>
                    <?php else: ?>
                        <?php $itemUrl = (string) ($item['url'] ?? url('/contato')); $isExternal = str_starts_with($itemUrl, 'http'); ?>
                        <a href="<?= e($itemUrl) ?>" class="showcase-card__button"<?= $isExternal ? ' target="_blank" rel="noopener"' : '' ?>><?= e((string) ($item['cta'] ?? 'Ver detalhes')) ?></a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <section class="hub-panel hub-panel--platform-access" id="acessar-plataformas">
        <header class="hub-panel__row" style="align-items:flex-start; gap: var(--space-3);">
            <div>
                <h2 class="hub-panel__title">Como acessar as plataformas</h2>
                <p class="hub-panel__text">Acesse os módulos direto pelo Hub para operar no dia a dia e acelerar a implantação.</p>
            </div>
        </header>

        <div class="platform-access-grid">
            <?php foreach (($platformAccessItems ?? []) as $access): ?>
                <article class="platform-access-card <?= !empty($access['highlight']) ? 'is-highlight' : '' ?>">
                    <h3 class="platform-access-card__title"><?= e((string) ($access['title'] ?? 'Plataforma')) ?></h3>
                    <p class="platform-access-card__description"><?= e((string) ($access['description'] ?? 'Acesse pelo Hub.')) ?></p>
                    <a href="<?= e((string) ($access['url'] ?? url('/hub'))) ?>" class="btn <?= !empty($access['highlight']) ? 'btn--gold' : 'btn--outline' ?>">
                        <?= e((string) ($access['cta'] ?? 'Acessar')) ?>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="hub-panel hub-panel--contract-packages" id="pacotes-contratacao">
        <header class="hub-panel__row" style="align-items:flex-start; gap: var(--space-3);">
            <div>
                <h2 class="hub-panel__title">Pacotes de contratação no Hub</h2>
                <p class="hub-panel__text">Escolha o pacote ideal e solicite contratação sem sair da plataforma.</p>
            </div>
        </header>

        <div class="contract-packages-grid">
            <?php foreach (($contractPackages ?? []) as $package): ?>
                <article class="contract-package-card">
                    <p class="contract-package-card__product"><?= e((string) ($package['product'] ?? 'Produto')) ?></p>
                    <h3 class="contract-package-card__title"><?= e((string) ($package['package'] ?? 'Pacote')) ?></h3>
                    <p class="contract-package-card__price"><?= e((string) ($package['price'] ?? 'Consulte')) ?></p>
                    <p class="contract-package-card__description"><?= e((string) ($package['description'] ?? 'Solicite uma proposta personalizada.')) ?></p>
                    <?php $packageUrl = (string) ($package['url'] ?? url('/contato')); $packageExternal = str_starts_with($packageUrl, 'http'); ?>
                    <a href="<?= e($packageUrl) ?>" class="btn btn--outline"<?= $packageExternal ? ' target="_blank" rel="noopener"' : '' ?>>
                        <?= e((string) ($package['cta'] ?? 'Solicitar')) ?>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</section>

<?php $__view->endSection(); ?>
