<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<?php
    $formatDate = static function (?string $date, string $format = 'd/m/Y H:i'): string {
        if (empty($date)) {
            return 'Data a confirmar';
        }
        $timestamp = strtotime($date);
        return $timestamp ? date($format, $timestamp) : 'Data a confirmar';
    };
    $selectedDay = (string) ($selectedDay ?? '');
    $selectedCategory = (string) ($selectedCategory ?? 'Todos');
    $eventFilterUrl = static function (?string $day, string $category): string {
        $query = [];
        if (!empty($day)) {
            $query['day'] = $day;
        }
        if ($category !== 'Todos') {
            $query['category'] = $category;
        }

        return url('/membro/eventos' . (!empty($query) ? '?' . http_build_query($query) : ''));
    };
?>

<div class="portal-page portal-page--wide">
    <div class="portal-page-header">
        <div>
            <h2 class="portal-title">Eventos</h2>
            <p class="portal-subtitle">Cultos, encontros, cursos e atividades públicas da igreja.</p>
        </div>
    </div>

    <div class="portal-grid" style="gap:18px;">
        <div class="portal-date-strip" aria-label="Próximos dias">
            <?php foreach ($days as $day): ?>
                <a href="<?= e($eventFilterUrl((string) ($day['iso'] ?? ''), $selectedCategory)) ?>" class="portal-date-pill <?= !empty($day['active']) ? 'active' : '' ?>" <?= !empty($day['active']) ? 'aria-current="date"' : '' ?>>
                    <span class="portal-date-pill__weekday"><?= e($day['weekday']) ?></span>
                    <span class="portal-date-pill__day"><?= e($day['day']) ?></span>
                    <span class="portal-date-pill__month"><?= e($day['month']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="portal-chip-row" aria-label="Categorias de eventos">
            <?php foreach ($categories as $category): ?>
                <a href="<?= e($eventFilterUrl($selectedDay, (string) $category)) ?>" class="portal-chip <?= $selectedCategory === (string) $category ? 'active' : '' ?>" <?= $selectedCategory === (string) $category ? 'aria-current="true"' : '' ?>>
                    <?= e($category) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($events)): ?>
            <div class="portal-empty">
                <div>
                    <span class="portal-empty__icon">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    </span>
                    <h3 class="portal-empty__title">Nenhum evento programado</h3>
                    <p class="portal-empty__text">Assim que a igreja publicar a agenda, os eventos aparecerão aqui.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="portal-grid portal-grid--2">
                <?php foreach ($events as $event): ?>
                    <?php $eventId = (int) ($event['id'] ?? 0); ?>
                    <article class="portal-card">
                        <div class="portal-card__body">
                            <div style="display:flex;align-items:flex-start;gap:16px;">
                                <span class="portal-soft-icon" style="background:var(--portal-gold-soft);color:var(--portal-gold);">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                </span>
                                <div style="min-width:0;flex:1;">
                                    <span class="portal-status portal-status--neutral"><?= e($event['category'] ?? 'Evento') ?></span>
                                    <h3 class="portal-card__title" style="margin-top:12px;"><?= e($event['title'] ?? 'Evento') ?></h3>
                                    <p class="portal-list-card__text"><?= e($event['description'] ?? '') ?></p>
                                    <div class="portal-meta">
                                        <span><?= e($formatDate($event['start_date'] ?? null)) ?></span>
                                        <span><?= e($event['location'] ?? 'Local a confirmar') ?></span>
                                    </div>
                                    <?php if ($eventId > 0): ?>
                                        <form method="POST" action="<?= url('/membro/eventos/' . $eventId . '/inscricao') ?>" style="margin-top:14px;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="portal-btn portal-btn--primary" style="font-size:0.85rem;padding:8px 18px;">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:-2px;margin-right:5px;"><path d="M20 6L9 17l-5-5"></path></svg>
                                                Confirmar presença
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__view->endSection(); ?>
