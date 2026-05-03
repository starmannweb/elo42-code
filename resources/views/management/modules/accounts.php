<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Contas e Caixa</h1>
            <p class="mgmt-subtitle">Gerencie contas bancárias e saldos</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addAccountModal').style.display='flex'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.5rem;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Nova Conta
        </button>
    </div>

    <div class="mgmt-stats" style="margin-bottom: 1.5rem;">
        <div class="stat-card">
            <div class="stat-card__icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <div>
                <div class="stat-card__label">Saldo Total</div>
                <div class="stat-card__value" style="color: #10b981;">R$ <?= number_format($totalBalance, 2, ',', '.') ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2.5" y="5" width="19" height="14" rx="2"></rect><path d="M2.5 9h19"></path></svg>
            </div>
            <div>
                <div class="stat-card__label">Contas Ativas</div>
                <div class="stat-card__value"><?= count(array_filter($accounts, fn($a) => ($a['status'] ?? '') === 'active')) ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            </div>
            <div>
                <div class="stat-card__label">Contas Bancárias</div>
                <div class="stat-card__value"><?= count(array_filter($accounts, fn($a) => ($a['type'] ?? '') === 'bank')) ?></div>
            </div>
        </div>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($accounts)): ?>
                <div class="mgmt-empty">
                    <div class="mgmt-empty__icon">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="5" width="19" height="14" rx="2"></rect><path d="M16 12h.01"></path><path d="M2.5 9h19"></path></svg>
                    </div>
                    <h3 class="mgmt-empty__title">Nenhuma conta cadastrada</h3>
                    <p class="mgmt-empty__text">Cadastre as contas bancárias e caixas para acompanhar saldos e fluxo financeiro.</p>
                </div>
            <?php else: ?>
                <div style="display: grid; gap: 1.5rem;">
                    <?php foreach ($accounts as $account): ?>
                        <div class="mgmt-card" style="border: 1px solid var(--hub-border); background: var(--hub-bg-secondary);">
                            <div class="mgmt-card__body">
                                <div style="display: flex; justify-content: space-between; align-items: start;">
                                    <div style="flex: 1;">
                                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem;">
                                            <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                                                    <?php if (($account['type'] ?? '') === 'bank'): ?>
                                                        <rect x="2.5" y="5" width="19" height="14" rx="2"></rect>
                                                        <path d="M2.5 9h19"></path>
                                                    <?php else: ?>
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                        <polyline points="7 10 12 15 17 10"></polyline>
                                                    <?php endif; ?>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600;"><?= e($account['name'] ?? 'Sem nome') ?></h3>
                                                <p style="margin: 0.25rem 0 0; color: var(--hub-text-secondary); font-size: 0.875rem;">
                                                    <?php
                                                        $typeLabels = ['bank' => 'Conta Bancária', 'cash' => 'Caixa', 'savings' => 'Poupança'];
                                                        echo $typeLabels[$account['type'] ?? 'cash'] ?? 'Conta';
                                                    ?>
                                                    <?php if (!empty($account['bank_name'])): ?>
                                                        • <?= e($account['bank_name']) ?>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1rem;">
                                            <div>
                                                <div style="font-size: 0.75rem; color: var(--hub-text-tertiary); margin-bottom: 0.25rem;">Saldo Atual</div>
                                                <div style="font-size: 1.5rem; font-weight: 700; color: <?= (float)($account['balance'] ?? 0) >= 0 ? '#10b981' : '#ef4444' ?>;">
                                                    R$ <?= number_format((float)($account['balance'] ?? 0), 2, ',', '.') ?>
                                                </div>
                                            </div>
                                            <?php if (!empty($account['account_number'])): ?>
                                                <div>
                                                    <div style="font-size: 0.75rem; color: var(--hub-text-tertiary); margin-bottom: 0.25rem;">Conta/Agência</div>
                                                    <div style="font-size: 0.875rem; font-weight: 600;">
                                                        <?= e($account['account_number']) ?>
                                                        <?php if (!empty($account['agency'])): ?>
                                                            / <?= e($account['agency']) ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                                            <?php
                                                $statusClass = [
                                                    'active' => 'badge badge-success',
                                                    'inactive' => 'badge badge-secondary',
                                                ];
                                                $statusLabel = [
                                                    'active' => 'Ativa',
                                                    'inactive' => 'Inativa',
                                                ];
                                                $currentStatus = $account['status'] ?? 'active';
                                            ?>
                                            <span class="<?= $statusClass[$currentStatus] ?? 'badge' ?>"><?= $statusLabel[$currentStatus] ?? 'N/A' ?></span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: flex; gap: 0.5rem; margin-left: 1rem;">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="viewTransactions(<?= $account['id'] ?>)">Ver Movimentações</button>
                                        <button type="button" class="btn-icon" title="Editar" onclick="editAccount(<?= $account['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button type="button" class="btn-icon btn-icon--danger" title="Remover" onclick="removeAccount(<?= $account['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="addAccountModal" class="modal" style="display:none;">
    <div class="modal__content" style="max-width: 600px;">
        <div class="modal__header">
            <h2 class="modal__title">Nova Conta</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('addAccountModal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/contas/nova') ?>">
            <input type="hidden" name="csrf_token" value="<?= e($csrf ?? '') ?>">
            <div class="modal__body">
                <div class="form-group">
                    <label for="name" class="form-label">Nome da Conta <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Ex: Conta Corrente Principal" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="type" class="form-label">Tipo <span style="color: var(--danger);">*</span></label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="bank">Conta Bancária</option>
                            <option value="cash">Caixa</option>
                            <option value="savings">Poupança</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="balance" class="form-label">Saldo Inicial</label>
                        <input type="number" step="0.01" id="balance" name="balance" class="form-control" placeholder="0.00" value="0">
                    </div>
                </div>
                <div class="form-group">
                    <label for="bank_name" class="form-label">Banco</label>
                    <input type="text" id="bank_name" name="bank_name" class="form-control" placeholder="Ex: Banco do Brasil">
                </div>
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="account_number" class="form-label">Número da Conta</label>
                        <input type="text" id="account_number" name="account_number" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="agency" class="form-label">Agência</label>
                        <input type="text" id="agency" name="agency" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addAccountModal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn-primary">Criar Conta</button>
            </div>
        </form>
    </div>
</div>

<script>
function viewTransactions(id) {
    window.location.href = '<?= url('/gestao/contas/') ?>' + id + '/movimentacoes';
}

function editAccount(id) {
    alert('Funcionalidade de edição em desenvolvimento. ID: ' + id);
}

function removeAccount(id) {
    if (confirm('Deseja realmente remover esta conta?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/gestao/contas/') ?>' + id + '/remover';
        form.innerHTML = '<input type="hidden" name="csrf_token" value="<?= e($csrf ?? '') ?>">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__view->endSection(); ?>
