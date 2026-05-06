<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>

<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Integrações</h1>
            <p class="mgmt-subtitle">As integrações globais do ecossistema são administradas pelo gestor master DEV.</p>
        </div>
    </div>

    <section class="mgmt-panel integration-admin-notice">
        <div class="integration-admin-notice__icon" aria-hidden="true">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 13a5 5 0 0 0 7.1 0l2.8-2.8a5 5 0 0 0-7.1-7.1L11 4.9"></path>
                <path d="M14 11a5 5 0 0 0-7.1 0L4.1 13.8a5 5 0 0 0 7.1 7.1L13 19.1"></path>
            </svg>
        </div>
        <div>
            <h2 class="mgmt-panel__title">Configuração centralizada no Admin Master</h2>
            <p class="mgmt-subtitle">
                Pagou, chaves de pagamento, webhooks, provedores de comunicação e parâmetros de IA não ficam mais nesta área da igreja.
                Esses dados alimentam todos os assinantes do Hub e devem ser mantidos em <strong>Admin &gt; Configurações</strong>.
            </p>
        </div>
    </section>
</div>

<style>
    .integration-admin-notice {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr);
        gap: 1rem;
        align-items: flex-start;
        max-width: 920px;
    }
    .integration-admin-notice__icon {
        width: 56px;
        height: 56px;
        display: grid;
        place-items: center;
        border-radius: 16px;
        background: rgba(10, 77, 255, .1);
        color: var(--color-bright-blue, #0a4dff);
    }
    @media (max-width: 640px) {
        .integration-admin-notice {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php $__view->endSection(); ?>
