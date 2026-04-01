<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Ministérios</h1>
        <p class="mgmt-header__subtitle">Gerencie os ministérios e equipes da igreja</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="window.location.href='<?= url('/gestao/ministerios/novo') ?>'">+ Novo Ministério</button>
    </div>
</div>

<?php if (empty($ministries)): ?>
<div class="mgmt-empty">
    <div class="mgmt-empty__icon">⛪</div>
    <h3 class="mgmt-empty__title">Nenhum ministério cadastrado</h3>
    <p class="mgmt-empty__text">Crie o primeiro ministério da sua organização.</p>
    <a href="<?= url('/gestao/ministerios/novo') ?>" class="btn btn--primary">Criar ministério</a>
</div>
<?php else: ?>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: var(--space-6);">
    <?php 
    $gradients = [
        'linear-gradient(135deg, #1e3a5f 0%, #0d1b2a 100%)',
        'linear-gradient(135deg, #10b981 0%, #059669 100%)',
        'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)',
        'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
        'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
        'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
    ];
    $icons = ['🎵', '👶', '📹', '📖', '🙏', '🎤'];
    $i = 0;
    foreach ($ministries as $m): 
        $gradient = $gradients[$i % count($gradients)];
        $icon = $icons[$i % count($icons)];
        $i++;
    ?>
    <div class="mgmt-dashboard-card" style="padding: 0; overflow: hidden;">
        <div style="height: 100px; background: <?= $gradient ?>; display: flex; align-items: center; justify-content: center; position: relative;">
            <span style="font-size: 32px; opacity: 0.8;"><?= $icon ?></span>
        </div>
        <div style="padding: var(--space-5);">
            <h3 style="font-size: var(--text-lg); font-weight: 700; margin-bottom: var(--space-2);"><?= e($m['name']) ?></h3>
            <p style="font-size: var(--text-sm); color: var(--text-muted); margin-bottom: var(--space-4); min-height: 40px;"><?= e($m['description'] ?? 'Sem descrição') ?></p>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: var(--space-2);">
                    <div style="width: 28px; height: 28px; border-radius: 50%; background: <?= e($m['color'] ?? '#0A4DFF') ?>; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: 700;"><?= strtoupper(substr($m['leader_name'] ?? 'L', 0, 2)) ?></div>
                    <div>
                        <div style="font-size: 11px; color: var(--text-muted);">Líder</div>
                        <div style="font-size: var(--text-sm); font-weight: 600;"><?= e($m['leader_name'] ?? '—') ?></div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 4px;">
                    <?php for ($j = 0; $j < min(3, $m['member_count'] ?? 0); $j++): ?>
                    <div style="width: 24px; height: 24px; border-radius: 50%; background: <?= ['#10b981', '#3b82f6', '#f59e0b'][$j] ?>; border: 2px solid var(--color-bg); margin-left: <?= $j > 0 ? '-8px' : '0' ?>;"></div>
                    <?php endfor; ?>
                    <span style="font-size: 11px; color: var(--text-muted); margin-left: 4px;"><?= $m['member_count'] ?? 0 ?> membros</span>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php $__view->endSection(); ?>
