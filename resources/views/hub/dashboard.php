<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<!-- Greeting -->
<div class="dashboard-greeting">
    <h1 class="dashboard-greeting__title">
        <?= e($greeting) ?>, <?= e($firstName) ?>! 👋
    </h1>
    <p class="dashboard-greeting__subtitle">
        <?php if ($organization): ?>
            Acompanhe tudo sobre a <?= e($organization['name']) ?> no painel abaixo.
        <?php else: ?>
            Bem-vindo ao Elo 42. Configure sua organização para começar.
        <?php endif; ?>
    </p>
</div>

<!-- Status Cards -->
<div class="dashboard-status-grid">
    <div class="status-card status-card--blue">
        <div class="status-card__header">
            <span class="status-card__icon">👤</span>
            <span class="status-card__badge status-card__badge--active">Ativa</span>
        </div>
        <div class="status-card__value"><?= e($user['name']) ?></div>
        <div class="status-card__label">Sua conta</div>
    </div>

    <?php if ($organization): ?>
        <div class="status-card status-card--gold">
            <div class="status-card__header">
                <span class="status-card__icon">🏢</span>
                <span class="status-card__badge status-card__badge--<?= $organization['status'] === 'trial' ? 'trial' : 'active' ?>">
                    <?= $organization['status'] === 'trial' ? 'Trial' : 'Ativa' ?>
                </span>
            </div>
            <div class="status-card__value"><?= e($organization['name']) ?></div>
            <div class="status-card__label">Sua organização</div>
        </div>

        <div class="status-card status-card--green">
            <div class="status-card__header">
                <span class="status-card__icon">🔑</span>
            </div>
            <div class="status-card__value"><?= e($organization['role_name'] ?? 'Usuário') ?></div>
            <div class="status-card__label">Seu perfil de acesso</div>
        </div>

        <div class="status-card status-card--purple">
            <div class="status-card__header">
                <span class="status-card__icon">📋</span>
            </div>
            <div class="status-card__value">
                <?= ucfirst(e($organization['plan'] ?? 'Basic')) ?>
            </div>
            <div class="status-card__label">Plano atual</div>
        </div>
    <?php else: ?>
        <div class="status-card status-card--gold">
            <div class="status-card__header">
                <span class="status-card__icon">🏢</span>
                <span class="status-card__badge status-card__badge--pending">Pendente</span>
            </div>
            <div class="status-card__value">Nenhuma</div>
            <div class="status-card__label">
                <a href="<?= url('/onboarding/organizacao') ?>" class="text-primary font-semibold">
                    Cadastrar organização →
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Next Steps -->
<div class="dashboard-section" style="margin-bottom: var(--space-8)">
    <h2 class="dashboard-section__title">Primeiros passos</h2>
    <div class="next-steps">
        <div class="next-step-item done">
            <span class="next-step-item__check done">✓</span>
            <div class="next-step-item__content">
                <div class="next-step-item__title">Criar sua conta</div>
                <div class="next-step-item__desc">Cadastro realizado com sucesso</div>
            </div>
        </div>

        <div class="next-step-item <?= $user['email_verified_at'] ? 'done' : '' ?>">
            <span class="next-step-item__check <?= $user['email_verified_at'] ? 'done' : '' ?>">
                <?= $user['email_verified_at'] ? '✓' : '2' ?>
            </span>
            <div class="next-step-item__content">
                <div class="next-step-item__title">Verificar seu e-mail</div>
                <div class="next-step-item__desc">
                    <?= $user['email_verified_at']
                        ? 'E-mail verificado'
                        : 'Verifique o e-mail enviado para ' . e($user['email'])
                    ?>
                </div>
            </div>
        </div>

        <div class="next-step-item <?= $organization ? 'done' : '' ?>">
            <span class="next-step-item__check <?= $organization ? 'done' : '' ?>">
                <?= $organization ? '✓' : '3' ?>
            </span>
            <div class="next-step-item__content">
                <div class="next-step-item__title">Cadastrar sua organização</div>
                <div class="next-step-item__desc">
                    <?= $organization
                        ? 'Organização cadastrada: ' . e($organization['name'])
                        : 'Configure os dados da sua igreja ou organização'
                    ?>
                </div>
            </div>
        </div>

        <div class="next-step-item">
            <span class="next-step-item__check">4</span>
            <div class="next-step-item__content">
                <div class="next-step-item__title">Cadastrar os primeiros membros</div>
                <div class="next-step-item__desc">Adicione membros da sua equipe à plataforma</div>
            </div>
        </div>

        <div class="next-step-item">
            <span class="next-step-item__check">5</span>
            <div class="next-step-item__content">
                <div class="next-step-item__title">Explorar os módulos</div>
                <div class="next-step-item__desc">Conheça as ferramentas de gestão disponíveis</div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<?php if ($organization): ?>
    <div>
        <h2 class="dashboard-section__title">Atalhos rápidos</h2>
        <div class="quick-actions-grid">
            <a href="<?= url('/gestao') ?>" class="quick-action">
                <span class="quick-action__icon quick-action__icon--blue">📊</span>
                <div>
                    <div class="quick-action__text">Gestão</div>
                    <div class="quick-action__desc">Painel de gestão da igreja</div>
                </div>
            </a>

            <a href="<?= url('/gestao/membros') ?>" class="quick-action">
                <span class="quick-action__icon quick-action__icon--blue">👥</span>
                <div>
                    <div class="quick-action__text">Membros</div>
                    <div class="quick-action__desc">Gerenciar membros</div>
                </div>
            </a>

            <a href="<?= url('/gestao/financeiro') ?>" class="quick-action">
                <span class="quick-action__icon quick-action__icon--gold">💰</span>
                <div>
                    <div class="quick-action__text">Finanças</div>
                    <div class="quick-action__desc">Controle financeiro</div>
                </div>
            </a>

            <a href="<?= url('/gestao/eventos') ?>" class="quick-action">
                <span class="quick-action__icon quick-action__icon--green">📅</span>
                <div>
                    <div class="quick-action__text">Eventos</div>
                    <div class="quick-action__desc">Agenda e eventos</div>
                </div>
            </a>

            <a href="<?= url('/gestao/relatorios') ?>" class="quick-action">
                <span class="quick-action__icon quick-action__icon--purple">📊</span>
                <div>
                    <div class="quick-action__text">Relatórios</div>
                    <div class="quick-action__desc">Dashboards e dados</div>
                </div>
            </a>

            <a href="<?= url('/gestao/configuracoes') ?>" class="quick-action">
                <span class="quick-action__icon quick-action__icon--gold">⚙️</span>
                <div>
                    <div class="quick-action__text">Configurações</div>
                    <div class="quick-action__desc">Configurar gestão</div>
                </div>
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert--info" role="alert">
        <span>🏢 <strong>Cadastre sua organização</strong> para ter acesso a todos os módulos da plataforma.
        <a href="<?= url('/onboarding/organizacao') ?>" class="text-primary font-bold"> Cadastrar agora →</a></span>
    </div>
<?php endif; ?>

<!-- Vitrine -->
<section class="dashboard-section dashboard-section--showcase" id="vitrine">
    <div class="dashboard-section__header">
        <h2 class="dashboard-section__title">Vitrine de solucoes</h2>
        <a href="<?= url('/contato') ?>" class="dashboard-section__link">Falar com especialista</a>
    </div>

    <div class="showcase-grid">
        <?php foreach (($showcaseItems ?? []) as $item): ?>
            <article class="showcase-card">
                <div class="showcase-card__head">
                    <span class="showcase-card__icon showcase-card__icon--<?= e($item['icon'] ?? 'briefcase') ?>" aria-hidden="true">
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
                        <?php elseif (($item['icon'] ?? '') === 'hand'): ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M8 13V4a1.5 1.5 0 0 1 3 0v6"></path><path d="M11 10V3.5a1.5 1.5 0 0 1 3 0V10"></path><path d="M14 10V5a1.5 1.5 0 0 1 3 0v7"></path><path d="M5 14.5c0-1 .8-1.8 1.8-1.8H8v.3a8 8 0 0 0 8 8h.4a3.6 3.6 0 0 0 3.6-3.6V13"></path></svg>
                        <?php else: ?>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"></path></svg>
                        <?php endif; ?>
                    </span>

                    <?php if (!empty($item['badge'])): ?>
                        <span class="showcase-card__badge showcase-card__badge--<?= e($item['badge_type'] ?: 'new') ?>">
                            <?= e($item['badge']) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <h3 class="showcase-card__title"><?= e($item['title'] ?? '') ?></h3>
                <p class="showcase-card__description"><?= e($item['description'] ?? '') ?></p>

                <div class="showcase-card__footer">
                    <strong class="showcase-card__price"><?= e($item['price'] ?? '') ?></strong>
                    <a href="<?= e($item['url'] ?? url('/contato')) ?>" class="showcase-card__button"><?= e($item['cta'] ?? 'Ver detalhes') ?></a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php $__view->endSection(); ?>
