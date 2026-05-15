<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<section class="hub-page">
    <header class="hub-page__header hub-team-header">
        <div>
            <h1 class="hub-page__title">Minha Equipe</h1>
            <p class="hub-page__subtitle">Gerencie os usuários e permissões de acesso da sua organização.</p>
        </div>
        <button class="btn btn--primary btn--sm hub-team-add-btn" type="button" onclick="document.getElementById('modal-add-user').style.display='flex'">
            <span aria-hidden="true">+</span> Adicionar Membro
        </button>
    </header>

    <div class="hub-panel">
        <?php if (!empty($degraded)): ?>
            <div class="alert alert--warning" role="alert" style="margin-bottom:1rem;">Banco indisponivel agora. Exibindo o usuário atual como referencia.</div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Membro</th>
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($teamMembers)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <p class="text-muted">Nenhum membro encontrado.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($teamMembers as $member): ?>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="hub-sidebar__user-avatar" style="width: 32px; height: 32px; font-size: 11px;">
                                            <?= e(strtoupper(substr($member['name'], 0, 1))) ?>
                                        </div>
                                        <div class="font-semibold"><?= e($member['name']) ?><?= !empty($member['is_session_fallback']) ? ' <span class="hub-badge">Sessao</span>' : '' ?></div>
                                    </div>
                                </td>
                                <td><?= e($member['email']) ?></td>
                                <td>
                                    <span class="hub-badge" style="background: rgba(10, 77, 255, 0.08); color: var(--color-primary);">
                                        <?= e($member['role_name'] ?? 'Membro') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (($member['org_status'] ?? 'active') === 'active'): ?>
                                        <span class="text-success font-semibold">Ativo</span>
                                    <?php else: ?>
                                        <span class="text-error font-semibold">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <button class="btn btn--ghost btn--sm" title="Editar" onclick="openEditModal(<?= e(json_encode($member)) ?>)">
                                            Editar
                                        </button>
                                        <?php if ($member['id'] !== $user['id']): ?>
                                            <form action="<?= url('/hub/usuarios/remover/' . $member['id']) ?>" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este acesso?')">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn--danger-outline btn--sm">Remover</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Adicionar Usuário -->
<div id="modal-add-user" class="hub-modal-overlay" style="display: none;">
    <div class="hub-modal">
        <div class="hub-modal__header">
            <h3 class="hub-modal__title">Adicionar Membro à Equipe</h3>
            <button class="hub-modal__close" onclick="this.closest('.hub-modal-overlay').style.display='none'">&times;</button>
        </div>
        <form action="<?= url('/hub/usuarios/adicionar') ?>" method="POST" class="hub-modal__body">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label" for="add-name">Nome completo</label>
                <input type="text" id="add-name" name="name" class="form-input" required placeholder="Ex: João Silva">
            </div>
            <div class="form-group">
                <label class="form-label" for="add-email">E-mail</label>
                <input type="email" id="add-email" name="email" class="form-input" required placeholder="email@exemplo.com">
            </div>
            <div class="form-group">
                <label class="form-label" for="add-role">Perfil de Acesso</label>
                <select id="add-role" name="role_id" class="form-select" required>
                    <?php
                        $rolesForSelect = is_array($availableRoles ?? null) ? $availableRoles : [];
                        if (empty($rolesForSelect)) {
                            $rolesForSelect = [
                                ['id' => 'org-admin', 'name' => 'Administrador da organização'],
                                ['id' => 'org-pastor', 'name' => 'Pastor / Liderança'],
                                ['id' => 'org-tesoureiro', 'name' => 'Tesoureiro'],
                                ['id' => 'org-secretaria', 'name' => 'Secretaria'],
                                ['id' => 'org-member', 'name' => 'Membro / Colaborador'],
                            ];
                        }
                    ?>
                    <option value="" disabled selected>Selecione um perfil</option>
                    <?php foreach ($rolesForSelect as $role): ?>
                        <option value="<?= e((string) ($role['id'] ?? '')) ?>"><?= e((string) ($role['name'] ?? 'Perfil')) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <p class="form-hint" style="margin-bottom: var(--space-4);">
                * Uma senha padrão (elo42@2026) será gerada para o primeiro acesso.
            </p>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" class="btn btn--ghost" onclick="this.closest('.hub-modal-overlay').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--gold">Criar Acesso</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit-user" class="hub-modal-overlay" style="display: none;">
    <div class="hub-modal">
        <div class="hub-modal__header">
            <h3 class="hub-modal__title">Editar nível de acesso</h3>
            <button class="hub-modal__close" onclick="this.closest('.hub-modal-overlay').style.display='none'">&times;</button>
        </div>
        <form id="edit-user-form" action="<?= url('/hub/usuarios/editar/0') ?>" method="POST" class="hub-modal__body">
            <?= csrf_field() ?>
            <p class="form-hint" id="edit-user-name" style="margin-bottom: var(--space-4);"></p>
            <div class="form-group">
                <label class="form-label" for="edit-role">Perfil de acesso</label>
                <select id="edit-role" name="role_id" class="form-select" required>
                    <?php foreach ($rolesForSelect as $role): ?>
                        <option value="<?= e((string) ($role['id'] ?? '')) ?>"><?= e((string) ($role['name'] ?? 'Perfil')) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit-status">Status</label>
                <select id="edit-status" name="status" class="form-select">
                    <option value="active">Ativo</option>
                    <option value="inactive">Inativo</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" class="btn btn--ghost" onclick="this.closest('.hub-modal-overlay').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--gold">Salvar nível</button>
            </div>
        </form>
    </div>
</div>

<style>
.hub-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(7, 27, 59, 0.6);
    backdrop-filter: blur(4px);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}
.hub-modal {
    background: var(--color-white);
    width: 100%;
    max-width: 500px;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-xl);
    overflow: hidden;
    animation: modalIn 0.3s ease-out;
}
.hub-modal__header {
    padding: var(--space-5) var(--space-6);
    border-bottom: 1px solid var(--color-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.hub-modal__body {
    padding: var(--space-6);
}
.hub-modal__close {
    font-size: 24px;
    color: var(--color-text-muted);
    cursor: pointer;
    background: none;
    border: none;
}
body[data-hub-theme="dark"] .hub-modal {
    background: #0f172a;
    border: 1px solid #1e293b;
    color: #e5edf9;
}
body[data-hub-theme="dark"] .hub-modal__header {
    background: #111c31;
    border-color: #253650;
}
body[data-hub-theme="dark"] .hub-modal__title,
body[data-hub-theme="dark"] .hub-modal .form-label {
    color: #f8fafc;
}
body[data-hub-theme="dark"] .hub-modal .form-hint {
    color: #c6d4ea !important;
}
body[data-hub-theme="dark"] .hub-modal .form-input,
body[data-hub-theme="dark"] .hub-modal .form-select {
    background: #13233b;
    border-color: #334155;
    color: #f8fafc;
}
body[data-hub-theme="dark"] .hub-modal .form-input::placeholder {
    color: #94a3b8;
}

@keyframes modalIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
function openEditModal(member) {
    var modal = document.getElementById('modal-edit-user');
    var form = document.getElementById('edit-user-form');
    var name = document.getElementById('edit-user-name');
    var role = document.getElementById('edit-role');
    var status = document.getElementById('edit-status');

    if (!modal || !form || !member) {
        return;
    }

    form.action = "<?= url('/hub/usuarios/editar') ?>/" + encodeURIComponent(member.id || '');
    if (name) {
        name.textContent = (member.name || 'Usuário') + ' · ' + (member.email || '');
    }
    if (role && member.role_id) {
        role.value = String(member.role_id);
    }
    if (status) {
        status.value = member.org_status || 'active';
    }

    modal.style.display = 'flex';
}
</script>

<?php $__view->endSection(); ?>
