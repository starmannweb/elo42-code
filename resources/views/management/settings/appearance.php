<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">Aparência</h1>
        <p class="mgmt-subtitle">Personalize as cores do aplicativo</p>
    </div>
    <div class="mgmt-actions">
        <button type="button" class="btn btn--outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><polyline points="3 3 3 8 8 8"></polyline></svg>
            Resetar
        </button>
        <button type="submit" form="form-appearance" class="btn btn--primary">Salvar</button>
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
    <a href="<?= url('/gestao/configuracoes/ia') ?>" style="padding-bottom: 0.75rem; text-decoration: none; color: <?= ($activeTab ?? '') === 'ia' ? 'var(--color-primary)' : 'var(--text-muted)' ?>; border-bottom: 2px solid <?= ($activeTab ?? '') === 'ia' ? 'var(--color-primary)' : 'transparent' ?>; font-weight: <?= ($activeTab ?? '') === 'ia' ? '600' : '500' ?>; white-space: nowrap;">
        Inteligência Artificial
    </a>
    <a href="<?= url('/gestao/configuracoes/aparencia') ?>" style="padding-bottom: 0.75rem; text-decoration: none; color: <?= ($activeTab ?? '') === 'aparencia' ? 'var(--color-primary)' : 'var(--text-muted)' ?>; border-bottom: 2px solid <?= ($activeTab ?? '') === 'aparencia' ? 'var(--color-primary)' : 'transparent' ?>; font-weight: <?= ($activeTab ?? '') === 'aparencia' ? '600' : '500' ?>; white-space: nowrap;">
        Aparência
    </a>
    <a href="<?= url('/gestao/configuracoes/seo') ?>" style="padding-bottom: 0.75rem; text-decoration: none; color: <?= ($activeTab ?? '') === 'seo' ? 'var(--color-primary)' : 'var(--text-muted)' ?>; border-bottom: 2px solid <?= ($activeTab ?? '') === 'seo' ? 'var(--color-primary)' : 'transparent' ?>; font-weight: <?= ($activeTab ?? '') === 'seo' ? '600' : '500' ?>; white-space: nowrap;">
        SEO
    </a>
    <a href="<?= url('/gestao/configuracoes/pwa') ?>" style="padding-bottom: 0.75rem; text-decoration: none; color: <?= ($activeTab ?? '') === 'pwa' ? 'var(--color-primary)' : 'var(--text-muted)' ?>; border-bottom: 2px solid <?= ($activeTab ?? '') === 'pwa' ? 'var(--color-primary)' : 'transparent' ?>; font-weight: <?= ($activeTab ?? '') === 'pwa' ? '600' : '500' ?>; white-space: nowrap;">
        PWA
    </a>
</div>

<div class="mgmt-grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <div class="mgmt-panel">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--color-text-primary); margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="13.5" cy="6.5" r="2.5"></circle><path d="M14.5 14.5l-6-6M9.5 17.5l-6-6"></path><path d="M17 19.5c-2.8-2.8-6.2-1.5-8-1 1.8 1.8 4.2 3.5 7 2.5 1.2-.5 1.5-1 1-1.5z"></path></svg>
            Cores do Tema
        </h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">As alterações são visualizadas em tempo real</p>
        
        <form id="form-appearance" action="#" method="POST">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label>Cor Primária</label>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">Cor principal do app (botões, links)</div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="color" value="#1e3a8a" style="width: 40px; height: 40px; padding: 0; border: none; border-radius: 8px; cursor: pointer;">
                    <input type="text" class="form-control" value="#1e3a8a" style="flex: 1;">
                </div>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <label>Cor Secundária</label>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">Cor de destaque secundário</div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="color" value="#f3f4f6" style="width: 40px; height: 40px; padding: 0; border: none; border-radius: 8px; cursor: pointer;">
                    <input type="text" class="form-control" value="#f3f4f6" style="flex: 1;">
                </div>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <label>Cor de Destaque</label>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">Cor para elementos de destaque</div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="color" value="#f59e0b" style="width: 40px; height: 40px; padding: 0; border: none; border-radius: 8px; cursor: pointer;">
                    <input type="text" class="form-control" value="#f59e0b" style="flex: 1;">
                </div>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <label>Cor de Fundo</label>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">Cor de fundo principal</div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="color" value="#ffffff" style="width: 40px; height: 40px; padding: 0; border: none; border-radius: 8px; cursor: pointer;">
                    <input type="text" class="form-control" value="#ffffff" style="flex: 1;">
                </div>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <label>Cor do Texto</label>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">Cor do texto principal</div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="color" value="#111827" style="width: 40px; height: 40px; padding: 0; border: none; border-radius: 8px; cursor: pointer;">
                    <input type="text" class="form-control" value="#111827" style="flex: 1;">
                </div>
            </div>
        </form>
    </div>

    <div class="mgmt-panel">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--color-text-primary); margin: 0 0 1rem;">Preview</h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Visualização das cores aplicadas</p>
        
        <div style="background: #ffffff; border: 1px solid var(--color-border-light); border-radius: 12px; padding: 2rem; display: flex; flex-direction: column; gap: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
            
            <div>
                <h4 style="font-family: 'Playfair Display', serif; font-size: 1.25rem; color: #111827; margin: 0 0 0.5rem;">Título de Exemplo</h4>
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Texto secundário de exemplo para visualização.</p>
            </div>
            
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <button style="background: #1e3a8a; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Botão Primário</button>
                <button style="background: #f3f4f6; color: #111827; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 500; cursor: pointer;">Secundário</button>
                <button style="background: transparent; color: #1e3a8a; border: 1px solid #1e3a8a; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 500; cursor: pointer;">Outline</button>
            </div>
            
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #1e3a8a; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">P</div>
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #111827; font-weight: 600;">S</div>
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #f59e0b; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">A</div>
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #d97706; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">G</div>
                <div style="width: 40px; height: 40px; border-radius: 8px; background: #991b1b; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">B</div>
            </div>
            
            <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem;">
                <span style="font-size: 0.85rem; color: #111827;">Card de exemplo</span>
            </div>
        </div>
    </div>
</div>
<?php $__view->endSection(); ?>
