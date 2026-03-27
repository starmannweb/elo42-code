<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Financeiro</h1></div><div class="mgmt-header__actions"><a href="<?= url('/gestao/financeiro/novo') ?>" class="btn btn--primary">+ Nova transação</a></div></div>

<div class="financial-summary">
    <div class="financial-summary__card"><div class="financial-summary__label">Entradas</div><div class="financial-summary__value financial-summary__value--income">R$ <?= number_format($summary['income'], 2, ',', '.') ?></div></div>
    <div class="financial-summary__card"><div class="financial-summary__label">Saídas</div><div class="financial-summary__value financial-summary__value--expense">R$ <?= number_format($summary['expense'], 2, ',', '.') ?></div></div>
    <div class="financial-summary__card"><div class="financial-summary__label">Saldo</div><div class="financial-summary__value financial-summary__value--balance">R$ <?= number_format($summary['balance'], 2, ',', '.') ?></div></div>
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
