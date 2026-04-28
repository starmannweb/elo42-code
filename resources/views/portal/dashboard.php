<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<?php
    $icon = static function (string $name): string {
        return match ($name) {
            'book' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h7a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-7a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h8z"/></svg>',
            'calendar' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>',
            'audio' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
            'gift' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M12 7v14M2 12h20"/><path d="M12 7H7.5a2.5 2.5 0 1 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 1 0 0-5C13 2 12 7 12 7z"/></svg>',
            'message' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/></svg>',
            'check' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>',
            'course' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 10-10-5-10 5 10 5 10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>',
            'award' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M8.2 13.2 7 22l5-3 5 3-1.2-8.8"/></svg>',
            default => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>',
        };
    };

    $planProgress = (int) ($currentPlan['progress'] ?? 0);
    $bannerStyle = !empty($banner['image_url'])
        ? "background-image: linear-gradient(115deg, rgba(6,24,58,.92), rgba(21,71,245,.66)), url('" . e((string) $banner['image_url']) . "');"
        : '';
?>

<div class="portal-page portal-page--wide">
    <div class="portal-page-header">
        <div>
            <p class="portal-subtitle" style="margin-top:0;"><?= e($greeting) ?>, <?= e($firstName) ?></p>
            <h2 class="portal-title">Sua jornada na igreja</h2>
            <p class="portal-subtitle">Acompanhe conteúdos, eventos, solicitações e próximos passos em um só lugar.</p>
        </div>
    </div>

    <div class="portal-dashboard-grid">
        <section class="portal-grid">
            <div class="portal-hero" style="<?= $bannerStyle ?>">
                <span class="portal-hero__badge"><?= e($banner['label'] ?? 'Destaque') ?></span>
                <h3 class="portal-hero__title"><?= e($banner['title'] ?? '') ?></h3>
                <p class="portal-hero__text"><?= e($banner['description'] ?? '') ?></p>
                <div>
                    <a class="portal-btn portal-btn--gold" href="<?= url((string) ($banner['link_url'] ?? '/membro/eventos')) ?>">Ver agora</a>
                </div>
            </div>

            <div class="portal-grid portal-grid--3">
                <div class="portal-card portal-stat">
                    <span class="portal-soft-icon"><?= $icon('calendar') ?></span>
                    <div>
                        <p class="portal-stat__value"><?= count($upcomingEvents ?? []) ?></p>
                        <p class="portal-stat__label">Próximos eventos</p>
                    </div>
                </div>
                <div class="portal-card portal-stat">
                    <span class="portal-soft-icon"><?= $icon('audio') ?></span>
                    <div>
                        <p class="portal-stat__value"><?= count($featuredSermons ?? []) ?></p>
                        <p class="portal-stat__label">Ministrações</p>
                    </div>
                </div>
                <div class="portal-card portal-stat">
                    <span class="portal-soft-icon"><?= $icon('award') ?></span>
                    <div>
                        <p class="portal-stat__value"><?= (int) ($achievementSummary['points'] ?? 0) ?></p>
                        <p class="portal-stat__label">Pontos</p>
                    </div>
                </div>
            </div>

            <div class="portal-card">
                <div class="portal-card__header">
                    <div>
                        <h3 class="portal-card__title">Plano em andamento</h3>
                        <p class="portal-card__subtitle"><?= e($currentPlan['title'] ?? 'Plano de leitura') ?></p>
                    </div>
                    <a class="portal-btn portal-btn--secondary" href="<?= url('/membro/planos-leitura') ?>">Continuar</a>
                </div>
                <div class="portal-card__body">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:12px;">
                        <div>
                            <strong>Dia <?= (int) ($currentPlan['current_day'] ?? 1) ?> de <?= (int) ($currentPlan['duration_days'] ?? 30) ?></strong>
                            <p class="portal-list-card__text"><?= e($currentPlan['book_range'] ?? 'Bíblia') ?></p>
                        </div>
                        <strong><?= $planProgress ?>%</strong>
                    </div>
                    <div class="portal-progress" style="--progress: <?= $planProgress ?>%;"><span></span></div>
                </div>
            </div>

            <div class="portal-card">
                <div class="portal-card__header">
                    <div>
                        <h3 class="portal-card__title">Versículo do dia</h3>
                        <p class="portal-card__subtitle"><?= e($verseOfDay['reference'] ?? '') ?></p>
                    </div>
                    <span class="portal-status portal-status--warning"><?= e($verseOfDay['tag'] ?? 'Devocional') ?></span>
                </div>
                <div class="portal-card__body">
                    <p class="portal-reading-text" style="margin:0;"><?= e($verseOfDay['text'] ?? '') ?></p>
                </div>
            </div>
        </section>

        <aside class="portal-grid">
            <div>
                <h3 class="portal-card__title" style="margin-bottom:14px;">Acesso rápido</h3>
                <div class="portal-quick-grid">
                    <?php foreach ($quickActions as $action): ?>
                        <a class="portal-quick-card" href="<?= url($action['href']) ?>">
                            <span class="portal-quick-card__icon"><?= $icon($action['icon']) ?></span>
                            <span class="portal-quick-card__label"><?= e($action['label']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="portal-card">
                <div class="portal-card__header">
                    <div>
                        <h3 class="portal-card__title">Ministrações em destaque</h3>
                        <p class="portal-card__subtitle">Conteúdos recentes para assistir e revisar.</p>
                    </div>
                </div>
                <div class="portal-card__body">
                    <div class="portal-list">
                        <?php foreach (array_slice($featuredSermons ?? [], 0, 3) as $sermon): ?>
                            <div class="portal-list-card">
                                <span class="portal-soft-icon"><?= $icon('audio') ?></span>
                                <div class="portal-list-card__content">
                                    <h4 class="portal-list-card__title"><?= e($sermon['title'] ?? '') ?></h4>
                                    <p class="portal-list-card__text"><?= e($sermon['preacher'] ?? '') ?></p>
                                    <div class="portal-meta">
                                        <?php if (!empty($sermon['reference'])): ?><span><?= e($sermon['reference']) ?></span><?php endif; ?>
                                        <?php if (!empty($sermon['series'])): ?><span><?= e($sermon['series']) ?></span><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="margin-top:16px;">
                        <a class="portal-btn portal-btn--secondary" style="width:100%;" href="<?= url('/membro/ministracoes') ?>">Explorar biblioteca</a>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
<?php $__view->endSection(); ?>
