<?php $__view->extends('layouts/portal'); ?>

<?php $__view->section('content'); ?>
<style>
    .events-subtitle {
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 2rem;
    }
    .date-scroller {
        display: flex;
        gap: 0.75rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        scrollbar-width: none;
    }
    .date-scroller::-webkit-scrollbar {
        display: none;
    }
    .date-box {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        min-width: 64px;
        padding: 0.75rem 0.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .date-box:hover {
        border-color: #d1d5db;
        background: #f9fafb;
    }
    .date-box.active {
        background: #f3f4f6;
        border-color: #d1d5db;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
    }
    .date-weekday {
        font-size: 0.65rem;
        text-transform: uppercase;
        font-weight: 600;
        color: #6b7280;
        letter-spacing: 0.05em;
    }
    .date-day {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        font-family: 'Playfair Display', serif;
    }
    .date-month {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
    }
    
    .filter-scroller {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        scrollbar-width: none;
    }
    .filter-scroller::-webkit-scrollbar {
        display: none;
    }
    .filter-btn {
        background: #fff;
        border: 1px solid #f3f4f6;
        color: #4b5563;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        cursor: pointer;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .filter-btn.active {
        background: #1e3a8a;
        color: white;
        border-color: #1e3a8a;
    }
    .filter-btn svg {
        width: 14px;
        height: 14px;
    }
    
    .empty-state {
        background: #fff;
        border: 1px dashed #e5e7eb;
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
        color: #6b7280;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
</style>

<div class="events-subtitle">Cultos, eventos e reuniões</div>

<div class="date-scroller">
    <div class="date-box active">
        <span class="date-weekday">HOJE</span>
        <span class="date-day">9</span>
        <span class="date-month">ABR</span>
    </div>
    <div class="date-box">
        <span class="date-weekday">SEXTA</span>
        <span class="date-day">10</span>
        <span class="date-month">ABR</span>
    </div>
    <div class="date-box">
        <span class="date-weekday">SÁBADO</span>
        <span class="date-day">11</span>
        <span class="date-month">ABR</span>
    </div>
    <div class="date-box">
        <span class="date-weekday">DOMINGO</span>
        <span class="date-day">12</span>
        <span class="date-month">ABR</span>
    </div>
    <div class="date-box">
        <span class="date-weekday">SEGUNDA</span>
        <span class="date-day">13</span>
        <span class="date-month">ABR</span>
    </div>
    <div class="date-box">
        <span class="date-weekday">TERÇA</span>
        <span class="date-day">14</span>
        <span class="date-month">ABR</span>
    </div>
    <div class="date-box">
        <span class="date-weekday">QUARTA</span>
        <span class="date-day">15</span>
        <span class="date-month">ABR</span>
    </div>
    <div class="date-box">
        <span class="date-weekday">QUINTA</span>
        <span class="date-day">16</span>
        <span class="date-month">ABR</span>
    </div>
    <div class="date-box">
        <span class="date-weekday">SEXTA</span>
        <span class="date-day">17</span>
        <span class="date-month">ABR</span>
    </div>
</div>

<div class="filter-scroller">
    <button class="filter-btn active">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg> Todos
    </button>
    <button class="filter-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg> Culto
    </button>
    <button class="filter-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> Célula
    </button>
    <button class="filter-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg> Conferência
    </button>
    <button class="filter-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> Retiro
    </button>
    <button class="filter-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg> Louvor
    </button>
    <button class="filter-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg> Especial
    </button>
</div>

<div class="empty-state">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
    <div>Nenhum evento programado.</div>
</div>

<?php $__view->endSection(); ?>
