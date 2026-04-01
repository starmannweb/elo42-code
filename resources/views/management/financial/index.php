<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Financeiro</h1>
        <p class="mgmt-header__subtitle">Controle de receitas e despesas da igreja</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--outline" onclick="window.location.href='<?= url('/gestao/financeiro/novo') ?>'">📤 Exportar</button>
        <button type="button" class="btn btn--success" onclick="window.location.href='<?= url('/gestao/financeiro/novo') ?>'">✅ Receita</button>
        <button type="button" class="btn btn--danger" onclick="window.location.href='<?= url('/gestao/financeiro/novo') ?>'">❌ Despesa</button>
    </div>
</div>

<div class="mgmt-kpi-grid" style="grid-template-columns: repeat(3, 1fr);">
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green" style="background: rgba(16, 185, 129, 0.1);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path><polyline points="7 17 12 22 17 17" stroke="#10b981"></polyline></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Receitas</div>
            <div class="mgmt-kpi-card__value" style="color: #10b981;">R$ <?= number_format($summary['income'], 2, ',', '.') ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Este mês</div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--red" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path><polyline points="7 7 12 2 17 7" stroke="#ef4444"></polyline></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Despesas</div>
            <div class="mgmt-kpi-card__value" style="color: #ef4444;">R$ <?= number_format($summary['expense'], 2, ',', '.') ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Este mês</div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--gold">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Saldo</div>
            <div class="mgmt-kpi-card__value">R$ <?= number_format($summary['balance'], 2, ',', '.') ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Resultado do mês</div>
        </div>
    </div>
</div>

<form method="GET" action="<?= url('/gestao/financeiro') ?>" class="mgmt-filters">
    <select name="type" class="form-select"><option value="">Todos</option><option value="income" <?= ($filters['type'] ?? '') === 'income' ? 'selected' : '' ?>>Entradas</option><option value="expense" <?= ($filters['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Saídas</option></select>
    <input type="date" name="start_date" class="form-input" value="<?= e($filters['start_date'] ?? '') ?>">
    <input type="date" name="end_date" class="form-input" value="<?= e($filters['end_date'] ?? '') ?>">
    <button type="submit" class="btn btn--ghost">Filtrar</button>
</form>

<?php if (empty($transactions)): ?>
    <div class="mgmt-empty"><div class="mgmt-empty__icon">💰</div><h3 class="mgmt-empty__title">Nenhuma transação</h3><p class="mgmt-empty__text">Registre a primeira movimentação financeira.</p><a href="<?= url('/gestao/financeiro/novo') ?>" class="btn btn--primary">Registrar</a></div>
<?php else: ?>
    <div class="mgmt-table-container">
        <table class="mgmt-table"><thead><tr><th>Data</th><th>Descrição</th><th>Categoria</th><th>Tipo</th><th>Valor</th><th>Status</th></tr></thead><tbody>
            <?php foreach ($transactions as $t): ?><tr>
                <td><?= date('d/m/Y', strtotime($t['transaction_date'])) ?></td>
                <td><div class="mgmt-table__name"><?= e($t['description']) ?></div><?php if ($t['reference']): ?><div class="mgmt-table__sub"><?= e($t['reference']) ?></div><?php endif; ?></td>
                <td><?php if ($t['category_name']): ?><span style="display:inline-flex;align-items:center;gap:4px;"><span style="width:8px;height:8px;border-radius:50%;background:<?= e($t['category_color'] ?? '#ccc') ?>;"></span><?= e($t['category_name']) ?></span><?php else: ?>—<?php endif; ?></td>
                <td><span class="badge badge--<?= $t['type'] ?>"><?= $t['type'] === 'income' ? 'Entrada' : 'Saída' ?></span></td>
                <td class="font-bold <?= $t['type'] === 'income' ? 'text-success' : 'text-error' ?>">R$ <?= number_format((float)$t['amount'], 2, ',', '.') ?></td>
                <td><span class="badge badge--<?= $t['status'] ?>"><?= ucfirst(e($t['status'])) ?></span></td>
            </tr><?php endforeach; ?>
        </tbody></table>
        <?php if ($pagination['totalPages'] > 1): ?><div class="mgmt-pagination"><?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?><?php if ($i === $pagination['page']): ?><span class="current"><?= $i ?></span><?php else: ?><a href="<?= url('/gestao/financeiro?page=' . $i . '&type=' . ($filters['type']??'') . '&start_date=' . ($filters['start_date']??'') . '&end_date=' . ($filters['end_date']??'')) ?>"><?= $i ?></a><?php endif; ?><?php endfor; ?></div><?php endif; ?>
    </div>
<?php endif; ?>
<?php $__view->endSection(); ?>
