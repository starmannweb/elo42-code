<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>

<?php
    $isEdit = $member !== null;
    $units = is_array($units ?? null) ? $units : [];
    $selectedUnit = (string) ($isEdit ? ($member['church_unit_id'] ?? '') : old('church_unit_id'));
    $selectedState = (string) ($isEdit ? ($member['state'] ?? '') : old('state'));
    $selectedCity = (string) ($isEdit ? ($member['city'] ?? '') : old('city'));
    $memberPhoto = trim((string) ($isEdit ? ($member['photo'] ?? '') : old('photo')));
    $memberPhotoUrl = $memberPhoto !== '' ? (preg_match('#^https?://#i', $memberPhoto) ? $memberPhoto : url($memberPhoto)) : '';
    $memberNameForInitial = trim((string) ($isEdit ? ($member['name'] ?? '') : old('name')));
    $memberInitial = strtoupper(substr($memberNameForInitial !== '' ? $memberNameForInitial : 'Membro', 0, 1));
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title"><?= $isEdit ? 'Editar membro' : 'Novo membro' ?></h1>
        <p class="mgmt-header__subtitle"><?= $isEdit ? 'Atualize os dados do membro.' : 'Preencha os dados do novo membro.' ?></p>
    </div>
</div>

<?php $errors = flash('errors') ?? []; ?>

<div class="mgmt-form-card mgmt-member-form-card">
    <form method="POST" action="<?= $isEdit ? url('/gestao/membros/' . $member['id'] . '/editar') : url('/gestao/membros') ?>" data-loading>
        <?= csrf_field() ?>

        <h3 class="mgmt-form-card__title">Dados pessoais</h3>

        <div class="member-photo-upload" data-member-photo-upload>
            <div class="member-photo-upload__preview" data-member-photo-preview aria-hidden="true">
                <?php if ($memberPhotoUrl !== ''): ?>
                    <img src="<?= e($memberPhotoUrl) ?>" alt="">
                <?php else: ?>
                    <span data-member-photo-initial><?= e($memberInitial) ?></span>
                <?php endif; ?>
            </div>
            <input type="hidden" name="photo_cropped" data-member-photo-output>
            <input type="file" accept="image/png,image/jpeg,image/webp" data-member-photo-input hidden>
            <button type="button" class="btn btn--ghost member-photo-upload__button" data-member-photo-trigger>Enviar foto</button>
            <small class="member-photo-upload__hint">A imagem será recortada em formato redondo.</small>
        </div>

        <div class="form-group">
            <label class="form-label">Nome completo *</label>
            <input type="text" name="name" class="form-input" value="<?= e($isEdit ? $member['name'] : old('name')) ?>" required>
        </div>

        <div class="mgmt-form-row--3">
            <div class="form-group">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-input" value="<?= e($isEdit ? $member['email'] : old('email')) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Telefone</label>
                <input type="tel" name="phone" class="form-input" value="<?= e($isEdit ? $member['phone'] : old('phone')) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Data de nascimento</label>
                <input type="date" name="birth_date" class="form-input" value="<?= e($isEdit ? $member['birth_date'] : old('birth_date')) ?>">
            </div>
        </div>

        <div class="mgmt-form-row--3">
            <div class="form-group">
                <label class="form-label">Gênero</label>
                <select name="gender" class="form-select">
                    <option value="">-</option>
                    <?php foreach (['M' => 'Masculino', 'F' => 'Feminino', 'other' => 'Outro'] as $k => $v): ?>
                        <option value="<?= $k ?>" <?= ($isEdit ? $member['gender'] : old('gender')) === $k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Estado civil</label>
                <select name="marital_status" class="form-select">
                    <option value="">-</option>
                    <?php foreach (['single' => 'Solteiro(a)', 'married' => 'Casado(a)', 'divorced' => 'Divorciado(a)', 'widowed' => 'Viúvo(a)'] as $k => $v): ?>
                        <option value="<?= $k ?>" <?= ($isEdit ? $member['marital_status'] : old('marital_status')) === $k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <?php foreach (['active' => 'Ativo', 'inactive' => 'Inativo', 'visitor' => 'Visitante', 'transferred' => 'Transferido'] as $k => $v): ?>
                        <option value="<?= $k ?>" <?= ($isEdit ? $member['status'] : (old('status') ?: 'active')) === $k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <h3 class="mgmt-form-card__title" style="margin-top: var(--space-6)">Endereço</h3>

        <div class="mgmt-form-row--3">
            <div class="form-group">
                <label class="form-label">CEP</label>
                <input type="text" name="zip_code" class="form-input" value="<?= e($isEdit ? $member['zip_code'] : old('zip_code')) ?>" inputmode="numeric" autocomplete="postal-code" placeholder="00000-000">
            </div>
            <div class="form-group">
                <label class="form-label">Estado</label>
                <select name="state" class="form-select" data-selected-state="<?= e($selectedState) ?>">
                    <option value="">UF</option>
                    <?php foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf): ?>
                        <option value="<?= $uf ?>" <?= $selectedState === $uf ? 'selected' : '' ?>><?= $uf ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Cidade</label>
                <select name="city" class="form-select" data-selected-city="<?= e($selectedCity) ?>">
                    <option value=""><?= $selectedState !== '' ? 'Carregando...' : 'Selecione a UF' ?></option>
                </select>
            </div>
        </div>

        <div class="mgmt-form-row--3">
            <div class="form-group mgmt-form-field--span-2">
                <label class="form-label">Endereço</label>
                <input type="text" name="address" class="form-input" value="<?= e($isEdit ? $member['address'] : old('address')) ?>" autocomplete="street-address">
            </div>
            <div class="form-group">
                <label class="form-label">N&ordm;</label>
                <input type="text" name="address_number" class="form-input" value="<?= e(old('address_number')) ?>" inputmode="numeric" autocomplete="address-line2">
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

<script>
(() => {
    const form = document.querySelector('form[action*="/gestao/membros"]');
    if (!form) return;

    const zip = form.querySelector('[name="zip_code"]');
    const address = form.querySelector('[name="address"]');
    const city = form.querySelector('[name="city"]');
    const state = form.querySelector('[name="state"]');
    const photoInput = form.querySelector('[data-member-photo-input]');
    const photoOutput = form.querySelector('[data-member-photo-output]');
    const photoTrigger = form.querySelector('[data-member-photo-trigger]');
    const photoPreview = form.querySelector('[data-member-photo-preview]');
    const selectedState = state?.dataset.selectedState || state?.value || '';
    const selectedCity = city?.dataset.selectedCity || '';
    const ibgeBase = 'https://servicodados.ibge.gov.br/api/v1/localidades';

    const updatePhotoPreview = (dataUrl) => {
        if (!photoPreview || !photoOutput) return;
        photoOutput.value = dataUrl;
        photoPreview.innerHTML = '';
        const previewImage = document.createElement('img');
        previewImage.src = dataUrl;
        previewImage.alt = '';
        photoPreview.appendChild(previewImage);
    };

    const cropPhoto = (file) => {
        if (!file || !file.type.startsWith('image/')) return;

        const reader = new FileReader();
        reader.onload = () => {
            const image = new Image();
            image.onload = () => {
                const size = 480;
                const sourceSize = Math.min(image.naturalWidth, image.naturalHeight);
                const sourceX = Math.max(0, (image.naturalWidth - sourceSize) / 2);
                const sourceY = Math.max(0, (image.naturalHeight - sourceSize) / 2);
                const canvas = document.createElement('canvas');
                canvas.width = size;
                canvas.height = size;
                const ctx = canvas.getContext('2d');
                if (!ctx) return;

                ctx.clearRect(0, 0, size, size);
                ctx.save();
                ctx.beginPath();
                ctx.arc(size / 2, size / 2, size / 2, 0, Math.PI * 2);
                ctx.clip();
                ctx.drawImage(image, sourceX, sourceY, sourceSize, sourceSize, 0, 0, size, size);
                ctx.restore();
                updatePhotoPreview(canvas.toDataURL('image/png'));
            };
            image.src = String(reader.result || '');
        };
        reader.readAsDataURL(file);
    };

    photoTrigger?.addEventListener('click', () => photoInput?.click());
    photoInput?.addEventListener('change', () => cropPhoto(photoInput.files?.[0]));

    const setCityPlaceholder = (label) => {
        if (!city) return;
        city.innerHTML = `<option value="">${label}</option>`;
    };

    const loadCities = async (uf, selected = '') => {
        if (!city || !uf) {
            setCityPlaceholder('Selecione a UF');
            return;
        }

        setCityPlaceholder('Carregando...');
        try {
            const response = await fetch(`${ibgeBase}/estados/${encodeURIComponent(uf)}/municipios?orderBy=nome`);
            const cities = await response.json();
            city.innerHTML = '<option value="">Selecione</option>';
            cities.forEach((item) => {
                const option = document.createElement('option');
                option.value = item.nome;
                option.textContent = item.nome;
                if (selected && selected === item.nome) option.selected = true;
                city.appendChild(option);
            });
        } catch (error) {
            setCityPlaceholder('Nao foi possivel carregar');
        }
    };

    const applyCep = async () => {
        const value = (zip?.value || '').replace(/\D+/g, '');
        if (value.length !== 8) return;

        try {
            const response = await fetch(`https://viacep.com.br/ws/${value}/json/`);
            const data = await response.json();
            if (data?.erro) return;

            if (address && !address.value) {
                address.value = [data.logradouro, data.bairro].filter(Boolean).join(', ');
            }
            if (state && data.uf) {
                state.value = data.uf;
                await loadCities(data.uf, data.localidade || '');
            }
        } catch (error) {}
    };

    state?.addEventListener('change', () => loadCities(state.value));
    zip?.addEventListener('blur', applyCep);
    zip?.addEventListener('input', () => {
        if ((zip.value || '').replace(/\D+/g, '').length === 8) applyCep();
    });

    if (selectedState) {
        if (state) state.value = selectedState;
        loadCities(selectedState, selectedCity);
    }
})();
</script>

<?php $__view->endSection(); ?>
