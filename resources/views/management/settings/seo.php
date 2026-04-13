<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">SEO & Meta Tags</h1>
        <p class="mgmt-subtitle">Configure como seu app aparece no Google e redes sociais</p>
    </div>
    <div class="mgmt-actions">
        <a href="<?= url('/gestao') ?>" class="btn btn--ghost">Cancelar</a>
        <button type="submit" form="form-seo" class="btn btn--primary">Salvar</button>
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
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            Configurações SEO
        </h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Otimize seu app para mecanismos de busca</p>
        
        <form id="form-seo" action="#" method="POST">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="seo_title">Título SEO</label>
                <input type="text" id="seo_title" name="seo_title" class="form-control" value="Igreja VERBO - Aplicativo Oficial">
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">0/60 caracteres (recomendado: até 60)</div>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="seo_desc">Meta Descrição</label>
                <textarea id="seo_desc" name="seo_desc" class="form-control" rows="3">Acesse a Bíblia, ministrações, planos de leitura e muito mais...</textarea>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">0/160 caracteres (recomendado: 120-160)</div>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="seo_keywords">Palavras-chave</label>
                <input type="text" id="seo_keywords" name="seo_keywords" class="form-control" value="igreja, bíblia, cristão, ministração, culto">
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Separe por vírgulas</div>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <label>Imagem Open Graph</label>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">Imagem exibida ao compartilhar nas redes sociais (1200x630px recomendado)</div>
                <button type="button" class="btn btn--outline" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    Upload Imagem
                </button>
            </div>
        </form>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="mgmt-panel">
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--color-text-primary); margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                Preview no Google
            </h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Como seu app aparece nos resultados de busca</p>
            
            <div style="background: #ffffff; border: 1px solid var(--color-border-light); border-radius: 12px; padding: 1.5rem;">
                <div style="font-size: 0.85rem; color: #16a34a; margin-bottom: 0.25rem;">https://seuapp.lovable.app</div>
                <div style="font-size: 1.1rem; color: #1e3a8a; margin-bottom: 0.25rem; font-weight: 500;">Título do seu app</div>
                <div style="font-size: 0.875rem; color: #4b5563;">Descrição do seu app aparecerá aqui...</div>
            </div>
        </div>

        <div class="mgmt-panel">
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--color-text-primary); margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                Preview Redes Sociais
            </h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Como aparece ao compartilhar no Facebook/LinkedIn</p>
            
            <div style="background: #ffffff; border: 1px solid var(--color-border-light); border-radius: 12px; overflow: hidden;">
                <div style="height: 180px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 0.85rem; font-weight: 500;">
                    Sem imagem OG
                </div>
                <div style="padding: 1.25rem; background: #f9fafb; border-top: 1px solid var(--color-border-light);">
                    <div style="font-size: 0.7rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">SEUAPP.LOVABLE.APP</div>
                    <div style="font-size: 1rem; color: #111827; font-weight: 600; margin-bottom: 0.25rem;">Título do app</div>
                    <div style="font-size: 0.85rem; color: #4b5563;">Descrição do app...</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__view->endSection(); ?>
