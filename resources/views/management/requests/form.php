<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Nova solicitação</h1></div></div>
<div class="mgmt-form-card">
    <form method="POST" action="<?= url('/gestao/solicitacoes') ?>" data-loading>
        <?= csrf_field() ?>
        <div class="form-group"><label class="form-label">Título *</label><input type="text" name="title" class="form-input" required></div>
        <div class="form-group"><label class="form-label">Descrição</label><textarea name="description" class="form-input" rows="3"></textarea></div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Tipo</label><select name="type" class="form-select"><option value="general">Geral</option><option value="prayer">Oração</option><option value="support">Apoio</option><option value="material">Material</option><option value="other">Outro</option></select></div>
            <div class="form-group"><label class="form-label">Prioridade</label><select name="priority" class="form-select"><option value="normal">Normal</option><option value="low">Baixa</option><option value="high">Alta</option><option value="urgent">Urgente</option></select></div>
        </div>
        <div class="form-group"><label class="form-label">Membro relacionado</label><select name="member_id" class="form-select"><option value="">Nenhum</option><?php foreach ($members as $m): ?><option value="<?= $m['id'] ?>"><?= e($m['name']) ?></option><?php endforeach; ?></select></div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary">Registrar</button><a href="<?= url('/gestao/solicitacoes') ?>" class="btn btn--ghost">Cancelar</a></div>
    </form>
</div>
<?php $__view->endSection(); ?>
