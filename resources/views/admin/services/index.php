<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
$statusLabels = ['active' => 'Ativo', 'inactive' => 'Inativo'];
$recurrenceLabels = ['one_time' => 'Único', 'monthly' => 'Mensal', 'quarterly' => 'Trimestral', 'yearly' => 'Anual'];
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Serviços</h1>
        <p class="mgmt-header__subtitle">Catálogo de produtos e serviços disponíveis no hub e nas cortesias.</p>
    </div>
    <div class="mgmt-header__actions"><button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-service').style.display='flex'">Novo serviço</button></div>
</div>

<?php if (empty($services)): ?>
    <div class="mgmt-empty"><div class="mgmt-empty__icon"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a4 4 0 0 1-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 1 5.4-5.4l-2.7 2.7-1.4-1.4 2.7-2.7z"></path></svg></div><h3 class="mgmt-empty__title">Nenhum serviço</h3></div>
<?php else: ?>
    <?php if (!empty($degraded)): ?>
        <div class="alert alert--warning" role="alert" style="margin-bottom:1rem;">Banco indisponivel agora. Exibindo o catalogo base para referencia.</div>
    <?php endif; ?>
    <div class="mgmt-table-container admin-services-table-wrap"><table class="mgmt-table admin-services-table"><thead><tr><th>Serviço</th><th>Preço</th><th>Recorrência</th><th>Status</th><th>Ações</th></tr></thead><tbody>
        <?php foreach ($services as $s): ?><tr>
            <td><div class="mgmt-table__name"><?= e($s['name']) ?></div><div class="mgmt-table__sub"><?= e($s['slug']) ?></div></td>
            <td style="font-weight:700;">R$ <?= number_format((float)($s['price'] ?? 0), 2, ',', '.') ?></td>
            <td><?= e($recurrenceLabels[$s['recurrence'] ?? ''] ?? ($s['recurrence'] ?? '-')) ?></td>
            <td><span class="badge badge--<?= e($s['status']) ?>"><?= e($statusLabels[$s['status'] ?? ''] ?? ($s['status'] ?? '-')) ?></span></td>
            <td class="mgmt-table__actions">
                <?php if ((int) ($s['id'] ?? 0) > 0): ?>
                    <a href="<?= url('/admin/servicos/' . $s['id'] . '/editar') ?>">Editar</a>
                <?php else: ?>
                    <span class="badge badge--inactive">Base</span>
                <?php endif; ?>
            </td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
<?php endif; ?>

<div class="modal" id="modal-new-service" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-new-service-title">
    <div class="modal__content">
        <div class="modal__header"><h2 class="modal__title" id="modal-new-service-title">Cadastrar serviço</h2><button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'">&times;</button></div>
        <form method="POST" action="<?= url('/admin/servicos') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="modal-grid">
                    <div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" required></div>
                    <div class="form-group"><label class="form-label">Slug *</label><input type="text" name="slug" class="form-input" required></div>
                    <div class="form-group"><label class="form-label">Preço (R$)</label><input type="number" name="price" class="form-input" step="0.01"></div>
                    <div class="form-group"><label class="form-label">Recorrência</label><select name="recurrence" class="form-select"><option value="one_time">Único</option><option value="monthly">Mensal</option><option value="quarterly">Trimestral</option><option value="yearly">Anual</option></select></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Ativo</option><option value="inactive">Inativo</option></select></div>
                </div>
                <div class="form-group"><label class="form-label">Descrição</label><textarea name="description" class="form-input" rows="3"></textarea></div>
                <div class="form-group"><label class="form-label">Regras</label><textarea name="rules" class="form-input" rows="3"></textarea></div>
            </div>
            <div class="modal__footer"><button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button><button type="submit" class="btn btn--primary">Criar serviço</button></div>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
