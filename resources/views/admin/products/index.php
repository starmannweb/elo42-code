<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div><h1 class="mgmt-header__title">Produtos</h1></div>
    <div class="mgmt-header__actions"><button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-product').style.display='flex'">Novo produto</button></div>
</div>

<?php if (empty($products)): ?>
    <div class="mgmt-empty"><div class="mgmt-empty__icon">📦</div><h3 class="mgmt-empty__title">Nenhum produto</h3><p class="mgmt-empty__text">Cadastre o primeiro produto da plataforma.</p></div>
<?php else: ?>
    <div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Produto</th><th>Categoria</th><th>Preço</th><th>Status</th><th>Destaque</th><th>Ações</th></tr></thead><tbody>
        <?php foreach ($products as $p): ?><tr>
            <td><div class="mgmt-table__name"><?= e($p['name']) ?></div><div class="mgmt-table__sub"><?= e($p['slug']) ?></div></td>
            <td><?= e($p['category_name'] ?? '-') ?></td>
            <td style="font-weight:700;">R$ <?= number_format((float)$p['price'], 2, ',', '.') ?></td>
            <td><span class="badge badge--<?= $p['status'] ?>"><?= e(match($p['status']) { 'active'=>'Ativo','inactive'=>'Inativo','coming_soon'=>'Em breve', default=>$p['status'] }) ?></span></td>
            <td><?= $p['is_featured'] ? 'Sim' : '-' ?></td>
            <td class="mgmt-table__actions"><a href="<?= url('/admin/produtos/' . $p['id'] . '/editar') ?>">Editar</a></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
<?php endif; ?>

<?php if (!empty($categories)): ?>
<div class="mgmt-info-card" style="margin-top:var(--space-6);max-width:500px;"><h3 class="mgmt-info-card__title">Categorias</h3>
    <?php foreach ($categories as $c): ?><div class="mgmt-info-row"><span class="mgmt-info-row__label"><?= e($c['name']) ?></span><span class="mgmt-info-row__value" style="font-family:monospace;font-size:var(--text-xs);"><?= e($c['slug']) ?></span></div><?php endforeach; ?>
</div>
<?php endif; ?>

<div class="mgmt-form-card" style="margin-top:var(--space-4);max-width:500px;">
    <form method="POST" action="<?= url('/admin/produtos/categoria') ?>"><?= csrf_field() ?>
        <h4 style="font-size:var(--text-sm);font-weight:700;margin-bottom:var(--space-3);">Nova categoria</h4>
        <div style="display:flex;gap:var(--space-3);"><input type="text" name="name" class="form-input" placeholder="Nome" required><input type="text" name="slug" class="form-input" placeholder="slug" required><button type="submit" class="btn btn--primary">+</button></div>
    </form>
</div>

<div class="modal" id="modal-new-product" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-new-product-title">
    <div class="modal__content modal__content--wide">
        <div class="modal__header"><h2 class="modal__title" id="modal-new-product-title">Cadastrar produto</h2><button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'">&times;</button></div>
        <form method="POST" action="<?= url('/admin/produtos') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="modal-grid">
                    <div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" required></div>
                    <div class="form-group"><label class="form-label">Slug *</label><input type="text" name="slug" class="form-input" required></div>
                    <div class="form-group"><label class="form-label">Preço (R$)</label><input type="number" name="price" class="form-input" step="0.01"></div>
                    <div class="form-group"><label class="form-label">Categoria</label><select name="category_id" class="form-select"><option value="">Nenhuma</option><?php foreach (($categories ?? []) as $c): ?><option value="<?= (int) $c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Ativo</option><option value="inactive">Inativo</option><option value="coming_soon">Em breve</option></select></div>
                    <div class="form-group"><label class="form-label">Destaque</label><select name="is_featured" class="form-select"><option value="0">Não</option><option value="1">Sim</option></select></div>
                </div>
                <div class="form-group"><label class="form-label">Descrição</label><textarea name="description" class="form-input" rows="3"></textarea></div>
                <div class="form-group"><label class="form-label">Features</label><textarea name="features" class="form-input" rows="3" placeholder="Uma por linha"></textarea></div>
            </div>
            <div class="modal__footer"><button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button><button type="submit" class="btn btn--primary">Criar produto</button></div>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
