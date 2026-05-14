<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
$isEdit        = $item !== null;
$services      = $services ?? [];
$organizations = $organizations ?? [];
$users         = is_array($users ?? null) ? $users : [];
$currentTargetType = $isEdit ? ($item['target_type'] ?? '') : '';
$currentTargetId   = $isEdit ? ($item['target_id'] ?? '') : '';
?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title"><?= $isEdit ? 'Editar cortesia' : 'Nova cortesia' ?></h1></div></div>
<div class="mgmt-form-card" style="max-width:760px;">
    <form method="POST" action="<?= $isEdit ? url('/admin/cortesias/' . $item['id'] . '/editar') : url('/admin/cortesias') ?>"><?= csrf_field() ?>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" value="<?= e($isEdit ? $item['name'] : '') ?>" required></div>
            <div class="form-group"><label class="form-label">Slug *</label><input type="text" name="slug" class="form-input" value="<?= e($isEdit ? $item['slug'] : '') ?>" required></div>
        </div>
        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">Serviço liberado</label>
                <select name="service_id" class="form-select">
                    <option value="">Todos os serviços</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= $service['id'] ?>" <?= $isEdit && (int)($item['service_id'] ?? 0) === (int)$service['id'] ? 'selected' : '' ?>><?= e($service['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label class="form-label">Duração da cortesia</label><input type="number" name="duration_days" class="form-input" min="1" value="<?= e($isEdit ? ($item['duration_days'] ?? '') : '') ?>" placeholder="Ex.: 30 dias"></div>
        </div>
        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">Vincular a</label>
                <select name="target_type" id="benefit_target_type" class="form-select" onchange="benefitTargetTypeChanged(this.value)">
                    <option value="">Todas as instituições</option>
                    <option value="organization" <?= $currentTargetType === 'organization' ? 'selected' : '' ?>>Instituição específica</option>
                    <option value="user" <?= $currentTargetType === 'user' ? 'selected' : '' ?>>Usuário cadastrado</option>
                </select>
            </div>
            <div class="form-group" id="benefit_org_wrap" style="<?= $currentTargetType !== 'organization' ? 'display:none' : '' ?>">
                <label class="form-label">Instituição</label>
                <select name="target_id" id="benefit_target_org" class="form-select">
                    <option value="">Selecione...</option>
                    <?php foreach ($organizations as $org): ?>
                        <option value="<?= $org['id'] ?>" <?= (string)$currentTargetId === (string)$org['id'] ? 'selected' : '' ?>><?= e($org['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" id="benefit_user_wrap" style="<?= $currentTargetType !== 'user' ? 'display:none' : '' ?>">
                <label class="form-label">Usuário cadastrado</label>
                <select name="target_id" id="benefit_target_user" class="form-select">
                    <option value="">Selecione o usuário...</option>
                    <?php foreach ($users as $user): ?>
                        <?php
                            $userName = (string) ($user['name'] ?? '');
                            $userEmail = (string) ($user['email'] ?? '');
                            $userLabel = trim($userName . ($userEmail !== '' ? ' - ' . $userEmail : ''));
                        ?>
                        <option value="<?= e((string) ($user['id'] ?? '')) ?>" data-label="<?= e($userName !== '' ? $userName : $userEmail) ?>" <?= (string)$currentTargetId === (string)($user['id'] ?? '') ? 'selected' : '' ?>><?= e($userLabel !== '' ? $userLabel : ('Usuário #' . ($user['id'] ?? ''))) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <input type="hidden" name="target_id" id="benefit_target_none" value="" <?= $currentTargetType !== '' ? 'disabled' : '' ?>>
        <div class="form-group"><label class="form-label">Nome exibido do vínculo</label><input type="text" name="target_label" id="benefit_target_label" class="form-input" value="<?= e($isEdit ? ($item['target_label'] ?? '') : '') ?>" placeholder="Ex.: Igreja Central ou Maria Silva"></div>
        <div class="form-group"><label class="form-label">Descrição</label><textarea name="description" class="form-input" rows="3"><?= e($isEdit ? $item['description'] : '') ?></textarea></div>
        <div class="form-group"><label class="form-label">Requisitos</label><textarea name="requirements" class="form-input" rows="3"><?= e($isEdit ? $item['requirements'] : '') ?></textarea></div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Limite de uso</label><input type="number" name="max_usage" class="form-input" value="<?= e($isEdit ? $item['max_usage'] : '') ?>" placeholder="Vazio = ilimitado"></div>
            <div class="form-group"><label class="form-label">Válido até</label><input type="date" name="valid_until" class="form-input" value="<?= e($isEdit ? $item['valid_until'] : '') ?>"></div>
        </div>
        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><?php foreach (['active'=>'Ativo','inactive'=>'Inativo','paused'=>'Pausado'] as $k=>$v): ?><option value="<?= $k ?>" <?= ($isEdit ? $item['status'] : 'active') === $k ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?></select></div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary"><?= $isEdit ? 'Salvar' : 'Criar' ?></button><a href="<?= url('/admin/cortesias') ?>" class="btn btn--secondary">Cancelar</a></div>
    </form>
</div>
<script>
function benefitTargetTypeChanged(type) {
    var orgWrap  = document.getElementById('benefit_org_wrap');
    var userWrap = document.getElementById('benefit_user_wrap');
    var noneInput = document.getElementById('benefit_target_none');
    var orgSel   = document.getElementById('benefit_target_org');
    var userInput = document.getElementById('benefit_target_user');
    var targetLabel = document.getElementById('benefit_target_label');

    orgWrap.style.display  = type === 'organization' ? '' : 'none';
    userWrap.style.display = type === 'user' ? '' : 'none';

    orgSel.disabled    = type !== 'organization';
    userInput.disabled = type !== 'user';
    noneInput.disabled = type !== '';

    if (type !== 'organization') orgSel.value = '';
    if (type !== 'user') userInput.value = '';
    if (type === 'user' && targetLabel && userInput.selectedOptions[0]?.dataset.label && targetLabel.value === '') {
        targetLabel.value = userInput.selectedOptions[0].dataset.label;
    }
}
document.addEventListener('DOMContentLoaded', function () {
    var typeField = document.getElementById('benefit_target_type');
    var userInput = document.getElementById('benefit_target_user');
    var targetLabel = document.getElementById('benefit_target_label');

    if (userInput) {
        userInput.addEventListener('change', function () {
            var option = userInput.selectedOptions[0];
            if (targetLabel && option?.dataset.label) targetLabel.value = option.dataset.label;
        });
    }
    benefitTargetTypeChanged(typeField.value);
});
</script>
<?php $__view->endSection(); ?>
