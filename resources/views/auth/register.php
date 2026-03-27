<?php $__view->extends('auth'); ?>

<?php $__view->section('sidebar_title'); ?>
Comece agora.<br>Sua organização merece <span>estrutura</span>.
<?php $__view->endSection(); ?>

<?php $__view->section('sidebar_text'); ?>
Crie sua conta em menos de 2 minutos e tenha acesso a todas as ferramentas do ecossistema Elo 42.
<?php $__view->endSection(); ?>

<?php $__view->section('content'); ?>

<div class="auth-form-container">

    <div class="onboarding-steps">
        <div class="onboarding-step active">
            <span class="onboarding-step__number">1</span>
            <span class="hide-mobile">Sua conta</span>
        </div>
        <div class="onboarding-step__connector"></div>
        <div class="onboarding-step">
            <span class="onboarding-step__number">2</span>
            <span class="hide-mobile">Organização</span>
        </div>
        <div class="onboarding-step__connector"></div>
        <div class="onboarding-step">
            <span class="onboarding-step__number">3</span>
            <span class="hide-mobile">Dashboard</span>
        </div>
    </div>

    <div class="auth-form-container__header">
        <h2 class="auth-form-container__title">Criar sua conta</h2>
        <p class="auth-form-container__subtitle">Preencha os dados abaixo para começar.</p>
    </div>

    <?php if ($error = flash('error')): ?>
        <div class="alert alert--error"><?= e($error) ?></div>
    <?php endif; ?>

    <?php $errors = flash('errors') ?? []; ?>

    <div class="auth-form">
        <form method="POST" action="<?= url('/cadastro') ?>">
            <?= csrf_field() ?>

            <div class="auth-form__row">
                <div class="form-group">
                    <label class="form-label" for="first_name">Nome</label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        class="form-input"
                        value="<?= e(old('first_name')) ?>"
                        placeholder="João"
                        required
                        autofocus
                    >
                    <?php foreach ($errors['first_name'] ?? [] as $err): ?>
                        <p class="form-error"><?= e($err) ?></p>
                    <?php endforeach; ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="last_name">Sobrenome</label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        class="form-input"
                        value="<?= e(old('last_name')) ?>"
                        placeholder="Silva"
                        required
                    >
                    <?php foreach ($errors['last_name'] ?? [] as $err): ?>
                        <p class="form-error"><?= e($err) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

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
                >
                <?php foreach ($errors['email'] ?? [] as $err): ?>
                    <p class="form-error"><?= e($err) ?></p>
                <?php endforeach; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Telefone</label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    class="form-input"
                    value="<?= e(old('phone')) ?>"
                    placeholder="(11) 99999-9999"
                    required
                >
                <?php foreach ($errors['phone'] ?? [] as $err): ?>
                    <p class="form-error"><?= e($err) ?></p>
                <?php endforeach; ?>
            </div>

            <div class="auth-form__row">
                <div class="form-group">
                    <label class="form-label" for="password">Senha</label>
                    <div class="form-password">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Mínimo 8 caracteres"
                            required
                            minlength="8"
                        >
                        <button type="button" class="form-password__toggle" data-toggle-password="password">
                            Mostrar
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirmar senha</label>
                    <div class="form-password">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-input"
                            placeholder="Repita a senha"
                            required
                        >
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="terms" value="1" required>
                    <span class="form-checkbox__label">
                        Li e aceito os <a href="#">Termos de Uso</a> e a <a href="#">Política de Privacidade</a> da Elo 42.
                    </span>
                </label>
            </div>

            <div class="auth-form__actions">
                <button type="submit" class="btn btn--primary auth-form__submit">Criar minha conta</button>
            </div>
        </form>
    </div>

    <p class="auth-form__footer">
        Já tem uma conta? <a href="<?= url('/login') ?>">Entrar</a>
    </p>
</div>

<?php $__view->endSection(); ?>
