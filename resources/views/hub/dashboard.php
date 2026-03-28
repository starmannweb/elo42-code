<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<div class="dashboard-greeting">
    <h1 class="dashboard-greeting__title">
        <?= e((string) $greeting) ?>, <?= e((string) $firstName) ?>.
    </h1>
    <p class="dashboard-greeting__subtitle">
        <?php if (!empty($organization['id'])): ?>
            Acompanhe a operação da <?= e((string) ($organization['name'] ?? 'organização')) ?> e acesse todos os módulos do ecossistema.
        <?php else: ?>
            Bem-vindo ao Hub Elo 42. Cadastre sua organização para liberar todos os recursos.
        <?php endif; ?>
    </p>
</div>

<?php if (($organizationDeadline['is_required'] ?? false) && empty($organization['id'])): ?>
    <?php if (!empty($organizationDeadline['is_overdue'])): ?>
        <div class="alert alert--error" role="alert">
            O prazo de 7 dias para cadastrar a organização foi atingido. Para continuar, conclua o cadastro da organização.
            <a href="<?= url('/onboarding/organizacao') ?>" class="text-primary font-bold">Cadastrar organização</a>
        </div>
    <?php elseif (($organizationDeadline['days_left'] ?? null) !== null): ?>
        <div class="alert alert--warning" role="alert">
            Você tem <?= e((string) $organizationDeadline['days_left']) ?> dia(s) para cadastrar sua organização e liberar o Hub completo.
            <a href="<?= url('/onboarding/organizacao') ?>" class="text-primary font-bold">Concluir agora</a>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="dashboard-status-grid">
    <article class="status-card status-card--blue">
        <div class="status-card__header">
            <span class="status-card__icon status-card__icon--solid" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-4.4 0-8 2-8 4.5V21h16v-2.5C20 16 16.4 14 12 14Z"/></svg>
            </span>
            <span class="status-card__badge status-card__badge--active">Ativa</span>
        </div>
        <div class="status-card__value"><?= e((string) ($user['name'] ?? 'Conta')) ?></div>
        <div class="status-card__label">Sua conta</div>
    </article>

    <article class="status-card status-card--gold">
        <div class="status-card__header">
            <span class="status-card__icon status-card__icon--solid" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4 5a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2Z"/></svg>
            </span>
            <?php if (!empty($organization['id'])): ?>
                <span class="status-card__badge status-card__badge--active">Ativa</span>
            <?php else: ?>
                <span class="status-card__badge status-card__badge--pending">Pendente</span>
            <?php endif; ?>
        </div>
        <div class="status-card__value"><?= e((string) ($organization['name'] ?? 'Sem organização')) ?></div>
        <div class="status-card__label">
            <?php if (empty($organization['id'])): ?>
                <a href="<?= url('/onboarding/organizacao') ?>" class="text-primary font-semibold">Cadastrar organização</a>
            <?php else: ?>
                Organização ativa
            <?php endif; ?>
        </div>
    </article>

    <article class="status-card status-card--green">
        <div class="status-card__header">
            <span class="status-card__icon status-card__icon--solid" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 11V8a3 3 0 1 1 6 0v3h1a2 2 0 0 1 2 2v7H6v-7a2 2 0 0 1 2-2Zm2 0h2V8a1 1 0 1 0-2 0Z"/></svg>
            </span>
        </div>
        <div class="status-card__value"><?= e((string) ($organization['role_name'] ?? 'Perfil padrão')) ?></div>
        <div class="status-card__label">Perfil de acesso</div>
    </article>

    <article class="status-card status-card--purple">
        <div class="status-card__header">
            <span class="status-card__icon status-card__icon--solid" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h16v3H4zm0 5h10v3H4zm0 5h16v3H4z"/></svg>
            </span>
        </div>
        <div class="status-card__value"><?= e((string) ($iaCredits ?? 0)) ?></div>
        <div class="status-card__label">Créditos para Expositor IA</div>
    </article>
</div>

<section class="dashboard-section" style="margin-bottom: var(--space-8);">
    <h2 class="dashboard-section__title">Ações rápidas</h2>
    <div class="quick-actions-grid">
        <a href="<?= url('/hub/sites') ?>" class="quick-action">
            <span class="quick-action__icon quick-action__icon--blue" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 2c.9 1 1.7 2.3 2.2 4H9.8C10.3 6.3 11.1 5 12 4Zm-5.9 6h2.1a17.6 17.6 0 0 0 0 4H6.1A8 8 0 0 1 6.1 10Zm.8 6h2.3A10.7 10.7 0 0 0 10.7 20a8 8 0 0 1-3.8-4Zm4.8 0h.6a15.5 15.5 0 0 1-.3 2.5A15.5 15.5 0 0 1 11.7 16Zm2.1 0h3.3A8 8 0 0 1 13.3 20a10.7 10.7 0 0 0 .5-4Zm4.1-2h-2.1a17.6 17.6 0 0 0 0-4h2.1a8 8 0 0 1 0 4Zm-4.2 0h-3.4a15.5 15.5 0 0 1 0-4h3.4a15.5 15.5 0 0 1 0 4Zm-.4-6A10.7 10.7 0 0 0 13.3 4a8 8 0 0 1 3.8 4Z"/></svg>
            </span>
            <div>
                <div class="quick-action__text">Meus sites</div>
                <div class="quick-action__desc">Construtor e publicação</div>
            </div>
        </a>

        <a href="<?= url('/hub/expositor-ia') ?>" class="quick-action">
            <span class="quick-action__icon quick-action__icon--gold" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M5 3h10a3 3 0 0 1 3 3v15l-5-3-5 3V6a3 3 0 0 1 3-3Zm0 2a1 1 0 0 0-1 1v11.4l3-1.8 3 1.8V6a1 1 0 0 0-1-1Z"/></svg>
            </span>
            <div>
                <div class="quick-action__text">Expositor IA</div>
                <div class="quick-action__desc">Custo de <?= e((string) ($iaCreditCost ?? 1)) ?> crédito por geração</div>
            </div>
        </a>

        <a href="<?= url('/hub/creditos') ?>" class="quick-action">
            <span class="quick-action__icon quick-action__icon--green" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6h18v12H3Zm2 2v8h14V8Zm5 2h4v4h-4Z"/></svg>
            </span>
            <div>
                <div class="quick-action__text">Comprar créditos</div>
                <div class="quick-action__desc">Pacotes para o Expositor IA</div>
            </div>
        </a>

        <a href="<?= url('/hub/suporte') ?>" class="quick-action">
            <span class="quick-action__icon quick-action__icon--purple" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a8 8 0 0 0-8 8v4a3 3 0 0 0 3 3h2v-7H6v-1a6 6 0 1 1 12 0v1h-3v7h2a3 3 0 0 0 3-3v-4a8 8 0 0 0-8-8Z"/></svg>
            </span>
            <div>
                <div class="quick-action__text">Suporte</div>
                <div class="quick-action__desc">Falar com especialista</div>
            </div>
        </a>
    </div>
</section>

<section class="dashboard-section dashboard-section--showcase" id="vitrine">
    <div class="dashboard-section__header">
        <h2 class="dashboard-section__title">Vitrine de soluções</h2>
        <a href="<?= url('/contato') ?>" class="dashboard-section__link">Falar com especialista</a>
    </div>

    <div class="showcase-grid">
        <?php foreach (($showcaseItems ?? []) as $item): ?>
            <article class="showcase-card">
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
                    <a href="<?= e((string) ($item['url'] ?? url('/contato'))) ?>" class="showcase-card__button"><?= e((string) ($item['cta'] ?? 'Ver detalhes')) ?></a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php $__view->endSection(); ?>
