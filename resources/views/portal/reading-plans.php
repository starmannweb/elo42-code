<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<div class="portal-page portal-page--wide">
    <div class="portal-page-header">
        <div>
            <h2 class="portal-title">Planos de leitura</h2>
            <p class="portal-subtitle">Acompanhe jornadas bíblicas propostas pela igreja e mantenha constância.</p>
        </div>
    </div>

    <?php if (empty($plans)): ?>
        <div class="portal-empty">
            <div>
                <span class="portal-empty__icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h7a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-7a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h8z"/></svg>
                </span>
                <h3 class="portal-empty__title">Nenhum plano publicado</h3>
                <p class="portal-empty__text">Quando a liderança publicar planos de leitura, eles aparecerão aqui.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="portal-grid portal-grid--3">
            <?php foreach ($plans as $plan): ?>
                <?php
                    $progress = (int) ($plan['progress'] ?? 0);
                    $duration = (int) ($plan['duration_days'] ?? 30);
                    $currentDay = (int) ($plan['current_day'] ?? 1);
                ?>
                <article class="portal-card">
                    <div class="portal-card__body">
                        <span class="portal-soft-icon" style="background:var(--portal-gold-soft);color:var(--portal-gold);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h7a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-7a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h8z"/></svg>
                        </span>
                        <h3 class="portal-card__title" style="margin-top:16px;"><?= e($plan['title'] ?? 'Plano de leitura') ?></h3>
                        <p class="portal-list-card__text"><?= e($plan['description'] ?? '') ?></p>
                        <div class="portal-meta">
                            <span><?= $duration ?> dias</span>
                            <span><?= e($plan['book_range'] ?? 'Bíblia') ?></span>
                            <span>Dia <?= $currentDay ?></span>
                        </div>
                        <div style="margin-top:18px;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:8px;color:var(--portal-text-soft);font-weight:800;font-size:.85rem;">
                                <span>Progresso</span>
                                <span><?= $progress ?>%</span>
                            </div>
                            <div class="portal-progress" style="--progress: <?= $progress ?>%;"><span></span></div>
                        </div>
                        <div style="margin-top:18px;">
                            <a class="portal-btn portal-btn--primary" href="<?= url('/membro/biblia') ?>">Ler agora</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php $__view->endSection(); ?>
