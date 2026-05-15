<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
$statusLabels = ['active' => 'Ativo', 'inactive' => 'Inativo', 'suspended' => 'Suspenso'];
$membershipLabels = ['active' => 'Ativo', 'inactive' => 'Inativo', 'pending' => 'Pendente', 'invited' => 'Convidado', 'suspended' => 'Suspenso'];
$formatDateTime = static function (mixed $value): string {
    $value = trim((string) ($value ?? ''));
    if ($value === '') {
        return '&mdash;';
    }

    $timestamp = strtotime($value);
    return $timestamp ? date('d/m/Y H:i', $timestamp) : '&mdash;';
};
$emailVerifiedAt = $user['email_verified_at'] ?? null;
$lastLoginAt = $user['last_login_at'] ?? null;
$createdAt = $user['created_at'] ?? null;
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title"><?= e($user['name'] ?? 'Usuário') ?></h1>
        <p class="mgmt-header__subtitle"><?= e($user['email'] ?? '') ?></p>
    </div>
    <div class="mgmt-header__actions" style="display:flex; gap:8px;">
        <button type="button" class="btn btn--outline" onclick="document.getElementById('modal-reset-password').style.display='flex'">Redefinir senha</button>
        <button type="button" class="btn btn--secondary" onclick="document.getElementById('modal-edit-user-detail').style.display='flex'">Editar</button>
    </div>
</div>

<div class="modal" id="modal-edit-user-detail" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-edit-user-detail-title">
    <div class="modal__content modal__content--wide">
        <div class="modal__header">
            <h2 class="modal__title" id="modal-edit-user-detail-title">Editar usu&aacute;rio</h2>
            <button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'" aria-label="Fechar">&times;</button>
        </div>
        <form method="POST" action="<?= url('/admin/usuarios/' . ($user['id'] ?? 0) . '/editar') ?>" data-loading>
            <?= csrf_field() ?>
            <input type="hidden" name="return_to" value="<?= e('/admin/usuarios/' . ($user['id'] ?? 0)) ?>">
            <div class="modal__body">
                <div class="modal-grid">
                    <div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" value="<?= e($user['name'] ?? '') ?>" required></div>
                    <div class="form-group"><label class="form-label">E-mail *</label><input type="email" name="email" class="form-input" value="<?= e($user['email'] ?? '') ?>" required></div>
                    <div class="form-group"><label class="form-label">Telefone</label><input type="text" name="phone" class="form-input" value="<?= e($user['phone'] ?? '') ?>"></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" <?= ($user['status'] ?? '') === 'active' ? 'selected' : '' ?>>Ativo</option><option value="inactive" <?= ($user['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inativo</option><option value="suspended" <?= ($user['status'] ?? '') === 'suspended' ? 'selected' : '' ?>>Suspenso</option></select></div>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--primary">Salvar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="modal-reset-password" style="display:none;" role="dialog" aria-modal="true">
    <div class="modal__content">
        <div class="modal__header">
            <h2 class="modal__title">Redefinir senha</h2>
            <button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/admin/usuarios/' . ($user['id'] ?? 0) . '/reset-password') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="return_to" value="<?= e('/admin/usuarios/' . ($user['id'] ?? 0)) ?>">
            <div class="modal__body">
                <p style="font-size:13px; color:var(--text-muted); margin-bottom:16px;">
                    Defina uma nova senha para este usuário. Ele poderá acessar o sistema imediatamente com a nova credencial.
                </p>
                <div class="form-group">
                    <label class="form-label">Nova senha</label>
                    <input type="password" name="password" class="form-input" required minlength="6">
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--primary">Salvar nova senha</button>
            </div>
        </form>
    </div>
</div>

<div class="mgmt-detail">
    <div class="mgmt-detail__main">
        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Dados do usuário</h3>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Nome</span><span class="mgmt-info-row__value"><?= e($user['name'] ?? '-') ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">E-mail</span><span class="mgmt-info-row__value"><?= e($user['email'] ?? '-') ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Telefone</span><span class="mgmt-info-row__value"><?= e($user['phone'] ?? '-') ?></span></div>
            <div class="mgmt-info-row">
                <span class="mgmt-info-row__label">Status</span>
                <span class="mgmt-info-row__value">
                    <span class="badge badge--<?= e($user['status'] ?? 'inactive') ?>"><?= e($statusLabels[$user['status'] ?? ''] ?? ($user['status'] ?? '-')) ?></span>
                </span>
            </div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">E-mail verificado</span><span class="mgmt-info-row__value"><?= $emailVerifiedAt ? $formatDateTime($emailVerifiedAt) : '&#10060; Não verificado' ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Último login</span><span class="mgmt-info-row__value"><?= $formatDateTime($lastLoginAt) ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Cadastrado em</span><span class="mgmt-info-row__value"><?= $formatDateTime($createdAt) ?></span></div>
        </div>

        <?php if (!empty($organizations)): ?>
        <div class="mgmt-info-card" style="margin-top:var(--space-5);">
            <h3 class="mgmt-info-card__title">Instituições vinculadas</h3>
            <table class="mgmt-table">
                <thead><tr><th>Instituição</th><th>Papel</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($organizations as $o): ?>
                    <tr>
                        <td><a href="<?= url('/admin/organizacoes/' . $o['id']) ?>" class="mgmt-table__name" style="color:var(--color-primary);"><?= e($o['name']) ?></a></td>
                        <td><?= e($o['role_name'] ?? '-') ?></td>
                        <td><span class="badge badge--<?= e($o['membership_status'] ?? 'inactive') ?>"><?= e($membershipLabels[$o['membership_status'] ?? ''] ?? ($o['membership_status'] ?? '-')) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
    <div class="mgmt-detail__sidebar">
        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Atividade recente</h3>
            <?php if (empty($logs)): ?>
                <p style="font-size:var(--text-sm);color:var(--color-text-muted);text-align:center;padding:var(--space-4);">Nenhum log registrado.</p>
            <?php else: ?>
                <?php foreach (array_slice($logs, 0, 10) as $l): ?>
                    <div style="padding:var(--space-2) 0;border-bottom:1px solid var(--color-border-light);font-size:var(--text-xs);">
                        <span style="color:var(--color-text-muted);"><?= $formatDateTime($l['created_at'] ?? null) ?></span> &mdash; <?= e($l['action'] ?? '') ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__view->endSection(); ?>
