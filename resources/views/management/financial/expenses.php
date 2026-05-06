<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php
    $categories = is_array($categories ?? null) ? $categories : [];
    $units = is_array($units ?? null) ? $units : [];
?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Despesas</h1>
        <p class="mgmt-header__subtitle">Registre saídas e acompanhe a aprovação financeira antes da confirmação.</p>
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

<div class="mgmt-card mgmt-filter-card">
    <div class="mgmt-card__body">
        <form method="GET" action="<?= url('/gestao/despesas') ?>" class="mgmt-filter-grid mgmt-filter-grid--range">
            <input type="hidden" name="type" value="expense">
            <div class="mgmt-filter-field">
                <label for="expense_search" class="form-label">Buscar</label>
                <input type="text" id="expense_search" name="search" class="form-control" value="<?= e((string) ($filters['search'] ?? '')) ?>" placeholder="Descrição ou referência">
            </div>
            <div class="mgmt-filter-field">
                <label for="expense_status" class="form-label">Status</label>
                <select id="expense_status" name="status" class="form-control">
                    <option value="">Todas</option>
                    <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pendente</option>
                    <option value="confirmed" <?= ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmada</option>
                    <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelada</option>
                </select>
            </div>
            <div class="mgmt-filter-field">
                <label for="expense_start_date" class="form-label">Início</label>
                <input type="date" id="expense_start_date" name="start_date" class="form-control" value="<?= e((string) ($filters['start_date'] ?? date('Y-m-01'))) ?>">
            </div>
            <div class="mgmt-filter-field">
                <label for="expense_end_date" class="form-label">Fim</label>
                <input type="date" id="expense_end_date" name="end_date" class="form-control" value="<?= e((string) ($filters['end_date'] ?? date('Y-m-t'))) ?>">
            </div>
            <div class="mgmt-filter-actions">
                <button type="submit" class="btn btn-secondary">Filtrar</button>
                <a href="<?= url('/gestao/despesas') ?>" class="btn btn-secondary">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="mgmt-dashboard-card" style="padding:0;overflow:hidden;">
    <?php if (empty($transactions)): ?>
        <div class="mgmt-empty">
            <div class="mgmt-empty__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M6 9h.01M18 15h.01"></path>
                </svg>
            </div>
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
                </div>
                <div class="form-group"><label class="form-label">Descrição *</label><input type="text" name="description" class="form-input" required></div>
                <div class="form-group"><label class="form-label">Referência</label><input type="text" name="reference" class="form-input" placeholder="NF, recibo, etc."></div>
                <p class="form-hint">Toda nova despesa entra como pendente e aparece em Aprovações para o responsável financeiro aprovar ou rejeitar.</p>
                <div class="form-group"><label class="form-label">Notas</label><textarea name="notes" class="form-input" rows="2"></textarea></div>
            </div>
            <div class="modal__footer"><button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button><button type="submit" class="btn btn--primary">Registrar</button></div>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
