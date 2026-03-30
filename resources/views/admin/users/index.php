<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Usuários</h1><p class="mgmt-header__subtitle"><?= $pagination['total'] ?> usuários na plataforma</p></div><div class="mgmt-header__actions"><a href="<?= url('/admin/usuarios/novo') ?>" class="btn btn--primary">Novo usuário</a></div></div>
<form method="GET" action="<?= url('/admin/usuarios') ?>" class="mgmt-filters">
    <div class="mgmt-search"><span class="mgmt-search__icon">🔍</span><input type="text" name="search" class="form-input" placeholder="Buscar por nome ou e-mail..." value="<?= e($filters['search']) ?>"></div>
    <select name="status" class="form-select"><option value="">Todos</option><option value="active" <?= $filters['status']==='active'?'selected':'' ?>>Ativos</option><option value="inactive" <?= $filters['status']==='inactive'?'selected':'' ?>>Inativos</option><option value="suspended" <?= $filters['status']==='suspended'?'selected':'' ?>>Suspensos</option></select>
    <button type="submit" class="btn btn--secondary">Filtrar</button>
</form>
<div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Nome</th><th>E-mail</th><th>Orgs</th><th>Status</th><th>Último login</th><th>Ações</th></tr></thead><tbody>
    <?php foreach ($users as $u): ?><tr>
        <td class="mgmt-table__name"><?= e($u['name']) ?></td>
        <td class="mgmt-table__sub"><?= e($u['email']) ?></td>
        <td><?= $u['org_count'] ?></td>
        <td><span class="badge badge--<?= $u['status'] ?>"><?= ucfirst(e($u['status'])) ?></span></td>
        <td><?= $u['last_login_at'] ? date('d/m/Y H:i', strtotime($u['last_login_at'])) : '—' ?></td>
        <td class="mgmt-table__actions" style="display:flex;gap:0.5rem;align-items:center;">
            <a href="<?= url('/admin/usuarios/' . $u['id']) ?>">Ver</a>
            <a href="<?= url('/admin/usuarios/' . $u['id'] . '/editar') ?>">Editar</a>
            <form method="POST" action="<?= url('/admin/usuarios/' . $u['id'] . '/excluir') ?>" onsubmit="return confirm('Tem certeza que deseja remover este usuário?');" style="margin:0;">
                <?= csrf_field() ?>
                <button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:inherit;padding:0;">Excluir</button>
            </form>
        </td>
    </tr><?php endforeach; ?>
</tbody></table>
<?php if ($pagination['totalPages'] > 1): ?><div class="mgmt-pagination"><?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?><?php if ($i === $pagination['page']): ?><span class="current"><?= $i ?></span><?php else: ?><a href="<?= url('/admin/usuarios?page=' . $i . '&search=' . urlencode($filters['search']) . '&status=' . $filters['status']) ?>"><?= $i ?></a><?php endif; ?><?php endfor; ?></div><?php endif; ?>
</div>
<?php $__view->endSection(); ?>
