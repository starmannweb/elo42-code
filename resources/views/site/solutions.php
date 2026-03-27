<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <h1 class="page-hero__title">Soluções</h1>
            <p class="page-hero__subtitle">
                Cada módulo do Elo 42 foi desenhado para resolver um problema real da sua organização.
                Juntos, eles formam um ecossistema completo e integrado.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="solution-highlight animate-on-scroll">
            <div>
                <div class="solution-highlight__icon solution-highlight__icon--blue">🏠</div>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-4);">Central Elo 42</h2>
                <p class="card__text">
                    O ponto de partida para tudo. A Central reúne em um único painel todos os indicadores,
                    atalhos e informações que líderes e gestores precisam para acompanhar a operação da organização
                    de forma rápida e objetiva.
                </p>
                <p class="card__tag" style="margin-top: var(--space-4);">Para gestores e líderes</p>
            </div>
            <div style="background: var(--color-bg-light); border-radius: var(--radius-2xl); padding: var(--space-10); display: flex; align-items: center; justify-content: center; min-height: 280px;">
                <span style="font-size: 3rem;">🏠</span>
            </div>
        </div>

        <div class="solution-highlight animate-on-scroll">
            <div>
                <div class="solution-highlight__icon solution-highlight__icon--blue">⚙️</div>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-4);">Plataforma de Gestão</h2>
                <p class="card__text">
                    Gerencie membros, finanças, eventos, grupos, comunicação e relatórios em um único sistema.
                    Tudo integrado, sem planilhas, sem ferramentas desconectadas, sem retrabalho.
                </p>
                <p class="card__tag" style="margin-top: var(--space-4);">Para a operação diária</p>
            </div>
            <div style="background: var(--color-bg-light); border-radius: var(--radius-2xl); padding: var(--space-10); display: flex; align-items: center; justify-content: center; min-height: 280px;">
                <span style="font-size: 3rem;">⚙️</span>
            </div>
        </div>

        <div class="solution-highlight animate-on-scroll">
            <div>
                <div class="solution-highlight__icon solution-highlight__icon--gold">🎁</div>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-4);">Benefícios e Programas</h2>
                <p class="card__text">
                    Ofereça vantagens reais para seus membros e colaboradores: convênios,
                    capacitações, programas de desenvolvimento e benefícios exclusivos vinculados à sua organização.
                </p>
                <p class="card__tag" style="margin-top: var(--space-4);">Para membros e equipe</p>
            </div>
            <div style="background: var(--color-bg-light); border-radius: var(--radius-2xl); padding: var(--space-10); display: flex; align-items: center; justify-content: center; min-height: 280px;">
                <span style="font-size: 3rem;">🎁</span>
            </div>
        </div>

        <div class="solution-highlight animate-on-scroll">
            <div>
                <div class="solution-highlight__icon solution-highlight__icon--blue">📦</div>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-4);">Hub de Implantação</h2>
                <p class="card__text">
                    Cada organização recebe um processo de implantação estruturado com etapas claras, checklists,
                    marcos de progresso e acompanhamento da equipe Elo 42 do início ao fim.
                </p>
                <p class="card__tag" style="margin-top: var(--space-4);">Para novas organizações</p>
            </div>
            <div style="background: var(--color-bg-light); border-radius: var(--radius-2xl); padding: var(--space-10); display: flex; align-items: center; justify-content: center; min-height: 280px;">
                <span style="font-size: 3rem;">📦</span>
            </div>
        </div>

        <div class="solution-highlight animate-on-scroll">
            <div>
                <div class="solution-highlight__icon solution-highlight__icon--gold">💡</div>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-4);">Consultoria</h2>
                <p class="card__text">
                    Consultoria estratégica e operacional para organizações que buscam crescimento sustentável:
                    análise de processos, reestruturação administrativa, planejamento e orientação personalizada.
                </p>
                <p class="card__tag" style="margin-top: var(--space-4);">Para líderes e diretoria</p>
            </div>
            <div style="background: var(--color-bg-light); border-radius: var(--radius-2xl); padding: var(--space-10); display: flex; align-items: center; justify-content: center; min-height: 280px;">
                <span style="font-size: 3rem;">💡</span>
            </div>
        </div>

        <div class="solution-highlight animate-on-scroll">
            <div>
                <div class="solution-highlight__icon solution-highlight__icon--blue">🌐</div>
                <h2 style="font-size: var(--text-3xl); margin-bottom: var(--space-4);">Sites Prontos</h2>
                <p class="card__text">
                    Sites profissionais e responsivos, prontos para publicação, com a identidade visual
                    da sua organização. Sem necessidade de contratar agência ou designer externo.
                </p>
                <p class="card__tag" style="margin-top: var(--space-4);">Para presença digital</p>
            </div>
            <div style="background: var(--color-bg-light); border-radius: var(--radius-2xl); padding: var(--space-10); display: flex; align-items: center; justify-content: center; min-height: 280px;">
                <span style="font-size: 3rem;">🌐</span>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-section__inner animate-on-scroll">
            <h2 class="cta-section__title">Qual solução faz mais sentido para você?</h2>
            <p class="cta-section__text">
                Fale com nossa equipe e descubra o melhor caminho para começar.
            </p>
            <div class="cta-section__actions">
                <a href="<?= url('/contato') ?>" class="btn btn--gold btn--lg">Falar com um especialista</a>
            </div>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
