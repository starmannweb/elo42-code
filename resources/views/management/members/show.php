<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title"><?= e($member['name']) ?></h1>
        <p class="mgmt-header__subtitle">Detalhes do membro</p>
    </div>
    <div class="mgmt-header__actions">
        <a href="<?= url('/gestao/membros/' . $member['id'] . '/editar') ?>" class="btn btn--ghost">Editar</a>
        <form method="POST" action="<?= url('/gestao/membros/' . $member['id'] . '/excluir') ?>" data-confirm="Tem certeza que deseja remover este membro? Esta ação não poderá ser desfeita." data-loading>
            <?= csrf_field() ?>
            <button type="submit" class="btn btn--danger-outline">Remover</button>
        </form>
    </div>
</div>

<div class="mgmt-detail">
    <div class="mgmt-detail__main">
        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Informações pessoais</h3>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Nome</span><span class="mgmt-info-row__value"><?= e($member['name']) ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">E-mail</span><span class="mgmt-info-row__value"><?= e($member['email'] ?? '—') ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Telefone</span><span class="mgmt-info-row__value"><?= e($member['phone'] ?? '—') ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Nascimento</span><span class="mgmt-info-row__value"><?= $member['birth_date'] ? date('d/m/Y', strtotime($member['birth_date'])) : '—' ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Gênero</span><span class="mgmt-info-row__value"><?= e(match($member['gender'] ?? '') { 'M' => 'Masculino', 'F' => 'Feminino', 'other' => 'Outro', default => '—' }) ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Estado civil</span><span class="mgmt-info-row__value"><?= e(match($member['marital_status'] ?? '') { 'single' => 'Solteiro(a)', 'married' => 'Casado(a)', 'divorced' => 'Divorciado(a)', 'widowed' => 'Viúvo(a)', default => '—' }) ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Unidade</span><span class="mgmt-info-row__value"><?= e((string) ($member['unit_name'] ?? 'Sede / não definida')) ?></span></div>
        </div>

        <?php if ($member['address'] || $member['city']): ?>
        <div class="mgmt-info-card" style="margin-top: var(--space-5)">
            <h3 class="mgmt-info-card__title">Endereço</h3>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Endereço</span><span class="mgmt-info-row__value"><?= e($member['address'] ?? '—') ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Cidade</span><span class="mgmt-info-row__value"><?= e(($member['city'] ?? '') . ($member['state'] ? '/' . $member['state'] : '')) ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">CEP</span><span class="mgmt-info-row__value"><?= e($member['zip_code'] ?? '—') ?></span></div>
        </div>
        <?php endif; ?>
    </div>

    <div class="mgmt-detail__sidebar">
        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Status</h3>
            <div class="text-center" style="padding: var(--space-3) 0">
                <span class="badge badge--<?= $member['status'] ?>"><?= ucfirst(e($member['status'])) ?></span>
            </div>
        </div>

        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Igreja</h3>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Membro desde</span><span class="mgmt-info-row__value"><?= $member['membership_date'] ? date('d/m/Y', strtotime($member['membership_date'])) : '—' ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Batismo</span><span class="mgmt-info-row__value"><?= $member['baptism_date'] ? date('d/m/Y', strtotime($member['baptism_date'])) : '—' ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Cadastrado em</span><span class="mgmt-info-row__value"><?= date('d/m/Y', strtotime($member['created_at'])) ?></span></div>
        </div>

        <?php if ($member['notes']): ?>
        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Observações</h3>
            <p class="text-muted" style="font-size: var(--text-sm); line-height: 1.7"><?= nl2br(e($member['notes'])) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__view->endSection(); ?>
