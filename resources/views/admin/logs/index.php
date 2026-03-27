<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Logs de auditoria</h1><p class="mgmt-header__subtitle">Ações registradas na plataforma</p></div></div>
<form method="GET" action="<?= url('/admin/logs') ?>" class="mgmt-filters">
    <div class="mgmt-search"><span class="mgmt-search__icon">🔍</span><input type="text" name="search" class="form-input" placeholder="Buscar por usuário ou ação..." value="<?= e($filters['search']) ?>"></div>
    <input type="text" name="module" class="form-input" style="max-width:200px;" placeholder="Módulo..." value="<?= e($filters['module']) ?>">
    <button type="submit" class="btn btn--secondary">Filtrar</button>
</form>

<?php if (empty($logs)): ?>
    <div class="mgmt-empty"><div class="mgmt-empty__icon">📋</div><h3 class="mgmt-empty__title">Nenhum log encontrado</h3><p class="mgmt-empty__text">Os logs de auditoria aparecerão aqui quando ações forem registradas.</p></div>
<?php else: ?>
    <div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Data/Hora</th><th>Usuário</th><th>Ação</th><th>Módulo</th><th>IP</th></tr></thead><tbody>
        <?php foreach ($logs as $l): ?><tr>
            <td style="font-family:monospace;font-size:var(--text-xs);"><?= date('d/m/Y H:i:s', strtotime($l['created_at'])) ?></td>
            <td><?= e($l['user_name'] ?? '—') ?><div class="mgmt-table__sub"><?= e($l['user_email'] ?? '') ?></div></td>
            <td><?= e($l['action'] ?? '') ?></td>
            <td><span class="badge badge--active"><?= e($l['module'] ?? '—') ?></span></td>
            <td style="font-family:monospace;font-size:var(--text-xs);"><?= e($l['ip_address'] ?? '—') ?></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
<?php endif; ?>
<?php $__view->endSection(); ?>
