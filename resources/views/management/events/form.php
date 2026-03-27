<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php $isEdit = $event !== null; ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title"><?= $isEdit ? 'Editar evento' : 'Novo evento' ?></h1></div></div>
<div class="mgmt-form-card">
    <form method="POST" action="<?= $isEdit ? url('/gestao/eventos/' . $event['id'] . '/editar') : url('/gestao/eventos') ?>" data-loading>
        <?= csrf_field() ?>
        <div class="form-group"><label class="form-label">Título *</label><input type="text" name="title" class="form-input" value="<?= e($isEdit ? $event['title'] : old('title')) ?>" required></div>
        <div class="form-group"><label class="form-label">Descrição</label><textarea name="description" class="form-input" rows="3"><?= e($isEdit ? $event['description'] : old('description')) ?></textarea></div>
        <div class="form-group"><label class="form-label">Local</label><input type="text" name="location" class="form-input" value="<?= e($isEdit ? $event['location'] : old('location')) ?>"></div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Início *</label><input type="datetime-local" name="start_date" class="form-input" value="<?= e($isEdit ? date('Y-m-d\TH:i', strtotime($event['start_date'])) : old('start_date')) ?>" required></div>
            <div class="form-group"><label class="form-label">Término</label><input type="datetime-local" name="end_date" class="form-input" value="<?= e($isEdit && $event['end_date'] ? date('Y-m-d\TH:i', strtotime($event['end_date'])) : old('end_date')) ?>"></div>
        </div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Inscrições máximas</label><input type="number" name="max_registrations" class="form-input" value="<?= e($isEdit ? $event['max_registrations'] : old('max_registrations')) ?>" min="0"></div>
            <div class="form-group"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <?php foreach (['draft'=>'Rascunho','published'=>'Publicado','ongoing'=>'Em andamento','completed'=>'Concluído','cancelled'=>'Cancelado'] as $k=>$v): ?>
                        <option value="<?= $k ?>" <?= ($isEdit ? $event['status'] : (old('status') ?: 'draft')) === $k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary"><?= $isEdit ? 'Salvar' : 'Criar evento' ?></button><a href="<?= url('/gestao/eventos') ?>" class="btn btn--ghost">Cancelar</a></div>
    </form>
</div>
<?php $__view->endSection(); ?>
