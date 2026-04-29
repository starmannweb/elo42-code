<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php
    $statusLabels = [
        'active' => 'Ativo',
        'inactive' => 'Inativo',
        'pending' => 'Pendente',
        'blocked' => 'Bloqueado',
        'suspended' => 'Suspenso',
    ];
    $roleLabels = [
        'super admin' => 'Super administrador',
        'admin' => 'Administrador',
        'member' => 'Membro',
        'manager' => 'Gestor',
        'finance' => 'Financeiro',
    ];
?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Usuários e permissões</h1>
        <p class="mgmt-header__subtitle">Controle quem acessa a organização e quais papéis cada pessoa possui.</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-management-user').style.display='flex'">Novo usuário</button>
    </div>
</div>

<div class="mgmt-dashboard-card mgmt-table-shell">
    <div class="table-responsive">
    <table class="mgmt-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Papel</th>
                <th>Status</th>
                <th style="text-align:right;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;color:var(--text-muted);padding:var(--space-8);">Nenhum usuário encontrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $item): ?>
                    <?php $status = strtolower((string) ($item['status'] ?? 'active')); ?>
                    <?php $roleName = (string) ($item['role_name'] ?? 'Membro'); ?>
                    <?php $roleKey = strtolower(trim($roleName)); ?>
                    <tr>
                        <td><div class="mgmt-table__name"><?= e((string) ($item['name'] ?? 'Usuário')) ?></div></td>
                        <td><?= e((string) ($item['email'] ?? '-')) ?></td>
                        <td><span class="badge badge--info"><?= e($roleLabels[$roleKey] ?? $roleName) ?></span></td>
                        <td><span class="badge badge--<?= e($status) ?>"><?= e($statusLabels[$status] ?? ucfirst($status)) ?></span></td>
                        <td style="text-align:right;">
                            <form action="<?= url('/gestao/usuarios/' . ($item['org_user_id'] ?? 0) . '/excluir') ?>" method="POST" style="display:inline-block;" onsubmit="return confirm('Desvincular usuário?');">
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

<div class="modal" id="modal-new-management-user" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-new-management-user-title">
    <div class="modal__content">
        <div class="modal__header">
            <h2 class="modal__title" id="modal-new-management-user-title">Novo usuário</h2>
            <button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'" aria-label="Fechar">&times;</button>
        </div>
        <form action="<?= url('/gestao/usuarios') ?>" method="POST" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="modal-grid">
                    <div class="form-group modal-grid__full">
                        <label class="form-label" for="settings-user-name">Nome completo *</label>
                        <input type="text" id="settings-user-name" name="name" class="form-input" required>
                    </div>
                    <div class="form-group modal-grid__full">
                        <label class="form-label" for="settings-user-email">E-mail *</label>
                        <input type="email" id="settings-user-email" name="email" class="form-input" required>
                        <span class="mgmt-auto-note">Se o e-mail já existir na Elo 42, ele será vinculado à organização.</span>
                    </div>
                    <div class="form-group modal-grid__full">
                        <label class="form-label" for="settings-user-password">Senha temporária *</label>
                        <input type="password" id="settings-user-password" name="password" class="form-input" required minlength="6">
                    </div>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--primary">Salvar usuário</button>
            </div>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
