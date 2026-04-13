<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">Configurações PWA</h1>
        <p class="mgmt-subtitle">Configure o aplicativo instalável</p>
    </div>
    <div class="mgmt-actions">
        <a href="<?= url('/gestao') ?>" class="btn btn--ghost">Cancelar</a>
        <button type="submit" form="form-pwa" class="btn btn--primary">Salvar</button>
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

<div class="alert alert--info" style="margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.75rem; background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; border-radius: 8px; padding: 1rem;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 2px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
    <div style="font-size: 0.85rem; line-height: 1.5;">
        As configurações PWA afetam como o app aparece quando instalado no celular. Algumas mudanças podem exigir que o usuário reinstale o app.
    </div>
</div>

<div class="mgmt-grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <div class="mgmt-panel">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--color-text-primary); margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
            Informações do App
        </h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Nome e descrição do aplicativo instalável</p>
        
        <form id="form-pwa" action="#" method="POST">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="pwa_name">Nome Completo</label>
                <input type="text" id="pwa_name" name="pwa_name" class="form-control" value="Igreja VERBO">
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Nome exibido na tela de instalação</div>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="pwa_short_name">Nome Curto</label>
                <input type="text" id="pwa_short_name" name="pwa_short_name" class="form-control" value="VERBO">
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Nome exibido abaixo do ícone (máx. 12 caracteres)</div>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="pwa_desc">Descrição</label>
                <textarea id="pwa_desc" name="pwa_desc" class="form-control" rows="3">Aplicativo oficial da igreja...</textarea>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <label>Cor do Tema</label>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="color" value="#4338ca" style="width: 40px; height: 40px; padding: 0; border: none; border-radius: 8px; cursor: pointer;">
                    <input type="text" class="form-control" value="#4338ca" style="flex: 1;">
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Cor da barra de status do navegador</div>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <label>Cor de Fundo</label>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="color" value="#faf8f5" style="width: 40px; height: 40px; padding: 0; border: none; border-radius: 8px; cursor: pointer;">
                    <input type="text" class="form-control" value="#faf8f5" style="flex: 1;">
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Cor de fundo da splash screen</div>
            </div>
        </form>
    </div>

    <div class="mgmt-panel">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--color-text-primary); margin: 0 0 1rem;">Ícones do App</h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Ícones exibidos na tela inicial do dispositivo</p>
        
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div>
                <label style="display: block; font-weight: 500; font-size: 0.85rem; margin-bottom: 0.25rem;">Ícone 192x192px</label>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">Usado em dispositivos Android e como ícone padrão</div>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="width: 64px; height: 64px; background: var(--color-bg-light); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px dashed var(--color-border-light); font-size: 0.7rem; color: var(--text-muted);">
                        192
                    </div>
                    <button type="button" class="btn btn--outline" style="display: inline-flex; align-items: center; gap: 0.5rem; height: 40px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        Upload
                    </button>
                </div>
            </div>

            <div>
                <label style="display: block; font-weight: 500; font-size: 0.85rem; margin-bottom: 0.25rem;">Ícone 512x512px</label>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">Usado na splash screen e dispositivos de alta resolução</div>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="width: 64px; height: 64px; background: var(--color-bg-light); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px dashed var(--color-border-light); font-size: 0.7rem; color: var(--text-muted);">
                        512
                    </div>
                    <button type="button" class="btn btn--outline" style="display: inline-flex; align-items: center; gap: 0.5rem; height: 40px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        Upload
                    </button>
                </div>
            </div>

            <div style="margin-top: 1rem; border-top: 1px solid var(--color-border-light); padding-top: 1.5rem;">
                <label style="display: block; font-weight: 500; font-size: 0.85rem; margin-bottom: 1rem;">Preview da Instalação</label>
                <div style="background: #111827; border-radius: 16px; padding: 2rem; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; position: relative; overflow: hidden;">
                    <div style="width: 64px; height: 64px; background: #4338ca; border-radius: 16px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 700; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        V
                    </div>
                    <div style="color: white; font-size: 0.75rem; font-weight: 500; letter-spacing: 0.05em;">VERBO</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__view->endSection(); ?>
