<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <h1 class="page-hero__title">Plataforma de Gestão</h1>
            <p class="page-hero__subtitle">
                Membros, finanças, eventos, comunicação, grupos e relatórios.
                Tudo o que sua organização precisa para operar, em um único lugar.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__badge">✦ Módulos de gestão</span>
            <h2 class="section__title">Tudo que você precisa, integrado e acessível</h2>
        </div>

        <div class="platform-features animate-on-scroll">
            <div class="platform-card">
                <div class="card__icon">👥</div>
                <h3 class="card__title">Gestão de Membros</h3>
                <p class="card__text">
                    Cadastro completo, perfis individuais, histórico de participação,
                    dados de contato e segmentação por grupo ou departamento.
                </p>
            </div>

            <div class="platform-card">
                <div class="card__icon">💰</div>
                <h3 class="card__title">Controle Financeiro</h3>
                <p class="card__text">
                    Receitas, despesas, dízimos, ofertas, relatórios financeiros,
                    categorização e acompanhamento mensal com visão clara de fluxo.
                </p>
            </div>

            <div class="platform-card">
                <div class="card__icon">📅</div>
                <h3 class="card__title">Agenda e Eventos</h3>
                <p class="card__text">
                    Calendário integrado, criação de eventos, controle de inscrições,
                    check-in e notificações automáticas para participantes.
                </p>
            </div>

            <div class="platform-card">
                <div class="card__icon">💬</div>
                <h3 class="card__title">Comunicação</h3>
                <p class="card__text">
                    Envio de avisos, notificações internas, comunicados e mensagens
                    segmentadas por grupo, ministério ou departamento.
                </p>
            </div>

            <div class="platform-card">
                <div class="card__icon">👨‍👩‍👧‍👦</div>
                <h3 class="card__title">Grupos e Células</h3>
                <p class="card__text">
                    Organização de pequenos grupos, células, departamentos e ministérios
                    com líderes designados e acompanhamento de atividades.
                </p>
            </div>

            <div class="platform-card">
                <div class="card__icon">📊</div>
                <h3 class="card__title">Relatórios</h3>
                <p class="card__text">
                    Dashboards com indicadores essenciais, relatórios exportáveis
                    e visão consolidada do desempenho da organização.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-section__inner animate-on-scroll">
            <h2 class="cta-section__title">Quer ver a plataforma em ação?</h2>
            <p class="cta-section__text">
                Solicite uma demonstração e conheça cada módulo de perto.
            </p>
            <div class="cta-section__actions">
                <a href="<?= url('/contato') ?>" class="btn btn--gold btn--lg">Solicitar demonstração</a>
            </div>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
