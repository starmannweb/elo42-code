<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Nova visita</h1></div></div>
<div class="mgmt-form-card">
    <form method="POST" action="<?= url('/gestao/visitas') ?>" data-loading><?= csrf_field() ?>
        <div class="form-group"><label class="form-label">Nome do visitante *</label><input type="text" name="visitor_name" class="form-input" required></div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Telefone</label><input type="tel" name="phone" class="form-input"></div>
            <div class="form-group"><label class="form-label">E-mail</label><input type="email" name="email" class="form-input"></div>
        </div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Data da visita *</label><input type="date" name="visit_date" class="form-input" value="<?= date('Y-m-d') ?>" required></div>
            <div class="form-group"><label class="form-label">Origem</label><select name="source" class="form-select"><option value="spontaneous">Espontâneo</option><option value="invited">Convidado</option><option value="event">Evento</option><option value="online">Online</option><option value="other">Outro</option></select></div>
        </div>
        <div class="form-group"><label class="form-label">Responsável pelo acompanhamento</label><select name="assigned_to" class="form-select"><option value="">Nenhum</option><?php foreach ($members as $m): ?><option value="<?= $m['id'] ?>"><?= e($m['name']) ?></option><?php endforeach; ?></select></div>
        <div class="form-group"><label class="form-label">Observações</label><textarea name="notes" class="form-input" rows="2"></textarea></div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary">Registrar</button><a href="<?= url('/gestao/visitas') ?>" class="btn btn--ghost">Cancelar</a></div>
    </form>
</div>
<?php $__view->endSection(); ?>
