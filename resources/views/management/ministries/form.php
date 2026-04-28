<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php
    $isEdit = $ministry !== null;
    $currentIds = [];
    if ($isEdit && !empty($current_members)) {
        foreach ($current_members as $currentMember) {
            $currentIds[] = (int) $currentMember['id'];
        }
    }
    $units = is_array($units ?? null) ? $units : [];
    $selectedUnit = (string) ($isEdit ? ($ministry['church_unit_id'] ?? '') : old('church_unit_id'));
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title"><?= $isEdit ? 'Editar ministério' : 'Novo ministério' ?></h1>
        <p class="mgmt-header__subtitle">Defina a liderança e os membros ministeriais que servem nesta equipe.</p>
    </div>
</div>

<form method="POST" action="<?= $isEdit ? url('/gestao/ministerios/' . $ministry['id'] . '/editar') : url('/gestao/ministerios') ?>" class="ministry-form" data-loading>
    <?= csrf_field() ?>

    <div class="mgmt-grid ministry-form__grid" style="grid-template-columns:minmax(0, 0.9fr) minmax(320px, 1.1fr); gap:1.25rem;">
        <section class="mgmt-panel">
            <h3 class="mgmt-panel__title">Dados do ministério</h3>
            <p class="mgmt-panel__hint">Use nomes claros como Infantil, Louvor, Mídia, Intercessão ou Recepção.</p>

            <div class="form-group">
                <label class="form-label">Nome *</label>
                <input type="text" name="name" class="form-input" value="<?= e($isEdit ? $ministry['name'] : old('name')) ?>" placeholder="Ex: Ministério de Louvor" required>
            </div>

            <div class="form-group">
                <label class="form-label">Descrição</label>
                <textarea name="description" class="form-input" rows="4" placeholder="Resumo da responsabilidade, escala ou foco deste ministério."><?= e($isEdit ? $ministry['description'] : old('description')) ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Unidade</label>
                <select name="church_unit_id" class="form-select">
                    <option value="">Sede / todas as unidades</option>
                    <?php foreach ($units as $unit): ?>
                        <option value="<?= (int) $unit['id'] ?>" <?= $selectedUnit === (string) $unit['id'] ? 'selected' : '' ?>>
                            <?= e((string) $unit['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mgmt-form-row">
                <div class="form-group">
                    <label class="form-label">Cor de identificação</label>
                    <input type="color" name="color" class="form-input ministry-color-input" value="<?= e($isEdit ? ($ministry['color'] ?? '#0A4DFF') : '#0A4DFF') ?>">
                </div>
                <?php if ($isEdit): ?>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= ($ministry['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Ativo</option>
                            <option value="inactive" <?= ($ministry['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inativo</option>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="mgmt-panel">
            <h3 class="mgmt-panel__title">Membros ministeriais</h3>
            <p class="mgmt-panel__hint">Escolha o líder e marque todos os membros que fazem parte da escala ministerial.</p>

            <div class="form-group">
                <label class="form-label">Líder do ministério</label>
                <select name="leader_member_id" class="form-select" id="leader_member_id">
                    <option value="">Selecione o líder...</option>
                    <?php foreach ($members as $member): ?>
                        <option value="<?= (int) $member['id'] ?>" <?= ($isEdit && (int) ($ministry['leader_member_id'] ?? 0) === (int) $member['id']) ? 'selected' : '' ?>>
                            <?= e($member['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="ministry-members-box">
                <div class="ministry-members-box__header">
                    <span>Participantes</span>
                    <strong id="selected-members-count"><?= count($currentIds) ?></strong>
                </div>

                <?php if (empty($members)): ?>
                    <div class="mgmt-empty ministry-members-box__empty">
                        <h3 class="mgmt-empty__title">Nenhum membro cadastrado</h3>
                        <p class="mgmt-empty__text">Cadastre membros antes de montar uma equipe ministerial.</p>
                        <a href="<?= url('/gestao/membros/novo') ?>" class="btn btn--primary">Cadastrar membro</a>
                    </div>
                <?php else: ?>
                    <div class="ministry-members-box__list" id="ministry-members-list">
                        <?php foreach ($members as $member): ?>
                            <?php
                                $memberId = (int) $member['id'];
                                $checked = in_array($memberId, $currentIds, true);
                            ?>
                            <label class="ministry-member-option">
                                <input type="checkbox" name="members[]" value="<?= $memberId ?>" <?= $checked ? 'checked' : '' ?>>
                                <span class="ministry-member-option__avatar"><?= e(strtoupper(substr((string) $member['name'], 0, 1))) ?></span>
                                <span class="ministry-member-option__body">
                                    <strong><?= e($member['name']) ?></strong>
                                    <small><?= e((string) ($member['email'] ?? $member['phone'] ?? 'Membro cadastrado')) ?></small>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <div class="mgmt-form-actions ministry-form__actions">
        <a href="<?= url('/gestao/ministerios') ?>" class="btn btn--ghost">Cancelar</a>
        <button type="submit" class="btn btn--primary"><?= $isEdit ? 'Salvar ministério' : 'Criar ministério' ?></button>
    </div>
</form>

<script>
    (() => {
        const list = document.getElementById('ministry-members-list');
        const count = document.getElementById('selected-members-count');
        const leader = document.getElementById('leader_member_id');
        if (!list || !count) return;

        const updateCount = () => {
            count.textContent = String(list.querySelectorAll('input[type="checkbox"]:checked').length);
        };

        list.addEventListener('change', updateCount);
        leader?.addEventListener('change', () => {
            const selected = leader.value;
            if (!selected) return;
            const checkbox = list.querySelector(`input[type="checkbox"][value="${selected}"]`);
            if (checkbox) {
                checkbox.checked = true;
                updateCount();
            }
        });
        updateCount();
    })();
</script>
<?php $__view->endSection(); ?>
