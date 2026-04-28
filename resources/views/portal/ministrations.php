<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<?php
    $formatDate = static function (?string $date): string {
        if (empty($date)) {
            return '';
        }
        $timestamp = strtotime($date);
        return $timestamp ? date('d/m/Y', $timestamp) : '';
    };
    $categories = is_array($categories ?? null) ? $categories : ['Todas', 'Fé', 'Família', 'Discipulado', 'Evangelho'];
    $selectedCategory = (string) ($selectedCategory ?? 'Todas');
?>

<div class="portal-page portal-page--wide">
    <div class="portal-page-header">
        <div>
            <h2 class="portal-title">Ministrações</h2>
            <p class="portal-subtitle">Biblioteca de mensagens para estudo, revisão e compartilhamento.</p>
        </div>
        <div class="portal-actions">
            <input class="portal-input" style="width:min(100%,320px);" type="search" placeholder="Buscar ministração">
        </div>
    </div>

    <div class="portal-chip-row" style="margin-bottom:20px;">
        <?php foreach ($categories as $category): ?>
            <a href="<?= url('/membro/ministracoes' . ($category !== 'Todas' ? '?category=' . urlencode((string) $category) : '')) ?>" class="portal-chip <?= $selectedCategory === (string) $category ? 'active' : '' ?>" <?= $selectedCategory === (string) $category ? 'aria-current="true"' : '' ?>>
                <?= e((string) $category) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($sermons)): ?>
        <div class="portal-empty">
            <div>
                <span class="portal-empty__icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                </span>
                <h3 class="portal-empty__title">Nenhuma ministração publicada</h3>
                <p class="portal-empty__text">As mensagens cadastradas pela equipe aparecerão aqui.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="portal-grid portal-grid--2">
            <?php foreach ($sermons as $sermon): ?>
                <article class="portal-list-card">
                    <span class="portal-soft-icon" style="width:64px;height:64px;border-radius:18px;">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                    </span>
                    <div class="portal-list-card__content">
                        <?php if (!empty($sermon['series'])): ?>
                            <span class="portal-status portal-status--neutral"><?= e($sermon['series']) ?></span>
                        <?php endif; ?>
                        <h3 class="portal-card__title" style="margin-top:10px;"><?= e($sermon['title'] ?? 'Ministração') ?></h3>
                        <p class="portal-list-card__text"><?= e($sermon['summary'] ?? '') ?></p>
                        <div class="portal-meta">
                            <?php if (!empty($sermon['preacher'])): ?><span><?= e($sermon['preacher']) ?></span><?php endif; ?>
                            <?php if (!empty($sermon['date'])): ?><span><?= e($formatDate($sermon['date'])) ?></span><?php endif; ?>
                            <?php if (!empty($sermon['reference'])): ?><span><?= e($sermon['reference']) ?></span><?php endif; ?>
                        </div>
                        <div style="margin-top:16px;">
                            <button class="portal-btn portal-btn--secondary" type="button">Ver detalhes</button>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php $__view->endSection(); ?>
