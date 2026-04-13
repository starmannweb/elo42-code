<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">Categorias Financeiras</h1>
        <p class="mgmt-subtitle">Gerencie as categorias de receitas e despesas</p>
    </div>
    <div class="mgmt-actions">
        <a href="<?= url('/gestao') ?>" class="btn btn--ghost">Voltar</a>
        <button type="button" class="btn btn--primary" onclick="alert('Funcionalidade em desenvolvimento')">Nova Categoria</button>
    </div>
</div>

<div class="mgmt-dashboard-card" style="margin-top: 1.5rem; max-width: 100%;">
    <table class="mgmt-table" style="width: 100%;">
        <thead>
            <tr>
                <th>Nome da Categoria</th>
                <th>Tipo</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categories)): ?>
            <tr>
                <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                    Nenhuma categoria financeira cadastrada.
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td style="font-weight: 500; color: var(--color-text-primary);"><?= e($cat['name']) ?></td>
                        <td>
                            <span class="badge <?= $cat['type'] === 'income' ? 'badge--success' : 'badge--danger' ?>" style="font-size: 0.75rem;">
                                <?= $cat['type'] === 'income' ? 'Receita' : 'Despesa' ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= $cat['is_active'] ? 'badge--success' : 'badge--warning' ?>" style="font-size: 0.75rem;">
                                <?= $cat['is_active'] ? 'Ativa' : 'Inativa' ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn--icon" title="Editar" onclick="alert('Editar categoria em desenvolvimento')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__view->endSection(); ?>
