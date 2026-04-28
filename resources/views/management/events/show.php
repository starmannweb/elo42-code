<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title"><?= e((string) $event['title']) ?></h1>
        <p class="mgmt-header__subtitle"><span class="badge badge--<?= e((string) $event['status']) ?>"><?= e(ucfirst((string) $event['status'])) ?></span></p>
    </div>
    <div class="mgmt-header__actions">
        <a href="<?= url('/gestao/eventos/' . $event['id'] . '/editar') ?>" class="btn btn--ghost">Editar</a>
    </div>
</div>

<div class="mgmt-detail">
    <div class="mgmt-detail__main">
        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Inscrições (<?= count($registrations) ?>)</h3>
            <?php if (empty($registrations)): ?>
                <div class="mgmt-empty" style="border:none;padding:var(--space-6)">
                    <div class="mgmt-empty__icon">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 2h6l1 2h3v18H5V4h3l1-2Z"></path><path d="M9 9h6"></path><path d="M9 13h6"></path><path d="M9 17h4"></path></svg>
                    </div>
                    <p class="mgmt-empty__text">Nenhuma inscrição ainda.</p>
                </div>
            <?php else: ?>
                <table class="mgmt-table">
                    <thead><tr><th>Nome</th><th>Contato</th><th>Check-in</th></tr></thead>
                    <tbody>
                        <?php foreach ($registrations as $registration): ?>
                            <tr>
                                <td><?= e((string) $registration['name']) ?></td>
                                <td><?= e((string) ($registration['email'] ?? $registration['phone'] ?? '—')) ?></td>
                                <td><?= !empty($registration['checked_in']) ? '<span class="badge badge--done">✓</span>' : '<span class="badge badge--todo">—</span>' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="mgmt-detail__sidebar">
        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Detalhes</h3>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Início</span><span class="mgmt-info-row__value"><?= date('d/m/Y H:i', strtotime((string) $event['start_date'])) ?></span></div>
            <?php if (!empty($event['end_date'])): ?>
                <div class="mgmt-info-row"><span class="mgmt-info-row__label">Término</span><span class="mgmt-info-row__value"><?= date('d/m/Y H:i', strtotime((string) $event['end_date'])) ?></span></div>
            <?php endif; ?>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Unidade</span><span class="mgmt-info-row__value"><?= e((string) ($event['unit_name'] ?? 'Sede / todas')) ?></span></div>
            <?php if (!empty($event['location'])): ?>
                <div class="mgmt-info-row"><span class="mgmt-info-row__label">Local</span><span class="mgmt-info-row__value"><?= e((string) $event['location']) ?></span></div>
            <?php endif; ?>
            <?php if (!empty($event['max_registrations'])): ?>
                <div class="mgmt-info-row"><span class="mgmt-info-row__label">Vagas</span><span class="mgmt-info-row__value"><?= count($registrations) ?>/<?= (int) $event['max_registrations'] ?></span></div>
            <?php endif; ?>
        </div>

        <?php if (!empty($event['description'])): ?>
            <div class="mgmt-info-card">
                <h3 class="mgmt-info-card__title">Descrição</h3>
                <p class="text-muted" style="font-size:var(--text-sm);line-height:1.7"><?= nl2br(e((string) $event['description'])) ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__view->endSection(); ?>
