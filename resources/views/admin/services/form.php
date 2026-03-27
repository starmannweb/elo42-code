<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php $isEdit = $item !== null; ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title"><?= $isEdit ? 'Editar serviço' : 'Novo serviço' ?></h1></div></div>
<div class="mgmt-form-card" style="max-width:640px;">
    <form method="POST" action="<?= $isEdit ? url('/admin/servicos/' . $item['id'] . '/editar') : url('/admin/servicos') ?>"><?= csrf_field() ?>
        <div class="mgmt-form-row"><div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" value="<?= e($isEdit ? $item['name'] : '') ?>" required></div><div class="form-group"><label class="form-label">Slug *</label><input type="text" name="slug" class="form-input" value="<?= e($isEdit ? $item['slug'] : '') ?>" required></div></div>
        <div class="form-group"><label class="form-label">Descrição</label><textarea name="description" class="form-input" rows="3"><?= e($isEdit ? $item['description'] : '') ?></textarea></div>
        <div class="form-group"><label class="form-label">Regras</label><textarea name="rules" class="form-input" rows="3"><?= e($isEdit ? $item['rules'] : '') ?></textarea></div>
        <div class="mgmt-form-row"><div class="form-group"><label class="form-label">Preço (R$)</label><input type="number" name="price" class="form-input" step="0.01" value="<?= e($isEdit ? $item['price'] : '') ?>"></div><div class="form-group"><label class="form-label">Recorrência</label><select name="recurrence" class="form-select"><?php foreach (['one_time'=>'Único','monthly'=>'Mensal','quarterly'=>'Trimestral','yearly'=>'Anual'] as $k=>$v): ?><option value="<?= $k ?>" <?= ($isEdit ? $item['recurrence'] : 'one_time') === $k ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?></select></div></div>
        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Ativo</option><option value="inactive" <?= ($isEdit && $item['status']==='inactive') ? 'selected' : '' ?>>Inativo</option></select></div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary"><?= $isEdit ? 'Salvar' : 'Criar' ?></button><a href="<?= url('/admin/servicos') ?>" class="btn btn--secondary">Cancelar</a></div>
    </form>
</div>
<?php $__view->endSection(); ?>
