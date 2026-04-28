<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Membros</h1>
        <p class="mgmt-header__subtitle">Gerencie os membros da igreja</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-member').style.display='flex'">+ Novo membro</button>
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
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">Total</div>
            <div class="mgmt-kpi-card__value"><?= $totalMembers ?></div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
    </div>
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">Ativos</div>
            <div class="mgmt-kpi-card__value"><?= $activeCount ?></div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
        </div>
    </div>
    <div class="mgmt-kpi-card" style="justify-content:space-between; border-color: rgba(239, 68, 68, 0.2);">
        <div>
            <div class="mgmt-kpi-card__label">Inativos</div>
            <div class="mgmt-kpi-card__value"><?= $inactiveCount ?></div>
        </div>
        <div class="mgmt-kpi-card__icon" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
        </div>
    </div>
    <div class="mgmt-kpi-card" style="justify-content:space-between;">
        <div>
            <div class="mgmt-kpi-card__label">Visitantes</div>
            <div class="mgmt-kpi-card__value"><?= $visitorCount ?></div>
        </div>
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--indigo">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        </div>
    </div>
</div>

<div class="mgmt-dashboard-card" style="padding:0; overflow:hidden;">
    <div style="display:flex; align-items:center; justify-content:space-between; padding: var(--space-4); border-bottom: 1px solid var(--color-border-light); flex-wrap:wrap; gap: var(--space-3);">
        <form method="GET" action="<?= url('/gestao/membros') ?>" style="display:flex; align-items:center; gap:0; margin:0;">
            <input type="hidden" name="status" value="<?= e($filters['status'] ?? '') ?>">
            <div class="mgmt-search" style="max-width:260px;">
                <span class="mgmt-search__icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></span>
                <input type="text" name="search" class="form-input" placeholder="Buscar por nome, email..." value="<?= e($filters['search']) ?>" style="font-size:13px;">
            </div>
        </form>
        <div style="display:flex; gap:2px; align-items:center; flex-wrap:wrap;">
            <?php 
            $currentStatus = $filters['status'] ?? '';
            $tabs = ['' => 'Todos', 'active' => 'Ativos', 'visitor' => 'Visitantes', 'new' => 'Novos Convertidos'];
            foreach ($tabs as $val => $label): 
                $isActive = $currentStatus === $val;
            ?>
            <a href="<?= url('/gestao/membros?status=' . $val . '&search=' . urlencode($filters['search'])) ?>" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 6px; text-decoration:none; <?= $isActive ? 'background: var(--color-primary); color: white;' : 'color: var(--text-muted); background: transparent;' ?>"><?= $label ?></a>
            <?php endforeach; ?>
            <a href="<?= url('/gestao/membros?status=birthday&search=' . urlencode($filters['search'])) ?>" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 6px; text-decoration:none; display:flex; align-items:center; gap:4px; <?= $currentStatus === 'birthday' ? 'background: var(--color-primary); color: white;' : 'color: var(--text-muted); background: transparent;' ?>"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Aniversariantes</a>
            <a href="<?= url('/gestao/membros?status=teams&search=' . urlencode($filters['search'])) ?>" style="padding: 6px 14px; font-size: 12px; font-weight: 600; border-radius: 6px; text-decoration:none; <?= $currentStatus === 'teams' ? 'background: var(--color-primary); color: white;' : 'color: var(--text-muted); background: transparent;' ?>">Equipes / Células</a>
        </div>
    </div>

<?php if (empty($members)): ?>
    <div style="text-align:center; padding: var(--space-10); color: var(--text-muted);">
        <div style="margin-bottom:8px; opacity:0.3;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path></svg></div>
        <h3 style="font-weight:700; margin-bottom:4px;">Nenhum membro encontrado</h3>
        <p style="font-size:13px; margin-bottom: var(--space-4);">Comece cadastrando o primeiro membro da sua organização.</p>
        <button type="button" onclick="document.getElementById('modal-new-member').style.display='flex'" class="btn btn--primary">Cadastrar membro</button>
    </div>
<?php else: ?>
    <table class="mgmt-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Categoria</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $m): ?>
            <tr>
                <td><div class="mgmt-table__name"><?= e($m['name']) ?></div></td>
                <td style="color: var(--text-muted);"><?= e($m['phone'] ?? '—') ?></td>
                <td style="color: var(--text-muted);"><?= e($m['email'] ?? '—') ?></td>
                <td><span class="badge badge--<?= $m['status'] === 'visitor' ? 'visitor' : 'active' ?>" style="font-size:10px;"><?= strtoupper($m['status'] === 'visitor' ? 'VISITANTE' : 'MEMBRO') ?></span></td>
                <td><span class="badge badge--<?= $m['status'] ?>" style="font-size:10px;"><?= strtoupper(match($m['status']) { 'active' => 'ATIVO', 'inactive' => 'INATIVO', 'visitor' => 'ATIVO', 'transferred' => 'TRANSFERIDO', default => e($m['status']) }) ?></span></td>
                <td style="text-align:right;">
                    <a href="<?= url('/gestao/membros/' . $m['id'] . '/editar') ?>" style="color: var(--text-muted); display:inline-flex;" title="Editar"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
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
<?php endif; ?>
</div>

<?php $units = is_array($units ?? null) ? $units : []; ?>
<div class="modal" id="modal-new-member" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-new-member-title">
    <div class="modal__content modal__content--wide">
        <div class="modal__header">
            <h2 class="modal__title" id="modal-new-member-title">Cadastrar membro</h2>
            <button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'" aria-label="Fechar">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/membros') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="modal-grid">
                    <div class="form-group"><label class="form-label">Nome *</label><input type="text" name="name" class="form-input" required></div>
                    <div class="form-group"><label class="form-label">E-mail</label><input type="email" name="email" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Telefone</label><input type="text" name="phone" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Nascimento</label><input type="date" name="birth_date" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Unidade</label><select name="church_unit_id" class="form-select"><option value="">Sede / todas as unidades</option><?php foreach ($units as $unit): ?><option value="<?= (int) $unit['id'] ?>"><?= e((string) $unit['name']) ?></option><?php endforeach; ?></select></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Ativo</option><option value="visitor">Visitante</option><option value="inactive">Inativo</option><option value="transferred">Transferido</option></select></div>
                    <div class="form-group"><label class="form-label">Data de membresia</label><input type="date" name="membership_date" class="form-input" value="<?= date('Y-m-d') ?>"></div>
                    <div class="form-group"><label class="form-label">Estado civil</label><select name="marital_status" class="form-select"><option value="">Não informado</option><option value="single">Solteiro(a)</option><option value="married">Casado(a)</option><option value="divorced">Divorciado(a)</option><option value="widowed">Viúvo(a)</option></select></div>
                </div>
                <div class="form-group"><label class="form-label">Endereço</label><input type="text" name="address" class="form-input"></div>
                <div class="form-group"><label class="form-label">Observações</label><textarea name="notes" class="form-input" rows="3"></textarea></div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--primary">Cadastrar membro</button>
            </div>
        </form>
    </div>
</div>

<?php $__view->endSection(); ?>
