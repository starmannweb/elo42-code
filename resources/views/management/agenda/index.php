<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php
    $monthParam = preg_match('/^\d{4}-\d{2}$/', (string) ($_GET['month'] ?? '')) ? (string) $_GET['month'] : date('Y-m');
    $current = DateTimeImmutable::createFromFormat('Y-m-d', $monthParam . '-01') ?: new DateTimeImmutable('first day of this month');
    $monthStart = $current->modify('first day of this month');
    $monthEnd = $current->modify('last day of this month');
    $monthNames = [1 => 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    $weekdays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
    $firstWeekday = (int) $monthStart->format('w');
    $daysInMonth = (int) $monthStart->format('t');
    $prevMonth = $monthStart->modify('-1 month')->format('Y-m');
    $nextMonth = $monthStart->modify('+1 month')->format('Y-m');
    $today = date('Y-m-d');
    $eventColors = ['#1455ff', '#10b981', '#7c3aed', '#f59e0b', '#ef4444'];
    $units = is_array($units ?? null) ? $units : [];
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Agenda</h1>
        <p class="mgmt-header__subtitle">Calendário unificado de eventos, visitas e aconselhamentos</p>
    </div>
    <div class="mgmt-header__actions">
        <a href="<?= url('/gestao/eventos') ?>" class="btn btn--outline">Lista de eventos</a>
        <button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-agenda-event').style.display='flex'">Novo evento</button>
    </div>
</div>

<div class="mgmt-dashboard-card agenda-toolbar">
    <div class="agenda-toolbar__inner">
        <div class="agenda-toolbar__legend">
            <span class="badge badge-primary">Eventos</span>
            <span class="badge badge-success">Visitas</span>
            <span class="badge" style="background:#efe3ff;color:#6d28d9;">Aconselhamento</span>
        </div>
        <div class="agenda-toolbar__nav">
            <a href="<?= url('/gestao/agenda?month=' . $prevMonth) ?>" class="btn btn--outline btn--sm" aria-label="Mês anterior">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </a>
            <strong class="agenda-toolbar__month"><?= $monthNames[(int) $monthStart->format('n')] ?> / <?= $monthStart->format('Y') ?></strong>
            <a href="<?= url('/gestao/agenda?month=' . $nextMonth) ?>" class="btn btn--outline btn--sm" aria-label="Próximo mês">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        </div>
    </div>
</div>

<div class="mgmt-calendar-shell">
    <div class="mgmt-calendar-weekdays">
        <?php foreach ($weekdays as $dayName): ?>
            <div class="mgmt-calendar-weekday"><?= e($dayName) ?></div>
        <?php endforeach; ?>
    </div>
    <div class="mgmt-calendar-grid">
        <?php for ($i = 0; $i < $firstWeekday; $i++): ?>
            <div class="mgmt-calendar-day is-muted"></div>
        <?php endfor; ?>

        <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
            <?php
                $date = $monthStart->format('Y-m') . '-' . str_pad((string) $day, 2, '0', STR_PAD_LEFT);
                $eventsOfDay = array_values(array_filter($events ?? [], static fn($event) => isset($event['start_date']) && str_starts_with((string) $event['start_date'], $date)));
            ?>
            <div class="mgmt-calendar-day <?= $date === $today ? 'is-today' : '' ?>">
                <span class="mgmt-calendar-day__number"><?= $day ?></span>
                <?php foreach (array_slice($eventsOfDay, 0, 3) as $index => $event): ?>
                    <a class="mgmt-calendar-event" href="<?= url('/gestao/eventos/' . (int) ($event['id'] ?? 0)) ?>" style="background:<?= e($eventColors[$index % count($eventColors)]) ?>;">
                        <?= e((string) ($event['title'] ?? 'Evento')) ?>
                    </a>
                <?php endforeach; ?>
                <?php if (count($eventsOfDay) > 3): ?>
                    <div style="margin-top:.35rem;color:#6b7892;font-size:.75rem;font-weight:700;">+<?= count($eventsOfDay) - 3 ?> eventos</div>
                <?php endif; ?>
            </div>
        <?php endfor; ?>

        <?php
            $remaining = (7 - (($firstWeekday + $daysInMonth) % 7)) % 7;
            for ($i = 0; $i < $remaining; $i++):
        ?>
            <div class="mgmt-calendar-day is-muted"></div>
        <?php endfor; ?>
    </div>
</div>

<div class="modal" id="modal-new-agenda-event" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-new-agenda-event-title">
    <div class="modal__content modal__content--wide">
        <div class="modal__header">
            <h2 class="modal__title" id="modal-new-agenda-event-title">Novo evento</h2>
            <button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'" aria-label="Fechar">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/eventos') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body modal__body--compact">
                <div class="modal-grid">
                    <div class="form-group modal-grid__full">
                        <label class="form-label" for="agenda-event-title">Título *</label>
                        <input id="agenda-event-title" type="text" name="title" class="form-input" required>
                    </div>
                    <div class="form-group modal-grid__full">
                        <label class="form-label" for="agenda-event-description">Descrição</label>
                        <textarea id="agenda-event-description" name="description" class="form-input" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="agenda-event-unit">Unidade</label>
                        <select id="agenda-event-unit" name="church_unit_id" class="form-select">
                            <option value="">Sede / todas as unidades</option>
                            <?php foreach ($units as $unit): ?>
                                <option value="<?= (int) $unit['id'] ?>"><?= e((string) ($unit['name'] ?? 'Unidade')) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="agenda-event-location">Local</label>
                        <input id="agenda-event-location" type="text" name="location" class="form-input" placeholder="Ex.: Auditório principal">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="agenda-event-start">Início *</label>
                        <input id="agenda-event-start" type="datetime-local" name="start_date" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="agenda-event-end">Término</label>
                        <input id="agenda-event-end" type="datetime-local" name="end_date" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="agenda-event-max">Inscrições máximas</label>
                        <input id="agenda-event-max" type="number" name="max_registrations" class="form-input" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="agenda-event-status">Status</label>
                        <select id="agenda-event-status" name="status" class="form-select">
                            <option value="draft">Rascunho</option>
                            <option value="published">Publicado</option>
                            <option value="ongoing">Em andamento</option>
                            <option value="completed">Concluído</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--primary">Criar evento</button>
            </div>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
