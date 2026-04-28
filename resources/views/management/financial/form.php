<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php
    $selectedType = $_GET['type'] ?? old('type') ?: 'income';
    $units = is_array($units ?? null) ? $units : [];
?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Nova transação financeira</h1>
        <p class="mgmt-header__subtitle">Registre uma receita ou despesa da organização.</p>
    </div>
</div>

<div class="mgmt-form-card">
    <form method="POST" action="<?= url('/gestao/financeiro') ?>" data-loading>
        <?= csrf_field() ?>
        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">Tipo *</label>
                <select name="type" class="form-select" required>
                    <option value="income" <?= $selectedType === 'income' ? 'selected' : '' ?>>Entrada</option>
                    <option value="expense" <?= $selectedType === 'expense' ? 'selected' : '' ?>>Saída</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Data *</label>
                <input type="date" name="transaction_date" class="form-input" value="<?= date('Y-m-d') ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Unidade</label>
            <select name="church_unit_id" class="form-select">
                <option value="">Sede / todas as unidades</option>
                <?php foreach ($units as $unit): ?>
                    <option value="<?= (int) $unit['id'] ?>"><?= e((string) $unit['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Descrição *</label>
            <input type="text" name="description" class="form-input" value="<?= e(old('description')) ?>" required>
        </div>
        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">Valor (R$) *</label>
                <input type="number" name="amount" class="form-input" step="0.01" min="0.01" value="<?= e(old('amount')) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Categoria</label>
                <select name="category_id" class="form-select">
                    <option value="">Nenhuma</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= e($c['name']) ?> (<?= $c['type'] === 'income' ? 'Entrada' : 'Saída' ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">Referência</label>
                <input type="text" name="reference" class="form-input" value="<?= e(old('reference')) ?>" placeholder="NF, recibo, etc.">
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="confirmed">Confirmado</option>
                    <option value="pending">Pendente</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Notas</label>
            <textarea name="notes" class="form-input" rows="2"><?= e(old('notes')) ?></textarea>
        </div>
        <div class="mgmt-form-actions">
            <button type="submit" class="btn btn--primary">Registrar transação</button>
            <a href="<?= url($selectedType === 'expense' ? '/gestao/despesas' : '/gestao/receitas') ?>" class="btn btn--ghost">Cancelar</a>
        </div>
    </form>
</div>
<?php $__view->endSection(); ?>
