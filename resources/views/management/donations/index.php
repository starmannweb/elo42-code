<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Doações PIX</h1>
        <p class="mgmt-header__subtitle">Gerencie pagamentos e doações recebidas via PIX integrado</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="window.location.href='<?= url('/gestao/doacoes/nova') ?>'">💳 Gerar Cobrança PIX</button>
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
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--gold">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path><polyline points="7 17 12 22 17 17"></polyline></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Arrecadado (mês)</div>
            <div class="mgmt-kpi-card__value" style="color: #d6a646;">R$ <?= number_format($totalArrecadado, 2, ',', '.') ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Neste mês</div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Doações Recebidas</div>
            <div class="mgmt-kpi-card__value"><?= $totalRecebidas ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Transações concluídas</div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Pagamentos Pendentes</div>
            <div class="mgmt-kpi-card__value"><?= $totalPendentes ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Aguardando pagamento</div>
        </div>
    </div>
</div>

<form method="GET" action="<?= url('/gestao/doacoes') ?>" class="mgmt-filters">
    <select name="type" class="form-select"><option value="">Todos os tipos</option><option value="tithe" <?= ($filters['type']??'') === 'tithe' ? 'selected' : '' ?>>Dízimo</option><option value="offering" <?= ($filters['type']??'') === 'offering' ? 'selected' : '' ?>>Oferta</option><option value="special" <?= ($filters['type']??'') === 'special' ? 'selected' : '' ?>>Especial</option><option value="campaign" <?= ($filters['type']??'') === 'campaign' ? 'selected' : '' ?>>Campanha</option></select>
    <input type="date" name="start_date" class="form-input" value="<?= e($filters['start_date'] ?? '') ?>"><input type="date" name="end_date" class="form-input" value="<?= e($filters['end_date'] ?? '') ?>">
    <button type="submit" class="btn btn--ghost">Filtrar</button>
</form>

<?php if (empty($donations)): ?>
<div class="mgmt-empty"><div class="mgmt-empty__icon">💳</div><h3 class="mgmt-empty__title">Nenhuma doação PIX</h3><p class="mgmt-empty__text">Gere uma cobrança PIX para começar a receber doações.</p></div>
<?php else: ?>
<div class="mgmt-table-container">
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
                <td class="font-bold" style="color: #d6a646;">R$ <?= number_format((float)$d['amount'], 2, ',', '.') ?></td>
                <td>
                    <?php $status = $d['status'] ?? 'paid'; ?>
                    <span class="badge badge--<?= $status === 'paid' ? 'active' : 'pending' ?>"><?= $status === 'paid' ? 'PAGO' : 'PENDENTE' ?></span>
                </td>
                <td style="color: var(--text-muted);">⏱ <?= date('d/m/Y, H:i', strtotime($d['donation_date'] ?? $d['created_at'] ?? 'now')) ?></td>
                <td style="color: var(--text-muted);"><?= ($d['status'] ?? 'paid') === 'paid' ? '⏱ ' . date('d/m/Y', strtotime($d['paid_at'] ?? $d['donation_date'] ?? 'now')) : '—' ?></td>
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
</div>
<?php endif; ?>
<?php $__view->endSection(); ?>
