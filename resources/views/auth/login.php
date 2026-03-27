<?php $__view->extends('auth'); ?>

<?php $__view->section('content'); ?>

<div class="auth-form-container">
    <div class="auth-form-container__header">
        <h2 class="auth-form-container__title">Bem-vindo de volta</h2>
        <p class="auth-form-container__subtitle">Entre com suas credenciais para acessar a plataforma.</p>
    </div>

    <?php if ($error = flash('error')): ?>
        <div class="alert alert--error"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($success = flash('success')): ?>
        <div class="alert alert--success"><?= e($success) ?></div>
    <?php endif; ?>

    <div class="auth-form">
        <form method="POST" action="<?= url('/login') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input"
                    value="<?= e(old('email')) ?>"
                    placeholder="seu@email.com"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Senha</label>
                <div class="form-password">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="••••••••"
                        required
                    >
                    <button type="button" class="form-password__toggle" data-toggle-password="password">
                        Mostrar
                    </button>
                </div>
            </div>

            <a href="<?= url('/esqueci-senha') ?>" class="auth-form__link">Esqueceu sua senha?</a>

            <div class="auth-form__actions">
                <button type="submit" class="btn btn--primary auth-form__submit">Entrar</button>
            </div>
        </form>
    </div>

    <p class="auth-form__footer">
        Ainda não tem conta? <a href="<?= url('/cadastro') ?>">Criar conta grátis</a>
    </p>
</div>

<?php $__view->endSection(); ?>
