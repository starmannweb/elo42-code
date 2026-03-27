<?php $__view->extends('auth'); ?>

<?php $__view->section('sidebar_title'); ?>
Agora, registre sua <span>organização</span>.
<?php $__view->endSection(); ?>

<?php $__view->section('sidebar_text'); ?>
Essa etapa é rápida. Precisamos de algumas informações para configurar o ambiente da sua organização.
<?php $__view->endSection(); ?>

<?php $__view->section('content'); ?>

<div class="auth-form-container">

    <div class="onboarding-steps">
        <div class="onboarding-step completed">
            <span class="onboarding-step__number">✓</span>
            <span class="hide-mobile">Sua conta</span>
        </div>
        <div class="onboarding-step__connector completed"></div>
        <div class="onboarding-step active">
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
        <h2 class="auth-form-container__title">Cadastrar organização</h2>
        <p class="auth-form-container__subtitle">
            Informe os dados da sua igreja ou organização para configurarmos tudo para você.
        </p>
    </div>

    <?php if ($error = flash('error')): ?>
        <div class="alert alert--error"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($success = flash('success')): ?>
        <div class="alert alert--success"><?= e($success) ?></div>
    <?php endif; ?>

    <?php $errors = flash('errors') ?? []; ?>

    <div class="auth-form">
        <form method="POST" action="<?= url('/onboarding/organizacao') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="org_name">Nome da organização *</label>
                <input
                    type="text"
                    id="org_name"
                    name="org_name"
                    class="form-input"
                    value="<?= e(old('org_name')) ?>"
                    placeholder="Ex: Igreja Batista Central"
                    required
                    autofocus
                >
                <?php foreach ($errors['org_name'] ?? [] as $err): ?>
                    <p class="form-error"><?= e($err) ?></p>
                <?php endforeach; ?>
            </div>

            <div class="auth-form__row">
                <div class="form-group">
                    <label class="form-label" for="org_legal_name">Razão Social</label>
                    <input
                        type="text"
                        id="org_legal_name"
                        name="org_legal_name"
                        class="form-input"
                        value="<?= e(old('org_legal_name')) ?>"
                        placeholder="Opcional"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="org_document">CNPJ</label>
                    <input
                        type="text"
                        id="org_document"
                        name="org_document"
                        class="form-input"
                        value="<?= e(old('org_document')) ?>"
                        placeholder="00.000.000/0000-00"
                    >
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="org_type">Tipo de organização *</label>
                <select id="org_type" name="org_type" class="form-select" required>
                    <option value="">Selecione...</option>
                    <option value="church" <?= old('org_type') === 'church' ? 'selected' : '' ?>>Igreja</option>
                    <option value="ministry" <?= old('org_type') === 'ministry' ? 'selected' : '' ?>>Ministério</option>
                    <option value="association" <?= old('org_type') === 'association' ? 'selected' : '' ?>>Associação</option>
                    <option value="other" <?= old('org_type') === 'other' ? 'selected' : '' ?>>Outro</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="org_phone">Telefone da organização *</label>
                <input
                    type="tel"
                    id="org_phone"
                    name="org_phone"
                    class="form-input"
                    value="<?= e(old('org_phone')) ?>"
                    placeholder="(11) 3333-4444"
                    required
                >
            </div>

            <div class="auth-form__row">
                <div class="form-group">
                    <label class="form-label" for="org_city">Cidade *</label>
                    <input
                        type="text"
                        id="org_city"
                        name="org_city"
                        class="form-input"
                        value="<?= e(old('org_city')) ?>"
                        placeholder="São Paulo"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="org_state">Estado *</label>
                    <select id="org_state" name="org_state" class="form-select" required>
                        <option value="">UF</option>
                        <?php
                        $states = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
                        foreach ($states as $uf):
                        ?>
                            <option value="<?= $uf ?>" <?= old('org_state') === $uf ? 'selected' : '' ?>><?= $uf ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="auth-form__row">
                <div class="form-group">
                    <label class="form-label" for="org_website">Site</label>
                    <input
                        type="url"
                        id="org_website"
                        name="org_website"
                        class="form-input"
                        value="<?= e(old('org_website')) ?>"
                        placeholder="https://..."
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="org_members_count">Membros/Equipe (aprox.)</label>
                    <select id="org_members_count" name="org_members_count" class="form-select">
                        <option value="">Selecione...</option>
                        <option value="1-50" <?= old('org_members_count') === '1-50' ? 'selected' : '' ?>>1 a 50</option>
                        <option value="51-200" <?= old('org_members_count') === '51-200' ? 'selected' : '' ?>>51 a 200</option>
                        <option value="201-500" <?= old('org_members_count') === '201-500' ? 'selected' : '' ?>>201 a 500</option>
                        <option value="501-1000" <?= old('org_members_count') === '501-1000' ? 'selected' : '' ?>>501 a 1.000</option>
                        <option value="1000+" <?= old('org_members_count') === '1000+' ? 'selected' : '' ?>>Mais de 1.000</option>
                    </select>
                </div>
            </div>

            <div class="auth-form__actions">
                <button type="submit" class="btn btn--primary auth-form__submit">Cadastrar organização</button>
            </div>
        </form>
    </div>

    <p class="auth-form__footer" style="margin-top: var(--space-4);">
        <a href="<?= url('/hub') ?>" style="color: var(--color-text-muted);">Pular por enquanto →</a>
    </p>
</div>

<?php $__view->endSection(); ?>
