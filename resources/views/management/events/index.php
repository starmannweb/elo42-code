<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Eventos</h1>
        <p class="mgmt-header__subtitle">Gerencie os eventos e atividades da igreja</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="window.location.href='<?= url('/gestao/eventos/novo') ?>'">+ Novo Evento</button>
    </div>
</div>

<?php
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
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Eventos Ativos</div>
            <div class="mgmt-kpi-card__value"><?= $eventosAtivos ?></div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Total de Inscritos</div>
            <div class="mgmt-kpi-card__value"><?= $totalInscritos ?></div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Próximo Evento</div>
            <div class="mgmt-kpi-card__value" style="font-size: var(--text-lg);"><?= $proximoEvento ? e($proximoEvento) : '—' ?></div>
        </div>
    </div>
</div>

<?php if (empty($events)): ?>
<div class="mgmt-empty"><div class="mgmt-empty__icon">📅</div><h3 class="mgmt-empty__title">Nenhum evento</h3><p class="mgmt-empty__text">Crie o primeiro evento da sua igreja.</p><a href="<?= url('/gestao/eventos/novo') ?>" class="btn btn--primary">Criar evento</a></div>
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
                <td style="color: var(--text-muted);">� <?= date('d/m/Y', strtotime($ev['start_date'])) ?></td>
                <td><?= e($ev['location'] ?? '—') ?></td>
                <td><?= $ev['registrations'] ?? 0 ?><?= !empty($ev['max_registrations']) ? '/' . $ev['max_registrations'] : '' ?></td>
                <td><span class="badge badge--<?= $ev['status'] === 'published' || $ev['status'] === 'ongoing' ? 'active' : $ev['status'] ?>"><?= strtoupper($ev['status'] === 'published' || $ev['status'] === 'ongoing' ? 'ATIVO' : e($ev['status'])) ?></span></td>
                <td class="mgmt-table__actions">
                    <a href="<?= url('/gestao/eventos/' . $ev['id'] . '/editar') ?>" style="padding: 4px;" title="Editar">✏️</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
<?php $__view->endSection(); ?>
