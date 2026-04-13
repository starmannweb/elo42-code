<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<style>
    .offerings-subtitle {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }
    .verse-quote {
        background: #1e3a8a;
        color: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .verse-quote svg {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 120px;
        height: 120px;
        opacity: 0.05;
    }
    .verse-text {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        font-style: italic;
        line-height: 1.6;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
    }
    .verse-ref {
        font-size: 0.85rem;
        opacity: 0.8;
        font-weight: 500;
        position: relative;
        z-index: 2;
    }
    
    .pix-card {
        background: #fff;
        border: 1px solid #f3f4f6;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        margin-bottom: 2.5rem;
    }
    .pix-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1.5rem;
    }
    .qr-container {
        background: #f9fafb;
        border-radius: 12px;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        border: 1px solid #f3f4f6;
    }
    .qr-placeholder {
        width: 120px;
        height: 120px;
        background: #e5e7eb;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .qr-text {
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 500;
    }
    .btn-copy {
        background: #f59e0b;
        color: white;
        border: none;
        width: 100%;
        padding: 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    .btn-copy:hover {
        background: #d97706;
    }
    .pix-hint {
        text-align: center;
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 1rem;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 1.5rem;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .campaign-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .campaign-card {
        background: #fff;
        border: 1px solid #f3f4f6;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .camp-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .camp-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .camp-title {
        font-weight: 600;
        font-size: 1.05rem;
        color: #111827;
        margin-bottom: 0.25rem;
    }
    .camp-desc {
        font-size: 0.85rem;
        color: #6b7280;
    }
    .camp-progress-bar {
        height: 6px;
        background: #f3f4f6;
        border-radius: 3px;
        margin-bottom: 0.75rem;
        overflow: hidden;
    }
    .camp-progress-fill {
        height: 100%;
        background: #f59e0b;
        border-radius: 3px;
    }
    .camp-stats {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        font-weight: 500;
        color: #4b5563;
        margin-bottom: 1.5rem;
    }
    .btn-contribute {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        width: 100%;
        padding: 0.875rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-contribute:hover {
        background: #f3f4f6;
        color: #111827;
    }
</style>

<div class="offerings-subtitle">Contribua para a obra de Deus</div>

<div style="max-width: 800px;">
    <div class="verse-quote">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
        <div class="verse-text">"Cada um dê conforme determinou em seu coração, não com pesar ou por obrigação, pois Deus ama quem dá com alegria."</div>
        <div class="verse-ref">— 2 Coríntios 9:7</div>
    </div>

    <div class="pix-card">
        <div class="pix-header">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Contribuir via PIX
        </div>
        
        <div class="qr-container">
            <div class="qr-placeholder">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </div>
            <div class="qr-text">CNPJ</div>
            <div style="font-weight: 600; color: #111827; margin-top: 0.25rem;">00.000.000/0001-00</div>
        </div>

        <button class="btn-copy">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
            Copiar Chave PIX
        </button>
        <div class="pix-hint">Escaneie o QR Code ou copie a chave PIX para fazer sua contribuição</div>
    </div>

    <h3 class="section-title">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
        Campanhas Ativas
    </h3>

    <div class="campaign-list">
        <div class="campaign-card">
            <div class="camp-header">
                <div class="camp-icon" style="background: #fffbeb; color: #d97706;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
                </div>
                <div>
                    <div class="camp-title">Reforma do Templo</div>
                    <div class="camp-desc">Campanha para reforma e ampliação do templo principal</div>
                </div>
            </div>
            
            <div class="camp-progress-bar">
                <div class="camp-progress-fill" style="width: 58%;"></div>
            </div>
            
            <div class="camp-stats">
                <span>R$ 87.500,00</span>
                <span style="color: #f59e0b;">58%</span>
                <span>R$ 150.000,00</span>
            </div>
            
            <button class="btn-contribute">Contribuir para Reforma do Templo</button>
        </div>

        <div class="campaign-card">
            <div class="camp-header">
                <div class="camp-icon" style="background: #fffbeb; color: #d97706;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                </div>
                <div>
                    <div class="camp-title">Missões 2026</div>
                    <div class="camp-desc">Apoio aos missionários da igreja no campo</div>
                </div>
            </div>
            
            <div class="camp-progress-bar">
                <div class="camp-progress-fill" style="width: 64%;"></div>
            </div>
            
            <div class="camp-stats">
                <span>R$ 32.000,00</span>
                <span style="color: #f59e0b;">64%</span>
                <span>R$ 50.000,00</span>
            </div>
            
            <button class="btn-contribute">Contribuir para Missões 2026</button>
        </div>

        <div class="campaign-card">
            <div class="camp-header">
                <div class="camp-icon" style="background: #fffbeb; color: #d97706;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                </div>
                <div>
                    <div class="camp-title">Cesta Solidária</div>
                    <div class="camp-desc">Doação de cestas básicas para famílias carentes</div>
                </div>
            </div>
            
            <div class="camp-progress-bar">
                <div class="camp-progress-fill" style="width: 85%;"></div>
            </div>
            
            <div class="camp-stats">
                <span>R$ 8.500,00</span>
                <span style="color: #f59e0b;">85%</span>
                <span>R$ 10.000,00</span>
            </div>
            
            <button class="btn-contribute">Contribuir para Cesta Solidária</button>
        </div>
    </div>
</div>

<?php $__view->endSection(); ?>
