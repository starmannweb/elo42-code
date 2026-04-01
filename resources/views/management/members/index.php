<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Membros</h1>
        <p class="mgmt-header__subtitle">Gerencie os membros da igreja</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="window.location.href='<?= url('/gestao/membros/novo') ?>'">+ Novo Membro</button>
    </div>
</div>

<?php
$totalMembers = $pagination['total'] ?? 0;
$activeCount = 0;
$inactiveCount = 0;
$visitorCount = 0;
foreach ($members as $m) {
    if ($m['status'] === 'active') $activeCount++;
    elseif ($m['status'] === 'inactive') $inactiveCount++;
    elseif ($m['status'] === 'visitor') $visitorCount++;
}
?>

<div class="mgmt-kpi-grid" style="grid-template-columns: repeat(4, 1fr);">
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue" style="background: rgba(59, 130, 246, 0.1);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Total</div>
            <div class="mgmt-kpi-card__value"><?= $totalMembers ?></div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Ativos</div>
            <div class="mgmt-kpi-card__value"><?= $activeCount ?></div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Inativos</div>
            <div class="mgmt-kpi-card__value"><?= $inactiveCount ?></div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--indigo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Visitantes</div>
            <div class="mgmt-kpi-card__value"><?= $visitorCount ?></div>
        </div>
    </div>
</div>

<form method="GET" action="<?= url('/gestao/membros') ?>" class="mgmt-filters">
    <div class="mgmt-search">
        <span class="mgmt-search__icon">🔍</span>
        <input type="text" name="search" class="form-input" placeholder="Buscar por nome, e-mail ou telefone..." value="<?= e($filters['search']) ?>">
    </div>
    <select name="status" class="form-select">
        <option value="">Todos os status</option>
        <option value="active" <?= $filters['status'] === 'active' ? 'selected' : '' ?>>Ativos</option>
        <option value="inactive" <?= $filters['status'] === 'inactive' ? 'selected' : '' ?>>Inativos</option>
        <option value="visitor" <?= $filters['status'] === 'visitor' ? 'selected' : '' ?>>Visitantes</option>
        <option value="transferred" <?= $filters['status'] === 'transferred' ? 'selected' : '' ?>>Transferidos</option>
    </select>
    <button type="submit" class="btn btn--ghost">Filtrar</button>
</form>

<?php if (empty($members)): ?>
    <div class="mgmt-empty">
        <div class="mgmt-empty__icon">👥</div>
        <h3 class="mgmt-empty__title">Nenhum membro encontrado</h3>
        <p class="mgmt-empty__text">Comece cadastrando o primeiro membro da sua organização.</p>
        <a href="<?= url('/gestao/membros/novo') ?>" class="btn btn--primary">Cadastrar membro</a>
    </div>
<?php else: ?>
    <div class="mgmt-table-container">
        <table class="mgmt-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Contato</th>
                    <th>Status</th>
                    <th>Membro desde</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $m): ?>
                <tr>
                    <td>
                        <div class="mgmt-table__name"><?= e($m['name']) ?></div>
                        <?php if ($m['email']): ?>
                            <div class="mgmt-table__sub"><?= e($m['email']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td><?= e($m['phone'] ?? '—') ?></td>
                    <td><span class="badge badge--<?= $m['status'] ?>"><?= e(ucfirst($m['status'])) ?></span></td>
                    <td><?= $m['membership_date'] ? date('d/m/Y', strtotime($m['membership_date'])) : '—' ?></td>
                    <td class="mgmt-table__actions">
                        <a href="<?= url('/gestao/membros/' . $m['id']) ?>">Ver</a>
                        <a href="<?= url('/gestao/membros/' . $m['id'] . '/editar') ?>">Editar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($pagination['totalPages'] > 1): ?>
        <div class="mgmt-pagination">
            <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                <?php if ($i === $pagination['page']): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= url('/gestao/membros?page=' . $i . '&search=' . urlencode($filters['search']) . '&status=' . $filters['status']) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php $__view->endSection(); ?>
