<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php $units = is_array($units ?? null) ? $units : []; ?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Nova doação</h1>
        <p class="mgmt-header__subtitle">Registre dízimos, ofertas, campanhas e contribuições especiais.</p>
    </div>
</div>

<div class="mgmt-form-card">
    <form method="POST" action="<?= url('/gestao/doacoes') ?>" data-loading>
        <?= csrf_field() ?>
        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">Tipo</label>
                <select name="type" class="form-select">
                    <option value="tithe">Dízimo</option>
                    <option value="offering">Oferta</option>
                    <option value="special">Especial</option>
                    <option value="campaign">Campanha</option>
                    <option value="other">Outro</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Data *</label>
                <input type="date" name="donation_date" class="form-input" value="<?= date('Y-m-d') ?>" required>
            </div>
        </div>

        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">Unidade</label>
                <select name="church_unit_id" class="form-select">
                    <option value="">Sede / todas as unidades</option>
                    <?php foreach ($units as $unit): ?>
                        <option value="<?= (int) $unit['id'] ?>"><?= e((string) $unit['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Forma de pagamento</label>
                <select name="payment_method" class="form-select">
                    <option value="cash">Dinheiro</option>
                    <option value="pix">PIX</option>
                    <option value="card">Cartão</option>
                    <option value="transfer">Transferência</option>
                    <option value="other">Outro</option>
                </select>
            </div>
        </div>

        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">Valor (R$) *</label>
                <input type="number" name="amount" class="form-input" step="0.01" min="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Membro</label>
                <select name="member_id" class="form-select">
                    <option value="">Anônimo</option>
                    <?php foreach (($members ?? []) as $member): ?>
                        <option value="<?= (int) $member['id'] ?>"><?= e((string) $member['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Nome do doador</label>
            <input type="text" name="donor_name" class="form-input" placeholder="Se não for membro cadastrado">
        </div>

        <div class="form-group">
            <label class="form-label">Referência</label>
            <input type="text" name="reference" class="form-input" placeholder="Comprovante, campanha ou observação curta">
        </div>

        <div class="form-group">
            <label class="form-label">Notas</label>
            <textarea name="notes" class="form-input" rows="2"></textarea>
        </div>

        <div class="mgmt-form-actions">
            <button type="submit" class="btn btn--primary">Registrar doação</button>
            <a href="<?= url('/gestao/doacoes') ?>" class="btn btn--ghost">Cancelar</a>
        </div>
    </form>
</div>
<?php $__view->endSection(); ?>
