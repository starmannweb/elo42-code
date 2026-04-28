<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div><h1 class="mgmt-header__title">Cortesias</h1></div>
    <div class="mgmt-header__actions"><button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-benefit').style.display='flex'">Nova cortesia</button></div>
</div>

<?php if (empty($benefits)): ?>
    <div class="mgmt-empty"><div class="mgmt-empty__icon">🎁</div><h3 class="mgmt-empty__title">Nenhuma cortesia</h3></div>
<?php else: ?>
    <div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Cortesia</th><th>Utilizações</th><th>Limite</th><th>Válido até</th><th>Status</th><th>Ações</th></tr></thead><tbody>
        <?php foreach ($benefits as $b): ?><tr>
            <td><div class="mgmt-table__name"><?= e($b['name']) ?></div><div class="mgmt-table__sub"><?= e($b['slug']) ?></div></td>
            <td><?= $b['usage_count'] ?? 0 ?></td>
            <td><?= $b['max_usage'] ?? '∞' ?></td>
            <td><?= $b['valid_until'] ? date('d/m/Y', strtotime($b['valid_until'])) : '-' ?></td>
            <td><span class="badge badge--<?= $b['status'] ?>"><?= ucfirst(e($b['status'])) ?></span></td>
            <td class="mgmt-table__actions"><a href="<?= url('/admin/cortesias/' . $b['id'] . '/editar') ?>">Editar</a></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
<?php endif; ?>

<div class="modal" id="modal-new-benefit" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-new-benefit-title">
    <div class="modal__content">
        <div class="modal__header"><h2 class="modal__title" id="modal-new-benefit-title">Cadastrar cortesia</h2><button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'">&times;</button></div>
        <form method="POST" action="<?= url('/admin/cortesias') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="modal-grid">
                    <div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" required></div>
                    <div class="form-group"><label class="form-label">Slug *</label><input type="text" name="slug" class="form-input" required></div>
                    <div class="form-group"><label class="form-label">Limite de uso</label><input type="number" name="max_usage" class="form-input" placeholder="Vazio = ilimitado"></div>
                    <div class="form-group"><label class="form-label">Válido até</label><input type="date" name="valid_until" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Ativo</option><option value="inactive">Inativo</option><option value="paused">Pausado</option></select></div>
                </div>
                <div class="form-group"><label class="form-label">Descrição</label><textarea name="description" class="form-input" rows="3"></textarea></div>
                <div class="form-group"><label class="form-label">Requisitos</label><textarea name="requirements" class="form-input" rows="3"></textarea></div>
            </div>
            <div class="modal__footer"><button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button><button type="submit" class="btn btn--primary">Criar cortesia</button></div>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
