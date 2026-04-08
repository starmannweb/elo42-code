<?php $__view->extends('management', ['pageTitle' => $pageTitle ?? 'Aniversarios', 'breadcrumb' => $breadcrumb ?? 'Aniversarios', 'activeMenu' => $activeMenu ?? 'aniversarios']); ?>

<?php $__view->section('content'); ?>
<div style="max-width: 960px; margin: 0 auto;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; margin: 0; color: var(--text-primary);">Aniversariantes do Mes</h1>
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0.25rem 0 0;"><?= date('F Y') ?></p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <span style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.375rem 0.75rem; background: var(--color-primary-soft, rgba(30,58,138,0.08)); color: var(--color-primary, #1e3a8a); border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-3-3.87"></path><path d="M4 21v-2a4 4 0 0 1 3-3.87"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <?= count($members ?? []) ?> aniversariante<?= count($members ?? []) !== 1 ? 's' : '' ?>
            </span>
        </div>
    </div>

    <?php if (empty($members)): ?>
        <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color, #e5e7eb); border-radius: 12px; padding: 3rem 2rem; text-align: center;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: 50%; background: var(--color-primary-soft, rgba(30,58,138,0.08)); color: var(--color-primary, #1e3a8a); margin-bottom: 1rem;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-3-3.87"></path><path d="M4 21v-2a4 4 0 0 1 3-3.87"></path><circle cx="12" cy="7" r="4"></circle><path d="M12 3v1"></path></svg>
            </div>
            <h2 style="font-size: 1.1rem; font-weight: 600; margin: 0 0 0.5rem;">Nenhum aniversariante este mes</h2>
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0;">Cadastre membros com data de nascimento para visualizar os aniversariantes.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 0.75rem;">
            <?php foreach ($members as $m): ?>
                <?php
                    $day = date('d', strtotime($m['birth_date']));
                    $monthName = date('M', strtotime($m['birth_date']));
                    $isToday = date('m-d') === date('m-d', strtotime($m['birth_date']));
                    $parts = explode(' ', trim($m['name'] ?? 'Membro'));
                    $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . substr(end($parts) ?: '', 0, 1));
                ?>
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: var(--card-bg, #fff); border: 1px solid <?= $isToday ? '#f59e0b' : 'var(--border-color, #e5e7eb)' ?>; border-radius: 10px; <?= $isToday ? 'box-shadow: 0 0 0 2px rgba(245,158,11,0.15);' : '' ?>">
                    <div style="flex-shrink: 0; width: 44px; height: 44px; border-radius: 50%; background: <?= $isToday ? 'linear-gradient(135deg, #f59e0b, #d97706)' : 'var(--color-primary-soft, rgba(30,58,138,0.08))' ?>; color: <?= $isToday ? '#fff' : 'var(--color-primary, #1e3a8a)' ?>; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem;">
                        <?= htmlspecialchars($initials) ?>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 600; font-size: 0.95rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= htmlspecialchars($m['name'] ?? 'Membro') ?>
                            <?php if ($isToday): ?>
                                <span style="display: inline-flex; align-items: center; gap: 0.25rem; margin-left: 0.5rem; padding: 0.125rem 0.5rem; background: #fef3c7; color: #92400e; border-radius: 4px; font-size: 0.7rem; font-weight: 700;">Hoje!</span>
                            <?php endif; ?>
                        </div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">
                            <?= htmlspecialchars($m['email'] ?? $m['phone'] ?? '') ?>
                        </div>
                    </div>
                    <div style="flex-shrink: 0; text-align: center; min-width: 48px;">
                        <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); line-height: 1;"><?= $day ?></div>
                        <div style="font-size: 0.65rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600;"><?= $monthName ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php $__view->endSection(); ?>
