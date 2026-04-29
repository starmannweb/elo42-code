<?php $__view->extends('management', ['pageTitle' => $pageTitle ?? 'Dízimos & Ofertas', 'breadcrumb' => $breadcrumb ?? 'Dízimos & Ofertas', 'activeMenu' => 'dizimos-ofertas']); ?>

<?php $__view->section('content'); ?>
<?php
    $members = is_array($members ?? null) ? $members : [];
    $units = is_array($units ?? null) ? $units : [];
    $pixKey = $pixKey ?? '';
    $orgName = $orgName ?? 'Igreja';
?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Dízimos & Ofertas</h1>
        <p class="mgmt-header__subtitle">Gerencie contribuições via PIX, dinheiro, cartão e transferência.</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-donation').style.display='flex'">Registrar contribuição</button>
    </div>
</div>

<?php if (!empty($pixWarning)): ?>
    <div class="mgmt-info-card" style="border-color:rgba(245,158,11,.25);background:rgba(245,158,11,.08);margin-bottom:var(--space-5);">
        <strong>Configure sua chave PIX</strong>
        <span class="premium-feature-icon" title="Recurso Premium" aria-label="Recurso Premium" style="color:#d97706;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 5 5 5 5-8 5 8 5-5-3 14H5L2 5z"></path><path d="M5 19h14"></path></svg>
        </span>
        - acesse <a href="<?= url('/gestao/configuracoes/pix') ?>" style="color:#d97706;font-weight:800;">PIX / Ofertas</a> para cadastrar a chave PIX da igreja e gerar o QR Code automaticamente.
    </div>
<?php endif; ?>

<div class="mgmt-kpi-grid" style="grid-template-columns:repeat(4,minmax(0,1fr));">
    <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Total do mês</div><div class="mgmt-kpi-card__value">R$ <?= number_format(($summary['total'] ?? 0), 2, ',', '.') ?></div></div></div>
    <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Dízimos</div><div class="mgmt-kpi-card__value" style="color:#059669;">R$ <?= number_format(($summary['tithe'] ?? 0), 2, ',', '.') ?></div></div></div>
    <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Ofertas</div><div class="mgmt-kpi-card__value" style="color:#d97706;">R$ <?= number_format(($summary['offering'] ?? 0), 2, ',', '.') ?></div></div></div>
    <div class="mgmt-kpi-card"><div><div class="mgmt-kpi-card__label">Contribuintes</div><div class="mgmt-kpi-card__value"><?= (int) ($summary['donors'] ?? 0) ?></div></div></div>
</div>

<div class="mgmt-grid" style="grid-template-columns:minmax(0,1.1fr) minmax(320px,.9fr);gap:var(--space-5);align-items:start;">
    <div class="mgmt-dashboard-card" style="padding:0;overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--color-border-light);">
            <h2 class="mgmt-info-card__title" style="margin:0;">Últimas contribuições</h2>
        </div>
        <?php if (empty($donations)): ?>
            <div class="mgmt-empty"><h3 class="mgmt-empty__title">Nenhuma contribuição registrada ainda.</h3></div>
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

    <div class="mgmt-info-card">
        <h2 class="mgmt-info-card__title">PIX da igreja</h2>
        <?php if (!empty($pixKey)): ?>
            <p class="mgmt-header__subtitle">Compartilhe a chave abaixo para receber dízimos e ofertas de <?= e((string) $orgName) ?>.</p>
            <code style="display:block;background:#f8fafc;border:1px solid #dfe7f4;border-radius:8px;padding:.75rem;margin-top:.75rem;word-break:break-all;"><?= e((string) $pixKey) ?></code>
            <button type="button" class="btn btn--outline" style="margin-top:1rem;" onclick="navigator.clipboard.writeText('<?= e((string) $pixKey) ?>');this.textContent='Copiado';">Copiar chave</button>
        <?php else: ?>
            <p class="mgmt-header__subtitle">Cadastre sua chave PIX nas configurações para centralizar as contribuições.</p>
            <a class="btn btn--outline" href="<?= url('/gestao/configuracoes/pix') ?>" style="display:inline-flex;align-items:center;gap:.4rem;">
                Configurar PIX
                <span class="premium-feature-icon" title="Recurso Premium" aria-label="Recurso Premium" style="color:#d97706;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 5 5 5 5-8 5 8 5-5-3 14H5L2 5z"></path><path d="M5 19h14"></path></svg>
                </span>
            </a>
        <?php endif; ?>
    </div>
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
