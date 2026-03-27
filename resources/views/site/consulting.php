<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <h1 class="page-hero__title">Consultoria</h1>
            <p class="page-hero__subtitle">
                Orientação especializada para organizações que buscam ordem, estrutura e crescimento sustentável.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="about-intro animate-on-scroll">
            <div>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-6);">
                    Mais do que tecnologia: apoio estratégico
                </h2>
                <p class="about-intro__text">
                    Muitas organizações enfrentam desafios que vão além de uma boa ferramenta.
                    Falta de processos, ausência de dados para decisão, acúmulo de responsabilidades
                    na liderança — são problemas que exigem orientação qualificada.
                </p>
                <p class="about-intro__text" style="margin-top: var(--space-4);">
                    A consultoria do Elo 42 oferece análise, diagnóstico e plano de ação para organizações
                    que querem sair do improviso e operar com estrutura profissional.
                </p>
            </div>
            <div class="about-intro__visual">
                <span style="font-size: 4rem; color: var(--color-gold);">💡</span>
            </div>
        </div>
    </div>
</section>

<section class="section section--light">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__badge">✦ Como funciona</span>
            <h2 class="section__title">Processo de consultoria</h2>
        </div>

        <div class="consulting-process animate-on-scroll">
            <div class="consulting-step">
                <div class="consulting-step__dot"></div>
                <h3 class="card__title">Diagnóstico inicial</h3>
                <p class="card__text">Avaliamos a situação atual da organização: processos, estrutura, equipe, finanças e comunicação.</p>
            </div>

            <div class="consulting-step">
                <div class="consulting-step__dot"></div>
                <h3 class="card__title">Mapeamento de oportunidades</h3>
                <p class="card__text">Identificamos gargalos, riscos e oportunidades de melhoria com base na análise de dados e entrevistas.</p>
            </div>

            <div class="consulting-step">
                <div class="consulting-step__dot"></div>
                <h3 class="card__title">Plano de ação</h3>
                <p class="card__text">Entregamos um plano estruturado com recomendações práticas, prioridades e cronograma de implementação.</p>
            </div>

            <div class="consulting-step">
                <div class="consulting-step__dot"></div>
                <h3 class="card__title">Acompanhamento contínuo</h3>
                <p class="card__text">Não paramos no plano. Acompanhamos a execução, ajustamos o que for necessário e garantimos resultados reais.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-section__inner animate-on-scroll">
            <h2 class="cta-section__title">Precisa de orientação para sua organização?</h2>
            <p class="cta-section__text">
                Converse com nosso time e descubra como a consultoria Elo 42 pode ajudar.
            </p>
            <div class="cta-section__actions">
                <a href="<?= url('/contato') ?>" class="btn btn--gold btn--lg">Solicitar consultoria</a>
            </div>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
