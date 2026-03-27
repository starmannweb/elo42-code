<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div><h1 class="mgmt-header__title">Eventos</h1></div>
    <div class="mgmt-header__actions"><a href="<?= url('/gestao/eventos/novo') ?>" class="btn btn--primary">+ Novo evento</a></div>
</div>
<form method="GET" action="<?= url('/gestao/eventos') ?>" class="mgmt-filters">
    <select name="status" class="form-select" onchange="this.form.submit()">
        <option value="">Todos</option>
        <?php foreach (['draft'=>'Rascunho','published'=>'Publicado','ongoing'=>'Em andamento','completed'=>'Concluído','cancelled'=>'Cancelado'] as $k=>$v): ?>
            <option value="<?= $k ?>" <?= ($filter_status ?? '') === $k ? 'selected' : '' ?>><?= $v ?></option>
        <?php endforeach; ?>
    </select>
</form>
<?php if (empty($events)): ?>
    <div class="mgmt-empty"><div class="mgmt-empty__icon">📅</div><h3 class="mgmt-empty__title">Nenhum evento</h3><p class="mgmt-empty__text">Crie o primeiro evento.</p><a href="<?= url('/gestao/eventos/novo') ?>" class="btn btn--primary">Criar evento</a></div>
<?php else: ?>
    <div class="mgmt-table-container">
        <table class="mgmt-table">
            <thead><tr><th>Evento</th><th>Data</th><th>Inscrições</th><th>Status</th><th>Ações</th></tr></thead>
            <tbody>
                <?php foreach ($events as $ev): ?>
                <tr>
                    <td><div class="mgmt-table__name"><?= e($ev['title']) ?></div><?php if ($ev['location']): ?><div class="mgmt-table__sub">📍 <?= e($ev['location']) ?></div><?php endif; ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($ev['start_date'])) ?></td>
                    <td><?= $ev['registrations'] ?><?= $ev['max_registrations'] ? '/' . $ev['max_registrations'] : '' ?></td>
                    <td><span class="badge badge--<?= $ev['status'] ?>"><?= e(ucfirst($ev['status'])) ?></span></td>
                    <td class="mgmt-table__actions"><a href="<?= url('/gestao/eventos/' . $ev['id']) ?>">Ver</a><a href="<?= url('/gestao/eventos/' . $ev['id'] . '/editar') ?>">Editar</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php $__view->endSection(); ?>
