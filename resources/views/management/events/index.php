<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Eventos</h1>
        <p class="mgmt-header__subtitle">Gerencie os eventos e atividades da igreja</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-event').style.display='flex'">+ Novo Evento</button>
    </div>
</div>

<?php
$units = is_array($units ?? null) ? $units : [];
$eventosAtivos = 0;
$totalInscritos = 0;
$proximoEvento = null;
foreach ($events ?? [] as $ev) {
    if (in_array($ev['status'], ['published', 'ongoing'])) $eventosAtivos++;
    $totalInscritos += (int)($ev['registrations'] ?? 0);
    if (!$proximoEvento && strtotime($ev['start_date']) > time()) {
        $proximoEvento = $ev['title'];
    }
}
?>

<div class="mgmt-kpi-grid" style="grid-template-columns: repeat(3, 1fr);">
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">Eventos Ativos</div>
            <div class="mgmt-kpi-card__value"><?= $eventosAtivos ?></div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </div>
    </div>
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">Total de Inscritos</div>
            <div class="mgmt-kpi-card__value"><?= $totalInscritos ?></div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
    </div>
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">Próximo Evento</div>
            <div class="mgmt-kpi-card__value" style="font-size: var(--text-lg);"><?= $proximoEvento ? e($proximoEvento) : '—' ?></div>
        </div>
        <div class="mgmt-kpi-card__icon" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
    </div>
</div>

<?php if (empty($events)): ?>
<div class="mgmt-empty"><div class="mgmt-empty__icon"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div><h3 class="mgmt-empty__title">Nenhum evento</h3><p class="mgmt-empty__text">Crie o primeiro evento da sua igreja.</p><button type="button" onclick="document.getElementById('modal-new-event').style.display='flex'" class="btn btn--primary">Criar evento</button></div>
<?php else: ?>
<div class="mgmt-table-container">
    <table class="mgmt-table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Data</th>
                <th>Local</th>
                <th>Vagas</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $ev): ?>
            <tr>
                <td><div class="mgmt-table__name"><?= e($ev['title']) ?></div></td>
                <td style="color: var(--text-muted); display:flex; align-items:center; gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> <?= date('d/m/Y', strtotime($ev['start_date'])) ?></td>
                <td><a href="#" style="color: var(--color-primary); text-decoration:none;"><?= e($ev['location'] ?? '—') ?></a></td>
                <td><?= $ev['registrations'] ?? 0 ?><?= !empty($ev['max_registrations']) ? '/' . $ev['max_registrations'] : '' ?></td>
                <td><span class="badge badge--<?= $ev['status'] === 'published' || $ev['status'] === 'ongoing' ? 'active' : $ev['status'] ?>"><?= strtoupper($ev['status'] === 'published' || $ev['status'] === 'ongoing' ? 'ATIVO' : e($ev['status'])) ?></span></td>
                <td style="text-align:right;">
                    <a href="<?= url('/gestao/eventos/' . $ev['id'] . '/editar') ?>" style="color: var(--text-muted); display:inline-flex;" title="Editar"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<div class="modal" id="modal-new-event" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-new-event-title">
    <div class="modal__content modal__content--wide">
        <div class="modal__header">
            <h2 class="modal__title" id="modal-new-event-title">Novo evento</h2>
            <button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'" aria-label="Fechar">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/eventos') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body modal__body--compact">
                <div class="modal-grid">
                    <div class="form-group modal-grid__full">
                        <label class="form-label" for="event-title">Título *</label>
                        <input id="event-title" type="text" name="title" class="form-input" required>
                    </div>
                    <div class="form-group modal-grid__full">
                        <label class="form-label" for="event-description">Descrição</label>
                        <textarea id="event-description" name="description" class="form-input" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="event-unit">Unidade</label>
                        <select id="event-unit" name="church_unit_id" class="form-select">
                            <option value="">Sede / todas as unidades</option>
                            <?php foreach ($units as $unit): ?>
                                <option value="<?= (int) $unit['id'] ?>"><?= e((string) ($unit['name'] ?? 'Unidade')) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="event-location">Local</label>
                        <input id="event-location" type="text" name="location" class="form-input" placeholder="Ex.: Auditório principal">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="event-start">Início *</label>
                        <input id="event-start" type="datetime-local" name="start_date" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="event-end">Término</label>
                        <input id="event-end" type="datetime-local" name="end_date" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="event-max">Inscrições máximas</label>
                        <input id="event-max" type="number" name="max_registrations" class="form-input" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="event-status">Status</label>
                        <select id="event-status" name="status" class="form-select">
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
