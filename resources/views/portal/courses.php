<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<?php
    $statusLabel = [
        'published' => 'Disponível',
        'ongoing' => 'Em andamento',
        'completed' => 'Concluído',
        'draft' => 'Rascunho',
    ];
    $formatDate = static function (?string $date): string {
        if (empty($date)) {
            return '';
        }
        $timestamp = strtotime($date);
        return $timestamp ? date('d/m/Y', $timestamp) : '';
    };
?>

<div class="portal-page portal-page--wide">
    <div class="portal-page-header">
        <div>
            <h2 class="portal-title">Cursos</h2>
            <p class="portal-subtitle">Aulas, materiais em PDF e links de vídeo disponibilizados pela igreja.</p>
        </div>
    </div>

    <?php if (empty($courses)): ?>
        <div class="portal-empty">
            <div>
                <span class="portal-empty__icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 10-10-5-10 5 10 5 10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </span>
                <h3 class="portal-empty__title">Nenhum curso disponível</h3>
                <p class="portal-empty__text">Quando a equipe publicar cursos, eles aparecerão nesta página.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="portal-grid portal-grid--3">
            <?php foreach ($courses as $course): ?>
                <?php $progress = (int) ($course['progress'] ?? 0); ?>
                <article class="portal-card">
                    <div class="portal-media-frame">
                        <svg width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m22 10-10-5-10 5 10 5 10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    </div>
                    <div class="portal-card__body">
                        <span class="portal-status portal-status--success"><?= e($statusLabel[$course['status'] ?? 'published'] ?? 'Disponível') ?></span>
                        <h3 class="portal-card__title" style="margin-top:14px;"><?= e($course['title'] ?? 'Curso') ?></h3>
                        <p class="portal-list-card__text"><?= e($course['description'] ?? '') ?></p>
                        <div class="portal-meta">
                            <?php if (!empty($course['instructor'])): ?><span><?= e($course['instructor']) ?></span><?php endif; ?>
                            <?php if (!empty($course['duration_hours'])): ?><span><?= (int) $course['duration_hours'] ?>h</span><?php endif; ?>
                            <?php if (!empty($course['start_date'])): ?><span><?= e($formatDate($course['start_date'])) ?></span><?php endif; ?>
                        </div>
                        <div style="margin-top:18px;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:8px;color:var(--portal-text-soft);font-weight:800;font-size:.85rem;">
                                <span>Progresso</span>
                                <span><?= $progress ?>%</span>
                            </div>
                            <div class="portal-progress" style="--progress: <?= $progress ?>%;"><span></span></div>
                        </div>
                        <div class="portal-actions" style="justify-content:flex-start;margin-top:18px;">
                            <?php if (!empty($course['video_url'])): ?>
                                <a class="portal-btn portal-btn--primary" href="<?= e($course['video_url']) ?>" target="_blank" rel="noopener noreferrer">Assistir</a>
                            <?php else: ?>
                                <button class="portal-btn portal-btn--primary" type="button">Abrir curso</button>
                            <?php endif; ?>
                            <?php if (!empty($course['pdf_file_url'])): ?>
                                <a class="portal-btn portal-btn--secondary" href="<?= e($course['pdf_file_url']) ?>" target="_blank" rel="noopener noreferrer">PDF</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php $__view->endSection(); ?>
