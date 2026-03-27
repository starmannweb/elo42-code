<?php $__view->extends('auth'); ?>

<?php $__view->section('content'); ?>

<div class="auth-form-container">
    <div class="auth-form-container__header">
        <h2 class="auth-form-container__title">Redefinir senha</h2>
        <p class="auth-form-container__subtitle">
            Escolha uma nova senha segura para sua conta.
        </p>
    </div>

    <?php if ($error = flash('error')): ?>
        <div class="alert alert--error"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="auth-form">
        <form method="POST" action="<?= url('/redefinir-senha') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= e($token ?? '') ?>">

            <div class="form-group">
                <label class="form-label" for="password">Nova senha</label>
                <div class="form-password">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Mínimo 8 caracteres"
                        required
                        minlength="8"
                        autofocus
                    >
                    <button type="button" class="form-password__toggle" data-toggle-password="password">
                        Mostrar
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirmar nova senha</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-input"
                    placeholder="Repita a senha"
                    required
                >
            </div>

            <div class="auth-form__actions">
                <button type="submit" class="btn btn--primary auth-form__submit">Redefinir senha</button>
            </div>
        </form>
    </div>

    <p class="auth-form__footer">
        <a href="<?= url('/login') ?>">Voltar ao login</a>
    </p>
</div>

<?php $__view->endSection(); ?>
