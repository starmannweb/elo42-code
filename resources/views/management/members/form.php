<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>

<?php
    $isEdit = $member !== null;
    $units = is_array($units ?? null) ? $units : [];
    $selectedUnit = (string) ($isEdit ? ($member['church_unit_id'] ?? '') : old('church_unit_id'));
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title"><?= $isEdit ? 'Editar membro' : 'Novo membro' ?></h1>
        <p class="mgmt-header__subtitle"><?= $isEdit ? 'Atualize os dados do membro.' : 'Preencha os dados do novo membro.' ?></p>
    </div>
</div>

<?php $errors = flash('errors') ?? []; ?>

<div class="mgmt-form-card">
    <form method="POST" action="<?= $isEdit ? url('/gestao/membros/' . $member['id'] . '/editar') : url('/gestao/membros') ?>" data-loading>
        <?= csrf_field() ?>

        <h3 class="mgmt-form-card__title">Dados pessoais</h3>

        <div class="form-group">
            <label class="form-label">Nome completo *</label>
            <input type="text" name="name" class="form-input" value="<?= e($isEdit ? $member['name'] : old('name')) ?>" required>
        </div>

        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-input" value="<?= e($isEdit ? $member['email'] : old('email')) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Telefone</label>
                <input type="tel" name="phone" class="form-input" value="<?= e($isEdit ? $member['phone'] : old('phone')) ?>">
            </div>
        </div>

        <div class="mgmt-form-row--3">
            <div class="form-group">
                <label class="form-label">Data de nascimento</label>
                <input type="date" name="birth_date" class="form-input" value="<?= e($isEdit ? $member['birth_date'] : old('birth_date')) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Gênero</label>
                <select name="gender" class="form-select">
                    <option value="">—</option>
                    <?php foreach (['M' => 'Masculino', 'F' => 'Feminino', 'other' => 'Outro'] as $k => $v): ?>
                        <option value="<?= $k ?>" <?= ($isEdit ? $member['gender'] : old('gender')) === $k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Estado civil</label>
                <select name="marital_status" class="form-select">
                    <option value="">—</option>
                    <?php foreach (['single' => 'Solteiro(a)', 'married' => 'Casado(a)', 'divorced' => 'Divorciado(a)', 'widowed' => 'Viúvo(a)'] as $k => $v): ?>
                        <option value="<?= $k ?>" <?= ($isEdit ? $member['marital_status'] : old('marital_status')) === $k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <h3 class="mgmt-form-card__title" style="margin-top: var(--space-6)">Endereço</h3>

        <div class="form-group">
            <label class="form-label">Endereço</label>
            <input type="text" name="address" class="form-input" value="<?= e($isEdit ? $member['address'] : old('address')) ?>">
        </div>

        <div class="mgmt-form-row--3">
            <div class="form-group">
                <label class="form-label">Cidade</label>
                <input type="text" name="city" class="form-input" value="<?= e($isEdit ? $member['city'] : old('city')) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Estado</label>
                <select name="state" class="form-select">
                    <option value="">UF</option>
                    <?php foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf): ?>
                        <option value="<?= $uf ?>" <?= ($isEdit ? $member['state'] : old('state')) === $uf ? 'selected' : '' ?>><?= $uf ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">CEP</label>
                <input type="text" name="zip_code" class="form-input" value="<?= e($isEdit ? $member['zip_code'] : old('zip_code')) ?>">
            </div>
        </div>

        <h3 class="mgmt-form-card__title" style="margin-top: var(--space-6)">Igreja</h3>

        <div class="mgmt-form-row--3">
            <div class="form-group">
                <label class="form-label">Unidade</label>
                <select name="church_unit_id" class="form-select">
                    <option value="">Sede / não definida</option>
                    <?php foreach ($units as $unit): ?>
                        <option value="<?= (int) $unit['id'] ?>" <?= $selectedUnit === (string) $unit['id'] ? 'selected' : '' ?>>
                            <?= e((string) $unit['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Membro desde</label>
                <input type="date" name="membership_date" class="form-input" value="<?= e($isEdit ? $member['membership_date'] : old('membership_date')) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Data de batismo</label>
                <input type="date" name="baptism_date" class="form-input" value="<?= e($isEdit ? $member['baptism_date'] : old('baptism_date')) ?>">
            </div>
        </div>

        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <?php foreach (['active' => 'Ativo', 'inactive' => 'Inativo', 'visitor' => 'Visitante', 'transferred' => 'Transferido'] as $k => $v): ?>
                        <option value="<?= $k ?>" <?= ($isEdit ? $member['status'] : (old('status') ?: 'active')) === $k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Observações</label>
            <textarea name="notes" class="form-input" rows="3"><?= e($isEdit ? $member['notes'] : old('notes')) ?></textarea>
        </div>

        <div class="mgmt-form-actions">
            <button type="submit" class="btn btn--primary"><?= $isEdit ? 'Salvar alterações' : 'Cadastrar membro' ?></button>
            <a href="<?= url('/gestao/membros') ?>" class="btn btn--ghost">Cancelar</a>
        </div>
    </form>
</div>

<?php $__view->endSection(); ?>
