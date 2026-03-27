<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Membros</h1>
        <p class="mgmt-header__subtitle"><?= $pagination['total'] ?> membros cadastrados</p>
    </div>
    <div class="mgmt-header__actions">
        <a href="<?= url('/gestao/membros/novo') ?>" class="btn btn--primary">+ Novo membro</a>
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
