<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<div class="mgmt-header">
    <div>
        <?php if (!empty($isTopDonors)): ?>
            <h1 class="mgmt-header__title">Ranking de Ofertantes</h1>
            <p class="mgmt-header__subtitle">Acompanhe os membros mais engajados financeiramente com a organização.</p>
        <?php else: ?>
            <h1 class="mgmt-header__title">Membros</h1>
            <p class="mgmt-header__subtitle">Gerencie os membros da igreja</p>
        <?php endif; ?>
    </div>
    <?php if (empty($isTopDonors)): ?>
    <div class="mgmt-header__actions">
        <a href="<?= url('/gestao/membros/novo') ?>" class="btn btn--primary">+ Novo membro</a>
    </div>
    <?php endif; ?>
</div>

<!-- Tabs removidas pois são renderizadas pelo layout base -->

<?php if (!empty($isTopDonors)): ?>
<div class="mgmt-filter-card mgmt-card">
    <div class="mgmt-card__body">
        <form method="GET" action="<?= url('/gestao/membros/top-ofertantes') ?>" class="mgmt-filter-grid">
            <div class="mgmt-filter-field">
                <label for="top_donors_period" class="form-label">Per&iacute;odo</label>
                <input type="month" id="top_donors_period" name="period" class="form-control" value="<?= e($topDonorsPeriod ?? date('Y-m')) ?>">
            </div>
            <div class="mgmt-filter-actions">
                <button type="submit" class="btn btn--outline">Filtrar</button>
                <a href="<?= url('/gestao/membros/top-ofertantes') ?>" class="btn btn--outline">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="mgmt-dashboard-card" style="padding:0;overflow:hidden;">
    <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--color-border-light);">
        <h2 class="mgmt-info-card__title" style="margin:0;">Ranking de Ofertantes (<?= e($topDonorsPeriodLabel ?? 'Periodo atual') ?>)</h2>
        <p style="margin:4px 0 0; font-size:12px; color:var(--text-muted);">Acompanhe os membros mais engajados financeiramente com a organização.</p>
    </div>
    <?php if (empty($topDonors)): ?>
        <div class="mgmt-empty" style="padding:2.5rem 1.5rem;text-align:center;">
            <p class="mgmt-empty__text" style="margin:0;color:var(--color-text-muted);">Nenhum dado financeiro registrado neste ano.</p>
        </div>
    <?php else: ?>
        <table class="mgmt-table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">Posição</th>
                    <th>Doador</th>
                    <th style="text-align:center;">Quantidade de Lançamentos</th>
                    <th style="text-align:right;">Valor Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $pos = 1; foreach ($topDonors as $d): ?>
                    <tr <?= $pos <= 3 ? 'style="background:rgba(217, 119, 6, 0.05);"' : '' ?>>
                        <td style="text-align:center; font-weight: 800; color: <?= $pos === 1 ? '#d97706' : ($pos === 2 ? '#64748b' : ($pos === 3 ? '#b45309' : 'var(--text-muted)')) ?>;"><?= $pos ?>º</td>
                        <td><div class="mgmt-table__name"><?= e($d['name'] ?? 'Anônimo') ?></div></td>
                        <td style="text-align:center;"><?= (int) $d['donations_count'] ?> lançamentos</td>
                        <td style="text-align:right;font-weight:800; color:#059669;">R$ <?= number_format((float)($d['total_amount'] ?? 0), 2, ',', '.') ?></td>
                    </tr>
                <?php $pos++; endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php else: ?>

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

<div class="mgmt-filter-card mgmt-card">
    <div class="mgmt-card__body">
        <form method="GET" action="<?= url('/gestao/membros') ?>" class="mgmt-filter-grid">
            <div class="mgmt-filter-field">
                <label for="member_search" class="form-label">Buscar</label>
                <input type="text" id="member_search" name="search" class="form-control" placeholder="Nome, e-mail ou telefone" value="<?= e($filters['search'] ?? '') ?>">
            </div>
            <div class="mgmt-filter-field">
                <label for="member_status" class="form-label">Status</label>
                <select id="member_status" name="status" class="form-control">
                    <option value="">Todos</option>
                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Ativos</option>
                    <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inativos</option>
                    <option value="visitor" <?= ($filters['status'] ?? '') === 'visitor' ? 'selected' : '' ?>>Visitantes</option>
                    <option value="transferred" <?= ($filters['status'] ?? '') === 'transferred' ? 'selected' : '' ?>>Transferidos</option>
                </select>
            </div>
            <div class="mgmt-filter-field">
                <label for="member_month" class="form-label">Período</label>
                <input type="month" id="member_month" name="month" class="form-control" value="<?= e($filters['month'] ?? date('Y-m')) ?>">
            </div>
            <div class="mgmt-filter-actions">
                <button type="submit" class="btn btn--outline">Filtrar</button>
                <a href="<?= url('/gestao/membros') ?>" class="btn btn--outline">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="mgmt-dashboard-card" style="padding:0; overflow:hidden;">

<?php if (empty($members)): ?>
    <div style="text-align:center; padding: var(--space-10); color: var(--text-muted);">
        <div style="margin-bottom:8px; opacity:0.3;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path></svg></div>
        <h3 style="font-weight:700; margin-bottom:4px;">Nenhum membro encontrado</h3>
        <p style="font-size:13px; margin-bottom: var(--space-4);">Comece cadastrando o primeiro membro da sua organização.</p>
        <a href="<?= url('/gestao/membros/novo') ?>" class="btn btn--primary">Cadastrar membro</a>
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
                <td style="text-align:right; display: flex; gap: 8px; justify-content: flex-end;">
                    <button type="button" onclick="openMemberDetails(<?= $m['id'] ?>)" class="btn btn--ghost" style="padding: 4px; height: auto;" title="Ficha do Membro">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                    <button type="button" onclick="openMemberEdit(<?= $m['id'] ?>)" class="btn btn--ghost" style="padding: 4px; height: auto;" title="Editar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
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

<!-- Modal Ficha do Membro -->
<div class="modal" id="modal-member-details" style="display:none;" role="dialog" aria-modal="true">
    <div class="modal__content" style="max-width: 500px; padding: 0; overflow: hidden;">
        <div style="background: var(--color-primary); color: white; padding: 24px; position: relative;">
            <button type="button" style="position:absolute; top: 16px; right: 16px; background:transparent; border:none; color:white; cursor:pointer; font-size: 20px;" onclick="this.closest('.modal').style.display='none'">&times;</button>
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div>
                    <h2 style="margin: 0; font-size: 20px; font-weight: 700;" id="det-name">Nome do Membro</h2>
                    <div style="font-size: 13px; opacity: 0.9; display: flex; align-items: center; gap: 4px; margin-top: 4px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <span id="det-age">--</span>
                    </div>
                    <div style="display: flex; gap: 8px; margin-top: 8px;">
                        <span id="det-status-badge" style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">Ativo</span>
                        <span id="det-category-badge" style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600;">Membro</span>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding: 24px;">
            <div style="display: flex; gap: 12px; margin-bottom: 24px;">
                <div style="flex:1; background: var(--color-bg-light); border: 1px solid var(--color-border-light); padding: 12px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 4px;">Total Dízimos</div>
                    <div style="font-size: 16px; font-weight: 700; color: #6366f1;">R$ 0,00</div>
                    <div style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">0 registros</div>
                </div>
                <div style="flex:1; background: var(--color-bg-light); border: 1px solid var(--color-border-light); padding: 12px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 4px;">Total Ofertas</div>
                    <div style="font-size: 16px; font-weight: 700; color: #10b981;">R$ 0,00</div>
                    <div style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">0 registros</div>
                </div>
            </div>

            <h3 style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; border-bottom: 1px solid var(--color-border-light); padding-bottom: 8px;">Dados Pessoais</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                <div>
                    <div style="font-size: 11px; color: var(--text-muted);">Gênero</div>
                    <div style="font-size: 14px; font-weight: 500;" id="det-gender">--</div>
                </div>
                <div>
                    <div style="font-size: 11px; color: var(--text-muted);">Estado Civil</div>
                    <div style="font-size: 14px; font-weight: 500;" id="det-marital">--</div>
                </div>
                <div style="grid-column: 1 / -1;">
                    <div style="font-size: 11px; color: var(--text-muted);">Endereço</div>
                    <div style="font-size: 14px; font-weight: 500;" id="det-address">--</div>
                </div>
            </div>

            <h3 style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; border-bottom: 1px solid var(--color-border-light); padding-bottom: 8px;">Contato</h3>
            <div style="display: grid; grid-template-columns: 1fr; gap: 16px; margin-bottom: 24px;">
                <div>
                    <div style="font-size: 11px; color: var(--text-muted);">Email</div>
                    <div style="font-size: 14px; font-weight: 500; display:flex; align-items:center; gap:6px;" id="det-email"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> <span>--</span></div>
                </div>
                <div>
                    <div style="font-size: 11px; color: var(--text-muted);">Telefone</div>
                    <div style="font-size: 14px; font-weight: 500; display:flex; align-items:center; gap:6px;" id="det-phone"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> <span>--</span></div>
                </div>
            </div>

            <h3 style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; border-bottom: 1px solid var(--color-border-light); padding-bottom: 8px;">Dados Eclesiásticos</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                <div>
                    <div style="font-size: 11px; color: var(--text-muted);">Membresia</div>
                    <div style="font-size: 14px; font-weight: 500;" id="det-membership">--</div>
                </div>
                <div>
                    <div style="font-size: 11px; color: var(--text-muted);">Batismo</div>
                    <div style="font-size: 14px; font-weight: 500;" id="det-baptism">--</div>
                </div>
                <div style="grid-column: 1 / -1;">
                    <div style="font-size: 11px; color: var(--text-muted);">Unidade</div>
                    <div style="font-size: 14px; font-weight: 500;" id="det-unit">--</div>
                </div>
            </div>

            <div id="det-notes-container" style="display:none;">
                <h3 style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; border-bottom: 1px solid var(--color-border-light); padding-bottom: 8px;">Observações</h3>
                <div style="background: var(--color-bg-light); border: 1px solid var(--color-border-light); padding: 12px; border-radius: 8px; font-size: 13px; color: var(--text-muted);" id="det-notes">
                    --
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo/Editar Membro -->
<div class="modal" id="modal-new-member" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-new-member-title">
    <div class="modal__content modal__content--wide">
        <div class="modal__header">
            <h2 class="modal__title" id="modal-new-member-title">Cadastrar membro</h2>
            <button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'" aria-label="Fechar">&times;</button>
        </div>
        <form id="form-member" method="POST" action="<?= url('/gestao/membros') ?>" enctype="multipart/form-data" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body">
                
                <div class="member-photo-upload">
                    <div class="member-photo-upload__preview" id="member-photo-preview">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <div class="member-photo-upload__actions">
                        <input type="file" id="member-photo-input" accept="image/png,image/jpeg,image/webp" hidden>
                        <input type="hidden" name="photo_cropped" id="member-photo-cropped">
                        <button type="button" class="btn btn--outline btn--sm" id="btn-upload-member-photo"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg> Enviar foto</button>
                    </div>
                </div>

                <div class="modal-grid">
                    <div class="form-group" style="grid-column: 1 / -1;"><label class="form-label">Nome completo *</label><input type="text" name="name" id="inp-name" class="form-input" required></div>
                    <div class="form-group"><label class="form-label">E-mail</label><input type="email" name="email" id="inp-email" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Telefone</label><input type="text" name="phone" id="inp-phone" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Data de Nascimento</label><input type="date" name="birth_date" id="inp-birth" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Gênero</label><select name="gender" id="inp-gender" class="form-select"><option value="">—</option><option value="M">Masculino</option><option value="F">Feminino</option><option value="other">Outro</option></select></div>
                    
                    <div class="form-group"><label class="form-label">Endereço Completo</label><input type="text" name="address" id="inp-address" class="form-input"></div>
                    <div class="form-group"><label class="form-label">CEP</label><input type="text" name="zip_code" id="inp-zip" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Cidade</label><input type="text" name="city" id="inp-city" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Estado</label>
                        <select name="state" id="inp-state" class="form-select">
                            <option value="">UF</option>
                            <?php foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf): ?>
                                <option value="<?= $uf ?>"><?= $uf ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="member-location-panel">
                    <input type="hidden" name="latitude" id="inp-latitude">
                    <input type="hidden" name="longitude" id="inp-longitude">
                    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 12px;">
                        <h4 style="margin:0; font-size: 13px; display:flex; align-items:center; gap: 8px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Localização no Mapa</h4>
                        <button type="button" class="btn btn--outline btn--sm" id="btn-open-member-map">Definir no mapa</button>
                    </div>
                    <div class="member-location-panel__status" id="member-location-status"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Localizacao nao definida (automatica pelo endereco)</div>
                </div>

                <div class="modal-grid">
                    <div class="form-group"><label class="form-label">Data de Batismo</label><input type="date" name="baptism_date" id="inp-baptism" class="form-input"></div>
                    <div class="form-group"><label class="form-label">Data de Membresia</label><input type="date" name="membership_date" id="inp-membership" class="form-input" value="<?= date('Y-m-d') ?>"></div>
                    <div class="form-group"><label class="form-label">Unidade</label><select name="church_unit_id" id="inp-unit" class="form-select"><option value="">Sede / todas as unidades</option><?php foreach ($units as $unit): ?><option value="<?= (int) $unit['id'] ?>"><?= e((string) $unit['name']) ?></option><?php endforeach; ?></select></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" id="inp-status" class="form-select"><option value="active">Ativo</option><option value="visitor">Visitante</option><option value="inactive">Inativo</option><option value="transferred">Transferido</option></select></div>
                    <div class="form-group"><label class="form-label">Estado civil</label><select name="marital_status" id="inp-marital" class="form-select"><option value="">Não informado</option><option value="single">Solteiro(a)</option><option value="married">Casado(a)</option><option value="divorced">Divorciado(a)</option><option value="widowed">Viúvo(a)</option></select></div>
                </div>
                <div class="form-group" style="margin-top: 16px;"><label class="form-label">Observações</label><textarea name="notes" id="inp-notes" class="form-input" rows="3"></textarea></div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button>
                <button type="submit" id="btn-submit-member" class="btn btn--primary">Salvar Membro</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="modal-photo-crop" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-photo-crop-title">
    <div class="modal__content modal__content--crop">
        <div class="modal__header">
            <h2 class="modal__title" id="modal-photo-crop-title">Recortar Foto</h2>
            <button type="button" class="modal__close" onclick="closePhotoCropModal()" aria-label="Fechar">&times;</button>
        </div>
        <div class="modal__body modal__body--compact">
            <div class="photo-cropper">
                <div class="photo-cropper__stage" id="photo-crop-stage">
                    <img id="photo-crop-image" alt="">
                    <div class="photo-cropper__frame" aria-hidden="true"></div>
                </div>
                <label class="photo-cropper__zoom">
                    <span>Zoom</span>
                    <input type="range" id="photo-crop-zoom" min="1" max="3" step="0.01" value="1">
                </label>
                <p class="photo-cropper__hint">Arraste para posicionar e use o controle para redimensionar. A imagem sera recortada em formato quadrado.</p>
            </div>
        </div>
        <div class="modal__footer">
            <button type="button" class="btn btn--ghost" onclick="closePhotoCropModal()">Cancelar</button>
            <button type="button" class="btn btn--primary" id="btn-apply-photo-crop">Aplicar Recorte</button>
        </div>
    </div>
</div>

<div class="modal" id="modal-member-map" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-member-map-title">
    <div class="modal__content modal__content--map">
        <div class="modal__header">
            <h2 class="modal__title" id="modal-member-map-title">Definir Localizacao</h2>
            <button type="button" class="modal__close" onclick="closeMemberMapModal()" aria-label="Fechar">&times;</button>
        </div>
        <div class="modal__body modal__body--compact">
            <div class="member-map-picker-toolbar">
                <button type="button" class="btn btn--outline btn--sm" id="btn-geocode-member-address">Buscar pelo endereco</button>
                <span id="member-map-helper">Clique no mapa ou arraste o marcador.</span>
            </div>
            <div id="member-map-picker" class="member-map-picker"></div>
        </div>
        <div class="modal__footer">
            <button type="button" class="btn btn--ghost" onclick="closeMemberMapModal()">Cancelar</button>
            <button type="button" class="btn btn--primary" id="btn-save-member-location">Salvar Localizacao</button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
function calculateAge(birthDateString) {
    if (!birthDateString) return '--';
    const today = new Date();
    const birthDate = new Date(birthDateString);
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age + ' anos';
}

function formatDate(dateString) {
    if (!dateString) return '--';
    const [year, month, day] = dateString.split('-');
    return `${day}/${month}/${year}`;
}

const memberAvatarSvg = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
const photoInput = document.getElementById('member-photo-input');
const photoPreview = document.getElementById('member-photo-preview');
const photoCropped = document.getElementById('member-photo-cropped');
const cropModal = document.getElementById('modal-photo-crop');
const cropStage = document.getElementById('photo-crop-stage');
const cropImage = document.getElementById('photo-crop-image');
const cropZoom = document.getElementById('photo-crop-zoom');
let cropOffset = { x: 0, y: 0 };
let cropDragging = false;
let cropStart = { x: 0, y: 0 };

document.getElementById('btn-upload-member-photo')?.addEventListener('click', () => photoInput?.click());
photoInput?.addEventListener('change', (event) => {
    const file = event.target.files && event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = () => openPhotoCropModal(String(reader.result || ''));
    reader.readAsDataURL(file);
});

cropZoom?.addEventListener('input', updateCropImageTransform);
cropImage?.addEventListener('load', resetCropImageLayout);
cropStage?.addEventListener('pointerdown', (event) => {
    cropDragging = true;
    cropStage.setPointerCapture(event.pointerId);
    cropStart = { x: event.clientX - cropOffset.x, y: event.clientY - cropOffset.y };
});
cropStage?.addEventListener('pointermove', (event) => {
    if (!cropDragging) return;
    cropOffset = { x: event.clientX - cropStart.x, y: event.clientY - cropStart.y };
    updateCropImageTransform();
});
cropStage?.addEventListener('pointerup', () => { cropDragging = false; });
document.getElementById('btn-apply-photo-crop')?.addEventListener('click', applyPhotoCrop);

function openPhotoCropModal(src) {
    cropOffset = { x: 0, y: 0 };
    if (cropZoom) cropZoom.value = '1';
    if (cropImage) cropImage.src = src;
    if (cropModal) cropModal.style.display = 'flex';
}

function closePhotoCropModal() {
    if (cropModal) cropModal.style.display = 'none';
    if (photoInput) photoInput.value = '';
}
window.closePhotoCropModal = closePhotoCropModal;

function resetCropImageLayout() {
    if (!cropImage || !cropStage || !cropImage.naturalWidth || !cropImage.naturalHeight) return;
    const rect = cropStage.getBoundingClientRect();
    const scale = Math.max(rect.width / cropImage.naturalWidth, rect.height / cropImage.naturalHeight);
    cropImage.style.width = `${cropImage.naturalWidth * scale}px`;
    cropImage.style.height = `${cropImage.naturalHeight * scale}px`;
    updateCropImageTransform();
}

function updateCropImageTransform() {
    if (!cropImage || !cropZoom) return;
    cropImage.style.transform = `translate(-50%, -50%) translate(${cropOffset.x}px, ${cropOffset.y}px) scale(${cropZoom.value})`;
}

function applyPhotoCrop() {
    if (!cropImage || !cropStage || !photoCropped || !photoPreview) return;
    const frame = cropStage.querySelector('.photo-cropper__frame');
    if (!frame) return;
    const frameRect = frame.getBoundingClientRect();
    const imageRect = cropImage.getBoundingClientRect();
    const sx = Math.max(0, (frameRect.left - imageRect.left) * (cropImage.naturalWidth / imageRect.width));
    const sy = Math.max(0, (frameRect.top - imageRect.top) * (cropImage.naturalHeight / imageRect.height));
    const sw = Math.min(cropImage.naturalWidth - sx, frameRect.width * (cropImage.naturalWidth / imageRect.width));
    const sh = Math.min(cropImage.naturalHeight - sy, frameRect.height * (cropImage.naturalHeight / imageRect.height));
    const canvas = document.createElement('canvas');
    canvas.width = 500;
    canvas.height = 500;
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.drawImage(cropImage, sx, sy, sw, sh, 0, 0, canvas.width, canvas.height);
    const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
    photoCropped.value = dataUrl;
    setMemberPhotoPreview(dataUrl);
    closePhotoCropModal();
}

function setMemberPhotoPreview(src) {
    if (!photoPreview) return;
    if (src) {
        photoPreview.innerHTML = `<img src="${escapeHtml(src)}" alt="">`;
        return;
    }
    photoPreview.innerHTML = memberAvatarSvg;
}

const memberMapModal = document.getElementById('modal-member-map');
const memberMapHelper = document.getElementById('member-map-helper');
let memberPickerMap = null;
let memberPickerMarker = null;
let pendingMemberLocation = null;

document.getElementById('btn-open-member-map')?.addEventListener('click', openMemberMapModal);
document.getElementById('btn-geocode-member-address')?.addEventListener('click', geocodeMemberAddress);
document.getElementById('btn-save-member-location')?.addEventListener('click', () => {
    if (pendingMemberLocation) {
        setMemberLocation(pendingMemberLocation.lat, pendingMemberLocation.lng);
    }
    closeMemberMapModal();
});

function openMemberMapModal() {
    if (!memberMapModal) return;
    memberMapModal.style.display = 'flex';
    const lat = parseFloat(document.getElementById('inp-latitude')?.value || '');
    const lng = parseFloat(document.getElementById('inp-longitude')?.value || '');
    const start = Number.isFinite(lat) && Number.isFinite(lng) ? { lat, lng } : { lat: -22.7057, lng: -46.9854 };
    pendingMemberLocation = start;
    window.setTimeout(() => initMemberPickerMap(start), 80);
}

function closeMemberMapModal() {
    if (memberMapModal) memberMapModal.style.display = 'none';
}
window.closeMemberMapModal = closeMemberMapModal;

function initMemberPickerMap(center) {
    const mapEl = document.getElementById('member-map-picker');
    if (!mapEl || typeof L === 'undefined') return;
    if (!memberPickerMap) {
        memberPickerMap = L.map(mapEl).setView([center.lat, center.lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(memberPickerMap);
        memberPickerMap.on('click', (event) => setPendingMemberMarker(event.latlng.lat, event.latlng.lng));
    } else {
        memberPickerMap.setView([center.lat, center.lng], 15);
    }
    memberPickerMap.invalidateSize();
    setPendingMemberMarker(center.lat, center.lng);
}

function setPendingMemberMarker(lat, lng) {
    pendingMemberLocation = { lat, lng };
    if (!memberPickerMap || typeof L === 'undefined') return;
    if (!memberPickerMarker) {
        memberPickerMarker = L.marker([lat, lng], { draggable: true }).addTo(memberPickerMap);
        memberPickerMarker.on('dragend', () => {
            const pos = memberPickerMarker.getLatLng();
            pendingMemberLocation = { lat: pos.lat, lng: pos.lng };
            updateMapHelper(pos.lat, pos.lng);
        });
    } else {
        memberPickerMarker.setLatLng([lat, lng]);
    }
    updateMapHelper(lat, lng);
}

function setMemberLocation(lat, lng) {
    const latInput = document.getElementById('inp-latitude');
    const lngInput = document.getElementById('inp-longitude');
    if (latInput) latInput.value = Number(lat).toFixed(7);
    if (lngInput) lngInput.value = Number(lng).toFixed(7);
    updateLocationStatus();
}

function updateMapHelper(lat, lng) {
    if (memberMapHelper) memberMapHelper.textContent = `${Number(lat).toFixed(6)}, ${Number(lng).toFixed(6)}`;
}

function updateLocationStatus() {
    const status = document.getElementById('member-location-status');
    const lat = document.getElementById('inp-latitude')?.value;
    const lng = document.getElementById('inp-longitude')?.value;
    if (!status) return;
    if (lat && lng) {
        status.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Localizacao definida no mapa';
        status.classList.add('is-defined');
    } else {
        status.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg> Localizacao nao definida (automatica pelo endereco)';
        status.classList.remove('is-defined');
    }
}

async function geocodeMemberAddress() {
    const parts = ['inp-address', 'inp-city', 'inp-state'].map((id) => document.getElementById(id)?.value || '').filter(Boolean);
    if (parts.length === 0) {
        if (memberMapHelper) memberMapHelper.textContent = 'Preencha o endereco antes de buscar.';
        return;
    }
    if (memberMapHelper) memberMapHelper.textContent = 'Buscando no OpenStreetMap...';
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=br&q=${encodeURIComponent(parts.join(', '))}`);
        const data = await response.json();
        if (!Array.isArray(data) || !data[0]) {
            if (memberMapHelper) memberMapHelper.textContent = 'Endereco nao encontrado. Clique no mapa para definir.';
            return;
        }
        const lat = parseFloat(data[0].lat);
        const lng = parseFloat(data[0].lon);
        if (Number.isFinite(lat) && Number.isFinite(lng)) {
            memberPickerMap?.setView([lat, lng], 16);
            setPendingMemberMarker(lat, lng);
        }
    } catch (error) {
        if (memberMapHelper) memberMapHelper.textContent = 'Nao foi possivel consultar o OpenStreetMap agora.';
    }
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    }[char]));
}

async function openMemberDetails(id) {
    try {
        const response = await fetch(`<?= url('/gestao/membros') ?>/${id}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        
        if (data.success && data.member) {
            const m = data.member;
            document.getElementById('det-name').textContent = m.name;
            document.getElementById('det-age').textContent = m.birth_date ? calculateAge(m.birth_date) : '-- anos';
            
            let statusText = m.status === 'visitor' ? 'Visitante' : (m.status === 'active' ? 'Ativo' : (m.status === 'inactive' ? 'Inativo' : 'Transferido'));
            document.getElementById('det-status-badge').textContent = statusText;
            document.getElementById('det-category-badge').textContent = m.status === 'visitor' ? 'Visitante' : 'Membro';
            
            document.getElementById('det-gender').textContent = m.gender === 'M' ? 'Masculino' : (m.gender === 'F' ? 'Feminino' : (m.gender === 'other' ? 'Outro' : '--'));
            
            let maritalText = '--';
            if(m.marital_status === 'single') maritalText = 'Solteiro(a)';
            if(m.marital_status === 'married') maritalText = 'Casado(a)';
            if(m.marital_status === 'divorced') maritalText = 'Divorciado(a)';
            if(m.marital_status === 'widowed') maritalText = 'Viúvo(a)';
            document.getElementById('det-marital').textContent = maritalText;
            
            let addr = [];
            if(m.address) addr.push(m.address);
            if(m.city) addr.push(m.city);
            if(m.state) addr.push(m.state);
            document.getElementById('det-address').textContent = addr.length > 0 ? addr.join(', ') : '--';
            
            document.getElementById('det-email').querySelector('span').textContent = m.email || '--';
            document.getElementById('det-phone').querySelector('span').textContent = m.phone || '--';
            
            document.getElementById('det-membership').textContent = formatDate(m.membership_date);
            document.getElementById('det-baptism').textContent = formatDate(m.baptism_date);
            document.getElementById('det-unit').textContent = m.unit_name || 'Sede / Não definida';
            
            if (m.notes) {
                document.getElementById('det-notes-container').style.display = 'block';
                document.getElementById('det-notes').textContent = m.notes;
            } else {
                document.getElementById('det-notes-container').style.display = 'none';
            }

            document.getElementById('modal-member-details').style.display = 'flex';
        } else {
            alert('Não foi possível carregar os detalhes do membro.');
        }
    } catch (e) {
        console.error(e);
        alert('Erro de comunicação com o servidor.');
    }
}

async function openMemberEdit(id) {
    if (id) {
        document.getElementById('modal-new-member-title').textContent = 'Editar Membro';
        document.getElementById('form-member').action = `<?= url('/gestao/membros') ?>/${id}/editar`;
        document.getElementById('btn-submit-member').textContent = 'Atualizar';
        
        try {
            const response = await fetch(`<?= url('/gestao/membros') ?>/${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            
            if (data.success && data.member) {
                const m = data.member;
                document.getElementById('inp-name').value = m.name || '';
                document.getElementById('inp-email').value = m.email || '';
                document.getElementById('inp-phone').value = m.phone || '';
                document.getElementById('inp-birth').value = m.birth_date || '';
                document.getElementById('inp-gender').value = m.gender || '';
                document.getElementById('inp-address').value = m.address || '';
                document.getElementById('inp-zip').value = m.zip_code || '';
                document.getElementById('inp-city').value = m.city || '';
                document.getElementById('inp-state').value = m.state || '';
                document.getElementById('inp-baptism').value = m.baptism_date || '';
                document.getElementById('inp-membership').value = m.membership_date || '';
                document.getElementById('inp-unit').value = m.church_unit_id || '';
                document.getElementById('inp-status').value = m.status || 'active';
                document.getElementById('inp-marital').value = m.marital_status || '';
                document.getElementById('inp-notes').value = m.notes || '';
                document.getElementById('inp-latitude').value = m.latitude || '';
                document.getElementById('inp-longitude').value = m.longitude || '';
                if (photoCropped) photoCropped.value = '';
                setMemberPhotoPreview(m.photo || '');
                updateLocationStatus();
                
                document.getElementById('modal-new-member').style.display = 'flex';
            }
        } catch (e) {
            console.error(e);
            alert('Erro de comunicação com o servidor.');
        }
    } else {
        // Novo Membro
        document.getElementById('modal-new-member-title').textContent = 'Cadastrar Membro';
        document.getElementById('form-member').action = `<?= url('/gestao/membros') ?>`;
        document.getElementById('form-member').reset();
        document.getElementById('inp-membership').value = '<?= date('Y-m-d') ?>';
        document.getElementById('inp-latitude').value = '';
        document.getElementById('inp-longitude').value = '';
        if (photoCropped) photoCropped.value = '';
        if (photoInput) photoInput.value = '';
        setMemberPhotoPreview('');
        updateLocationStatus();
        document.getElementById('btn-submit-member').textContent = 'Cadastrar';
        document.getElementById('modal-new-member').style.display = 'flex';
    }
}
</script>

<?php endif; // fechar bloco isTopDonors ?>

<?php $__view->endSection(); ?>
