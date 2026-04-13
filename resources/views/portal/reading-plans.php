<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<style>
    .tabs-nav {
        display: flex;
        background: #f3f4f6;
        padding: 4px;
        border-radius: 12px;
        margin-bottom: 2rem;
        max-width: 800px;
    }
    .tab-btn {
        flex: 1;
        padding: 0.6rem;
        text-align: center;
        font-size: 0.85rem;
        font-weight: 500;
        color: #4b5563;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .tab-btn.active {
        background: #fff;
        color: #111827;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        font-weight: 700;
        margin: 2rem 0 1rem;
        color: #111827;
    }
    .card-destaque {
        position: relative;
        height: 180px;
        border-radius: 12px;
        overflow: hidden;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 1.25rem;
        text-decoration: none;
    }
    .card-destaque::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0.1));
        z-index: 1;
    }
    .card-destaque > div {
        position: relative;
        z-index: 2;
    }
    .card-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    .card-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    .tag-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .tag-item {
        background: #f3f4f6;
        color: #4b5563;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        text-decoration: none;
    }
    .plan-card {
        background: #fff;
        border: 1px solid #f3f4f6;
        border-radius: 12px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .plan-card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    .plan-content {
        padding: 1rem;
    }
    .plan-title {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
        color: #111827;
    }
    .plan-meta {
        font-size: 0.75rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
</style>

<div class="tabs-nav">
    <div class="tab-btn">Meus</div>
    <div class="tab-btn active">Encontrar</div>
    <div class="tab-btn">Salvo</div>
    <div class="tab-btn">Concluídos</div>
</div>

<h3 class="section-title">⭐ Em Destaque</h3>
<div class="card-grid-3">
    <a href="#" class="card-destaque" style="background: url('https://images.unsplash.com/photo-1491841550275-ad7854e35ca6?w=400&q=80') center/cover;">
        <div>
            <div style="font-size: 0.7rem; font-weight: 600; color: #fcd34d; margin-bottom: 0.25rem;">⭐ Destaque</div>
            <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.25rem; font-family: 'Playfair Display', serif;">Salmos em 30 Dias</div>
            <div style="font-size: 0.75rem; opacity: 0.9;">30 dias</div>
        </div>
    </a>
    <a href="#" class="card-destaque" style="background: url('https://images.unsplash.com/photo-1504052434569-70ad5836ab65?w=400&q=80') center/cover;">
        <div>
            <div style="font-size: 0.7rem; font-weight: 600; color: #fcd34d; margin-bottom: 0.25rem;">⭐ Destaque</div>
            <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.25rem; font-family: 'Playfair Display', serif;">Evangelho de João</div>
            <div style="font-size: 0.75rem; opacity: 0.9;">21 dias</div>
        </div>
    </a>
    <a href="#" class="card-destaque" style="background: url('https://images.unsplash.com/photo-1438232992991-995b7058bbb3?w=400&q=80') center/cover;">
        <div>
            <div style="font-size: 0.7rem; font-weight: 600; color: #fcd34d; margin-bottom: 0.25rem;">⭐ Destaque</div>
            <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.25rem; font-family: 'Playfair Display', serif;">Cartas de Paulo</div>
            <div style="font-size: 0.75rem; opacity: 0.9;">60 dias</div>
        </div>
    </a>
</div>

<h3 class="section-title">Temas</h3>
<div class="tag-list">
    <a href="#" class="tag-item">salmos</a>
    <a href="#" class="tag-item">louvor</a>
    <a href="#" class="tag-item">adoração</a>
    <a href="#" class="tag-item">evangelho</a>
    <a href="#" class="tag-item">jesus</a>
    <a href="#" class="tag-item">amor</a>
    <a href="#" class="tag-item">sabedoria</a>
</div>

<h3 class="section-title">Devocional</h3>
<div class="card-grid-2">
    <a href="#" class="plan-card">
        <img src="https://images.unsplash.com/photo-1491841550275-ad7854e35ca6?w=600&q=80" alt="Salmos">
        <div class="plan-content">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="plan-title">Salmos em 30 Dias</div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
            <div class="plan-meta">
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> 30 dias</span>
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Alpha Church</span>
            </div>
        </div>
    </a>
    <a href="#" class="plan-card">
        <img src="https://images.unsplash.com/photo-1504052434569-70ad5836ab65?w=600&q=80" alt="Provérbios">
        <div class="plan-content">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="plan-title">Provérbios: Sabedoria Diária</div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
            <div class="plan-meta">
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> 31 dias</span>
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Alpha Church</span>
            </div>
        </div>
    </a>
</div>

<h3 class="section-title">Estudo</h3>
<div class="card-grid-2">
    <a href="#" class="plan-card">
        <img src="https://images.unsplash.com/photo-1438232992991-995b7058bbb3?w=600&q=80" alt="Joao">
        <div class="plan-content">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="plan-title">Evangelho de João</div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
            <div class="plan-meta">
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> 21 dias</span>
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Pr. Carlos Silva</span>
            </div>
        </div>
    </a>
</div>

<h3 class="section-title">Todos os Planos</h3>
<!-- List view of plans -->
<div style="display: flex; flex-direction: column; gap: 1rem;">
    <a href="#" class="plan-card" style="display: flex; height: auto;">
        <img src="https://images.unsplash.com/photo-1491841550275-ad7854e35ca6?w=600&q=80" alt="Salmos" style="width: 140px; height: 100px;">
        <div class="plan-content" style="flex: 1;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div class="plan-title">Salmos em 30 Dias</div>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
            <div class="plan-meta">
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect></svg> 30 dias</span>
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Alpha Church</span>
            </div>
        </div>
    </a>
</div>
<?php $__view->endSection(); ?>
