<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div><h1 class="mgmt-header__title">Ticket #<?= $ticket['id'] ?></h1><p class="mgmt-header__subtitle"><span class="badge badge--<?= $ticket['priority'] ?>"><?= ucfirst(e($ticket['priority'])) ?></span> · <span class="badge badge--<?= $ticket['status'] ?>"><?= e(match($ticket['status']) { 'open'=>'Aberto','in_progress'=>'Em andamento','waiting'=>'Aguardando','resolved'=>'Resolvido','closed'=>'Fechado', default=>$ticket['status'] }) ?></span></p></div>
    <div class="mgmt-header__actions">
        <form method="POST" action="<?= url('/admin/tickets/' . $ticket['id'] . '/status') ?>" style="display:flex;gap:var(--space-2);">
            <?= csrf_field() ?>
            <select name="status" class="form-select"><?php foreach (['open'=>'Aberto','in_progress'=>'Em andamento','waiting'=>'Aguardando','resolved'=>'Resolvido','closed'=>'Fechado'] as $k=>$v): ?><option value="<?= $k ?>" <?= $ticket['status']===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select>
            <button type="submit" class="btn btn--secondary">Atualizar</button>
        </form>
    </div>
</div>

<div class="mgmt-detail">
    <div class="mgmt-detail__main">
        <div class="mgmt-info-card" style="margin-bottom:var(--space-5);">
            <h3 class="mgmt-info-card__title"><?= e($ticket['subject']) ?></h3>
            <p style="font-size:var(--text-sm);color:var(--color-text-secondary);white-space:pre-wrap;"><?= e($ticket['description']) ?></p>
        </div>

        <div class="ticket-thread">
            <?php foreach ($replies as $r): ?>
            <div class="ticket-reply <?= $r['is_admin'] ? 'ticket-reply--admin' : '' ?>">
                <div class="ticket-reply__meta">
                    <span class="ticket-reply__author"><?= e($r['user_name']) ?> <?= $r['is_admin'] ? '(Admin)' : '' ?></span>
                    <span><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></span>
                </div>
                <p style="white-space:pre-wrap;"><?= e($r['message']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="mgmt-form-card" style="margin-top:var(--space-5);">
            <form method="POST" action="<?= url('/admin/tickets/' . $ticket['id'] . '/responder') ?>"><?= csrf_field() ?>
                <div class="form-group"><label class="form-label">Responder</label><textarea name="message" class="form-input" rows="4" placeholder="Escreva sua resposta..." required></textarea></div>
                <button type="submit" class="btn btn--primary">Enviar resposta</button>
            </form>
        </div>
    </div>
    <div class="mgmt-detail__sidebar">
        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Informações</h3>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Categoria</span><span class="mgmt-info-row__value"><?= e(match($ticket['category']) { 'support'=>'Suporte','bug'=>'Bug','feature'=>'Feature','billing'=>'Faturamento', default=>$ticket['category'] }) ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Aberto em</span><span class="mgmt-info-row__value"><?= date('d/m/Y H:i', strtotime($ticket['created_at'])) ?></span></div>
            <?php if ($ticket['resolved_at']): ?><div class="mgmt-info-row"><span class="mgmt-info-row__label">Resolvido em</span><span class="mgmt-info-row__value"><?= date('d/m/Y H:i', strtotime($ticket['resolved_at'])) ?></span></div><?php endif; ?>
        </div>
    </div>
</div>
<?php $__view->endSection(); ?>
