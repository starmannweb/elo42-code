<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2.5rem;
    }
    .stat-card {
        background: #fff;
        border: 1px solid #f3f4f6;
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stat-val {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.2;
    }
    .stat-lbl {
        font-size: 0.75rem;
        color: #6b7280;
    }
    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 1rem;
        color: #111827;
    }
    .course-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .course-card {
        background: #fff;
        border: 1px solid #f3f4f6;
        border-radius: 12px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }
    .course-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.05);
    }
    .course-thumb {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }
    .course-content {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .course-tag {
        font-size: 0.65rem;
        padding: 2px 8px;
        background: #f3f4f6;
        color: #4b5563;
        border-radius: 12px;
        font-weight: 600;
        position: absolute;
        top: 1rem;
        left: 1rem;
    }
    .course-level {
        font-size: 0.65rem;
        padding: 2px 8px;
        background: rgba(255,255,255,0.9);
        color: #4b5563;
        border-radius: 12px;
        font-weight: 600;
        position: absolute;
        top: 1rem;
        right: 1rem;
    }
    .course-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 1.1rem;
        color: #111827;
        margin-bottom: 0.5rem;
    }
    .course-desc {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 1rem;
        line-height: 1.4;
        flex: 1;
    }
    .course-meta {
        font-size: 0.75rem;
        color: #9ca3af;
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .course-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #f3f4f6;
        padding-top: 1rem;
        font-size: 0.8rem;
    }
    .btn-comecar {
        color: #1e3a8a;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
</style>

<div style="margin-bottom: 0.5rem; font-size: 0.9rem; color: #6b7280;">Treinamentos e estudos em vídeo</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #fff7ed; color: #d97706;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M4 4.5A2.5 2.5 0 0 1 6.5 7H20"></path><path d="M6.5 7v10"></path></svg>
        </div>
        <div>
            <div class="stat-val">4</div>
            <div class="stat-lbl">Total de Cursos</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #f3f4f6; color: #4b5563;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
        </div>
        <div>
            <div class="stat-val">3</div>
            <div class="stat-lbl">Categorias</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #ecfdf5; color: #10b981;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
        </div>
        <div>
            <div class="stat-val">0</div>
            <div class="stat-lbl">Aulas Concluídas</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #f5f3ff; color: #c026d3;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div>
            <div class="stat-val">0min</div>
            <div class="stat-lbl">Tempo Total</div>
        </div>
    </div>
</div>

<h3 class="section-title">Em Destaque</h3>
<div class="course-grid">
    <a href="#" class="course-card">
        <div style="position: relative;">
            <img src="https://images.unsplash.com/photo-1491841550275-ad7854e35ca6?w=600&q=80" alt="Curso" class="course-thumb">
            <span class="course-tag">Discipulado</span>
            <span class="course-level">Iniciante</span>
        </div>
        <div class="course-content">
            <div class="course-title">Primeiros Passos</div>
            <div class="course-desc">Curso introdutório para novos membros da igreja. Entenda os fundamentos da nossa congregação.</div>
            <div class="course-meta">
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect></svg> 6 aulas</span>
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 45min</span>
            </div>
            <div class="course-footer">
                <span style="color: #6b7280;">Pr. Roberto Santos</span>
                <span class="btn-comecar">Começar <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg></span>
            </div>
        </div>
    </a>
    <a href="#" class="course-card">
        <div style="position: relative;">
            <img src="https://images.unsplash.com/photo-1504052434569-70ad5836ab65?w=600&q=80" alt="Curso" class="course-thumb">
            <span class="course-tag">Discipulado</span>
            <span class="course-level">Intermediário</span>
        </div>
        <div class="course-content">
            <div class="course-title">Fundamentos da Fé</div>
            <div class="course-desc">Estudo das doutrinas básicas do cristianismo, como Trindade, Salvação e Graça.</div>
            <div class="course-meta">
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect></svg> 12 aulas</span>
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 120min</span>
            </div>
            <div class="course-footer">
                <span style="color: #6b7280;">Pr. Carlos Silva</span>
                <span class="btn-comecar">Começar <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg></span>
            </div>
        </div>
    </a>
    <a href="#" class="course-card">
        <div style="position: relative;">
            <img src="https://images.unsplash.com/photo-1438232992991-995b7058bbb3?w=600&q=80" alt="Curso" class="course-thumb">
            <span class="course-tag">Liderança</span>
            <span class="course-level">Avançado</span>
        </div>
        <div class="course-content">
            <div class="course-title">Liderança Servidora</div>
            <div class="course-desc">Desenvolvendo líderes segundo o coração de Deus para atuar nos ministérios.</div>
            <div class="course-meta">
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect></svg> 8 aulas</span>
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 90min</span>
            </div>
            <div class="course-footer">
                <span style="color: #6b7280;">Pr. Carlos Silva</span>
                <span class="btn-comecar">Começar <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg></span>
            </div>
        </div>
    </a>
</div>

<div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem;">
    <button style="padding: 0.4rem 1rem; border-radius: 20px; background: #1e3a8a; color: white; border: none; font-size: 0.8rem; font-weight: 500;">Todos</button>
    <button style="padding: 0.4rem 1rem; border-radius: 20px; background: #f3f4f6; color: #4b5563; border: none; font-size: 0.8rem; font-weight: 500;">Discipulado</button>
    <button style="padding: 0.4rem 1rem; border-radius: 20px; background: #f3f4f6; color: #4b5563; border: none; font-size: 0.8rem; font-weight: 500;">Liderança</button>
    <button style="padding: 0.4rem 1rem; border-radius: 20px; background: #f3f4f6; color: #4b5563; border: none; font-size: 0.8rem; font-weight: 500;">Teologia</button>
</div>

<h3 class="section-title">Todos os Cursos</h3>
<div class="course-grid">
    <a href="#" class="course-card">
        <div style="position: relative;">
            <img src="https://images.unsplash.com/photo-1504052434569-70ad5836ab65?w=600&q=80" alt="Curso" class="course-thumb">
            <span class="course-tag">Teologia</span>
        </div>
        <div class="course-content">
            <div class="course-title">Panorama Bíblico</div>
            <div class="course-desc">Visão geral de toda a Bíblia, do Gênesis ao Apocalipse.</div>
            <div class="course-meta">
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect></svg> 24 aulas</span>
                <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 240min</span>
            </div>
            <div class="course-footer">
                <span style="color: #6b7280;">Pr. Roberto Santos</span>
                <span class="btn-comecar">Começar <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg></span>
            </div>
        </div>
    </a>
</div>
<?php $__view->endSection(); ?>
