<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div class="mgmt-header__title">
        <h1>Novo Usuário</h1>
        <p>Adicione um usuário e atribua a ele acesso à sua organização.</p>
    </div>
    <div class="mgmt-header__actions">
        <a href="<?= url('/gestao/configuracoes/usuarios') ?>" class="btn btn--outline">Voltar</a>
    </div>
</div>

<div class="mgmt-card">
    <form action="<?= url('/gestao/usuarios') ?>" method="POST" class="mgmt-form">
        <?= csrf_field() ?>

        <div class="mgmt-form__group">
            <label for="name" class="mgmt-form__label">Nome Completo</label>
            <input type="text" id="name" name="name" class="mgmt-form__input" required>
        </div>

        <div class="mgmt-form__group">
            <label for="email" class="mgmt-form__label">E-mail</label>
            <input type="email" id="email" name="email" class="mgmt-form__input" required>
            <span class="mgmt-form__help">Se o e-mail já existir na plataforma Elo 42, ele será vinculado à sua organização.</span>
        </div>

        <div class="mgmt-form__group">
            <label for="password" class="mgmt-form__label">Senha Temporária</label>
            <input type="password" id="password" name="password" class="mgmt-form__input" required minlength="6">
        </div>

        <div class="mgmt-form__actions">
            <button type="submit" class="btn btn--primary">Salvar Usuário</button>
        </div>
    </form>
</div>
<?php $__view->endSection(); ?>
