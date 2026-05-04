<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php $statusLabels = ['trial' => 'Teste', 'active' => 'Ativa', 'past_due' => 'Pendente', 'cancelled' => 'Cancelada', 'expired' => 'Expirada']; ?>

<div class="mgmt-header"><div><h1 class="mgmt-header__title">Assinaturas</h1></div></div>
<form method="GET" action="<?= url('/admin/assinaturas') ?>" class="mgmt-filters">
    <select name="status" class="form-select" onchange="this.form.submit()"><option value="">Todos</option><?php foreach (['trial'=>'Trial','active'=>'Ativa','past_due'=>'Pendente','cancelled'=>'Cancelada','expired'=>'Expirada'] as $k=>$v): ?><option value="<?= $k ?>" <?= ($filters['status']??'')===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select>
</form>
<?php if (empty($subscriptions)): ?><div class="mgmt-empty"><div class="mgmt-empty__icon"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="5" width="19" height="14" rx="2"></rect><path d="M2.5 9h19"></path><path d="M16 14h.01"></path></svg></div><h3 class="mgmt-empty__title">Nenhuma assinatura</h3></div>
<?php else: ?>
    <div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Instituição</th><th>Plano</th><th>Valor</th><th>Ciclo</th><th>Status</th><th>Início</th><th>Expira</th><th>Ações</th></tr></thead><tbody>
        <?php foreach ($subscriptions as $s): ?><tr>
            <td><a href="<?= url('/admin/organizacoes/' . $s['organization_id']) ?>" class="mgmt-table__name" style="color:var(--color-primary);"><?= e($s['org_name']) ?></a></td>
            <td><?= e($s['plan_name']) ?></td>
            <td style="font-weight:700;">R$ <?= number_format((float)$s['price'], 2, ',', '.') ?></td>
            <td><?= e(match($s['billing_cycle']) { 'monthly'=>'Mensal','quarterly'=>'Trimestral','yearly'=>'Anual', default=>$s['billing_cycle'] }) ?></td>
            <td><span class="badge badge--<?= e($s['status']) ?>"><?= e($statusLabels[$s['status'] ?? ''] ?? ($s['status'] ?? '-')) ?></span></td>
            <td><?= $s['starts_at'] ? date('d/m/Y', strtotime($s['starts_at'])) : '—' ?></td>
            <td><?= $s['expires_at'] ? date('d/m/Y', strtotime($s['expires_at'])) : '—' ?></td>
            <td class="mgmt-table__actions"><a href="<?= url('/admin/assinaturas/' . $s['id']) ?>">Detalhe</a></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
<?php endif; ?>
<?php $__view->endSection(); ?>
