<?php $__view->extends('layouts/portal'); ?>

<?php $__view->section('content'); ?>
<style>
    .grid-dashboard {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }
    
    .hero-banner {
        background: url('https://images.unsplash.com/photo-1438232992991-995b7058bbb3?q=80&w=1200&auto=format&fit=crop') center/cover no-resize;
        border-radius: 16px;
        padding: 3rem 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .hero-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.2));
    }
    
    .hero-content {
        position: relative;
        z-index: 10;
    }
    
    .hero-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-family: 'Playfair Display', serif;
    }
    
    .hero-text {
        font-size: 0.95rem;
        opacity: 0.9;
        margin-bottom: 1.5rem;
    }
    
    .streak-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.04);
        margin-bottom: 1.5rem;
    }
    
    .verse-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.04);
    }
    
    .quick-access {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .quick-btn {
        background: #fff;
        border: 1px solid #f3f4f6;
        border-radius: 12px;
        padding: 1.25rem 0.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        text-decoration: none;
        color: var(--portal-text);
        font-size: 0.85rem;
        font-weight: 500;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }
    
    .quick-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .quick-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .quick-icon svg {
        width: 18px;
        height: 18px;
    }
    
    .ministration-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #fff;
        border: 1px solid #f3f4f6;
        border-radius: 12px;
        margin-bottom: 0.75rem;
    }
    
    .min-thumb {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        background: #1e3a8a;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="grid-dashboard">
    <div class="col-left">
        <div class="hero-banner">
            <div class="hero-content">
                <h2 class="hero-title">Bem-vindo à <?= e($organization['name'] ?? 'Igreja') ?></h2>
                <p class="hero-text">Um lugar para encontrar Deus e fazer amigos</p>
                <a href="#" style="color: white; font-weight: 500; text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem;">Saiba mais <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg></a>
            </div>
        </div>

        <div class="streak-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="color: #9ca3af;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2c0 0-5 3.5-5 9s3 6.5 5 11c2-4.5 5-5.5 5-11s-5-9-5-9z"></path></svg></div>
                    <div>
                        <div style="font-weight: 600; font-size: 0.95rem;">0 dias de sequência</div>
                        <div style="font-size: 0.75rem; color: #6b7280;">Continue assim! 🎉</div>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-weight: 700; font-size: 1.25rem;">0</div>
                    <div style="font-size: 0.75rem; color: #6b7280;">pontos</div>
                </div>
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 0.5rem;">
                    <span style="color: #6b7280;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="4"></circle><line x1="21.17" y1="8" x2="12" y2="8"></line><line x1="3.95" y1="6.06" x2="8.54" y2="14"></line><line x1="10.88" y1="21.94" x2="15.46" y2="14"></line></svg> Meta diária</span>
                    <span style="font-weight: 600;">0%</span>
                </div>
                <div style="height: 6px; background: #f3f4f6; border-radius: 3px; overflow: hidden;">
                    <div style="height: 100%; width: 0%; background: #f59e0b; border-radius: 3px;"></div>
                </div>
            </div>

            <a href="<?= url('/membro/planos-leitura') ?>" style="display: block; width: 100%; text-align: center; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; text-decoration: none; color: #374151; font-weight: 500; font-size: 0.85rem; transition: all 0.2s;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg> Ler agora</a>
        </div>

        <div class="verse-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; color: #374151;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Versículo do Dia
                </div>
                <span style="font-size: 0.7rem; font-weight: 600; padding: 2px 8px; border-radius: 12px; background: rgba(245, 158, 11, 0.1); color: #d97706;">Força</span>
            </div>

            <p style="font-size: 1.1rem; line-height: 1.6; color: #111827; margin-bottom: 1rem; font-family: 'Playfair Display', serif;">"Tudo posso naquele que me fortalece."</p>
            <p style="font-size: 0.85rem; color: #6b7280; font-weight: 600; margin-bottom: 1.5rem;">Filipenses 4:13</p>

            <div style="display: flex; justify-content: space-between; border-top: 1px solid #f3f4f6; padding-top: 1rem;">
                <button style="background: none; border: none; color: #6b7280; font-size: 0.85rem; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg> Compartilhar</button>
                <button style="background: none; border: none; color: #6b7280; font-size: 0.85rem; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg> Salvar</button>
            </div>
        </div>
    </div>

    <div class="col-right">
        <h3 class="serif-heading" style="margin: 0 0 1rem; font-size: 1.1rem;">Acesso Rápido</h3>
        <div class="quick-access">
            <a href="<?= url('/membro/biblia') ?>" class="quick-btn">
                <div class="quick-icon" style="background: #eff6ff; color: #4f46e5;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M4 4.5A2.5 2.5 0 0 1 6.5 7H20"></path><path d="M6.5 7v10"></path></svg></div>
                Bíblia
            </a>
            <a href="<?= url('/membro/eventos') ?>" class="quick-btn">
                <div class="quick-icon" style="background: #f0fdf4; color: #0891b2;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
                Agenda
            </a>
            <a href="<?= url('/membro/ministracoes') ?>" class="quick-btn">
                <div class="quick-icon" style="background: #fef2f2; color: #16a34a;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg></div>
                Ministrações
            </a>
            <a href="<?= url('/membro/ofertas') ?>" class="quick-btn">
                <div class="quick-icon" style="background: #fff1f2; color: #dc2626;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></div>
                Dízimos
            </a>
            <a href="<?= url('/membro/pedidos') ?>" class="quick-btn">
                <div class="quick-icon" style="background: #fdf4ff; color: #c026d3;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></div>
                Pedidos
            </a>
            <a href="<?= url('/membro/planos-leitura') ?>" class="quick-btn">
                <div class="quick-icon" style="background: #f0f9ff; color: #0284c7;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg></div>
                Planos
            </a>
            <a href="<?= url('/membro/cursos') ?>" class="quick-btn">
                <div class="quick-icon" style="background: #fdf8f6; color: #b45309;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg></div>
                Cursos
            </a>
            <a href="#" class="quick-btn">
                <div class="quick-icon" style="background: #fffbeb; color: #d97706;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg></div>
                Ranking
            </a>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1rem;">
            <h3 class="serif-heading" style="margin: 0; font-size: 1.1rem;">Ministrações em Destaque</h3>
            <a href="<?= url('/membro/ministracoes') ?>" style="font-size: 0.8rem; color: #f59e0b; font-weight: 500; text-decoration: none;">Ver todas <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg></a>
        </div>

        <div class="ministration-item">
            <div class="min-thumb"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg></div>
            <div style="flex: 1;">
                <span style="font-size: 0.65rem; background: #f3f4f6; color: #4b5563; padding: 2px 6px; border-radius: 8px; font-weight: 600;">Fé</span>
                <div style="font-weight: 600; font-size: 0.95rem; margin: 4px 0; color: #111827;">O Poder da Fé</div>
                <div style="font-size: 0.8rem; color: #6b7280; display: flex; gap: 1rem;">
                    <span>Pr. Carlos Silva</span>
                    <span style="display: flex; align-items: center; gap: 0.25rem;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 45min</span>
                </div>
            </div>
        </div>

        <div class="ministration-item">
            <div class="min-thumb"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg></div>
            <div style="flex: 1;">
                <span style="font-size: 0.65rem; background: #ecfdf5; color: #10b981; padding: 2px 6px; border-radius: 8px; font-weight: 600;">Família</span>
                <div style="font-weight: 600; font-size: 0.95rem; margin: 4px 0; color: #111827;">Família: Projeto de Deus</div>
                <div style="font-size: 0.8rem; color: #6b7280; display: flex; gap: 1rem;">
                    <span>Pr. Roberto Santos</span>
                    <span style="display: flex; align-items: center; gap: 0.25rem;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 52min</span>
                </div>
            </div>
        </div>

        <button style="width: 100%; margin-top: 0.5rem; padding: 1rem; background: #fff8f1; border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 12px; color: #92400e; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 0.5rem; transition: all 0.2s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            Explorar Biblioteca
        </button>
    </div>
</div>
<?php $__view->endSection(); ?>
