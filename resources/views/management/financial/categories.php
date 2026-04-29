<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Categorias Financeiras</h1>
        <p class="mgmt-header__subtitle">Organize categorias de receitas, despesas e prestação de contas na área financeira.</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="submit" form="form-category" class="btn btn--primary">Adicionar categoria</button>
    </div>
</div>

<div class="mgmt-dashboard-grid" style="grid-template-columns: minmax(280px, .7fr) 1fr;">
    <div class="mgmt-dashboard-card">
        <header class="mgmt-dashboard-card__header">
            <h2>Nova categoria</h2>
        </header>
        <form id="form-category" method="POST" action="<?= url('/gestao/financeiro/categoria') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label" for="category-name">Nome</label>
                <input id="category-name" class="form-input" type="text" name="name" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="category-type">Tipo</label>
                <select id="category-type" class="form-input" name="type" required>
                    <option value="income">Receita</option>
                    <option value="expense">Despesa</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="category-color">Cor</label>
                <input id="category-color" class="form-input" type="color" name="color" value="#0A4DFF">
            </div>
        </form>
    </div>

    <div class="mgmt-dashboard-card" style="padding:0;overflow:hidden;">
        <table class="mgmt-table">
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Tipo</th>
                    <th>Cor</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr><td colspan="3" style="text-align:center;color:var(--text-muted);padding:var(--space-8);">Nenhuma categoria cadastrada.</td></tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><div class="mgmt-table__name"><?= e((string) ($category['name'] ?? '-')) ?></div></td>
                            <td><?= ($category['type'] ?? '') === 'income' ? 'Receita' : 'Despesa' ?></td>
                            <td><span style="display:inline-flex;width:20px;height:20px;border-radius:6px;background:<?= e((string) ($category['color'] ?? '#0A4DFF')) ?>;"></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__view->endSection(); ?>
