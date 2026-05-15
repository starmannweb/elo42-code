<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
    $statusLabels = ['active' => 'Ativo', 'inactive' => 'Inativo', 'suspended' => 'Suspenso'];
    $sessionUser = \App\Core\Session::user() ?? [];
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Usuários</h1>
        <p class="mgmt-header__subtitle"><?= $pagination['total'] ?> usuários na plataforma</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-user').style.display='flex'">Novo usuário</button>
    </div>
</div>

<form method="GET" action="<?= url('/admin/usuarios') ?>" class="mgmt-filters">
    <div class="mgmt-search">
        <span class="mgmt-search__icon">🔍</span>
        <input type="text" name="search" class="form-input" placeholder="Buscar por nome ou e-mail..." value="<?= e($filters['search']) ?>">
    </div>
    <select name="status" class="form-select">
        <option value="">Todos</option>
        <option value="active" <?= $filters['status']==='active'?'selected':'' ?>>Ativos</option>
        <option value="inactive" <?= $filters['status']==='inactive'?'selected':'' ?>>Inativos</option>
        <option value="suspended" <?= $filters['status']==='suspended'?'selected':'' ?>>Suspensos</option>
    </select>
    <button type="submit" class="btn btn--secondary">Filtrar</button>
</form>

<?php if (!empty($degraded)): ?>
    <div class="alert alert--warning" role="alert" style="margin-bottom:1rem;">Banco indisponível agora. Exibindo o usuário da sessão como referência.</div>
<?php endif; ?>

<div class="mgmt-table-container">
    <table class="mgmt-table">
        <thead><tr><th>Nome</th><th>E-mail</th><th>Orgs</th><th>Status</th><th>Último login</th><th>Ações</th></tr></thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--text-muted);padding:1.25rem;">Nenhum usuário encontrado.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($users as $u): ?>
                <?php $isSelf = (int) ($sessionUser['id'] ?? 0) === (int) ($u['id'] ?? 0); ?>
                <tr>
                    <td class="mgmt-table__name"><?= e($u['name']) ?><?= !empty($u['is_session_fallback']) ? ' <span class="badge badge--inactive">Sessão</span>' : '' ?></td>
                    <td class="mgmt-table__sub"><?= e($u['email']) ?></td>
                    <td><?= $u['org_count'] ?></td>
                    <td><span class="badge badge--<?= e($u['status']) ?>"><?= e($statusLabels[$u['status'] ?? ''] ?? ($u['status'] ?? '-')) ?></span></td>
                    <td><?= $u['last_login_at'] ? date('d/m/Y H:i', strtotime($u['last_login_at'])) : '-' ?></td>
                    <td class="mgmt-table__actions" style="display:flex;gap:0.5rem;align-items:center;">
                        <a href="<?= url('/admin/usuarios/' . $u['id']) ?>">Ver</a>
                        <a href="<?= url('/admin/usuarios/' . $u['id'] . '/editar') ?>">Editar</a>
                        <?php if (empty($u['is_session_fallback']) && !$isSelf): ?>
                        <form method="POST" action="<?= url('/admin/usuarios/' . $u['id'] . '/excluir') ?>" onsubmit="return confirm('Tem certeza que deseja remover este usuário?');" style="margin:0;">
                            <?= csrf_field() ?>
                            <button type="submit" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:inherit;padding:0;">Excluir</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if ($pagination['totalPages'] > 1): ?>
        <div class="mgmt-pagination">
            <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                <?php if ($i === $pagination['page']): ?><span class="current"><?= $i ?></span>
                <?php else: ?><a href="<?= url('/admin/usuarios?page=' . $i . '&search=' . urlencode($filters['search']) . '&status=' . $filters['status']) ?>"><?= $i ?></a><?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<div class="modal" id="modal-new-user" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-new-user-title">
    <div class="modal__content">
        <div class="modal__header">
            <h2 class="modal__title" id="modal-new-user-title">Cadastrar usuário</h2>
            <button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'" aria-label="Fechar">&times;</button>
        </div>
        <form method="POST" action="<?= url('/admin/usuarios') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="modal-grid">
                    <div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" required></div>
                    <div class="form-group"><label class="form-label">E-mail *</label><input type="email" name="email" class="form-input" required></div>
                    <div class="form-group"><label class="form-label">Telefone</label><input type="text" name="phone" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Ativo</option><option value="inactive">Inativo</option><option value="suspended">Suspenso</option></select></div>
                    <div class="form-group"><label class="form-label">Senha inicial *</label><input type="password" name="password" class="form-input" minlength="6" required></div>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--primary">Cadastrar</button>
            </div>
        </form>
    </div>
</div>

<?php $__view->endSection(); ?>
