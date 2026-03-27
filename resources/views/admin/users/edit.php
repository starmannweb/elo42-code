<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Editar usuário</h1></div></div>
<div class="mgmt-form-card" style="max-width:600px;">
    <form method="POST" action="<?= url('/admin/usuarios/' . $user['id'] . '/editar') ?>"><?= csrf_field() ?>
        <div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" value="<?= e($user['name']) ?>" required></div>
        <div class="form-group"><label class="form-label">E-mail *</label><input type="email" name="email" class="form-input" value="<?= e($user['email']) ?>" required></div>
        <div class="form-group"><label class="form-label">Telefone</label><input type="tel" name="phone" class="form-input" value="<?= e($user['phone'] ?? '') ?>"></div>
        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" <?= $user['status']==='active'?'selected':'' ?>>Ativo</option><option value="inactive" <?= $user['status']==='inactive'?'selected':'' ?>>Inativo</option><option value="suspended" <?= $user['status']==='suspended'?'selected':'' ?>>Suspenso</option></select></div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary">Salvar</button><a href="<?= url('/admin/usuarios/' . $user['id']) ?>" class="btn btn--secondary">Cancelar</a></div>
    </form>
</div>
<?php $__view->endSection(); ?>
