<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
    $degraded = !empty($degraded) || !empty($user['is_session_fallback']);
    $sessionUser = \App\Core\Session::user() ?? [];
    $isSelf = (int) ($sessionUser['id'] ?? 0) === (int) ($user['id'] ?? 0);
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Editar usuario</h1>
        <p class="mgmt-header__subtitle"><?= e($user['email'] ?? '') ?></p>
    </div>
    <div class="mgmt-header__actions" style="display:flex;gap:8px;">
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
        Banco indisponivel agora. Esta tela esta usando os dados da sessao e pode nao persistir alteracoes no cadastro.
    </div>
<?php endif; ?>

<div class="mgmt-grid" style="grid-template-columns:minmax(0, 1.2fr) minmax(320px, 0.8fr); gap:1rem; align-items:start;">
    <div class="mgmt-form-card">
        <h3 class="mgmt-form-card__title">Dados do usuario</h3>
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

    <?php if (!$degraded): ?>
    <div class="mgmt-form-card">
        <h3 class="mgmt-form-card__title">Redefinir senha</h3>
        <form method="POST" action="<?= url('/admin/usuarios/' . $user['id'] . '/reset-password') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label">Nova senha</label>
                <input type="password" name="password" class="form-input" required minlength="6">
            </div>
            <button type="submit" class="btn btn--primary" style="width:100%;">Salvar nova senha</button>
        </form>
    </div>
    <?php endif; ?>
</div>

<?php $__view->endSection(); ?>
