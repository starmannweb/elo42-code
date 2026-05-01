<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php
    $categories = is_array($categories ?? null) ? $categories : [];
    $income = array_filter($categories, static fn ($c) => ($c['type'] ?? '') === 'income');
    $expenses = array_filter($categories, static fn ($c) => ($c['type'] ?? '') === 'expense');
    $incomeCount = count($income);
    $expenseCount = count($expenses);
    $totalCount = count($categories);
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Categorias Financeiras</h1>
        <p class="mgmt-header__subtitle">Organize categorias de receitas, despesas e prestação de contas para a gestão financeira da igreja.</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="document.getElementById('form-category').scrollIntoView({behavior:'smooth', block:'center'}); document.getElementById('category-name').focus();">+ Nova categoria</button>
    </div>
</div>

<div class="mgmt-stats-grid" style="margin-bottom:var(--space-5);">
    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--green">📈</span>
        <div>
            <div class="mgmt-stat__value"><?= $incomeCount ?></div>
            <div class="mgmt-stat__label">Categorias de receita</div>
        </div>
    </div>
    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--red">📉</span>
        <div>
            <div class="mgmt-stat__value"><?= $expenseCount ?></div>
            <div class="mgmt-stat__label">Categorias de despesa</div>
        </div>
    </div>
    <div class="mgmt-stat">
        <span class="mgmt-stat__icon mgmt-stat__icon--blue">📁</span>
        <div>
            <div class="mgmt-stat__value"><?= $totalCount ?></div>
            <div class="mgmt-stat__label">Total cadastrado</div>
        </div>
    </div>
</div>

<div class="mgmt-dashboard-grid" style="grid-template-columns: minmax(300px, .85fr) 1fr; gap: var(--space-5);">
    <div class="mgmt-dashboard-card">
        <header class="mgmt-dashboard-card__header" style="padding-bottom: var(--space-4); border-bottom: 1px solid var(--color-border);">
            <h2 style="margin:0;">Nova categoria</h2>
            <p class="mgmt-header__subtitle" style="margin: var(--space-1) 0 0; font-size: var(--text-sm);">Cadastre uma nova categoria para organizar suas movimentações.</p>
        </header>
        <form id="form-category" method="POST" action="<?= url('/gestao/financeiro/categoria') ?>" style="padding-top: var(--space-4);">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="category-name">Nome da categoria *</label>
                <input id="category-name" class="form-input" type="text" name="name" required placeholder="Ex.: Dízimos, Aluguel, Doações">
            </div>

            <div class="form-group">
                <label class="form-label" for="category-type">Tipo *</label>
                <select id="category-type" class="form-select" name="type" required>
                    <option value="income">📈 Receita</option>
                    <option value="expense">📉 Despesa</option>
                </select>
                <span class="mgmt-auto-note">Receitas representam entradas; despesas, saídas.</span>
            </div>

            <div class="form-group">
                <label class="form-label" for="category-color">Cor de identificação</label>
                <div style="display:flex; align-items:center; gap: var(--space-3);">
                    <input id="category-color" class="form-input" type="color" name="color" value="#0A4DFF" style="width: 64px; height: 42px; padding: 4px; cursor: pointer;">
                    <span class="mgmt-auto-note" style="margin: 0;">Aparece em gráficos e listas para facilitar a leitura.</span>
                </div>
            </div>

            <div style="display:flex; gap: var(--space-3); margin-top: var(--space-5);">
                <button type="submit" class="btn btn--primary" style="flex: 1;">Adicionar categoria</button>
                <button type="reset" class="btn btn--ghost">Limpar</button>
            </div>
        </form>
    </div>

    <div class="mgmt-dashboard-card" style="padding:0; overflow:hidden;">
        <header class="mgmt-dashboard-card__header" style="padding: var(--space-5) var(--space-5) var(--space-3); border-bottom: 1px solid var(--color-border);">
            <h2 style="margin:0;">Categorias cadastradas</h2>
            <p class="mgmt-header__subtitle" style="margin: var(--space-1) 0 0; font-size: var(--text-sm);"><?= $totalCount ?> categoria<?= $totalCount === 1 ? '' : 's' ?> no total</p>
        </header>

        <?php if (empty($categories)): ?>
            <div class="mgmt-empty" style="border:0; border-radius:0;">
                <div class="mgmt-empty__icon" aria-hidden="true">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <h3 class="mgmt-empty__title">Nenhuma categoria cadastrada</h3>
                <p class="mgmt-empty__text">Comece criando categorias de receita e despesa para organizar a vida financeira da igreja.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="mgmt-table">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th style="width:120px;">Tipo</th>
                            <th style="width:80px;">Cor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <?php
                                $type = (string) ($category['type'] ?? '');
                                $color = (string) ($category['color'] ?? '#0A4DFF');
                            ?>
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap: var(--space-3);">
                                        <span style="display:inline-block; width:10px; height:32px; border-radius:4px; background:<?= e($color) ?>;"></span>
                                        <div class="mgmt-table__name"><?= e((string) ($category['name'] ?? '-')) ?></div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($type === 'income'): ?>
                                        <span class="badge badge--active">Receita</span>
                                    <?php else: ?>
                                        <span class="badge badge--inactive">Despesa</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span style="display:inline-flex; align-items:center; gap: var(--space-2);">
                                        <span style="display:inline-block; width:24px; height:24px; border-radius:6px; background:<?= e($color) ?>; border:1px solid rgba(0,0,0,0.1);"></span>
                                        <code style="font-size:0.78rem; color: var(--color-text-muted);"><?= e($color) ?></code>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__view->endSection(); ?>
