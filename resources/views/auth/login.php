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
                    <button type="button" class="form-password__toggle" data-toggle-password="password" aria-label="Mostrar senha">
                        <svg class="icon-eye" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="icon-eye-off" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
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
