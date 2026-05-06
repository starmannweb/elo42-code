<?php $__view->extends('management', ['pageTitle' => $pageTitle ?? 'Dízimos & Ofertas', 'breadcrumb' => $breadcrumb ?? 'Dízimos & Ofertas', 'activeMenu' => 'dizimos-ofertas']); ?>

<?php $__view->section('content'); ?>
<?php
    $members = is_array($members ?? null) ? $members : [];
    $units = is_array($units ?? null) ? $units : [];
    $orgName = $orgName ?? 'Igreja';
    $filters = is_array($filters ?? null) ? $filters : ['search' => '', 'type' => '', 'month' => date('Y-m')];
?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Receitas</h1>
        <p class="mgmt-header__subtitle">Registre dízimos, ofertas e demais entradas. Configurações de PIX ficam em Configurações &rsaquo; PIX / Ofertas.</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-donation').style.display='flex'">Registrar contribuição</button>
    </div>
</div>

<div class="mgmt-card mgmt-filter-card">
    <div class="mgmt-card__body">
        <form method="GET" action="<?= url('/gestao/receitas') ?>" class="mgmt-filter-grid">
            <div class="mgmt-filter-field">
                <label for="revenue_search" class="form-label">Buscar</label>
                <input type="text" id="revenue_search" name="search" class="form-control" placeholder="Doador ou referência" value="<?= e((string) ($filters['search'] ?? '')) ?>">
            </div>
            <div class="mgmt-filter-field">
                <label for="revenue_type" class="form-label">Tipo</label>
                <select id="revenue_type" name="type" class="form-control">
                    <option value="">Todos</option>
                    <option value="tithe" <?= ($filters['type'] ?? '') === 'tithe' ? 'selected' : '' ?>>Dízimo</option>
                    <option value="offering" <?= ($filters['type'] ?? '') === 'offering' ? 'selected' : '' ?>>Oferta</option>
                    <option value="special" <?= ($filters['type'] ?? '') === 'special' ? 'selected' : '' ?>>Especial</option>
                    <option value="campaign" <?= ($filters['type'] ?? '') === 'campaign' ? 'selected' : '' ?>>Campanha</option>
                    <option value="other" <?= ($filters['type'] ?? '') === 'other' ? 'selected' : '' ?>>Outro</option>
                </select>
            </div>
            <div class="mgmt-filter-field">
                <label for="revenue_month" class="form-label">Período</label>
                <input type="month" id="revenue_month" name="month" class="form-control" value="<?= e((string) ($filters['month'] ?? date('Y-m'))) ?>">
            </div>
            <div class="mgmt-filter-actions">
                <button type="submit" class="btn btn--outline">Filtrar</button>
                <a href="<?= url('/gestao/receitas') ?>" class="btn btn--outline">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="mgmt-kpi-grid" style="grid-template-columns:repeat(4,minmax(0,1fr));">
    <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Total do mês</div><div class="mgmt-kpi-card__value">R$ <?= number_format(($summary['total'] ?? 0), 2, ',', '.') ?></div></div></div>
    <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Dízimos</div><div class="mgmt-kpi-card__value" style="color:#059669;">R$ <?= number_format(($summary['tithe'] ?? 0), 2, ',', '.') ?></div></div></div>
    <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Ofertas</div><div class="mgmt-kpi-card__value" style="color:#d97706;">R$ <?= number_format(($summary['offering'] ?? 0), 2, ',', '.') ?></div></div></div>
    <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Contribuintes</div><div class="mgmt-kpi-card__value"><?= (int) ($summary['donors'] ?? 0) ?></div></div></div>
</div>

<div class="mgmt-dashboard-card" style="padding:0;overflow:hidden;">
    <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--color-border-light);">
        <h2 class="mgmt-info-card__title" style="margin:0;">Últimas contribuições</h2>
    </div>
    <?php if (empty($donations)): ?>
        <div class="mgmt-empty" style="padding:2.5rem 1.5rem;text-align:center;">
            <div class="mgmt-empty__icon" style="width:56px;height:56px;border-radius:50%;background:rgba(10,77,255,0.08);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#0a4dff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <h3 class="mgmt-empty__title" style="margin:0 0 0.5rem;">Nenhuma contribuição registrada ainda</h3>
            <p class="mgmt-empty__text" style="margin:0 0 1.25rem;color:var(--color-text-muted);">Registre dízimos, ofertas ou contribuições especiais para acompanhar a saúde financeira da igreja.</p>
            <button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-donation').style.display='flex'">+ Registrar contribuição</button>
        </div>
    <?php else: ?>
        <table class="mgmt-table">
            <thead><tr><th>Doador</th><th>Tipo</th><th>Data</th><th style="text-align:right;">Valor</th></tr></thead>
            <tbody>
                <?php foreach ($donations as $d): ?>
                    <?php $typeLabel = match($d['type'] ?? '') { 'tithe' => 'Dízimo', 'offering' => 'Oferta', 'campaign' => 'Campanha', default => 'Contribuição' }; ?>
                    <tr>
                        <td><div class="mgmt-table__name"><?= e($d['member_name'] ?? $d['donor_name'] ?? 'Anônimo') ?></div></td>
                        <td><span class="badge badge--active"><?= e($typeLabel) ?></span></td>
                        <td><?= !empty($d['donation_date']) ? date('d/m/Y', strtotime($d['donation_date'])) : '-' ?></td>
                        <td style="text-align:right;font-weight:800;">R$ <?= number_format((float)($d['amount'] ?? 0), 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="modal" id="modal-new-donation" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-new-donation-title">
    <div class="modal__content">
        <div class="modal__header"><h2 class="modal__title" id="modal-new-donation-title">Registrar contribuição</h2><button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'">&times;</button></div>
        <form method="POST" action="<?= url('/gestao/doacoes') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="modal-grid">
                    <div class="form-group"><label class="form-label">Tipo</label><select name="type" class="form-select"><option value="tithe">Dízimo</option><option value="offering">Oferta</option><option value="special">Especial</option><option value="campaign">Campanha</option><option value="other">Outro</option></select></div>
                    <div class="form-group"><label class="form-label">Data *</label><input type="date" name="donation_date" class="form-input" value="<?= date('Y-m-d') ?>" required></div>
                    <div class="form-group"><label class="form-label">Valor (R$) *</label><input type="number" name="amount" class="form-input" step="0.01" min="0.01" required></div>
                    <div class="form-group"><label class="form-label">Forma de pagamento</label><select name="payment_method" class="form-select"><option value="pix">PIX</option><option value="cash">Dinheiro</option><option value="card">Cartão</option><option value="transfer">Transferência</option><option value="other">Outro</option></select></div>
                    <div class="form-group"><label class="form-label">Membro</label><select name="member_id" class="form-select"><option value="">Anônimo</option><?php foreach ($members as $member): ?><option value="<?= (int) $member['id'] ?>"><?= e((string) $member['name']) ?></option><?php endforeach; ?></select></div>
                    <div class="form-group"><label class="form-label">Unidade</label><select name="church_unit_id" class="form-select"><option value="">Sede / todas as unidades</option><?php foreach ($units as $unit): ?><option value="<?= (int) $unit['id'] ?>"><?= e((string) $unit['name']) ?></option><?php endforeach; ?></select></div>
                </div>
                <div class="form-group"><label class="form-label">Nome do doador</label><input type="text" name="donor_name" class="form-input" placeholder="Se não for membro cadastrado"></div>
                <div class="form-group"><label class="form-label">Referência</label><input type="text" name="reference" class="form-input" placeholder="Comprovante, campanha ou observação curta"></div>
                <div class="form-group"><label class="form-label">Notas</label><textarea name="notes" class="form-input" rows="2"></textarea></div>
            </div>
            <div class="modal__footer"><button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button><button type="submit" class="btn btn--primary">Registrar</button></div>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
