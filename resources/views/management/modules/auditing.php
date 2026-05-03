<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Auditoria Financeira</h1>
            <p class="mgmt-subtitle">Trilha completa de movimentações financeiras</p>
        </div>
        <button type="button" class="btn btn-secondary" onclick="window.print()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.5rem;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Exportar Relatório
        </button>
    </div>

    <div class="mgmt-card" style="margin-bottom: 1.5rem;">
        <div class="mgmt-card__body">
            <form method="GET" action="<?= url('/gestao/auditoria') ?>" style="display: grid; grid-template-columns: 1fr 200px 200px 200px auto; gap: 1rem; align-items: end;">
                <div>
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Descrição ou usuário" value="<?= e($search ?? '') ?>">
                </div>
                <div>
                    <label for="type" class="form-label">Tipo</label>
                    <select id="type" name="type" class="form-control">
                        <option value="">Todos</option>
                        <option value="receita" <?= ($type ?? '') === 'receita' ? 'selected' : '' ?>>Receita</option>
                        <option value="despesa" <?= ($type ?? '') === 'despesa' ? 'selected' : '' ?>>Despesa</option>
                        <option value="transferencia" <?= ($type ?? '') === 'transferencia' ? 'selected' : '' ?>>Transferência</option>
                    </select>
                </div>
                <div>
                    <label for="start_date" class="form-label">Data Inicial</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="<?= e($startDate ?? date('Y-m-01')) ?>">
                </div>
                <div>
                    <label for="end_date" class="form-label">Data Final</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="<?= e($endDate ?? date('Y-m-d')) ?>">
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-secondary">Filtrar</button>
                    <a href="<?= url('/gestao/auditoria') ?>" class="btn btn-secondary">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mgmt-stats" style="margin-bottom: 1.5rem;">
        <div class="stat-card">
            <div class="stat-card__icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
            </div>
            <div>
                <div class="stat-card__label">Total de Receitas</div>
                <div class="stat-card__value" style="color: #10b981;">R$ <?= number_format($totalRevenue, 2, ',', '.') ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
            </div>
            <div>
                <div class="stat-card__label">Total de Despesas</div>
                <div class="stat-card__value" style="color: #ef4444;">R$ <?= number_format($totalExpenses, 2, ',', '.') ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <div>
                <div class="stat-card__label">Saldo do Período</div>
                <div class="stat-card__value" style="color: <?= ($totalRevenue - $totalExpenses) >= 0 ? '#10b981' : '#ef4444' ?>;">
                    R$ <?= number_format($totalRevenue - $totalExpenses, 2, ',', '.') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($transactions)): ?>
                <div class="mgmt-empty">
                    <div class="mgmt-empty__icon">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    </div>
                    <h3 class="mgmt-empty__title">Nenhuma transação encontrada</h3>
                    <p class="mgmt-empty__text">Movimentações financeiras aparecem aqui assim que receitas e despesas forem registradas.</p>
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Tipo</th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Valor</th>
                            <th>Usuário</th>
                            <th style="width: 100px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td style="font-size: 0.875rem;">
                                    <?= !empty($transaction['created_at']) ? date('d/m/Y H:i', strtotime($transaction['created_at'])) : '-' ?>
                                </td>
                                <td>
                                    <?php 
                                        $typeClass = [
                                            'receita' => 'badge badge-success',
                                            'despesa' => 'badge badge-danger',
                                            'transferencia' => 'badge badge-info',
                                        ];
                                        $typeLabel = [
                                            'receita' => 'Receita',
                                            'despesa' => 'Despesa',
                                            'transferencia' => 'Transferência',
                                        ];
                                        $txType = $transaction['type'] ?? 'receita';
                                    ?>
                                    <span class="<?= $typeClass[$txType] ?? 'badge' ?>"><?= $typeLabel[$txType] ?? 'N/A' ?></span>
                                </td>
                                <td><strong><?= e($transaction['description'] ?? 'N/A') ?></strong></td>
                                <td><?= e($transaction['category'] ?? '-') ?></td>
                                <td>
                                    <strong style="color: <?= $txType === 'receita' ? '#10b981' : '#ef4444' ?>;">
                                        <?= $txType === 'receita' ? '+' : '-' ?> R$ <?= number_format((float)($transaction['amount'] ?? 0), 2, ',', '.') ?>
                                    </strong>
                                </td>
                                <td style="font-size: 0.875rem;"><?= e($transaction['user_name'] ?? '-') ?></td>
                                <td>
                                    <button type="button" class="btn-icon" title="Ver Detalhes" onclick="viewTransaction(<?= $transaction['id'] ?>)">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function viewTransaction(id) {
    alert('Detalhes da transação ID: ' + id);
}
</script>
<?php $__view->endSection(); ?>
