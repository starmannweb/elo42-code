<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Novo sermão</h1></div></div>
<div class="mgmt-form-card">
    <form method="POST" action="<?= url('/gestao/sermoes') ?>" data-loading><?= csrf_field() ?>
        <div class="form-group"><label class="form-label">Título *</label><input type="text" name="title" class="form-input" required></div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Pregador</label><input type="text" name="preacher" class="form-input"></div>
            <div class="form-group"><label class="form-label">Data</label><input type="date" name="sermon_date" class="form-input" value="<?= date('Y-m-d') ?>"></div>
        </div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Referência bíblica</label><input type="text" name="bible_reference" class="form-input" placeholder="Ex: João 3:16"></div>
            <div class="form-group"><label class="form-label">Série</label><input type="text" name="series_name" class="form-input" placeholder="Nome da série"></div>
        </div>
        <div class="form-group"><label class="form-label">Resumo</label><textarea name="summary" class="form-input" rows="4"></textarea></div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Tags</label><input type="text" name="tags" class="form-input" placeholder="fé, esperança, amor"></div>
            <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="draft">Rascunho</option><option value="published">Publicado</option></select></div>
        </div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary">Registrar</button><a href="<?= url('/gestao/sermoes') ?>" class="btn btn--ghost">Cancelar</a></div>
    </form>
</div>
<?php $__view->endSection(); ?>
