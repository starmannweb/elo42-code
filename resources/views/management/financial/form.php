<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Nova transação financeira</h1></div></div>
<div class="mgmt-form-card">
    <form method="POST" action="<?= url('/gestao/financeiro') ?>" data-loading>
        <?= csrf_field() ?>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Tipo *</label><select name="type" class="form-select" required><option value="income">Entrada</option><option value="expense">Saída</option></select></div>
            <div class="form-group"><label class="form-label">Data *</label><input type="date" name="transaction_date" class="form-input" value="<?= date('Y-m-d') ?>" required></div>
        </div>
        <div class="form-group"><label class="form-label">Descrição *</label><input type="text" name="description" class="form-input" value="<?= e(old('description')) ?>" required></div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Valor (R$) *</label><input type="number" name="amount" class="form-input" step="0.01" min="0.01" value="<?= e(old('amount')) ?>" required></div>
            <div class="form-group"><label class="form-label">Categoria</label><select name="category_id" class="form-select"><option value="">Nenhuma</option><?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['name']) ?> (<?= $c['type'] === 'income' ? 'Entrada' : 'Saída' ?>)</option><?php endforeach; ?></select></div>
        </div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Referência</label><input type="text" name="reference" class="form-input" value="<?= e(old('reference')) ?>" placeholder="NF, recibo, etc."></div>
            <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="confirmed">Confirmado</option><option value="pending">Pendente</option></select></div>
        </div>
        <div class="form-group"><label class="form-label">Notas</label><textarea name="notes" class="form-input" rows="2"><?= e(old('notes')) ?></textarea></div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary">Registrar transação</button><a href="<?= url('/gestao/financeiro') ?>" class="btn btn--ghost">Cancelar</a></div>
    </form>
</div>
<?php $__view->endSection(); ?>
