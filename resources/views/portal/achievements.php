<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<?php
    $earnedCount = count(array_filter($achievements, static fn (array $item): bool => !empty($item['earned'])));
    $totalPoints = array_sum(array_map(static fn (array $item): int => !empty($item['earned']) ? (int) ($item['points'] ?? 0) : 0, $achievements));
?>

<div class="portal-page portal-page--wide">
    <div class="portal-page-header">
        <div>
            <h2 class="portal-title">Conquistas</h2>
            <p class="portal-subtitle">Acompanhe objetivos espirituais, participação e crescimento no portal.</p>
        </div>
    </div>

    <div class="portal-grid portal-grid--3" style="margin-bottom:20px;">
        <div class="portal-card portal-stat">
            <span class="portal-soft-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M8.2 13.2 7 22l5-3 5 3-1.2-8.8"/></svg>
            </span>
            <div>
                <p class="portal-stat__value"><?= $earnedCount ?></p>
                <p class="portal-stat__label">Conquistadas</p>
            </div>
        </div>
        <div class="portal-card portal-stat">
            <span class="portal-soft-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20V10"></path><path d="M18 20V4"></path><path d="M6 20v-6"></path><path d="M4 20h16"></path></svg>
            </span>
            <div>
                <p class="portal-stat__value"><?= $totalPoints ?></p>
                <p class="portal-stat__label">Pontos</p>
            </div>
        </div>
        <div class="portal-card portal-stat">
            <span class="portal-soft-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            </span>
            <div>
                <p class="portal-stat__value"><?= count($achievements) ?></p>
                <p class="portal-stat__label">Disponíveis</p>
            </div>
        </div>
    </div>

    <div class="portal-grid portal-grid--3">
        <?php foreach ($achievements as $achievement): ?>
            <?php $progress = (int) ($achievement['progress'] ?? 0); ?>
            <article class="portal-card">
                <div class="portal-card__body">
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">
                        <span class="portal-soft-icon" style="background:<?= !empty($achievement['earned']) ? 'var(--portal-success-soft)' : 'var(--portal-gold-soft)' ?>;color:<?= !empty($achievement['earned']) ? 'var(--portal-success)' : 'var(--portal-gold)' ?>;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M8.2 13.2 7 22l5-3 5 3-1.2-8.8"/></svg>
                        </span>
                        <span class="portal-status <?= !empty($achievement['earned']) ? 'portal-status--success' : 'portal-status--neutral' ?>">
                            <?= !empty($achievement['earned']) ? 'Conquistada' : 'Em progresso' ?>
                        </span>
                    </div>
                    <h3 class="portal-card__title" style="margin-top:18px;"><?= e($achievement['title'] ?? 'Conquista') ?></h3>
                    <p class="portal-list-card__text"><?= e($achievement['description'] ?? '') ?></p>
                    <div class="portal-meta">
                        <span><?= (int) ($achievement['points'] ?? 0) ?> pontos</span>
                        <span><?= e($achievement['criteria_type'] ?? 'crescimento') ?></span>
                    </div>
                    <div style="margin-top:18px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:8px;color:var(--portal-text-soft);font-weight:800;font-size:.85rem;">
                            <span>Requisito</span>
                            <span><?= $progress ?>%</span>
                        </div>
                        <div class="portal-progress" style="--progress: <?= $progress ?>%;"><span></span></div>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>
<?php $__view->endSection(); ?>
