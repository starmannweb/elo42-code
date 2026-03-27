<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Benefícios</h1></div><div class="mgmt-header__actions"><a href="<?= url('/admin/beneficios/novo') ?>" class="btn btn--primary">+ Novo benefício</a></div></div>
<?php if (empty($benefits)): ?><div class="mgmt-empty"><div class="mgmt-empty__icon">🎁</div><h3 class="mgmt-empty__title">Nenhum benefício</h3></div>
<?php else: ?>
    <div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Benefício</th><th>Utilizações</th><th>Limite</th><th>Válido até</th><th>Status</th><th>Ações</th></tr></thead><tbody>
        <?php foreach ($benefits as $b): ?><tr>
            <td><div class="mgmt-table__name"><?= e($b['name']) ?></div><div class="mgmt-table__sub"><?= e($b['slug']) ?></div></td>
            <td><?= $b['usage_count'] ?? 0 ?></td>
            <td><?= $b['max_usage'] ?? '∞' ?></td>
            <td><?= $b['valid_until'] ? date('d/m/Y', strtotime($b['valid_until'])) : '—' ?></td>
            <td><span class="badge badge--<?= $b['status'] ?>"><?= ucfirst(e($b['status'])) ?></span></td>
            <td class="mgmt-table__actions"><a href="<?= url('/admin/beneficios/' . $b['id'] . '/editar') ?>">Editar</a></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
<?php endif; ?>
<?php $__view->endSection(); ?>
