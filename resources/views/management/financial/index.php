<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Financeiro</h1>
        <p class="mgmt-header__subtitle">Controle de receitas e despesas da igreja</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--outline" style="display:flex;align-items:center;gap:6px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Exportar</button>
        <button type="button" class="btn btn--primary" style="background:#10b981; border-color:#10b981; display:flex;align-items:center;gap:6px;" onclick="window.location.href='<?= url('/gestao/financeiro/novo?type=income') ?>'"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Receita</button>
        <button type="button" class="btn btn--primary" style="background:#ef4444; border-color:#ef4444; display:flex;align-items:center;gap:6px;" onclick="window.location.href='<?= url('/gestao/financeiro/novo?type=expense') ?>'"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="7 7 12 2 17 7"></polyline><line x1="12" y1="2" x2="12" y2="16"></line></svg> Despesa</button>
    </div>
</div>

<div class="mgmt-kpi-grid" style="grid-template-columns: repeat(3, 1fr);">
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">Receitas</div>
            <div class="mgmt-kpi-card__value" style="color: #10b981;">R$ <?= number_format($summary['income'], 2, ',', '.') ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Este mês</div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
        </div>
    </div>
    <div class="mgmt-kpi-card" style="justify-content:space-between; border-color: rgba(239, 68, 68, 0.2);">
        <div>
            <div class="mgmt-kpi-card__label">Despesas</div>
            <div class="mgmt-kpi-card__value" style="color: #ef4444;">R$ <?= number_format($summary['expense'], 2, ',', '.') ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Este mês</div>
        </div>
        <div class="mgmt-kpi-card__icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
        </div>
    </div>
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">Saldo</div>
            <div class="mgmt-kpi-card__value">R$ <?= number_format($summary['balance'], 2, ',', '.') ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Resultado do mês</div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2.5" y="5" width="19" height="14" rx="2"></rect><path d="M16 12h.01"></path><path d="M2.5 9h19"></path></svg>
        </div>
    </div>
</div>

<div class="mgmt-dashboard-card" style="padding:0; overflow:hidden;">
    <div style="display:flex; gap: var(--space-1); padding: var(--space-3) var(--space-4); border-bottom: 1px solid var(--color-border-light); overflow-x: auto;">
        <?php 
        $finTabs = ['dashboard' => 'Dashboard', 'transactions' => 'Receitas & Despesas', 'approvals' => 'Aprovações', 'reports' => 'Relatórios', 'audit' => 'Auditoria', 'accounts' => 'Contas', 'categories' => 'Categorias'];
        $activeTab = $_GET['tab'] ?? 'dashboard';
        foreach ($finTabs as $tabKey => $tabLabel): 
            $isActive = $activeTab === $tabKey;
        ?>
        <a href="<?= url('/gestao/financeiro?tab=' . $tabKey) ?>" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 6px; text-decoration:none; white-space:nowrap; <?= $isActive ? 'background: var(--color-primary); color: white;' : 'color: var(--text-muted);' ?>"><?= $tabLabel ?></a>
        <?php endforeach; ?>
    </div>

    <div style="padding: var(--space-4);">
        <h3 style="font-size: var(--text-base); font-weight: 700; margin-bottom: 4px;">Lançamentos</h3>
        <p style="font-size: 12px; color: var(--text-muted); margin-bottom: var(--space-4);">Histórico de movimentações financeiras</p>
    </div>

<?php if (empty($transactions)): ?>
    <div style="text-align:center; padding: var(--space-10); color: var(--text-muted);">
        <div style="margin-bottom:8px; opacity:0.3;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2.5 8A2.5 2.5 0 0 1 5 5.5h14A2.5 2.5 0 0 1 21.5 8v8A2.5 2.5 0 0 1 19 18.5H5A2.5 2.5 0 0 1 2.5 16z"></path></svg></div>
        <h3 style="font-weight:700; margin-bottom:4px;">Nenhuma transação</h3>
        <p style="font-size:13px; margin-bottom: var(--space-4);">Registre a primeira movimentação financeira.</p>
        <a href="<?= url('/gestao/financeiro/novo') ?>" class="btn btn--primary">Registrar</a>
    </div>
<?php else: ?>
    <table class="mgmt-table">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Categoria</th>
                <th>Descrição</th>
                <th>Data</th>
                <th style="text-align:right;">Valor</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $t): ?>
            <tr>
                <td>
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:50%; background: <?= $t['type'] === 'income' ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' ?>; color: <?= $t['type'] === 'income' ? '#10b981' : '#ef4444' ?>;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="<?= $t['type'] === 'income' ? '19' : '5' ?>" x2="12" y2="<?= $t['type'] === 'income' ? '5' : '19' ?>"></line><polyline points="<?= $t['type'] === 'income' ? '5 12 12 5 19 12' : '19 12 12 19 5 12' ?>"></polyline></svg>
                    </span>
                </td>
                <td>
                    <?php if ($t['category_name']): ?>
                    <span class="badge badge--category"><?= e($t['category_name']) ?></span>
                    <?php else: ?>—<?php endif; ?>
                </td>
                <td><div class="mgmt-table__name"><?= e($t['description']) ?></div></td>
                <td style="color: var(--text-muted);"><?= date('d M Y', strtotime($t['transaction_date'])) ?></td>
                <td style="text-align:right; font-weight:700; color: <?= $t['type'] === 'income' ? '#10b981' : '#ef4444' ?>;"><?= $t['type'] === 'income' ? '+' : '-' ?> R$ <?= number_format((float)$t['amount'], 2, ',', '.') ?></td>
                <td style="text-align:right;">
                    <button style="background:none; border:none; color: var(--text-muted); cursor:pointer; padding:4px;" title="Excluir"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if ($pagination['totalPages'] > 1): ?>
    <div class="mgmt-pagination">
        <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
            <?php if ($i === $pagination['page']): ?><span class="current"><?= $i ?></span>
            <?php else: ?><a href="<?= url('/gestao/financeiro?page=' . $i . '&type=' . ($filters['type']??'') . '&start_date=' . ($filters['start_date']??'') . '&end_date=' . ($filters['end_date']??'')) ?>"><?= $i ?></a><?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
<?php endif; ?>
</div>
<?php $__view->endSection(); ?>
