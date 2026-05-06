<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">Configurações</h1>
        <p class="mgmt-subtitle">Gerencie as informações principais da sua organização</p>
    </div>
    <div class="mgmt-actions">
        <a href="<?= url('/gestao') ?>" class="btn btn--ghost">Voltar</a>
        <button type="submit" form="form-settings" class="btn btn--primary">Salvar Alterações</button>
    </div>
</div>

<!-- Tabs de navegação -->
<div style="border-bottom: 1px solid var(--color-border-light); margin-bottom: 1.5rem; display: flex; gap: 1.5rem; overflow-x: auto;">
    <a href="<?= url('/gestao/configuracoes') ?>" style="padding-bottom: 0.75rem; text-decoration: none; color: <?= ($activeTab ?? '') === 'igreja' ? 'var(--color-primary)' : 'var(--text-muted)' ?>; border-bottom: 2px solid <?= ($activeTab ?? '') === 'igreja' ? 'var(--color-primary)' : 'transparent' ?>; font-weight: <?= ($activeTab ?? '') === 'igreja' ? '600' : '500' ?>; white-space: nowrap;">
        Igreja
    </a>
    <a href="<?= url('/gestao/configuracoes/pix') ?>" style="padding-bottom: 0.75rem; text-decoration: none; color: <?= ($activeTab ?? '') === 'pix' ? 'var(--color-primary)' : 'var(--text-muted)' ?>; border-bottom: 2px solid <?= ($activeTab ?? '') === 'pix' ? 'var(--color-primary)' : 'transparent' ?>; font-weight: <?= ($activeTab ?? '') === 'pix' ? '600' : '500' ?>; white-space: nowrap;">
        PIX / Ofertas
    </a>
    <a href="<?= url('/gestao/configuracoes/seo') ?>" style="padding-bottom: 0.75rem; text-decoration: none; color: <?= ($activeTab ?? '') === 'seo' ? 'var(--color-primary)' : 'var(--text-muted)' ?>; border-bottom: 2px solid <?= ($activeTab ?? '') === 'seo' ? 'var(--color-primary)' : 'transparent' ?>; font-weight: <?= ($activeTab ?? '') === 'seo' ? '600' : '500' ?>; white-space: nowrap;">
        SEO
    </a>
    <a href="<?= url('/gestao/configuracoes/pwa') ?>" style="padding-bottom: 0.75rem; text-decoration: none; color: <?= ($activeTab ?? '') === 'pwa' ? 'var(--color-primary)' : 'var(--text-muted)' ?>; border-bottom: 2px solid <?= ($activeTab ?? '') === 'pwa' ? 'var(--color-primary)' : 'transparent' ?>; font-weight: <?= ($activeTab ?? '') === 'pwa' ? '600' : '500' ?>; white-space: nowrap;">
        APP
    </a>
</div>

<!-- Formulário de configurações da Igreja -->
<div class="mgmt-dashboard-card" style="max-width: 100%;">
    <form id="form-settings" method="POST" action="<?= url('/gestao/configuracoes') ?>">
        <?= csrf_field() ?>
        
        <div style="margin-bottom: var(--space-5);">
            <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-2);">Nome da Igreja</label>
            <input type="text" name="church_name" class="form-input" value="<?= e($settings['church_name'] ?? 'Minha Igreja') ?>" style="width: 100%;">
        </div>

        <div style="margin-bottom: var(--space-5);">
            <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-2);">Slogan</label>
            <input type="text" name="slogan" class="form-input" value="<?= e($settings['slogan'] ?? '') ?>" placeholder="Ex: Conectando vidas ao propósito de Deus" style="width: 100%;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4); margin-bottom: var(--space-5);">
            <div>
                <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-2);">E-mail</label>
                <input type="email" name="email" class="form-input" value="<?= e($settings['email'] ?? '') ?>" placeholder="contato@suaigreja.com" style="width: 100%;">
            </div>
            <div>
                <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-2);">Telefone / WhatsApp</label>
                <input type="text" name="phone" class="form-input" value="<?= e($settings['phone'] ?? '') ?>" placeholder="(00) 00000-0000" style="width: 100%;">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn--primary">✓ Salvar</button>
        </div>
    </form>
</div>
<?php $__view->endSection(); ?>
