<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php
$typeLabels = ['church' => 'Igreja', 'association' => 'Associação', 'ministry' => 'Ministério', 'ong' => 'ONG', 'other' => 'Outro'];
$statusLabels = ['active' => 'Ativa', 'trial' => 'Teste', 'inactive' => 'Inativa', 'suspended' => 'Suspensa'];
$membershipLabels = ['active' => 'Ativo', 'inactive' => 'Inativo', 'pending' => 'Pendente', 'invited' => 'Convidado', 'suspended' => 'Suspenso'];
$subscriptionLabels = ['trial' => 'Teste', 'active' => 'Ativa', 'past_due' => 'Pendente', 'cancelled' => 'Cancelada', 'expired' => 'Expirada'];
$formatDateTime = static function (mixed $value): string {
    $value = trim((string) ($value ?? ''));
    if ($value === '') {
        return '&mdash;';
    }

    $timestamp = strtotime($value);
    return $timestamp ? date('d/m/Y H:i', $timestamp) : '&mdash;';
};
$formatDate = static function (mixed $value): string {
    $value = trim((string) ($value ?? ''));
    if ($value === '') {
        return '&mdash;';
    }

    $timestamp = strtotime($value);
    return $timestamp ? date('d/m/Y', $timestamp) : '&mdash;';
};
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title"><?= e($org['name'] ?? 'Instituição') ?></h1>
        <p class="mgmt-header__subtitle">
            <span class="badge badge--<?= e($org['status'] ?? 'inactive') ?>"><?= e($statusLabels[$org['status'] ?? ''] ?? ($org['status'] ?? '-')) ?></span>
        </p>
    </div>
    <div class="mgmt-header__actions">
        <a href="<?= url('/admin/organizacoes/' . ($org['id'] ?? 0) . '/editar') ?>" class="btn btn--secondary">Editar</a>
    </div>
</div>

<?php if (!empty($degraded)): ?>
    <div class="alert alert--warning" role="alert" style="margin-bottom:1rem;">
        Não foi possível carregar todos os vínculos desta instituição agora. Os dados principais continuam disponíveis.
    </div>
<?php endif; ?>

<div class="mgmt-detail">
    <div class="mgmt-detail__main">
        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Dados da instituição</h3>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Nome</span><span class="mgmt-info-row__value"><?= e($org['name'] ?? '-') ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Razão social</span><span class="mgmt-info-row__value"><?= e($org['legal_name'] ?? '-') ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">CNPJ</span><span class="mgmt-info-row__value"><?= e($org['cnpj'] ?? '-') ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Tipo</span><span class="mgmt-info-row__value"><?= $typeLabels[$org['type'] ?? ''] ?? e($org['type'] ?? '-') ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Cidade/UF</span><span class="mgmt-info-row__value"><?= e(trim((string) ($org['city'] ?? '') . (($org['state'] ?? '') ? '/' . $org['state'] : '')) ?: '-') ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Criada em</span><span class="mgmt-info-row__value"><?= $formatDateTime($org['created_at'] ?? null) ?></span></div>
        </div>

        <div class="mgmt-info-card" style="margin-top:var(--space-5);">
            <h3 class="mgmt-info-card__title">Usuários vinculados (<?= count($users) ?>)</h3>
            <table class="mgmt-table">
                <thead><tr><th>Nome</th><th>E-mail</th><th>Papel</th><th>Status</th></tr></thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:1rem;">Nenhum usuário vinculado.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><a href="<?= url('/admin/usuarios/' . $u['id']) ?>" class="mgmt-table__name" style="color:var(--color-primary);"><?= e($u['name']) ?></a></td>
                        <td class="mgmt-table__sub"><?= e($u['email']) ?></td>
                        <td><?= e($u['role_name'] ?? '-') ?></td>
                        <td><span class="badge badge--<?= e($u['membership_status'] ?? 'inactive') ?>"><?= e($membershipLabels[$u['membership_status'] ?? ''] ?? ($u['membership_status'] ?? '-')) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mgmt-detail__sidebar">
        <?php if ($subscription): ?>
        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Assinatura</h3>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Plano</span><span class="mgmt-info-row__value"><?= e($subscription['plan_name'] ?? '-') ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Status</span><span class="mgmt-info-row__value"><span class="badge badge--<?= e($subscription['status'] ?? 'inactive') ?>"><?= e($subscriptionLabels[$subscription['status'] ?? ''] ?? ($subscription['status'] ?? '-')) ?></span></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Valor</span><span class="mgmt-info-row__value">R$ <?= number_format((float) ($subscription['price'] ?? 0), 2, ',', '.') ?></span></div>
            <div class="mgmt-info-row"><span class="mgmt-info-row__label">Expira</span><span class="mgmt-info-row__value"><?= $formatDate($subscription['expires_at'] ?? null) ?></span></div>
            <div style="text-align:center;margin-top:var(--space-3);"><a href="<?= url('/admin/assinaturas/' . ($subscription['id'] ?? 0)) ?>" class="btn btn--secondary" style="font-size:var(--text-xs);">Ver assinatura</a></div>
        </div>
        <?php else: ?>
        <div class="mgmt-info-card">
            <h3 class="mgmt-info-card__title">Assinatura</h3>
            <p style="font-size:var(--text-sm);color:var(--color-text-muted);text-align:center;padding:var(--space-4);">Nenhuma assinatura ativa.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__view->endSection(); ?>
