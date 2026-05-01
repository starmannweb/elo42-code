<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php
    $categories = is_array($categories ?? null) ? $categories : [];
    $filterType = $filterType ?? '';
    $totals = is_array($totals ?? null) ? $totals : ['income' => 0, 'expense' => 0, 'used' => 0];
    $colorPresets = ['#10b981','#f59e0b','#3b82f6','#a855f7','#ef4444','#0ea5e9','#f97316','#64748b','#8b5cf6','#14b8a6','#0A4DFF','#e11d48'];

    $incomeCategories = array_values(array_filter($categories, static fn ($c) => ($c['type'] ?? '') === 'income'));
    $expenseCategories = array_values(array_filter($categories, static fn ($c) => ($c['type'] ?? '') === 'expense'));
    $totalUsage = array_sum(array_map(static fn ($c) => (int) ($c['usage_count'] ?? 0), $categories));
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Categorias Financeiras</h1>
        <p class="mgmt-header__subtitle">Organize entradas e saídas em grupos consistentes — facilita relatórios, prestação de contas e auditoria.</p>
    </div>
    <div class="mgmt-header__actions">
        <?php if (empty($categories)): ?>
            <form method="POST" action="<?= url('/gestao/financeiro/categoria/popular') ?>" style="margin:0;" onsubmit="return confirm('Criar 10 categorias padrão (Dízimos, Ofertas, Aluguel, Salários...)?');">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn--outline">Criar conjunto padrão</button>
            </form>
        <?php endif; ?>
        <button type="submit" form="form-category" class="btn btn--primary">Adicionar categoria</button>
    </div>
</div>

<div class="mgmt-kpi-grid" style="grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:var(--space-5);">
    <div class="mgmt-kpi-card">
        <div>
            <div class="mgmt-kpi-card__label">Categorias de receita</div>
            <div class="mgmt-kpi-card__value" style="color:#059669;"><?= (int) $totals['income'] ?></div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div>
            <div class="mgmt-kpi-card__label">Categorias de despesa</div>
            <div class="mgmt-kpi-card__value" style="color:#dc2626;"><?= (int) $totals['expense'] ?></div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div>
            <div class="mgmt-kpi-card__label">Categorias em uso</div>
            <div class="mgmt-kpi-card__value"><?= (int) $totals['used'] ?></div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div>
            <div class="mgmt-kpi-card__label">Lançamentos categorizados</div>
            <div class="mgmt-kpi-card__value"><?= (int) $totalUsage ?></div>
        </div>
    </div>
</div>

<nav class="mgmt-tabs-frame" style="margin-bottom:var(--space-5);">
    <div class="mgmt-tabs">
        <a class="mgmt-tabs__link <?= $filterType === '' ? 'active' : '' ?>" href="<?= url('/gestao/categorias-financeiras') ?>"><span>Todas</span></a>
        <a class="mgmt-tabs__link <?= $filterType === 'income' ? 'active' : '' ?>" href="<?= url('/gestao/categorias-financeiras?type=income') ?>"><span>Receitas</span></a>
        <a class="mgmt-tabs__link <?= $filterType === 'expense' ? 'active' : '' ?>" href="<?= url('/gestao/categorias-financeiras?type=expense') ?>"><span>Despesas</span></a>
    </div>
</nav>

<div class="mgmt-dashboard-grid" style="grid-template-columns: minmax(300px, .55fr) 1fr;align-items:start;">
    <div class="mgmt-dashboard-card">
        <header class="mgmt-dashboard-card__header" style="margin-bottom:var(--space-4);">
            <h2 class="mgmt-info-card__title" style="margin:0;">Nova categoria</h2>
        </header>
        <form id="form-category" method="POST" action="<?= url('/gestao/financeiro/categoria') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label" for="category-name">Nome</label>
                <input id="category-name" class="form-input" type="text" name="name" required placeholder="Ex.: Dízimos, Aluguel, Eventos...">
            </div>
            <div class="form-group">
                <label class="form-label" for="category-type">Tipo</label>
                <select id="category-type" class="form-select" name="type" required>
                    <option value="income">Receita</option>
                    <option value="expense">Despesa</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Cor</label>
                <div class="category-color-presets" role="radiogroup" aria-label="Cor da categoria">
                    <?php foreach ($colorPresets as $i => $hex): ?>
                        <label class="category-color-presets__chip" style="--chip-color: <?= e($hex) ?>;">
                            <input type="radio" name="color" value="<?= e($hex) ?>" <?= $i === 0 ? 'checked' : '' ?> aria-label="<?= e($hex) ?>">
                            <span aria-hidden="true"></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <p class="form-hint" style="margin-top:.5rem;">Use cores distintas para reconhecer a categoria nos relatórios.</p>
            </div>
            <button type="submit" class="btn btn--primary" style="width:100%;">Adicionar categoria</button>
        </form>
    </div>

    <div class="mgmt-dashboard-card" style="padding:0;overflow:hidden;">
        <?php if (empty($categories)): ?>
            <div class="mgmt-empty" style="padding:var(--space-8);">
                <h3 class="mgmt-empty__title">Nenhuma categoria cadastrada.</h3>
                <p class="mgmt-empty__text">Crie categorias para classificar receitas e despesas — ou comece com um conjunto padrão pronto.</p>
                <form method="POST" action="<?= url('/gestao/financeiro/categoria/popular') ?>" style="margin-top:var(--space-3);" onsubmit="return confirm('Criar 10 categorias padrão (Dízimos, Ofertas, Aluguel, Salários...)?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn--primary">Criar conjunto padrão</button>
                </form>
            </div>
        <?php else: ?>
            <?php foreach ([['title' => 'Receitas', 'type' => 'income', 'rows' => $incomeCategories, 'accent' => '#059669'], ['title' => 'Despesas', 'type' => 'expense', 'rows' => $expenseCategories, 'accent' => '#dc2626']] as $section): ?>
                <?php if ($filterType !== '' && $section['type'] !== $filterType) continue; ?>
                <?php if (empty($section['rows'])) continue; ?>
                <div class="mgmt-section-header" style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid var(--color-border-light);">
                    <h3 style="margin:0;font-size:.85rem;text-transform:uppercase;letter-spacing:.04em;color:<?= $section['accent'] ?>;font-weight:700;"><?= e($section['title']) ?></h3>
                    <span class="hub-panel__text" style="margin:0;font-size:.8rem;"><?= count($section['rows']) ?> categoria<?= count($section['rows']) === 1 ? '' : 's' ?></span>
                </div>
                <table class="mgmt-table">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th style="text-align:center;">Lançamentos</th>
                            <th style="text-align:right;">Total movimentado</th>
                            <th style="text-align:right;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($section['rows'] as $category): ?>
                            <?php
                                $catId = (int) ($category['id'] ?? 0);
                                $color = (string) ($category['color'] ?? '#0A4DFF');
                                $name = (string) ($category['name'] ?? '-');
                                $usage = (int) ($category['usage_count'] ?? 0);
                                $amount = (float) ($category['total_amount'] ?? 0);
                                $isSystem = (int) ($category['is_system'] ?? 0) === 1;
                            ?>
                            <tr data-category-row="<?= $catId ?>">
                                <td>
                                    <div style="display:flex;align-items:center;gap:.65rem;">
                                        <span style="display:inline-block;width:14px;height:14px;border-radius:4px;background:<?= e($color) ?>;border:1px solid rgba(255,255,255,.18);flex-shrink:0;"></span>
                                        <div class="mgmt-table__name"><?= e($name) ?></div>
                                        <?php if ($isSystem): ?>
                                            <span class="badge badge--active" style="font-size:.65rem;">Sistema</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    <?php if ($usage > 0): ?>
                                        <span class="badge badge--active"><?= $usage ?></span>
                                    <?php else: ?>
                                        <span class="hub-panel__text" style="margin:0;font-size:.8rem;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;font-weight:700;color:<?= $section['accent'] ?>;">
                                    R$ <?= number_format($amount, 2, ',', '.') ?>
                                </td>
                                <td style="text-align:right;">
                                    <div style="display:inline-flex;gap:.4rem;">
                                        <button type="button" class="btn btn--ghost btn--sm" data-edit-category data-id="<?= $catId ?>" data-name="<?= e($name) ?>" data-type="<?= e((string) ($category['type'] ?? 'income')) ?>" data-color="<?= e($color) ?>">Editar</button>
                                        <?php if (!$isSystem): ?>
                                            <form method="POST" action="<?= url('/gestao/financeiro/categoria/' . $catId . '/excluir') ?>" style="margin:0;" onsubmit="return confirm('Excluir a categoria <?= e($name) ?>?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn--ghost btn--sm" style="color:#dc2626;" <?= $usage > 0 ? 'disabled title="Existem lançamentos vinculados"' : '' ?>>Excluir</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="modal" id="modal-edit-category" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-edit-category-title">
    <div class="modal__content">
        <div class="modal__header">
            <h2 class="modal__title" id="modal-edit-category-title">Editar categoria</h2>
            <button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'">&times;</button>
        </div>
        <form id="form-edit-category" method="POST" action="<?= url('/gestao/financeiro/categoria/0/editar') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="form-group">
                    <label class="form-label" for="edit-name">Nome</label>
                    <input id="edit-name" class="form-input" type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-type">Tipo</label>
                    <select id="edit-type" class="form-select" name="type" required>
                        <option value="income">Receita</option>
                        <option value="expense">Despesa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit-color">Cor</label>
                    <input id="edit-color" class="form-input" type="color" name="color" value="#0A4DFF" style="height:42px;">
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--primary">Salvar alterações</button>
            </div>
        </form>
    </div>
</div>

<style>
.category-color-presets { display:flex; flex-wrap:wrap; gap:.45rem; }
.category-color-presets__chip { position:relative; cursor:pointer; }
.category-color-presets__chip input { position:absolute; opacity:0; pointer-events:none; }
.category-color-presets__chip span { display:block; width:26px; height:26px; border-radius:8px; background:var(--chip-color); border:2px solid transparent; transition:transform .12s ease, border-color .12s ease; }
.category-color-presets__chip input:checked + span { border-color: rgba(255,255,255,.85); transform:scale(1.08); box-shadow:0 0 0 2px var(--chip-color); }
.category-color-presets__chip:hover span { transform:scale(1.06); }
</style>

<script>
(function () {
    var modal = document.getElementById('modal-edit-category');
    var form = document.getElementById('form-edit-category');
    if (!modal || !form) return;
    var nameField = document.getElementById('edit-name');
    var typeField = document.getElementById('edit-type');
    var colorField = document.getElementById('edit-color');
    var baseAction = '<?= url('/gestao/financeiro/categoria/__ID__/editar') ?>';

    document.querySelectorAll('[data-edit-category]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            nameField.value = btn.dataset.name || '';
            typeField.value = btn.dataset.type || 'income';
            colorField.value = btn.dataset.color || '#0A4DFF';
            form.action = baseAction.replace('__ID__', btn.dataset.id || '0');
            modal.style.display = 'flex';
        });
    });
    modal.addEventListener('click', function (e) { if (e.target === modal) modal.style.display = 'none'; });
})();
</script>

<?php $__view->endSection(); ?>
