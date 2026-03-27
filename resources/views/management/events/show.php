<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title"><?= e($event['title']) ?></h1><p class="mgmt-header__subtitle"><span class="badge badge--<?= $event['status'] ?>"><?= ucfirst(e($event['status'])) ?></span></p></div><div class="mgmt-header__actions"><a href="<?= url('/gestao/eventos/' . $event['id'] . '/editar') ?>" class="btn btn--ghost">Editar</a></div></div>
<div class="mgmt-detail">
    <div class="mgmt-detail__main">
        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Inscrições (<?= count($registrations) ?>)</h3>
            <?php if (empty($registrations)): ?>
                <div class="mgmt-empty" style="border:none;padding:var(--space-6)"><div class="mgmt-empty__icon">📋</div><p class="mgmt-empty__text">Nenhuma inscrição ainda.</p></div>
            <?php else: ?>
                <table class="mgmt-table"><thead><tr><th>Nome</th><th>Contato</th><th>Check-in</th></tr></thead><tbody>
                    <?php foreach ($registrations as $r): ?><tr><td><?= e($r['name']) ?></td><td><?= e($r['email'] ?? $r['phone'] ?? '—') ?></td><td><?= $r['checked_in'] ? '<span class="badge badge--done">✓</span>' : '<span class="badge badge--todo">—</span>' ?></td></tr><?php endforeach; ?>
                </tbody></table>
            <?php endif; ?>
        </div>
    </div>
    <div class="mgmt-detail__sidebar">
        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Detalhes</h3>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Início</span><span class="mgmt-info-row__value"><?= date('d/m/Y H:i', strtotime($event['start_date'])) ?></span></div>
            <?php if ($event['end_date']): ?><div class="mgmt-info-row"><span class="mgmt-info-row__label">Término</span><span class="mgmt-info-row__value"><?= date('d/m/Y H:i', strtotime($event['end_date'])) ?></span></div><?php endif; ?>
            <?php if ($event['location']): ?><div class="mgmt-info-row"><span class="mgmt-info-row__label">Local</span><span class="mgmt-info-row__value"><?= e($event['location']) ?></span></div><?php endif; ?>
            <?php if ($event['max_registrations']): ?><div class="mgmt-info-row"><span class="mgmt-info-row__label">Vagas</span><span class="mgmt-info-row__value"><?= count($registrations) ?>/<?= $event['max_registrations'] ?></span></div><?php endif; ?>
        </div>
        <?php if ($event['description']): ?><div class="mgmt-info-card"><h3 class="mgmt-info-card__title">Descrição</h3><p class="text-muted" style="font-size:var(--text-sm);line-height:1.7"><?= nl2br(e($event['description'])) ?></p></div><?php endif; ?>
    </div>
</div>
<?php $__view->endSection(); ?>
