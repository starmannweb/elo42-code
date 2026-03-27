<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <h1 class="page-hero__title">Funcionalidades</h1>
            <p class="page-hero__subtitle">
                Explore os recursos que fazem do Elo 42 a plataforma mais completa
                para gestão de igrejas e organizações.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="feature-row animate-on-scroll">
            <div class="feature-row__content">
                <span class="section__badge">Membros</span>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-4);">Gestão completa de membros</h2>
                <p class="card__text">
                    Cadastro com dados pessoais, foto, histórico de participação, grupos vinculados,
                    data de ingresso e status. Visualize, edite e segmente sua comunidade com facilidade.
                </p>
            </div>
            <div class="feature-row__visual">
                <span style="font-size: 3rem;">👥</span>
            </div>
        </div>

        <div class="feature-row animate-on-scroll">
            <div class="feature-row__content">
                <span class="section__badge">Finanças</span>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-4);">Controle financeiro transparente</h2>
                <p class="card__text">
                    Registre receitas e despesas, categorize por tipo (dízimo, oferta, doação), acompanhe
                    o fluxo de caixa mensal e gere relatórios financeiros prontos para prestação de contas.
                </p>
            </div>
            <div class="feature-row__visual">
                <span style="font-size: 3rem;">💰</span>
            </div>
        </div>

        <div class="feature-row animate-on-scroll">
            <div class="feature-row__content">
                <span class="section__badge">Eventos</span>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-4);">Agenda e eventos integrados</h2>
                <p class="card__text">
                    Crie eventos com data, local, inscrição online e limite de vagas. Faça check-in dos
                    participantes e envie notificações automáticas por email ou pela plataforma.
                </p>
            </div>
            <div class="feature-row__visual">
                <span style="font-size: 3rem;">📅</span>
            </div>
        </div>

        <div class="feature-row animate-on-scroll">
            <div class="feature-row__content">
                <span class="section__badge">Comunicação</span>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-4);">Comunicação segmentada</h2>
                <p class="card__text">
                    Envie comunicados, avisos e notificações para toda a organização ou apenas para grupos
                    específicos. Mantenha todos informados de forma organizada e eficiente.
                </p>
            </div>
            <div class="feature-row__visual">
                <span style="font-size: 3rem;">💬</span>
            </div>
        </div>

        <div class="feature-row animate-on-scroll">
            <div class="feature-row__content">
                <span class="section__badge">Grupos</span>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-4);">Grupos, células e ministérios</h2>
                <p class="card__text">
                    Organize sua comunidade em grupos com líderes designados. Acompanhe atividades,
                    frequência e engajamento de cada célula ou ministério.
                </p>
            </div>
            <div class="feature-row__visual">
                <span style="font-size: 3rem;">👨‍👩‍👧‍👦</span>
            </div>
        </div>

        <div class="feature-row animate-on-scroll">
            <div class="feature-row__content">
                <span class="section__badge">Relatórios</span>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-4);">Dashboards e relatórios</h2>
                <p class="card__text">
                    Indicadores visuais sobre crescimento, finanças, engajamento e operação.
                    Tome decisões com base em dados reais, não em suposições.
                </p>
            </div>
            <div class="feature-row__visual">
                <span style="font-size: 3rem;">📊</span>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-section__inner animate-on-scroll">
            <h2 class="cta-section__title">Quer ver todas as funcionalidades em ação?</h2>
            <p class="cta-section__text">
                Solicite uma demonstração personalizada com nossa equipe.
            </p>
            <div class="cta-section__actions">
                <a href="<?= url('/contato') ?>" class="btn btn--gold btn--lg">Solicitar demonstração</a>
                <a href="<?= url('/cadastro') ?>" class="btn btn--secondary btn--lg">Criar minha conta</a>
            </div>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
