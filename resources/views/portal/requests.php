<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<style>
    .requests-subtitle {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }
    .req-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        max-width: 800px;
    }
    .req-card {
        background: #fff;
        border: 1px solid #f3f4f6;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        transition: all 0.2s;
    }
    .req-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border-color: #e5e7eb;
    }
    .req-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .req-icon svg {
        width: 24px;
        height: 24px;
    }
    .req-content {
        flex: 1;
    }
    .req-title {
        font-weight: 600;
        font-size: 1rem;
        color: #111827;
        margin-bottom: 0.25rem;
    }
    .req-desc {
        font-size: 0.85rem;
        color: #6b7280;
    }
</style>

<div class="requests-subtitle">Como podemos ajudar você?</div>

<div class="req-list">
    <a href="#" class="req-card">
        <div class="req-icon" style="background: #fef2f2; color: #e11d48;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
        </div>
        <div class="req-content">
            <div class="req-title">Oração</div>
            <div class="req-desc">Envie seu pedido de oração</div>
        </div>
    </a>

    <a href="#" class="req-card">
        <div class="req-icon" style="background: #eff6ff; color: #2563eb;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
        <div class="req-content">
            <div class="req-title">Batismo</div>
            <div class="req-desc">Inscreva-se para o próximo batismo</div>
        </div>
    </a>

    <a href="#" class="req-card">
        <div class="req-icon" style="background: #fffbeb; color: #d97706;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
        </div>
        <div class="req-content">
            <div class="req-title">Cesta Básica</div>
            <div class="req-desc">Solicite auxílio alimentar</div>
        </div>
    </a>

    <a href="#" class="req-card">
        <div class="req-icon" style="background: #f0fdf4; color: #16a34a;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
        </div>
        <div class="req-content">
            <div class="req-title">Visita</div>
            <div class="req-desc">Solicite uma visita pastoral</div>
        </div>
    </a>

    <a href="#" class="req-card">
        <div class="req-icon" style="background: #fff7ed; color: #ea580c;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div class="req-content">
            <div class="req-title">Direção Pastoral</div>
            <div class="req-desc">Agende uma conversa com um pastor</div>
        </div>
    </a>
</div>

<?php $__view->endSection(); ?>
