<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<!-- Hero -->
<section class="hero">
    <div class="hero__grid"></div>
    <div class="container">
        <div class="hero__content">
            <div class="hero__badge">✦ Plataforma para igrejas e organizações</div>

            <h1 class="hero__title">
                Menos improviso.<br>
                Mais <span>gestão, tecnologia e impacto</span> para a sua missão.
            </h1>

            <p class="hero__subtitle">
                A Elo 42 reúne implantação, benefícios, suporte e gestão em uma plataforma feita
                para igrejas e organizações que precisam operar com mais ordem, clareza e eficiência.
            </p>

            <div class="hero__actions">
                <a href="<?= url('/contato') ?>" class="btn btn--primary btn--lg">Falar com um especialista</a>
                <a href="<?= url('/plataforma') ?>" class="btn btn--secondary btn--lg">Conhecer a plataforma</a>
            </div>

            <div class="hero__stats">
                <div>
                    <div class="hero__stat-value">100%</div>
                    <div class="hero__stat-label">Centralizado</div>
                </div>
                <div>
                    <div class="hero__stat-value">6+</div>
                    <div class="hero__stat-label">Módulos integrados</div>
                </div>
                <div>
                    <div class="hero__stat-value">24/7</div>
                    <div class="hero__stat-label">Suporte disponível</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- O que é o Elo 42? -->
<section class="section">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__badge">✦ Sobre a plataforma</span>
            <h2 class="section__title">O que é o Elo 42?</h2>
            <p class="section__subtitle">
                O Elo 42 é um ecossistema completo que centraliza gestão, implantação, benefícios,
                suporte e acompanhamento para organizações que não podem depender de soluções fragmentadas.
                Tudo em uma única plataforma, com estrutura real e acompanhamento contínuo.
            </p>
        </div>

        <div class="grid grid--3 animate-on-scroll">
            <div class="card">
                <div class="card__icon">📋</div>
                <h3 class="card__title">Gestão organizada</h3>
                <p class="card__text">
                    Membros, finanças, eventos, grupos, comunicação e relatórios centralizados em um painel claro e funcional.
                </p>
            </div>
            <div class="card">
                <div class="card__icon">🚀</div>
                <h3 class="card__title">Implantação assistida</h3>
                <p class="card__text">
                    Sua organização não começa sozinha. O Elo 42 acompanha cada etapa, da configuração inicial à operação plena.
                </p>
            </div>
            <div class="card">
                <div class="card__icon">🤝</div>
                <h3 class="card__title">Suporte contínuo</h3>
                <p class="card__text">
                    Acesso a suporte técnico, consultoria operacional e atualizações constantes sem custos extras.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Soluções em Cards -->
<section class="section section--light">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__badge">✦ Soluções</span>
            <h2 class="section__title">Um ecossistema completo para a sua operação</h2>
            <p class="section__subtitle">
                Cada módulo do Elo 42 resolve um problema real. Juntos, eles formam a base operacional
                que sua organização precisa para funcionar com clareza.
            </p>
        </div>

        <div class="grid grid--3 animate-on-scroll">
            <div class="card">
                <div class="card__icon">🏠</div>
                <h3 class="card__title">Central Elo 42</h3>
                <p class="card__text">
                    O hub central onde líderes e gestores acessam todos os recursos, relatórios e acompanhamentos da organização em tempo real.
                </p>
                <span class="card__tag">Para gestores e líderes</span>
            </div>

            <div class="card">
                <div class="card__icon">⚙️</div>
                <h3 class="card__title">Plataforma de Gestão</h3>
                <p class="card__text">
                    Cadastro de membros, controle financeiro, agenda de eventos, grupos, comunicação interna e relatórios em um só lugar.
                </p>
                <span class="card__tag">Para a operação diária</span>
            </div>

            <div class="card">
                <div class="card__icon">🎁</div>
                <h3 class="card__title">Benefícios e Programas</h3>
                <p class="card__text">
                    Programas exclusivos, convênios, capacitações e vantagens para membros e colaboradores vinculados à sua organização.
                </p>
                <span class="card__tag">Para membros e equipe</span>
            </div>

            <div class="card">
                <div class="card__icon">📦</div>
                <h3 class="card__title">Hub de Implantação</h3>
                <p class="card__text">
                    Acompanhamento passo a passo da implantação. Checklists, marcos, reuniões e documentação para cada fase do processo.
                </p>
                <span class="card__tag">Para novas organizações</span>
            </div>

            <div class="card">
                <div class="card__icon">💡</div>
                <h3 class="card__title">Consultoria</h3>
                <p class="card__text">
                    Consultoria estratégica e operacional sob medida para organizações que precisam de orientação qualificada e contínua.
                </p>
                <span class="card__tag">Para líderes e diretoria</span>
            </div>

            <div class="card">
                <div class="card__icon">🌐</div>
                <h3 class="card__title">Sites prontos</h3>
                <p class="card__text">
                    Sites profissionais e responsivos, prontos para publicação, com a identidade visual e informações da sua organização.
                </p>
                <span class="card__tag">Para presença digital</span>
            </div>
        </div>
    </div>
</section>

<!-- Como funciona -->
<section class="section">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__badge">✦ Como funciona</span>
            <h2 class="section__title">Três passos para transformar sua operação</h2>
            <p class="section__subtitle">
                Começar com o Elo 42 é simples, rápido e acompanhado do início ao fim.
            </p>
        </div>

        <div class="steps animate-on-scroll">
            <div class="steps__tabs">
                <button class="steps__tab active" data-target="step-1">1. Cadastre-se</button>
                <button class="steps__tab" data-target="step-2">2. Ative seus recursos</button>
                <button class="steps__tab" data-target="step-3">3. Centralize tudo</button>
            </div>

            <div id="step-1" class="steps__content active">
                <div class="step-card" style="justify-content: center;">
                    <div class="step-card__number">1</div>
                    <div>
                        <h3 class="step-card__title">Cadastre sua organização</h3>
                        <p class="step-card__text">
                            Crie sua conta, registre sua organização e defina seu perfil.
                            Em poucos minutos, você já terá acesso ao painel completo e poderá
                            iniciar a configuração com apoio da equipe Elo 42.
                        </p>
                    </div>
                </div>
            </div>

            <div id="step-2" class="steps__content">
                <div class="step-card" style="justify-content: center;">
                    <div class="step-card__number">2</div>
                    <div>
                        <h3 class="step-card__title">Ative seus recursos</h3>
                        <p class="step-card__text">
                            Escolha os módulos que fazem sentido para a sua realidade:
                            gestão de membros, finanças, eventos, comunicação, benefícios e mais.
                            Cada módulo é ativado sob demanda, sem obrigatoriedade.
                        </p>
                    </div>
                </div>
            </div>

            <div id="step-3" class="steps__content">
                <div class="step-card" style="justify-content: center;">
                    <div class="step-card__number">3</div>
                    <div>
                        <h3 class="step-card__title">Centralize sua operação</h3>
                        <p class="step-card__text">
                            Com tudo em um só lugar, sua equipe opera com mais foco,
                            sua liderança toma decisões com dados e sua organização
                            cresce com estrutura de verdade.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Diferenciais -->
<section class="section section--dark">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__badge">✦ Diferenciais</span>
            <h2 class="section__title">Por que o Elo 42 é diferente</h2>
            <p class="section__subtitle">
                Não somos apenas mais uma ferramenta. Somos um parceiro de operação
                comprometido com a estrutura, a clareza e o crescimento da sua organização.
            </p>
        </div>

        <div class="diff-grid animate-on-scroll">
            <div class="diff-card">
                <div class="diff-card__icon">🎯</div>
                <div>
                    <h4 class="diff-card__title">Foco em operação real</h4>
                    <p class="diff-card__text">
                        Não entregamos tecnologia solta. Entregamos uma plataforma pensada para resolver
                        problemas reais de gestão, com implantação guiada e suporte contínuo.
                    </p>
                </div>
            </div>

            <div class="diff-card">
                <div class="diff-card__icon">🔗</div>
                <div>
                    <h4 class="diff-card__title">Ecossistema integrado</h4>
                    <p class="diff-card__text">
                        Tudo conectado: membros, finanças, eventos, comunicação e relatórios
                        em um único ambiente, sem necessidade de integrar sistemas separados.
                    </p>
                </div>
            </div>

            <div class="diff-card">
                <div class="diff-card__icon">🛡️</div>
                <div>
                    <h4 class="diff-card__title">Segurança e confiança</h4>
                    <p class="diff-card__text">
                        Dados criptografados, controle de permissões por perfil,
                        logs de auditoria e conformidade com boas práticas de segurança.
                    </p>
                </div>
            </div>

            <div class="diff-card">
                <div class="diff-card__icon">📈</div>
                <div>
                    <h4 class="diff-card__title">Cresce com você</h4>
                    <p class="diff-card__text">
                        A plataforma se adapta ao tamanho e à maturidade da sua organização.
                        Comece com o essencial e ative novos recursos à medida que precisar.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="section">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__badge">✦ FAQ</span>
            <h2 class="section__title">Perguntas frequentes</h2>
        </div>

        <div class="faq-list animate-on-scroll">
            <div class="faq-item">
                <button class="faq-item__question">
                    O Elo 42 é indicado para qual tipo de organização?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    O Elo 42 foi projetado para igrejas, ministérios, associações e organizações do terceiro setor
                    que precisam de uma estrutura completa de gestão, implantação e acompanhamento.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Preciso ter conhecimento técnico para usar a plataforma?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Não. A plataforma foi desenhada para ser intuitiva e acessível. Além disso, o processo
                    de implantação é acompanhado por nossa equipe, garantindo que tudo esteja configurado corretamente.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    A implantação é feita pela equipe Elo 42?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Sim. Cada organização recebe acompanhamento durante todo o processo de implantação,
                    com checklists, reuniões de alinhamento e suporte técnico dedicado.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Quais módulos estão inclusos?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    A plataforma inclui gestão de membros, finanças, eventos, comunicação interna, grupos,
                    relatórios, benefícios, implantação assistida, consultoria e sites prontos. Cada módulo
                    pode ser ativado conforme a necessidade da organização.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Meus dados ficam seguros?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Sim. Utilizamos criptografia, controle de acesso por perfil, logs de auditoria
                    e seguimos as melhores práticas de segurança da informação.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Como funciona o suporte?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    O suporte é contínuo e está disponível através da própria plataforma.
                    Você pode abrir chamados, acessar a base de conhecimento e, nos planos
                    avançados, ter atendimento prioritário.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Final -->
<section class="cta-section">
    <div class="container">
        <div class="cta-section__inner animate-on-scroll">
            <h2 class="cta-section__title">
                Pronto para estruturar sua operação?
            </h2>
            <p class="cta-section__text">
                Fale com nossa equipe e descubra como o Elo 42 pode transformar a gestão
                da sua organização com tecnologia, acompanhamento e clareza.
            </p>
            <div class="cta-section__actions">
                <a href="<?= url('/contato') ?>" class="btn btn--gold btn--lg">Falar com um especialista</a>
                <a href="<?= url('/cadastro') ?>" class="btn btn--secondary btn--lg">Criar minha conta</a>
            </div>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
