<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php $statusLabels = ['trial' => 'Teste', 'active' => 'Ativa', 'past_due' => 'Pendente', 'cancelled' => 'Cancelada', 'expired' => 'Expirada']; ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Assinatura #<?= $sub['id'] ?></h1><p class="mgmt-header__subtitle"><span class="badge badge--<?= e($sub['status']) ?>"><?= e($statusLabels[$sub['status'] ?? ''] ?? ($sub['status'] ?? '-')) ?></span></p></div></div>
<div class="mgmt-detail">
    <div class="mgmt-detail__main">
        <div class="mgmt-info-card"><h3 class="mgmt-info-card__title">Detalhes da assinatura</h3>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Instituição</span><span class="mgmt-info-row__value"><a href="<?= url('/admin/organizacoes/' . $org['id']) ?>" style="color:var(--color-primary);"><?= e($org['name']) ?></a></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Plano</span><span class="mgmt-info-row__value"><?= e($sub['plan_name']) ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Valor</span><span class="mgmt-info-row__value">R$ <?= number_format((float)$sub['price'], 2, ',', '.') ?>/<?= e(match($sub['billing_cycle']) { 'monthly'=>'mês','quarterly'=>'trimestre','yearly'=>'ano', default=>$sub['billing_cycle'] }) ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Status</span><span class="mgmt-info-row__value"><span class="badge badge--<?= e($sub['status']) ?>"><?= e($statusLabels[$sub['status'] ?? ''] ?? ($sub['status'] ?? '-')) ?></span></span></div>
            <?php if ($sub['trial_ends_at']): ?><div class="mgmt-info-row"><span class="mgmt-info-row__label">Fim do trial</span><span class="mgmt-info-row__value"><?= date('d/m/Y H:i', strtotime($sub['trial_ends_at'])) ?></span></div><?php endif; ?>
            <?php if ($sub['starts_at']): ?><div class="mgmt-info-row"><span class="mgmt-info-row__label">Início</span><span class="mgmt-info-row__value"><?= date('d/m/Y', strtotime($sub['starts_at'])) ?></span></div><?php endif; ?>
            <?php if ($sub['expires_at']): ?><div class="mgmt-info-row"><span class="mgmt-info-row__label">Expira</span><span class="mgmt-info-row__value"><?= date('d/m/Y', strtotime($sub['expires_at'])) ?></span></div><?php endif; ?>
            <?php if ($sub['cancelled_at']): ?><div class="mgmt-info-row"><span class="mgmt-info-row__label">Cancelada em</span><span class="mgmt-info-row__value"><?= date('d/m/Y H:i', strtotime($sub['cancelled_at'])) ?></span></div><?php endif; ?>
        </div>

        <?php if (!empty($history)): ?>
        <div class="mgmt-info-card" style="margin-top:var(--space-5);"><h3 class="mgmt-info-card__title">Histórico</h3>
            <?php foreach ($history as $h): ?>
            <div style="padding:var(--space-2) 0;border-bottom:1px solid var(--color-border-light);font-size:var(--text-sm);display:flex;gap:var(--space-3);">
                <span style="font-family:monospace;font-size:var(--text-xs);color:var(--color-text-muted);min-width:120px;"><?= date('d/m/Y H:i', strtotime($h['created_at'])) ?></span>
                <span class="badge badge--active" style="font-size:0.6rem;"><?= e($h['action']) ?></span>
                <?php if ($h['old_plan'] && $h['new_plan'] && $h['old_plan'] !== $h['new_plan']): ?>
                    <span><?= e($h['old_plan']) ?> → <?= e($h['new_plan']) ?></span>
                <?php endif; ?>
                <?php if ($h['user_name']): ?><span style="color:var(--color-text-muted);">por <?= e($h['user_name']) ?></span><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="mgmt-detail__sidebar">
        <div class="mgmt-form-card"><h3 class="mgmt-info-card__title">Atualizar</h3>
            <form method="POST" action="<?= url('/admin/assinaturas/' . $sub['id']) ?>"><?= csrf_field() ?>
                <div class="form-group"><label class="form-label" style="font-size:var(--text-xs);">Status</label><select name="status" class="form-select"><?php foreach ($statusLabels as $st => $label): ?><option value="<?= $st ?>" <?= $sub['status']===$st?'selected':'' ?>><?= e($label) ?></option><?php endforeach; ?></select></div>
                <div class="form-group"><label class="form-label" style="font-size:var(--text-xs);">Plano</label><input type="text" name="plan_name" class="form-input" value="<?= e($sub['plan_name']) ?>"></div>
                <div class="form-group"><label class="form-label" style="font-size:var(--text-xs);">Valor</label><input type="number" name="price" class="form-input" step="0.01" value="<?= e($sub['price']) ?>"></div>
                <button type="submit" class="btn btn--primary" style="width:100%;">Atualizar</button>
            </form>
        </div>
    </div>
</div>
<?php $__view->endSection(); ?>
