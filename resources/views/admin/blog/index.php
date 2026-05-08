<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
$statusLabels = ['draft' => 'Rascunho', 'published' => 'Publicado'];
$filters = is_array($filters ?? null) ? $filters : [];
$articles = is_array($articles ?? null) ? $articles : [];
$pagination = is_array($pagination ?? null) ? $pagination : ['total' => 0, 'page' => 1, 'perPage' => 20, 'totalPages' => 1];
?>

<div class="mgmt-header" style="margin-bottom: var(--space-8);">
    <div>
        <h1 class="mgmt-header__title">Blog</h1>
        <p class="mgmt-header__subtitle">Gerencie os artigos publicados no blog do site.</p>
    </div>
    <div class="mgmt-header__actions">
        <a href="<?= url('/admin/blog/novo') ?>" class="btn btn--primary">Novo artigo</a>
    </div>
</div>

<form method="GET" action="<?= url('/admin/blog') ?>" class="mgmt-filter-form" data-auto-submit style="grid-template-columns:1fr 160px auto;">
    <input type="text" name="search" class="form-control" placeholder="Buscar por título ou autor..." value="<?= e((string) ($filters['search'] ?? '')) ?>">
    <select name="status" class="form-select">
        <option value="">Todos</option>
        <option value="published" <?= ($filters['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publicados</option>
        <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Rascunhos</option>
    </select>
    <button type="submit" class="btn btn--outline">Filtrar</button>
</form>

<?php if (empty($articles)): ?>
    <div class="mgmt-empty">
        <div class="mgmt-empty__icon">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
        </div>
        <h3 class="mgmt-empty__title">Nenhum artigo encontrado</h3>
        <p class="mgmt-empty__text">Crie o primeiro artigo para o blog.</p>
    </div>
<?php else: ?>
    <div class="mgmt-table-container">
        <table class="mgmt-table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Status</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $a): ?>
                <tr>
                    <td>
                        <div class="mgmt-table__name"><?= e((string) ($a['title'] ?? '')) ?></div>
                        <div class="mgmt-table__sub"><?= e((string) ($a['slug'] ?? '')) ?></div>
                    </td>
                    <td><?= e((string) ($a['author'] ?? '')) ?></td>
                    <td>
                        <span class="badge badge--<?= ($a['status'] ?? '') === 'published' ? 'active' : 'inactive' ?>">
                            <?= e($statusLabels[$a['status'] ?? ''] ?? ($a['status'] ?? '-')) ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime((string) ($a['created_at'] ?? 'now'))) ?></td>
                    <td class="mgmt-table__actions">
                        <a href="<?= url('/admin/blog/' . (int) $a['id'] . '/editar') ?>">Editar</a>
                        <form method="POST" action="<?= url('/admin/blog/' . (int) $a['id'] . '/excluir') ?>" style="display:inline;" onsubmit="return confirm('Excluir este artigo?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn-link btn-link--danger">Excluir</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ((int) ($pagination['totalPages'] ?? 1) > 1): ?>
    <div class="mgmt-pagination">
        <?php for ($i = 1; $i <= (int) $pagination['totalPages']; $i++): ?>
            <a href="<?= url('/admin/blog?page=' . $i . ($filters['search'] ? '&search=' . urlencode($filters['search']) : '') . ($filters['status'] ? '&status=' . $filters['status'] : '')) ?>"
               class="mgmt-pagination__link <?= $i === (int) ($pagination['page'] ?? 1) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
<?php endif; ?>

<?php $__view->endSection(); ?>
