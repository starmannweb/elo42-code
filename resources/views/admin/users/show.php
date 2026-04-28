<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title"><?= e($user['name']) ?></h1><p class="mgmt-header__subtitle"><?= e($user['email']) ?></p></div><div class="mgmt-header__actions"><a href="<?= url('/admin/usuarios/' . $user['id'] . '/editar') ?>" class="btn btn--secondary">Editar</a></div></div>
<div class="mgmt-detail">
    <div class="mgmt-detail__main">
        <div class="mgmt-info-card"><h3 class="mgmt-info-card__title">Dados do usuário</h3>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Nome</span><span class="mgmt-info-row__value"><?= e($user['name']) ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">E-mail</span><span class="mgmt-info-row__value"><?= e($user['email']) ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Telefone</span><span class="mgmt-info-row__value"><?= e($user['phone'] ?? '—') ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Status</span><span class="mgmt-info-row__value"><span class="badge badge--<?= $user['status'] ?>"><?= ucfirst(e($user['status'])) ?></span></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">E-mail verificado</span><span class="mgmt-info-row__value"><?= $user['email_verified_at'] ? date('d/m/Y H:i', strtotime($user['email_verified_at'])) : '❌ Não verificado' ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Último login</span><span class="mgmt-info-row__value"><?= $user['last_login_at'] ? date('d/m/Y H:i', strtotime($user['last_login_at'])) : '—' ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Cadastrado em</span><span class="mgmt-info-row__value"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></span></div>
        </div>

        <?php if (!empty($organizations)): ?>
        <div class="mgmt-info-card" style="margin-top:var(--space-5);"><h3 class="mgmt-info-card__title">Instituições vinculadas</h3>
            <table class="mgmt-table"><thead><tr><th>Instituição</th><th>Papel</th><th>Status</th></tr></thead><tbody>
                <?php foreach ($organizations as $o): ?><tr>
                    <td><a href="<?= url('/admin/organizacoes/' . $o['id']) ?>" class="mgmt-table__name" style="color:var(--color-primary);"><?= e($o['name']) ?></a></td>
                    <td><?= e($o['role_name'] ?? '—') ?></td>
                    <td><span class="badge badge--<?= $o['membership_status'] ?>"><?= ucfirst(e($o['membership_status'])) ?></span></td>
                </tr><?php endforeach; ?>
            </tbody></table>
        </div>
        <?php endif; ?>
    </div>
    <div class="mgmt-detail__sidebar">
        <div class="mgmt-info-card"><h3 class="mgmt-info-card__title">Atividade recente</h3>
            <?php if (empty($logs)): ?><p style="font-size:var(--text-sm);color:var(--color-text-muted);text-align:center;padding:var(--space-4);">Nenhum log registrado.</p>
            <?php else: ?><?php foreach (array_slice($logs, 0, 10) as $l): ?>
                <div style="padding:var(--space-2) 0;border-bottom:1px solid var(--color-border-light);font-size:var(--text-xs);">
                    <span style="color:var(--color-text-muted);"><?= date('d/m H:i', strtotime($l['created_at'])) ?></span> — <?= e($l['action'] ?? '') ?>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>
<?php $__view->endSection(); ?>
