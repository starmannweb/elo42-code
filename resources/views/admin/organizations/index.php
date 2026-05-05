<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
$typeLabels = ['church' => 'Igreja', 'association' => 'Associação', 'ministry' => 'Ministério', 'ong' => 'ONG', 'other' => 'Outro'];
$statusLabels = ['active' => 'Ativa', 'trial' => 'Teste', 'inactive' => 'Inativa', 'suspended' => 'Suspensa'];
?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Instituições</h1></div></div>
<form method="GET" action="<?= url('/admin/organizacoes') ?>" class="mgmt-filters">
    <div class="mgmt-search"><span class="mgmt-search__icon">🔍</span><input type="text" name="search" class="form-input" placeholder="Buscar por nome ou CNPJ..." value="<?= e($filters['search']) ?>"></div>
    <select name="status" class="form-select"><option value="">Todos</option><option value="active" <?= $filters['status']==='active'?'selected':'' ?>>Ativas</option><option value="trial" <?= $filters['status']==='trial'?'selected':'' ?>>Teste</option><option value="inactive" <?= $filters['status']==='inactive'?'selected':'' ?>>Inativas</option></select>
    <button type="submit" class="btn btn--secondary">Filtrar</button>
</form>
<?php if (!empty($degraded)): ?>
    <div class="alert alert--warning" role="alert" style="margin-bottom:1rem;">Banco indisponivel agora. Exibindo a instituicao da sessao como referencia.</div>
<?php endif; ?>
<div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Instituição</th><th>Tipo</th><th>Usuários</th><th>Membros</th><th>Status</th><th>Criação</th><th>Ações</th></tr></thead><tbody>
    <?php if (empty($organizations)): ?><tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:1.25rem;">Nenhuma instituicao encontrada.</td></tr><?php endif; ?>
    <?php foreach ($organizations as $o): ?><tr>
        <td><div class="mgmt-table__name"><?= e($o['name']) ?><?= !empty($o['is_session_fallback']) ? ' <span class="badge badge--inactive">Sessao</span>' : '' ?></div><?php if ($o['cnpj']): ?><div class="mgmt-table__sub"><?= e($o['cnpj']) ?></div><?php endif; ?></td>
        <td><?= e($typeLabels[$o['type'] ?? ''] ?? ($o['type'] ?? '—')) ?></td>
        <td><?= $o['user_count'] ?></td>
        <td><?= $o['member_count'] ?></td>
        <td><span class="badge badge--<?= e($o['status']) ?>"><?= e($statusLabels[$o['status'] ?? ''] ?? ($o['status'] ?? '-')) ?></span></td>
        <td><?= date('d/m/Y', strtotime($o['created_at'])) ?></td>
        <td class="mgmt-table__actions">
            <?php if ((int) ($o['id'] ?? 0) > 0): ?>
                <a href="<?= url('/admin/organizacoes/' . $o['id']) ?>">Ver</a><a href="<?= url('/admin/organizacoes/' . $o['id'] . '/editar') ?>">Editar</a>
            <?php else: ?>
                <span class="badge badge--inactive">Base</span>
            <?php endif; ?>
        </td>
    </tr><?php endforeach; ?>
</tbody></table></div>
<?php $__view->endSection(); ?>
