<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<style>
    .search-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        align-items: center;
    }
    .search-input {
        flex: 1;
        position: relative;
    }
    .search-input input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 2.5rem;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        font-size: 0.95rem;
        box-sizing: border-box;
    }
    .search-input svg {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
    .tag-filter {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
    }
    .filter-btn {
        background: #fff;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        white-space: nowrap;
    }
    .filter-btn.active {
        background: #1e3a8a;
        color: white;
        border-color: #1e3a8a;
    }
    .min-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    .min-card {
        background: #fff;
        border: 1px solid #f3f4f6;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .min-header {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .min-icon {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        background: #1e3a8a;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        position: relative;
    }
    .min-tag {
        font-size: 0.65rem;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
        margin-bottom: 0.25rem;
        display: inline-block;
    }
    .min-tag.fe { background: #f3f4f6; color: #4b5563; }
    .min-tag.familia { background: #ecfdf5; color: #10b981; }
    .min-tag.salvacao { background: #fff1f2; color: #e11d48; }
    .min-tag.esperanca { background: #fffbeb; color: #d97706; }
    
    .min-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: #111827;
        margin-bottom: 0.25rem;
        font-family: 'Playfair Display', serif;
    }
    .min-author {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }
    .min-stats {
        display: flex;
        gap: 0.75rem;
        font-size: 0.75rem;
        color: #9ca3af;
        align-items: center;
    }
    .min-desc {
        font-size: 0.9rem;
        color: #4b5563;
        line-height: 1.5;
    }
</style>

<div class="search-row">
    <div class="search-input">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" placeholder="Buscar ministração...">
    </div>
    <div class="tag-filter">
        <button class="filter-btn active">Todos</button>
        <button class="filter-btn">Esperança</button>
        <button class="filter-btn">Família</button>
        <button class="filter-btn">Fé</button>
        <button class="filter-btn">Salvação</button>
    </div>
</div>

<div class="min-grid">
    <div class="min-card">
        <div class="min-header">
            <div class="min-icon">
                <div style="position: absolute; top: -4px; right: -4px; width: 12px; height: 12px; background: #f59e0b; border-radius: 50%; border: 2px solid white;"></div>
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>
            </div>
            <div>
                <span class="min-tag fe">Fé</span>
                <div class="min-title">O Poder da Fé</div>
                <div class="min-author">Pr. Carlos Silva</div>
                <div class="min-stats">
                    <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 45min</span>
                    <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> 1.250</span>
                    <span>04 de jan.</span>
                </div>
            </div>
        </div>
        <div class="min-desc">Uma mensagem inspiradora sobre como a fé pode mover montanhas em nossa vida.</div>
    </div>

    <div class="min-card">
        <div class="min-header">
            <div class="min-icon">
                <div style="position: absolute; top: -4px; right: -4px; width: 12px; height: 12px; background: #f59e0b; border-radius: 50%; border: 2px solid white;"></div>
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>
            </div>
            <div>
                <span class="min-tag familia">Família</span>
                <div class="min-title">Família: Projeto de Deus</div>
                <div class="min-author">Pr. Roberto Santos</div>
                <div class="min-stats">
                    <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 52min</span>
                    <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> 890</span>
                    <span>26 de dez.</span>
                </div>
            </div>
        </div>
        <div class="min-desc">Estudo sobre os princípios bíblicos para uma família saudável e estruturada no Senhor.</div>
    </div>

    <div class="min-card">
        <div class="min-header">
            <div class="min-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>
            </div>
            <div>
                <span class="min-tag esperanca">Esperança</span>
                <div class="min-title">A Esperança que Não Falha</div>
                <div class="min-author">Pr. Carlos Silva</div>
                <div class="min-stats">
                    <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 38min</span>
                    <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> 670</span>
                    <span>21 de dez.</span>
                </div>
            </div>
        </div>
        <div class="min-desc">Mensagem de encorajamento para tempos difíceis e provações constantes.</div>
    </div>

    <div class="min-card">
        <div class="min-header">
            <div class="min-icon">
                <div style="position: absolute; top: -4px; right: -4px; width: 12px; height: 12px; background: #f59e0b; border-radius: 50%; border: 2px solid white;"></div>
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>
            </div>
            <div>
                <span class="min-tag salvacao">Salvação</span>
                <div class="min-title">Jesus: O Caminho</div>
                <div class="min-author">Ev. Maria Oliveira</div>
                <div class="min-stats">
                    <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 42min</span>
                    <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:text-bottom"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> 1.100</span>
                    <span>14 de dez.</span>
                </div>
            </div>
        </div>
        <div class="min-desc">Mensagem evangelística sobre a salvação exclusiva e suficiente em Jesus Cristo.</div>
    </div>
</div>
<?php $__view->endSection(); ?>
