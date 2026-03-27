<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php $isEdit = $ministry !== null; ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title"><?= $isEdit ? 'Editar ministério' : 'Novo ministério' ?></h1></div></div>
<div class="mgmt-form-card">
    <form method="POST" action="<?= $isEdit ? url('/gestao/ministerios/' . $ministry['id'] . '/editar') : url('/gestao/ministerios') ?>" data-loading>
        <?= csrf_field() ?>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" value="<?= e($isEdit ? $ministry['name'] : old('name')) ?>" required></div>
            <div class="form-group"><label class="form-label">Cor</label><input type="color" name="color" class="form-input" value="<?= e($isEdit ? ($ministry['color'] ?? '#0A4DFF') : '#0A4DFF') ?>" style="height:42px;padding:4px;"></div>
        </div>
        <div class="form-group"><label class="form-label">Descrição</label><textarea name="description" class="form-input" rows="3"><?= e($isEdit ? $ministry['description'] : old('description')) ?></textarea></div>
        <div class="form-group">
            <label class="form-label">Líder</label>
            <select name="leader_member_id" class="form-select">
                <option value="">Selecione...</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= ($isEdit && $ministry['leader_member_id'] == $m['id']) ? 'selected' : '' ?>><?= e($m['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Membros do ministério</label>
            <div style="max-height:200px;overflow-y:auto;border:1px solid var(--color-border);border-radius:var(--radius-md);padding:var(--space-3);">
                <?php
                    $currentIds = [];
                    if ($isEdit && !empty($current_members)) { foreach($current_members as $cm) { $currentIds[] = $cm['id']; } }
                ?>
                <?php foreach ($members as $m): ?>
                    <label style="display:flex;align-items:center;gap:8px;padding:4px 0;font-size:var(--text-sm);cursor:pointer;">
                        <input type="checkbox" name="members[]" value="<?= $m['id'] ?>" <?= in_array($m['id'], $currentIds) ? 'checked' : '' ?>>
                        <?= e($m['name']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if ($isEdit): ?>
        <div class="form-group"><label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active" <?= $ministry['status'] === 'active' ? 'selected' : '' ?>>Ativo</option>
                <option value="inactive" <?= $ministry['status'] === 'inactive' ? 'selected' : '' ?>>Inativo</option>
            </select>
        </div>
        <?php endif; ?>
        <div class="mgmt-form-actions">
            <button type="submit" class="btn btn--primary"><?= $isEdit ? 'Salvar' : 'Criar ministério' ?></button>
            <a href="<?= url('/gestao/ministerios') ?>" class="btn btn--ghost">Cancelar</a>
        </div>
    </form>
</div>
<?php $__view->endSection(); ?>
