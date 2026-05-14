<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
    $degraded = !empty($degraded) || !empty($user['is_session_fallback']);
    $sessionUser = \App\Core\Session::user() ?? [];
    $isSelf = (int) ($sessionUser['id'] ?? 0) === (int) ($user['id'] ?? 0);
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Editar usu&aacute;rio</h1>
        <p class="mgmt-header__subtitle"><?= e($user['email'] ?? '') ?></p>
    </div>
    <div class="mgmt-header__actions" style="display:flex;gap:8px;">
        <?php if (!$degraded): ?>
            <a href="<?= url('/admin/usuarios/' . $user['id']) ?>" class="btn btn--outline">Ver</a>
        <?php endif; ?>
        <a href="<?= url('/admin/usuarios') ?>" class="btn btn--secondary">Voltar</a>
        <?php if (!$degraded && !$isSelf): ?>
            <form method="POST" action="<?= url('/admin/usuarios/' . $user['id'] . '/excluir') ?>" onsubmit="return confirm('Tem certeza que deseja remover este usuario?');">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn--danger-outline">Excluir</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php if ($degraded): ?>
    <div class="alert alert--warning" role="alert" style="margin-bottom:1rem;">
        Banco indispon&iacute;vel agora. Esta tela est&aacute; usando os dados da sess&atilde;o e pode n&atilde;o persistir altera&ccedil;&otilde;es no cadastro.
    </div>
<?php endif; ?>

<div class="mgmt-grid admin-user-edit-grid">
    <div class="mgmt-form-card">
        <h3 class="mgmt-form-card__title">Dados do usu&aacute;rio</h3>
        <form method="POST" action="<?= url('/admin/usuarios/' . $user['id'] . '/editar') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label">Nome *</label>
                <input type="text" name="name" class="form-input" value="<?= e($user['name']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">E-mail *</label>
                <input type="email" name="email" class="form-input" value="<?= e($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Telefone</label>
                <input type="tel" name="phone" class="form-input" value="<?= e($user['phone'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Ativo</option>
                    <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inativo</option>
                    <option value="suspended" <?= $user['status'] === 'suspended' ? 'selected' : '' ?>>Suspenso</option>
                </select>
            </div>
            <div class="mgmt-form-actions">
                <button type="submit" class="btn btn--primary">Salvar</button>
                <a href="<?= url('/admin/usuarios') ?>" class="btn btn--secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <div class="mgmt-form-card">
        <h3 class="mgmt-form-card__title">Redefinir senha</h3>
        <?php if ($degraded): ?>
            <p class="mgmt-auto-note" style="margin-top:0;">
                Conecte o banco de dados para redefinir a senha deste usu&aacute;rio.
            </p>
            <div class="form-group">
                <label class="form-label">Nova senha</label>
                <input type="password" class="form-input" disabled>
            </div>
            <button type="button" class="btn btn--primary" style="width:100%;" disabled>Salvar nova senha</button>
        <?php else: ?>
        <form method="POST" action="<?= url('/admin/usuarios/' . $user['id'] . '/reset-password') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label">Nova senha</label>
                <input type="password" name="password" class="form-input" required minlength="6">
            </div>
            <button type="submit" class="btn btn--primary" style="width:100%;">Salvar nova senha</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php $__view->endSection(); ?>
