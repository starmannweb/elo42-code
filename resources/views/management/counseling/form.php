<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Novo atendimento</h1></div></div>
<div class="mgmt-form-card">
    <form method="POST" action="<?= url('/gestao/aconselhamento') ?>" data-loading><?= csrf_field() ?>
        <div class="form-group"><label class="form-label">Assunto *</label><input type="text" name="subject" class="form-input" required></div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Membro</label><select name="member_id" class="form-select"><option value="">Não informado</option><?php foreach ($members as $m): ?><option value="<?= $m['id'] ?>"><?= e($m['name']) ?></option><?php endforeach; ?></select></div>
            <div class="form-group"><label class="form-label">Conselheiro</label><input type="text" name="counselor_name" class="form-input"></div>
        </div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Data *</label><input type="date" name="session_date" class="form-input" value="<?= date('Y-m-d') ?>" required></div>
            <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="scheduled">Agendada</option><option value="completed">Concluída</option></select></div>
        </div>
        <div class="form-group"><label class="form-label">Notas</label><textarea name="notes" class="form-input" rows="3"></textarea></div>
        <div class="form-group"><label class="form-checkbox"><input type="checkbox" name="is_confidential" value="1" checked><span class="form-checkbox__label">Sessão confidencial</span></label></div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary">Registrar</button><a href="<?= url('/gestao/aconselhamento') ?>" class="btn btn--ghost">Cancelar</a></div>
    </form>
</div>
<?php $__view->endSection(); ?>
