<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Editar instituição</h1></div></div>
<div class="mgmt-form-card" style="max-width:640px;">
    <form method="POST" action="<?= url('/admin/organizacoes/' . $org['id'] . '/editar') ?>"><?= csrf_field() ?>
        <div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" value="<?= e($org['name']) ?>" required></div>
        <div class="mgmt-form-row"><div class="form-group"><label class="form-label">Razão social</label><input type="text" name="legal_name" class="form-input" value="<?= e($org['legal_name'] ?? '') ?>"></div><div class="form-group"><label class="form-label">CNPJ</label><input type="text" name="cnpj" class="form-input" value="<?= e($org['cnpj'] ?? '') ?>"></div></div>
        <div class="mgmt-form-row"><div class="form-group"><label class="form-label">Telefone</label><input type="tel" name="phone" class="form-input" value="<?= e($org['phone'] ?? '') ?>"></div><div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" <?= $org['status']==='active'?'selected':'' ?>>Ativa</option><option value="trial" <?= $org['status']==='trial'?'selected':'' ?>>Trial</option><option value="inactive" <?= $org['status']==='inactive'?'selected':'' ?>>Inativa</option><option value="suspended" <?= $org['status']==='suspended'?'selected':'' ?>>Suspensa</option></select></div></div>
        <div class="mgmt-form-row"><div class="form-group"><label class="form-label">Cidade</label><input type="text" name="city" class="form-input" value="<?= e($org['city'] ?? '') ?>"></div><div class="form-group"><label class="form-label">Estado</label><input type="text" name="state" class="form-input" value="<?= e($org['state'] ?? '') ?>"></div></div>
        <div class="form-group"><label class="form-label">Plano</label><select name="plan" class="form-select"><option value="free" <?= ($org['plan']??'')==='free'?'selected':'' ?>>Gratuito</option><option value="starter" <?= ($org['plan']??'')==='starter'?'selected':'' ?>>Starter</option><option value="professional" <?= ($org['plan']??'')==='professional'?'selected':'' ?>>Professional</option><option value="enterprise" <?= ($org['plan']??'')==='enterprise'?'selected':'' ?>>Enterprise</option></select></div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary">Salvar</button><a href="<?= url('/admin/organizacoes/' . $org['id']) ?>" class="btn btn--secondary">Cancelar</a></div>
    </form>
</div>
<?php $__view->endSection(); ?>
