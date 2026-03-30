<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div class="mgmt-header__title">
        <h1>Controle de Usuários</h1>
        <p>Gerencie os usuários e permissões da sua organização.</p>
    </div>
    <div class="mgmt-header__actions">
        <a href="<?= url('/gestao/usuarios/novo') ?>" class="btn btn--primary">Novo Usuário</a>
    </div>
</div>

<div class="mgmt-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Função</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Nenhum usuário encontrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <strong><?= e($u['name']) ?></strong>
                            </td>
                            <td><?= e($u['email']) ?></td>
                            <td>
                                <span class="badge badge--info"><?= e($u['role_name'] ?? 'Membro') ?></span>
                            </td>
                            <td class="text-right">
                                <form action="<?= url('/gestao/usuarios/' . $u['org_user_id'] . '/excluir') ?>" method="POST" style="display:inline-block;" onsubmit="return confirm('Desvincular usuário?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn--sm btn--danger">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__view->endSection(); ?>
