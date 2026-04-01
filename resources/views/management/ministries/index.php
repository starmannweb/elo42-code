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
    <div class="mgmt-empty__icon"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
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
    $svgIcons = [
        '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>',
        '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>',
        '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>',
        '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>',
        '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>',
        '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>',
    ];
    $i = 0;
    foreach ($ministries as $m): 
        $gradient = $gradients[$i % count($gradients)];
        $icon = $svgIcons[$i % count($svgIcons)];
        $i++;
    ?>
    <div class="mgmt-dashboard-card" style="padding: 0; overflow: hidden;">
        <div style="height: 100px; background: <?= $gradient ?>; display: flex; align-items: center; justify-content: center; position: relative;">
            <span style="opacity: 0.85;"><?= $icon ?></span>
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
