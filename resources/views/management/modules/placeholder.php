<?php $__view->extends('management', ['pageTitle' => $pageTitle ?? 'Gestao', 'breadcrumb' => $breadcrumb ?? '', 'activeMenu' => $activeMenu ?? '']); ?>

<?php $__view->section('content'); ?>
<div style="max-width: 720px; margin: 2rem auto; text-align: center;">
    <div style="display: inline-flex; align-items: center; justify-content: center; width: 72px; height: 72px; border-radius: 16px; background: var(--color-primary-soft, rgba(30,58,138,0.08)); color: var(--color-primary, #1e3a8a); margin-bottom: 1.5rem;">
        <?= $moduleIcon ?? '' ?>
    </div>
    <h1 style="font-size: 1.75rem; font-weight: 700; margin: 0 0 0.75rem; color: var(--text-primary);"><?= htmlspecialchars($moduleTitle ?? 'Modulo') ?></h1>
    <p style="font-size: 1rem; color: var(--text-secondary); line-height: 1.6; margin: 0 0 2rem;">
        <?= htmlspecialchars($moduleDescription ?? '') ?>
    </p>

    <div style="background: var(--card-bg, #fff); border: 1px solid var(--border-color, #e5e7eb); border-radius: 12px; padding: 2.5rem 2rem; margin-bottom: 1.5rem;">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; margin-bottom: 1rem;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path></svg>
        </div>
        <h2 style="font-size: 1.25rem; font-weight: 600; margin: 0 0 0.5rem; color: var(--text-primary);">Em desenvolvimento</h2>
        <p style="font-size: 0.9rem; color: var(--text-secondary); margin: 0;">
            Este modulo esta sendo construido e estara disponivel em breve. Fique atento as atualizacoes!
        </p>
    </div>

    <a href="<?= url('/gestao') ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; background: var(--color-primary, #1e3a8a); color: #fff; border-radius: 8px; text-decoration: none; font-size: 0.875rem; font-weight: 500;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"></path></svg>
        Voltar ao Dashboard
    </a>
</div>
<?php $__view->endSection(); ?>
