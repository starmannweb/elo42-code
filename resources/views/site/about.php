<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <h1 class="page-hero__title">Sobre o Elo 42</h1>
            <p class="page-hero__subtitle">
                Uma plataforma criada com propósito: dar estrutura, tecnologia e acompanhamento
                para igrejas e organizações que querem operar com mais clareza e eficiência.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="about-intro animate-on-scroll">
            <div>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-6);">
                    Nascemos de uma necessidade real
                </h2>
                <p class="about-intro__text">
                    O Elo 42 surgiu da observação direta dos desafios que igrejas e organizações enfrentam
                    no dia a dia: processos manuais, informações dispersas, falta de acompanhamento e
                    ferramentas desconectadas que não conversam entre si.
                </p>
                <p class="about-intro__text" style="margin-top: var(--space-4);">
                    Criamos uma plataforma que não é apenas uma ferramenta — é um ecossistema completo
                    de gestão, implantação e suporte, construído por quem entende a realidade de quem lidera.
                </p>
            </div>
            <div class="about-intro__visual">
                <span style="font-size: 4rem; color: var(--color-gold);">✦</span>
            </div>
        </div>
    </div>
</section>

<section class="section section--light">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__badge">✦ Nossos valores</span>
            <h2 class="section__title">O que nos guia</h2>
        </div>

        <div class="about-values animate-on-scroll">
            <div class="about-value-card">
                <div class="about-value-card__icon">🎯</div>
                <h3 class="card__title">Clareza</h3>
                <p class="card__text">Processos claros, dashboards legíveis, comunicação direta. Nada é confuso por aqui.</p>
            </div>
            <div class="about-value-card">
                <div class="about-value-card__icon">🏗️</div>
                <h3 class="card__title">Estrutura</h3>
                <p class="card__text">Tudo tem seu lugar. Cada módulo, cada funcionalidade, cada dado — organizado e acessível.</p>
            </div>
            <div class="about-value-card">
                <div class="about-value-card__icon">🤝</div>
                <h3 class="card__title">Parceria</h3>
                <p class="card__text">Não entregamos tecnologia e saímos. Acompanhamos, ajustamos e crescemos junto com você.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-section__inner animate-on-scroll">
            <h2 class="cta-section__title">Conheça o que construímos para você</h2>
            <p class="cta-section__text">
                Explore as soluções da plataforma e descubra como o Elo 42 pode transformar sua operação.
            </p>
            <div class="cta-section__actions">
                <a href="<?= url('/solucoes') ?>" class="btn btn--gold btn--lg">Ver soluções</a>
                <a href="<?= url('/contato') ?>" class="btn btn--secondary btn--lg">Falar com a equipe</a>
            </div>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
