<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php
    $categories = is_array($categories ?? null) ? $categories : [];
    $units = is_array($units ?? null) ? $units : [];
?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Despesas</h1>
        <p class="mgmt-header__subtitle">Acompanhe as saídas financeiras da igreja.</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-expense').style.display='flex'">Nova despesa</button>
    </div>
</div>

<div class="mgmt-kpi-grid" style="grid-template-columns: repeat(3, minmax(0, 1fr));">
    <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Despesas</div><div class="mgmt-kpi-card__value" style="color:#ef4444;">R$ <?= number_format((float) ($summary['expense'] ?? 0), 2, ',', '.') ?></div></div></div>
    <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Receitas no período</div><div class="mgmt-kpi-card__value" style="color:#10b981;">R$ <?= number_format((float) ($summary['income'] ?? 0), 2, ',', '.') ?></div></div></div>
    <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Saldo</div><div class="mgmt-kpi-card__value">R$ <?= number_format((float) ($summary['balance'] ?? 0), 2, ',', '.') ?></div></div></div>
</div>

<div class="mgmt-dashboard-card" style="padding:0;overflow:hidden;">
    <?php if (empty($transactions)): ?>
        <div class="mgmt-empty">
            <div class="mgmt-empty__icon">R$</div>
            <h3 class="mgmt-empty__title">Nenhuma despesa registrada</h3>
            <p class="mgmt-empty__text">Registre as saídas para manter o financeiro em dia.</p>
            <button type="button" onclick="document.getElementById('modal-new-expense').style.display='flex'" class="btn btn--primary">Registrar despesa</button>
        </div>
    <?php else: ?>
        <table class="mgmt-table">
            <thead><tr><th>Descrição</th><th>Categoria</th><th>Data</th><th style="text-align:right;">Valor</th></tr></thead>
            <tbody>
                <?php foreach ($transactions as $item): ?>
                    <tr>
                        <td><div class="mgmt-table__name"><?= e((string) ($item['description'] ?? 'Despesa')) ?></div></td>
                        <td><?= !empty($item['category_name']) ? e((string) $item['category_name']) : '-' ?></td>
                        <td><?= !empty($item['transaction_date']) ? date('d/m/Y', strtotime((string) $item['transaction_date'])) : '-' ?></td>
                        <td style="text-align:right;font-weight:700;color:#ef4444;">R$ <?= number_format((float) ($item['amount'] ?? 0), 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="modal" id="modal-new-expense" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-new-expense-title">
    <div class="modal__content">
        <div class="modal__header"><h2 class="modal__title" id="modal-new-expense-title">Registrar despesa</h2><button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'">&times;</button></div>
        <form method="POST" action="<?= url('/gestao/financeiro') ?>" data-loading>
            <?= csrf_field() ?>
            <input type="hidden" name="type" value="expense">
            <div class="modal__body">
                <div class="modal-grid">
                    <div class="form-group"><label class="form-label">Data *</label><input type="date" name="transaction_date" class="form-input" value="<?= date('Y-m-d') ?>" required></div>
                    <div class="form-group"><label class="form-label">Valor (R$) *</label><input type="number" name="amount" class="form-input" step="0.01" min="0.01" required></div>
                    <div class="form-group"><label class="form-label">Categoria</label><select name="category_id" class="form-select"><option value="">Nenhuma</option><?php foreach ($categories as $c): ?><?php if (($c['type'] ?? '') === 'expense'): ?><option value="<?= (int) $c['id'] ?>"><?= e((string) $c['name']) ?></option><?php endif; ?><?php endforeach; ?></select></div>
                    <div class="form-group"><label class="form-label">Unidade</label><select name="church_unit_id" class="form-select"><option value="">Sede / todas as unidades</option><?php foreach ($units as $unit): ?><option value="<?= (int) $unit['id'] ?>"><?= e((string) $unit['name']) ?></option><?php endforeach; ?></select></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="confirmed">Confirmado</option><option value="pending">Pendente</option></select></div>
                </div>
                <div class="form-group"><label class="form-label">Descrição *</label><input type="text" name="description" class="form-input" required></div>
                <div class="form-group"><label class="form-label">Referência</label><input type="text" name="reference" class="form-input" placeholder="NF, recibo, etc."></div>
                <div class="form-group"><label class="form-label">Notas</label><textarea name="notes" class="form-input" rows="2"></textarea></div>
            </div>
            <div class="modal__footer"><button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button><button type="submit" class="btn btn--primary">Registrar</button></div>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
