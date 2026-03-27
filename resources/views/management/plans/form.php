<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Novo plano de ação</h1></div></div>
<div class="mgmt-form-card">
    <form method="POST" action="<?= url('/gestao/planos') ?>" data-loading><?= csrf_field() ?>
        <div class="form-group"><label class="form-label">Título *</label><input type="text" name="title" class="form-input" required></div>
        <div class="form-group"><label class="form-label">Descrição</label><textarea name="description" class="form-input" rows="3"></textarea></div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Data de início</label><input type="date" name="start_date" class="form-input" value="<?= date('Y-m-d') ?>"></div>
            <div class="form-group"><label class="form-label">Data de término</label><input type="date" name="end_date" class="form-input"></div>
        </div>
        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="planning">Planejamento</option><option value="active">Ativo</option></select></div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary">Criar plano</button><a href="<?= url('/gestao/planos') ?>" class="btn btn--ghost">Cancelar</a></div>
    </form>
</div>
<?php $__view->endSection(); ?>
