<?php $__view->extends('auth'); ?>

<?php $__view->section('content'); ?>

<div class="auth-form-container">
    <div class="auth-form-container__header">
        <h2 class="auth-form-container__title">Recuperar senha</h2>
        <p class="auth-form-container__subtitle">
            Informe seu e-mail e enviaremos as instruções para redefinir sua senha.
        </p>
    </div>

    <?php if ($error = flash('error')): ?>
        <div class="alert alert--error"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($success = flash('success')): ?>
        <div class="alert alert--success"><?= e($success) ?></div>
    <?php endif; ?>

    <div class="auth-form">
        <form method="POST" action="<?= url('/esqueci-senha') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="email">E-mail cadastrado</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input"
                    placeholder="seu@email.com"
                    required
                    autofocus
                >
            </div>

            <div class="auth-form__actions">
                <button type="submit" class="btn btn--primary auth-form__submit">Enviar instruções</button>
            </div>
        </form>
    </div>

    <p class="auth-form__footer">
        Lembrou sua senha? <a href="<?= url('/login') ?>">Voltar ao login</a>
    </p>
</div>

<?php $__view->endSection(); ?>
