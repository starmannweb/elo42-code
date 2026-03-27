<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>

<div class="mgmt-header">
    <div><h1 class="mgmt-header__title">Ministérios</h1></div>
    <div class="mgmt-header__actions"><a href="<?= url('/gestao/ministerios/novo') ?>" class="btn btn--primary">+ Novo ministério</a></div>
</div>

<?php if (empty($ministries)): ?>
    <div class="mgmt-empty">
        <div class="mgmt-empty__icon">⛪</div>
        <h3 class="mgmt-empty__title">Nenhum ministério cadastrado</h3>
        <p class="mgmt-empty__text">Crie o primeiro ministério da sua organização.</p>
        <a href="<?= url('/gestao/ministerios/novo') ?>" class="btn btn--primary">Criar ministério</a>
    </div>
<?php else: ?>
    <div class="mgmt-table-container">
        <table class="mgmt-table">
            <thead><tr><th>Ministério</th><th>Líder</th><th>Membros</th><th>Status</th><th>Ações</th></tr></thead>
            <tbody>
                <?php foreach ($ministries as $m): ?>
                <tr>
                    <td>
                        <div class="mgmt-table__name" style="display:flex;align-items:center;gap:8px;">
                            <span style="width:12px;height:12px;border-radius:50%;background:<?= e($m['color'] ?? '#0A4DFF') ?>;flex-shrink:0;"></span>
                            <?= e($m['name']) ?>
                        </div>
                        <?php if ($m['description']): ?><div class="mgmt-table__sub"><?= e(mb_strimwidth($m['description'], 0, 60, '...')) ?></div><?php endif; ?>
                    </td>
                    <td><?= e($m['leader_name'] ?? '—') ?></td>
                    <td><?= $m['member_count'] ?></td>
                    <td><span class="badge badge--<?= $m['status'] ?>"><?= e(ucfirst($m['status'])) ?></span></td>
                    <td class="mgmt-table__actions"><a href="<?= url('/gestao/ministerios/' . $m['id'] . '/editar') ?>">Editar</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php $__view->endSection(); ?>
