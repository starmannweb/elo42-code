<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
$isEdit = $item !== null;
$services = $services ?? [];
?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title"><?= $isEdit ? 'Editar cortesia' : 'Nova cortesia' ?></h1></div></div>
<div class="mgmt-form-card" style="max-width:760px;">
    <form method="POST" action="<?= $isEdit ? url('/admin/cortesias/' . $item['id'] . '/editar') : url('/admin/cortesias') ?>"><?= csrf_field() ?>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" value="<?= e($isEdit ? $item['name'] : '') ?>" required></div>
            <div class="form-group"><label class="form-label">Slug *</label><input type="text" name="slug" class="form-input" value="<?= e($isEdit ? $item['slug'] : '') ?>" required></div>
        </div>
        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">Produto/serviço liberado</label>
                <select name="service_id" class="form-select">
                    <option value="">Qualquer serviço</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= $service['id'] ?>" <?= $isEdit && (int)($item['service_id'] ?? 0) === (int)$service['id'] ? 'selected' : '' ?>><?= e($service['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label class="form-label">Duração da cortesia</label><input type="number" name="duration_days" class="form-input" min="1" value="<?= e($isEdit ? ($item['duration_days'] ?? '') : '') ?>" placeholder="Ex.: 30 dias"></div>
        </div>
        <div class="form-group"><label class="form-label">Descrição</label><textarea name="description" class="form-input" rows="3"><?= e($isEdit ? $item['description'] : '') ?></textarea></div>
        <div class="form-group"><label class="form-label">Requisitos</label><textarea name="requirements" class="form-input" rows="3"><?= e($isEdit ? $item['requirements'] : '') ?></textarea></div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Limite de uso</label><input type="number" name="max_usage" class="form-input" value="<?= e($isEdit ? $item['max_usage'] : '') ?>" placeholder="Vazio = ilimitado"></div>
            <div class="form-group"><label class="form-label">Válido até</label><input type="date" name="valid_until" class="form-input" value="<?= e($isEdit ? $item['valid_until'] : '') ?>"></div>
        </div>
        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><?php foreach (['active'=>'Ativo','inactive'=>'Inativo','paused'=>'Pausado'] as $k=>$v): ?><option value="<?= $k ?>" <?= ($isEdit ? $item['status'] : 'active') === $k ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?></select></div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary"><?= $isEdit ? 'Salvar' : 'Criar' ?></button><a href="<?= url('/admin/cortesias') ?>" class="btn btn--secondary">Cancelar</a></div>
    </form>
</div>
<?php $__view->endSection(); ?>
