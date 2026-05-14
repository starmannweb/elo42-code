<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
$services = is_array($services ?? null) ? $services : [];
$users = is_array($users ?? null) ? $users : [];
$organizations = is_array($organizations ?? null) ? $organizations : [];
$statusLabels = ['active' => 'Ativo', 'inactive' => 'Inativo', 'paused' => 'Pausado'];
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Cortesias</h1>
        <p class="mgmt-header__subtitle">Libere produtos ou servi&ccedil;os para uma igreja/usu&aacute;rio por um per&iacute;odo definido.</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-benefit').style.display='flex'">Nova cortesia</button>
    </div>
</div>

<?php if (empty($benefits)): ?>
    <div class="mgmt-empty">
        <div class="mgmt-empty__icon"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4"></rect><path d="M5 12v9h14v-9"></path><path d="M12 8v13"></path><path d="M7.5 8a2.5 2.5 0 0 1 0-5C9 3 12 8 12 8s3-5 4.5-5a2.5 2.5 0 0 1 0 5"></path></svg></div>
        <h3 class="mgmt-empty__title">Nenhuma cortesia</h3>
    </div>
<?php else: ?>
    <div class="mgmt-table-container">
        <table class="mgmt-table">
            <thead>
                <tr><th>Cortesia</th><th>Produto/servi&ccedil;o</th><th>V&iacute;nculo</th><th>Prazo</th><th>Utiliza&ccedil;&otilde;es</th><th>Limite</th><th>V&aacute;lido at&eacute;</th><th>Status</th><th>A&ccedil;&otilde;es</th></tr>
            </thead>
            <tbody>
                <?php foreach ($benefits as $b): ?>
                    <tr>
                        <td><div class="mgmt-table__name"><?= e($b['name']) ?></div><div class="mgmt-table__sub"><?= e($b['slug']) ?></div></td>
                        <td><?= e($b['service_name'] ?? 'Qualquer servi&ccedil;o') ?></td>
                        <td><?= e(($b['target_label'] ?? '') ?: (($b['target_type'] ?? '') === 'user' ? 'Usu&aacute;rio' : (($b['target_type'] ?? '') === 'organization' ? 'Institui&ccedil;&atilde;o' : 'Livre'))) ?></td>
                        <td><?= $b['duration_days'] ? (int) $b['duration_days'] . ' dias' : 'Sem prazo autom&aacute;tico' ?></td>
                        <td><?= $b['usage_count'] ?? 0 ?></td>
                        <td><?= $b['max_usage'] ?? 'Ilimitado' ?></td>
                        <td><?= $b['valid_until'] ? date('d/m/Y', strtotime($b['valid_until'])) : '-' ?></td>
                        <td><span class="badge badge--<?= e($b['status']) ?>"><?= e($statusLabels[$b['status'] ?? ''] ?? ($b['status'] ?? '-')) ?></span></td>
                        <td class="mgmt-table__actions"><a href="<?= url('/admin/cortesias/' . $b['id'] . '/editar') ?>">Editar</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div class="modal" id="modal-new-benefit" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-new-benefit-title">
    <div class="modal__content">
        <div class="modal__header">
            <h2 class="modal__title" id="modal-new-benefit-title">Cadastrar cortesia</h2>
            <button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/admin/cortesias') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="modal-grid">
                    <div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" required></div>
                    <div class="form-group"><label class="form-label">Slug *</label><input type="text" name="slug" class="form-input" required></div>
                    <div class="form-group"><label class="form-label">Produto/servi&ccedil;o liberado</label><select name="service_id" class="form-select"><option value="">Qualquer servi&ccedil;o</option><?php foreach ($services as $service): ?><option value="<?= $service['id'] ?>"><?= e($service['name']) ?></option><?php endforeach; ?></select></div>
                    <div class="form-group"><label class="form-label">Vincular a</label><select name="target_type" class="form-select" data-benefit-target-type><option value="">Definir depois</option><option value="organization">Institui&ccedil;&atilde;o</option><option value="user">Usu&aacute;rio cadastrado</option></select></div>
                    <div class="form-group" data-benefit-organization-group><label class="form-label">Institui&ccedil;&atilde;o</label><select name="target_id" class="form-select" data-benefit-organization-select disabled><option value="">Selecione uma institui&ccedil;&atilde;o</option><?php foreach ($organizations as $organization): ?><option value="<?= (int) $organization['id'] ?>" data-label="<?= e((string) ($organization['name'] ?? '')) ?>"><?= e((string) ($organization['name'] ?? 'Institui&ccedil;&atilde;o')) ?></option><?php endforeach; ?></select></div>
                    <div class="form-group" data-benefit-user-group><label class="form-label">Usu&aacute;rio cadastrado</label><select name="target_id" class="form-select" data-benefit-user-select disabled><option value="">Selecione um usu&aacute;rio</option><?php foreach ($users as $user): ?><option value="<?= (int) $user['id'] ?>" data-label="<?= e((string) ($user['name'] ?? $user['email'] ?? '')) ?>"><?= e((string) ($user['name'] ?? 'Usu&aacute;rio')) ?><?= !empty($user['email']) ? ' - ' . e((string) $user['email']) : '' ?></option><?php endforeach; ?></select></div>
                    <div class="form-group"><label class="form-label">Nome exibido do v&iacute;nculo</label><input type="text" name="target_label" class="form-input" placeholder="Ex.: Igreja Central ou Maria Silva" data-benefit-target-label></div>
                    <div class="form-group"><label class="form-label">Dura&ccedil;&atilde;o da cortesia</label><input type="number" name="duration_days" class="form-input" min="1" placeholder="Ex.: 30 dias"></div>
                    <div class="form-group"><label class="form-label">Limite de uso</label><input type="number" name="max_usage" class="form-input" placeholder="Vazio = ilimitado"></div>
                    <div class="form-group"><label class="form-label">V&aacute;lido at&eacute;</label><input type="date" name="valid_until" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Ativo</option><option value="inactive">Inativo</option><option value="paused">Pausado</option></select></div>
                </div>
                <div class="form-group"><label class="form-label">Descri&ccedil;&atilde;o</label><textarea name="description" class="form-input" rows="3"></textarea></div>
                <div class="form-group"><label class="form-label">Requisitos</label><textarea name="requirements" class="form-input" rows="3"></textarea></div>
            </div>
            <div class="modal__footer"><button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button><button type="submit" class="btn btn--primary">Criar cortesia</button></div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('[data-benefit-target-type]').forEach((typeField) => {
    const form = typeField.closest('form');
    const organizationSelect = form?.querySelector('[data-benefit-organization-select]');
    const userSelect = form?.querySelector('[data-benefit-user-select]');
    const organizationGroup = form?.querySelector('[data-benefit-organization-group]');
    const userGroup = form?.querySelector('[data-benefit-user-group]');
    const targetLabel = form?.querySelector('[data-benefit-target-label]');

    const fillLabel = (select) => {
        const option = select?.selectedOptions?.[0];
        if (targetLabel && option?.dataset?.label) targetLabel.value = option.dataset.label;
    };

    const sync = () => {
        const type = typeField.value;
        const isOrganization = type === 'organization';
        const isUser = type === 'user';

        if (organizationSelect) {
            organizationSelect.disabled = !isOrganization;
            organizationGroup.style.display = isOrganization ? '' : 'none';
            if (!isOrganization) organizationSelect.value = '';
        }
        if (userSelect) {
            userSelect.disabled = !isUser;
            userGroup.style.display = isUser ? '' : 'none';
            if (!isUser) userSelect.value = '';
        }
        if (!isOrganization && !isUser && targetLabel) targetLabel.value = '';
        if (isOrganization) fillLabel(organizationSelect);
        if (isUser) fillLabel(userSelect);
    };

    organizationSelect?.addEventListener('change', () => fillLabel(organizationSelect));
    userSelect?.addEventListener('change', () => fillLabel(userSelect));
    typeField.addEventListener('change', sync);
    sync();
});
</script>
<?php $__view->endSection(); ?>
