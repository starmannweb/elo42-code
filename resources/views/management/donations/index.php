<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Doações</h1></div><div class="mgmt-header__actions"><a href="<?= url('/gestao/doacoes/nova') ?>" class="btn btn--primary">+ Nova doação</a></div></div>

<?php if (!empty($summary)): ?>
<div class="mgmt-stats-grid" style="margin-bottom:var(--space-6);">
    <?php foreach ($summary as $s): ?>
    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--gold">🤝</span>
        <div>
            <div class="mgmt-stat__value">R$ <?= number_format((float)$s['total'], 2, ',', '.') ?></div>
            <div class="mgmt-stat__label"><?= e(match($s['type']) { 'tithe'=>'Dízimos','offering'=>'Ofertas','special'=>'Especial','campaign'=>'Campanha', default=>ucfirst($s['type']) }) ?> (<?= $s['count'] ?>)</div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<form method="GET" action="<?= url('/gestao/doacoes') ?>" class="mgmt-filters">
    <select name="type" class="form-select"><option value="">Todos os tipos</option><option value="tithe" <?= ($filters['type']??'') === 'tithe' ? 'selected' : '' ?>>Dízimo</option><option value="offering" <?= ($filters['type']??'') === 'offering' ? 'selected' : '' ?>>Oferta</option><option value="special" <?= ($filters['type']??'') === 'special' ? 'selected' : '' ?>>Especial</option><option value="campaign" <?= ($filters['type']??'') === 'campaign' ? 'selected' : '' ?>>Campanha</option></select>
    <input type="date" name="start_date" class="form-input" value="<?= e($filters['start_date'] ?? '') ?>"><input type="date" name="end_date" class="form-input" value="<?= e($filters['end_date'] ?? '') ?>">
    <button type="submit" class="btn btn--ghost">Filtrar</button>
</form>

<?php if (empty($donations)): ?><div class="mgmt-empty"><div class="mgmt-empty__icon">🤝</div><h3 class="mgmt-empty__title">Nenhuma doação</h3><p class="mgmt-empty__text">Registre doações recebidas.</p></div>
<?php else: ?>
    <div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Data</th><th>Doador</th><th>Tipo</th><th>Método</th><th>Valor</th></tr></thead><tbody>
        <?php foreach ($donations as $d): ?><tr>
            <td><?= date('d/m/Y', strtotime($d['donation_date'])) ?></td>
            <td><?= e($d['member_name'] ?? $d['donor_name'] ?? 'Anônimo') ?></td>
            <td><?= e(match($d['type']) { 'tithe'=>'Dízimo','offering'=>'Oferta','special'=>'Especial','campaign'=>'Campanha', default=>$d['type'] }) ?></td>
            <td><?= e(match($d['payment_method']) { 'cash'=>'Dinheiro','pix'=>'Pix','card'=>'Cartão','transfer'=>'Transferência', default=>$d['payment_method'] }) ?></td>
            <td class="font-bold text-success">R$ <?= number_format((float)$d['amount'], 2, ',', '.') ?></td>
        </tr><?php endforeach; ?>
    </tbody></table>
    <?php if ($pagination['totalPages'] > 1): ?><div class="mgmt-pagination"><?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?><?php if ($i === $pagination['page']): ?><span class="current"><?= $i ?></span><?php else: ?><a href="<?= url('/gestao/doacoes?page=' . $i) ?>"><?= $i ?></a><?php endif; ?><?php endfor; ?></div><?php endif; ?>
    </div>
<?php endif; ?>
<?php $__view->endSection(); ?>
