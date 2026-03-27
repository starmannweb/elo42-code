<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Serviços</h1></div><div class="mgmt-header__actions"><a href="<?= url('/admin/servicos/novo') ?>" class="btn btn--primary">+ Novo serviço</a></div></div>
<?php if (empty($services)): ?><div class="mgmt-empty"><div class="mgmt-empty__icon">🔧</div><h3 class="mgmt-empty__title">Nenhum serviço</h3></div>
<?php else: ?>
    <div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Serviço</th><th>Preço</th><th>Recorrência</th><th>Status</th><th>Ações</th></tr></thead><tbody>
        <?php foreach ($services as $s): ?><tr>
            <td><div class="mgmt-table__name"><?= e($s['name']) ?></div><div class="mgmt-table__sub"><?= e($s['slug']) ?></div></td>
            <td style="font-weight:700;">R$ <?= number_format((float)$s['price'], 2, ',', '.') ?></td>
            <td><?= e(match($s['recurrence']) { 'one_time'=>'Único','monthly'=>'Mensal','quarterly'=>'Trimestral','yearly'=>'Anual', default=>$s['recurrence'] }) ?></td>
            <td><span class="badge badge--<?= $s['status'] ?>"><?= ucfirst(e($s['status'])) ?></span></td>
            <td class="mgmt-table__actions"><a href="<?= url('/admin/servicos/' . $s['id'] . '/editar') ?>">Editar</a></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
<?php endif; ?>
<?php $__view->endSection(); ?>
