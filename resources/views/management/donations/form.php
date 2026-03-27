<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Nova doação</h1></div></div>
<div class="mgmt-form-card">
    <form method="POST" action="<?= url('/gestao/doacoes') ?>" data-loading><?= csrf_field() ?>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Tipo</label><select name="type" class="form-select"><option value="tithe">Dízimo</option><option value="offering">Oferta</option><option value="special">Especial</option><option value="campaign">Campanha</option><option value="other">Outro</option></select></div>
            <div class="form-group"><label class="form-label">Data *</label><input type="date" name="donation_date" class="form-input" value="<?= date('Y-m-d') ?>" required></div>
        </div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Valor (R$) *</label><input type="number" name="amount" class="form-input" step="0.01" min="0.01" required></div>
            <div class="form-group"><label class="form-label">Forma de pagamento</label><select name="payment_method" class="form-select"><option value="cash">Dinheiro</option><option value="pix">Pix</option><option value="card">Cartão</option><option value="transfer">Transferência</option><option value="other">Outro</option></select></div>
        </div>
        <div class="mgmt-form-row">
            <div class="form-group"><label class="form-label">Membro</label><select name="member_id" class="form-select"><option value="">Anônimo</option><?php foreach ($members as $m): ?><option value="<?= $m['id'] ?>"><?= e($m['name']) ?></option><?php endforeach; ?></select></div>
            <div class="form-group"><label class="form-label">Nome do doador</label><input type="text" name="donor_name" class="form-input" placeholder="Se não for membro"></div>
        </div>
        <div class="form-group"><label class="form-label">Referência</label><input type="text" name="reference" class="form-input" placeholder="Comprovante, etc."></div>
        <div class="form-group"><label class="form-label">Notas</label><textarea name="notes" class="form-input" rows="2"></textarea></div>
        <div class="mgmt-form-actions"><button type="submit" class="btn btn--primary">Registrar</button><a href="<?= url('/gestao/doacoes') ?>" class="btn btn--ghost">Cancelar</a></div>
    </form>
</div>
<?php $__view->endSection(); ?>
