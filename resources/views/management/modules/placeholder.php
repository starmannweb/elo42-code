<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title"><?= e($moduleTitle) ?></h1>
        <p class="mgmt-subtitle"><?= e($moduleDescription) ?></p>
    </div>
    <div class="mgmt-actions">
        <button class="btn btn--outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            Exportar
        </button>
        <button class="btn btn--primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Novo Registro
        </button>
    </div>
</div>

<div class="mgmt-dashboard-card" style="margin-top: 1.5rem; text-align: center; padding: 4rem 2rem;">
    <div style="width: 80px; height: 80px; background: rgba(30, 58, 138, 0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: var(--color-primary);">
        <?= $moduleIcon ?>
    </div>
    <h2 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; color: var(--color-text-primary); margin-bottom: 0.5rem;">
        <?= e($moduleTitle) ?> em breve!
    </h2>
    <p style="color: var(--text-muted); max-width: 500px; margin: 0 auto 2rem;">
        Estamos finalizando os últimos detalhes deste módulo para tornar a gestão da sua igreja ainda mais completa.
    </p>
    <button class="btn btn--outline" style="min-width: 200px;">Me avise quando lançar</button>
</div>
<?php $__view->endSection(); ?>
