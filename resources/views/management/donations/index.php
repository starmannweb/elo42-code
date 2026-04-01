<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Doações PIX</h1>
        <p class="mgmt-header__subtitle">Gerencie pagamentos e doações recebidas via PIX integrado</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="window.location.href='<?= url('/gestao/doacoes/nova') ?>'">Gerar Cobrança PIX</button>
    </div>
</div>

<?php 
$totalArrecadado = 0;
$totalRecebidas = 0;
$totalPendentes = 0;
if (!empty($summary)) {
    foreach ($summary as $s) {
        $totalArrecadado += (float)$s['total'];
        $totalRecebidas += (int)$s['count'];
    }
}
foreach ($donations ?? [] as $d) {
    if (($d['status'] ?? 'paid') === 'pending') $totalPendentes++;
}
?>

<div class="mgmt-kpi-grid" style="grid-template-columns: repeat(3, 1fr);">
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">Arrecadado (mês)</div>
            <div class="mgmt-kpi-card__value" style="color: #d6a646;">R$ <?= number_format($totalArrecadado, 2, ',', '.') ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Neste mês</div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--gold">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
        </div>
    </div>
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">Doações Recebidas</div>
            <div class="mgmt-kpi-card__value"><?= $totalRecebidas ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Transações concluídas</div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path></svg>
        </div>
    </div>
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">Pagamentos Pendentes</div>
            <div class="mgmt-kpi-card__value" style="color: #d6a646;"><?= $totalPendentes ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Aguardando pagamento</div>
        </div>
        <div class="mgmt-kpi-card__icon" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
    </div>
</div>

<div class="mgmt-table-container">
<?php if (empty($donations)): ?>
    <div style="text-align:center; padding: var(--space-10); color: var(--text-muted);">
        <div style="margin-bottom:8px; opacity:0.3;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 21s-8-4.5-8-11a4 4 0 0 1 7-2.6A4 4 0 0 1 18 10c0 6.5-6 11-6 11z"></path></svg></div>
        <h3 style="font-weight:700; margin-bottom:4px;">Nenhuma doação PIX</h3>
        <p style="font-size:13px;">Gere uma cobrança PIX para começar a receber doações.</p>
    </div>
<?php else: ?>
    <table class="mgmt-table">
        <thead>
            <tr>
                <th>Doador</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Gerado Em</th>
                <th>Pago Em</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($donations as $d): ?>
            <tr>
                <td><div class="mgmt-table__name"><?= e($d['member_name'] ?? $d['donor_name'] ?? 'Anônimo') ?></div></td>
                <td style="font-weight:700;">R$ <?= number_format((float)$d['amount'], 2, ',', '.') ?></td>
                <td>
                    <?php $status = $d['status'] ?? 'paid'; ?>
                    <span class="badge badge--<?= $status === 'paid' ? 'active' : 'pending' ?>"><?= $status === 'paid' ? 'PAGO' : 'PENDENTE' ?></span>
                </td>
                <td style="color: var(--text-muted); display:flex; align-items:center; gap:4px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <?= date('d/m/Y', strtotime($d['donation_date'] ?? $d['created_at'] ?? 'now')) ?>
                </td>
                <td style="color: var(--text-muted);"><?= ($d['status'] ?? 'paid') === 'paid' ? date('d/m/Y', strtotime($d['paid_at'] ?? $d['donation_date'] ?? 'now')) : '—' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (($pagination['totalPages'] ?? 1) > 1): ?>
    <div class="mgmt-pagination">
        <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
            <?php if ($i === $pagination['page']): ?><span class="current"><?= $i ?></span>
            <?php else: ?><a href="<?= url('/gestao/doacoes?page=' . $i) ?>"><?= $i ?></a><?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
<?php endif; ?>
</div>
<?php $__view->endSection(); ?>
