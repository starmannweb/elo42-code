<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Cuidado Pastoral</h1>
        <p class="mgmt-header__subtitle">Gerencie pedidos de oração, batismo, visitas e aconselhamento</p>
    </div>
</div>

<?php
$pendentes = 0;
$emAndamento = 0;
$concluidos = 0;
foreach ($sessions ?? [] as $s) {
    if (($s['status'] ?? '') === 'scheduled' || ($s['status'] ?? '') === 'pending') $pendentes++;
    elseif (($s['status'] ?? '') === 'in_progress') $emAndamento++;
    elseif (($s['status'] ?? '') === 'completed') $concluidos++;
}
?>

<div class="mgmt-kpi-grid" style="grid-template-columns: repeat(3, 1fr);">
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Pendentes</div>
            <div class="mgmt-kpi-card__value"><?= $pendentes ?></div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"></path></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Em Andamento</div>
            <div class="mgmt-kpi-card__value"><?= $emAndamento ?></div>
        </div>
    </div>
    <div class="mgmt-kpi-card">
        <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div>
            <div class="mgmt-kpi-card__label">Concluídos</div>
            <div class="mgmt-kpi-card__value"><?= $concluidos ?></div>
        </div>
    </div>
</div>

<div style="display: flex; gap: var(--space-4); margin-bottom: var(--space-6); border-bottom: 1px solid var(--color-border);">
    <button class="btn btn--ghost" style="border-bottom: 2px solid var(--color-primary); border-radius: 0; padding-bottom: var(--space-3);">Pendentes (<?= $pendentes ?>)</button>
    <button class="btn btn--ghost" style="border-radius: 0; padding-bottom: var(--space-3); color: var(--text-muted);">Em Andamento (<?= $emAndamento ?>)</button>
    <button class="btn btn--ghost" style="border-radius: 0; padding-bottom: var(--space-3); color: var(--text-muted);">Concluídos (<?= $concluidos ?>)</button>
</div>

<?php if (empty($sessions)): ?>
<div class="mgmt-empty"><div class="mgmt-empty__icon">🙏</div><h3 class="mgmt-empty__title">Nenhum pedido</h3><p class="mgmt-empty__text">Não há pedidos de cuidado pastoral no momento.</p></div>
<?php else: ?>
<div style="display: flex; flex-direction: column; gap: var(--space-4);">
    <?php foreach ($sessions as $s): ?>
    <div class="mgmt-dashboard-card" style="padding: var(--space-4); display: flex; align-items: center; gap: var(--space-4);">
        <div style="width: 40px; height: 40px; border-radius: 50%; background: <?= match($s['type'] ?? 'prayer') { 'prayer' => 'rgba(239, 68, 68, 0.1)', 'baptism' => 'rgba(59, 130, 246, 0.1)', 'counseling' => 'rgba(124, 58, 237, 0.1)', default => 'rgba(214, 166, 70, 0.1)' } ?>; display: flex; align-items: center; justify-content: center; color: <?= match($s['type'] ?? 'prayer') { 'prayer' => '#ef4444', 'baptism' => '#3b82f6', 'counseling' => '#7c3aed', default => '#d6a646' } ?>;">
            <?= match($s['type'] ?? 'prayer') { 'prayer' => '❤️', 'baptism' => '💧', 'counseling' => '💬', default => '🙏' } ?>
        </div>
        <div style="flex: 1;">
            <div style="display: flex; align-items: center; gap: var(--space-2); margin-bottom: 4px;">
                <strong style="font-size: var(--text-base);"><?= e($s['member_name'] ?? 'Anônimo') ?></strong>
                <span class="badge badge--<?= match($s['type'] ?? 'prayer') { 'prayer' => 'pending', 'baptism' => 'visitor', 'counseling' => 'transferred', default => 'pending' } ?>" style="font-size: 10px;"><?= strtoupper(match($s['type'] ?? 'prayer') { 'prayer' => 'ORAÇÃO', 'baptism' => 'BATISMO', 'counseling' => 'ACONSELHAMENTO', default => e($s['type'] ?? 'OUTRO') }) ?></span>
                <span class="badge badge--<?= ($s['status'] ?? 'pending') === 'completed' ? 'active' : 'pending' ?>" style="font-size: 10px;"><?= strtoupper(match($s['status'] ?? 'pending') { 'scheduled' => 'PENDENTE', 'pending' => 'PENDENTE', 'in_progress' => 'EM ANDAMENTO', 'completed' => 'CONCLUÍDO', default => e($s['status']) }) ?></span>
            </div>
            <p style="color: var(--text-muted); font-size: var(--text-sm); margin-bottom: 4px;"><?= e($s['subject'] ?? 'Sem descrição') ?></p>
            <span style="font-size: 11px; color: var(--text-muted);">Enviado em <?= date('d', strtotime($s['session_date'] ?? 'now')) ?> de <?= date('M', strtotime($s['session_date'] ?? 'now')) ?>.</span>
        </div>
        <a href="<?= url('/gestao/aconselhamento/' . ($s['id'] ?? '')) ?>" style="color: var(--text-muted); font-size: 20px;">›</a>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php $__view->endSection(); ?>
