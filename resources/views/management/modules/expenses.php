<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Aprovações de Despesas</h1>
            <p class="mgmt-subtitle">Controle e aprove despesas da igreja</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addExpenseModal').style.display='flex'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.5rem;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Nova Despesa
        </button>
    </div>

    <div class="mgmt-stats" style="margin-bottom: 1.5rem;">
        <div class="stat-card">
            <div class="stat-card__icon" style="background: rgba(251, 191, 36, 0.1); color: #fbbf24;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div>
                <div class="stat-card__label">Pendentes</div>
                <div class="stat-card__value"><?= count(array_filter($expenses, fn($e) => ($e['status'] ?? '') === 'pending')) ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div>
                <div class="stat-card__label">Aprovadas</div>
                <div class="stat-card__value"><?= count(array_filter($expenses, fn($e) => ($e['status'] ?? '') === 'approved')) ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            </div>
            <div>
                <div class="stat-card__label">Rejeitadas</div>
                <div class="stat-card__value"><?= count(array_filter($expenses, fn($e) => ($e['status'] ?? '') === 'rejected')) ?></div>
            </div>
        </div>
    </div>

    <div class="mgmt-card" style="margin-bottom: 1.5rem;">
        <div class="mgmt-card__body">
            <form method="GET" action="<?= url('/gestao/aprovacoes-despesas') ?>" style="display: grid; grid-template-columns: 1fr 200px 200px auto; gap: 1rem; align-items: end;">
                <div>
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Descrição ou fornecedor" value="<?= e($search ?? '') ?>">
                </div>
                <div>
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">Todos</option>
                        <option value="pending" <?= ($status ?? '') === 'pending' ? 'selected' : '' ?>>Pendente</option>
                        <option value="approved" <?= ($status ?? '') === 'approved' ? 'selected' : '' ?>>Aprovado</option>
                        <option value="rejected" <?= ($status ?? '') === 'rejected' ? 'selected' : '' ?>>Rejeitado</option>
                    </select>
                </div>
                <div>
                    <label for="month" class="form-label">Período</label>
                    <input type="month" id="month" name="month" class="form-control" value="<?= e($month ?? date('Y-m')) ?>">
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-secondary">Filtrar</button>
                    <a href="<?= url('/gestao/aprovacoes-despesas') ?>" class="btn btn-secondary">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($expenses)): ?>
                <div style="text-align: center; padding: 3rem 1rem; color: var(--hub-text-tertiary);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 1rem; opacity: 0.4;">
                        <path d="M9 14l6-6"></path>
                        <circle cx="9.5" cy="8.5" r="1.5"></circle>
                        <circle cx="14.5" cy="13.5" r="1.5"></circle>
                        <rect x="2" y="2" width="20" height="20" rx="2.5"></rect>
                    </svg>
                    <p style="font-size: 0.875rem;">Nenhuma despesa registrada</p>
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Fornecedor</th>
                            <th>Valor</th>
                            <th>Data</th>
                            <th>Solicitante</th>
                            <th>Status</th>
                            <th style="width: 150px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td><strong><?= e($expense['description'] ?? 'N/A') ?></strong></td>
                                <td><?= e($expense['supplier'] ?? '-') ?></td>
                                <td>
                                    <strong style="color: var(--danger);">R$ <?= number_format((float)($expense['amount'] ?? 0), 2, ',', '.') ?></strong>
                                </td>
                                <td><?= !empty($expense['expense_date']) ? date('d/m/Y', strtotime($expense['expense_date'])) : '-' ?></td>
                                <td><?= e($expense['requester_name'] ?? '-') ?></td>
                                <td>
                                    <?php 
                                        $statusClass = [
                                            'pending' => 'badge badge-warning',
                                            'approved' => 'badge badge-success',
                                            'rejected' => 'badge badge-danger',
                                        ];
                                        $statusLabel = [
                                            'pending' => 'Pendente',
                                            'approved' => 'Aprovado',
                                            'rejected' => 'Rejeitado',
                                        ];
                                        $currentStatus = $expense['status'] ?? 'pending';
                                    ?>
                                    <span class="<?= $statusClass[$currentStatus] ?? 'badge' ?>"><?= $statusLabel[$currentStatus] ?? 'N/A' ?></span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <?php if ($currentStatus === 'pending'): ?>
                                            <button type="button" class="btn btn-sm btn-success" onclick="approveExpense(<?= $expense['id'] ?>)" title="Aprovar">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="rejectExpense(<?= $expense['id'] ?>)" title="Rejeitar">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn-icon" title="Ver Detalhes" onclick="viewExpense(<?= $expense['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="addExpenseModal" class="modal" style="display:none;">
    <div class="modal__content" style="max-width: 600px;">
        <div class="modal__header">
            <h2 class="modal__title">Nova Despesa</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('addExpenseModal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/aprovacoes-despesas/nova') ?>">
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="form-group">
                    <label for="description" class="form-label">Descrição <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="description" name="description" class="form-control" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="supplier" class="form-label">Fornecedor</label>
                        <input type="text" id="supplier" name="supplier" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="amount" class="form-label">Valor <span style="color: var(--danger);">*</span></label>
                        <input type="number" step="0.01" id="amount" name="amount" class="form-control" placeholder="0.00" required>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="expense_date" class="form-label">Data <span style="color: var(--danger);">*</span></label>
                        <input type="date" id="expense_date" name="expense_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="requester_name" class="form-label">Solicitante</label>
                        <input type="text" id="requester_name" name="requester_name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes" class="form-label">Observações</label>
                    <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addExpenseModal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn-primary">Criar Despesa</button>
            </div>
        </form>
    </div>
</div>

<script>
function approveExpense(id) {
    if (confirm('Deseja aprovar esta despesa?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/gestao/aprovacoes-despesas/') ?>' + id + '/aprovar';
        form.innerHTML = '<input type="hidden" name="_csrf_token" value="<?= e(csrf_token()) ?>">';
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectExpense(id) {
    if (confirm('Deseja rejeitar esta despesa?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/gestao/aprovacoes-despesas/') ?>' + id + '/rejeitar';
        form.innerHTML = '<input type="hidden" name="_csrf_token" value="<?= e(csrf_token()) ?>">';
        document.body.appendChild(form);
        form.submit();
    }
}

function viewExpense(id) {
    window.location.href = '<?= url('/gestao/aprovacoes-despesas/') ?>' + id;
}
</script>
<?php $__view->endSection(); ?>
