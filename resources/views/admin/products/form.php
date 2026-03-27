<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php $isEdit = $item !== null; ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title"><?= $isEdit ? 'Editar produto' : 'Novo produto' ?></h1></div></div>
<div class="mgmt-form-card" style="max-width:640px;">
    <form method="POST" action="<?= $isEdit ? url('/admin/produtos/' . $item['id'] . '/editar') : url('/admin/produtos') ?>"><?= csrf_field() ?>
        <div class="mgmt-form-row"><div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" value="<?= e($isEdit ? $item['name'] : old('name')) ?>" required></div><div class="form-group"><label class="form-label">Slug *</label><input type="text" name="slug" class="form-input" value="<?= e($isEdit ? $item['slug'] : old('slug')) ?>" required></div></div>
        <div class="form-group"><label class="form-label">Descrição</label><textarea name="description" class="form-input" rows="3"><?= e($isEdit ? $item['description'] : old('description')) ?></textarea></div>
        <div class="mgmt-form-row"><div class="form-group"><label class="form-label">Preço (R$)</label><input type="number" name="price" class="form-input" step="0.01" value="<?= e($isEdit ? $item['price'] : old('price')) ?>"></div><div class="form-group"><label class="form-label">Categoria</label><select name="category_id" class="form-select"><option value="">Nenhuma</option><?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>" <?= ($isEdit && $item['category_id'] == $c['id']) ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select></div></div>
        <div class="form-group"><label class="form-label">Features</label><textarea name="features" class="form-input" rows="3" placeholder="Uma por linha"><?= e($isEdit ? $item['features'] : old('features')) ?></textarea></div>
        <div class="mgmt-form-row"><div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><?php foreach (['active'=>'Ativo','inactive'=>'Inativo','coming_soon'=>'Em breve'] as $k=>$v): ?><option value="<?= $k ?>" <?= ($isEdit ? $item['status'] : 'active') === $k ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?></select></div><div class="form-group"><label class="form-label">Destaque</label><select name="is_featured" class="form-select"><option value="0" <?= ($isEdit && !$item['is_featured']) ? 'selected' : '' ?>>Não</option><option value="1" <?= ($isEdit && $item['is_featured']) ? 'selected' : '' ?>>Sim</option></select></div></div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary"><?= $isEdit ? 'Salvar' : 'Criar' ?></button><a href="<?= url('/admin/produtos') ?>" class="btn btn--secondary">Cancelar</a></div>
    </form>
</div>
<?php $__view->endSection(); ?>
