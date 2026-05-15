<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
$typeLabels = ['church' => 'Igreja', 'association' => 'Associa&ccedil;&atilde;o', 'ministry' => 'Minist&eacute;rio', 'ong' => 'ONG', 'other' => 'Outro'];
$statusLabels = ['active' => 'Ativa', 'trial' => 'Teste', 'inactive' => 'Inativa', 'suspended' => 'Suspensa'];

$renderOrgEditModal = static function (array $org, string $returnTo): void {
    $orgId = (int) ($org['id'] ?? 0);
    if ($orgId <= 0) {
        return;
    }
    ?>
    <div class="modal" id="modal-edit-org-<?= $orgId ?>" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-edit-org-title-<?= $orgId ?>">
        <div class="modal__content modal__content--wide">
            <div class="modal__header">
                <h2 class="modal__title" id="modal-edit-org-title-<?= $orgId ?>">Editar institui&ccedil;&atilde;o</h2>
                <button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'" aria-label="Fechar">&times;</button>
            </div>
            <form method="POST" action="<?= url('/admin/organizacoes/' . $orgId . '/editar') ?>" data-loading>
                <?= csrf_field() ?>
                <input type="hidden" name="return_to" value="<?= e($returnTo) ?>">
                <div class="modal__body">
                    <div class="modal-grid">
                        <div class="form-group modal-grid__full">
                            <label class="form-label">Nome *</label>
                            <input type="text" name="name" class="form-input" value="<?= e($org['name'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Raz&atilde;o social</label>
                            <input type="text" name="legal_name" class="form-input" value="<?= e($org['legal_name'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">CNPJ</label>
                            <input type="text" name="cnpj" class="form-input" value="<?= e($org['cnpj'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Telefone</label>
                            <input type="tel" name="phone" class="form-input" value="<?= e($org['phone'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" <?= ($org['status'] ?? '') === 'active' ? 'selected' : '' ?>>Ativa</option>
                                <option value="trial" <?= ($org['status'] ?? '') === 'trial' ? 'selected' : '' ?>>Teste</option>
                                <option value="inactive" <?= ($org['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inativa</option>
                                <option value="suspended" <?= ($org['status'] ?? '') === 'suspended' ? 'selected' : '' ?>>Suspensa</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Cidade</label>
                            <input type="text" name="city" class="form-input" value="<?= e($org['city'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Estado</label>
                            <input type="text" name="state" class="form-input" value="<?= e($org['state'] ?? '') ?>">
                        </div>
                        <div class="form-group modal-grid__full">
                            <label class="form-label">Plano</label>
                            <select name="plan" class="form-select">
                                <option value="free" <?= ($org['plan'] ?? '') === 'free' ? 'selected' : '' ?>>Gratuito</option>
                                <option value="starter" <?= ($org['plan'] ?? '') === 'starter' ? 'selected' : '' ?>>Starter</option>
                                <option value="professional" <?= ($org['plan'] ?? '') === 'professional' ? 'selected' : '' ?>>Professional</option>
                                <option value="enterprise" <?= ($org['plan'] ?? '') === 'enterprise' ? 'selected' : '' ?>>Enterprise</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal__footer">
                    <button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button>
                    <button type="submit" class="btn btn--primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
    <?php
};
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Institui&ccedil;&otilde;es</h1>
    </div>
</div>

<form method="GET" action="<?= url('/admin/organizacoes') ?>" class="mgmt-filters">
    <div class="mgmt-search">
        <span class="mgmt-search__icon">&#128269;</span>
        <input type="text" name="search" class="form-input" placeholder="Buscar por nome ou CNPJ..." value="<?= e($filters['search']) ?>">
    </div>
    <select name="status" class="form-select">
        <option value="">Todos</option>
        <option value="active" <?= $filters['status'] === 'active' ? 'selected' : '' ?>>Ativas</option>
        <option value="trial" <?= $filters['status'] === 'trial' ? 'selected' : '' ?>>Teste</option>
        <option value="inactive" <?= $filters['status'] === 'inactive' ? 'selected' : '' ?>>Inativas</option>
    </select>
    <button type="submit" class="btn btn--secondary">Filtrar</button>
</form>

<?php if (!empty($degraded)): ?>
    <div class="alert alert--warning" role="alert" style="margin-bottom:1rem;">Banco indispon&iacute;vel agora. Exibindo a institui&ccedil;&atilde;o da sess&atilde;o como refer&ecirc;ncia.</div>
<?php endif; ?>

<div class="mgmt-table-container">
    <table class="mgmt-table">
        <thead>
            <tr>
                <th>Institui&ccedil;&atilde;o</th>
                <th>Tipo</th>
                <th>Usu&aacute;rios</th>
                <th>Membros</th>
                <th>Status</th>
                <th>Cria&ccedil;&atilde;o</th>
                <th>A&ccedil;&otilde;es</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($organizations)): ?>
                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:1.25rem;">Nenhuma institui&ccedil;&atilde;o encontrada.</td></tr>
            <?php endif; ?>
            <?php foreach ($organizations as $org): ?>
                <?php
                    $orgId = (int) ($org['id'] ?? 0);
                    $createdAt = trim((string) ($org['created_at'] ?? ''));
                    $createdLabel = $createdAt !== '' && strtotime($createdAt) ? date('d/m/Y', strtotime($createdAt)) : '-';
                ?>
                <tr>
                    <td>
                        <div class="mgmt-table__name">
                            <?= e($org['name'] ?? '-') ?>
                            <?= !empty($org['is_session_fallback']) ? ' <span class="badge badge--inactive">Sess&atilde;o</span>' : '' ?>
                        </div>
                        <?php if (!empty($org['cnpj'])): ?>
                            <div class="mgmt-table__sub"><?= e($org['cnpj']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td><?= $typeLabels[$org['type'] ?? ''] ?? e($org['type'] ?? '-') ?></td>
                    <td><?= e((string) ($org['user_count'] ?? 0)) ?></td>
                    <td><?= e((string) ($org['member_count'] ?? 0)) ?></td>
                    <td><span class="badge badge--<?= e($org['status'] ?? 'inactive') ?>"><?= $statusLabels[$org['status'] ?? ''] ?? e($org['status'] ?? '-') ?></span></td>
                    <td><?= e($createdLabel) ?></td>
                    <td class="mgmt-table__actions" style="display:flex;gap:0.5rem;align-items:center;">
                        <?php if ($orgId > 0): ?>
                            <a href="<?= url('/admin/organizacoes/' . $orgId) ?>">Ver</a>
                            <button type="button" class="mgmt-action-link" onclick="document.getElementById('modal-edit-org-<?= $orgId ?>').style.display='flex'">Editar</button>
                        <?php else: ?>
                            <span class="badge badge--inactive">Base</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php foreach ($organizations as $org): ?>
    <?php $renderOrgEditModal($org, '/admin/organizacoes'); ?>
<?php endforeach; ?>

<?php $__view->endSection(); ?>
