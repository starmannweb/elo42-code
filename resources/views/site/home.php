<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<!-- Hero -->
<section class="hero" id="inicio" data-hero-mouse>
    <div class="hero__bg"></div>
    <div class="hero__overlay"></div>
    <div class="hero__mouse-light" aria-hidden="true"></div>
    <div class="container">
        <div class="hero__content">
            <div class="hero__badge">✦ Plataforma para igrejas e organizações</div>

            <h1 class="hero__title">
                Chega de improviso.<br>
                Sua missão merece escala.<br>
                Com <span>gestão, tecnologia e implantação</span> para gerar impacto real.
            </h1>

            <p class="hero__subtitle">
                <strong>Plataformas para ONGs e organizações</strong> com
                <strong>gestão de benefícios</strong>, <strong>implantação assistida</strong> e
                <strong>organização operacional</strong>, além de <strong>tráfego estratégico com ads</strong>
                (Google Ad Grants e campanhas de performance) em um só lugar.
                Estruture sua operação com clareza, controle e crescimento sustentável.
            </p>

            <div class="hero__actions">
                <div class="glowbox glowbox-active">
                    <div class="glowbox-animations">
                        <div class="glowbox-glow"></div>
                        <div class="glowbox-stars-masker">
                            <div class="glowbox-stars"></div>
                        </div>
                    </div>
                    <div class="glowbox-borders-masker">
                        <div class="glowbox-borders"></div>
                    </div>
                    <a href="#servicos">
                        <div class="btn-cta-box">
                            <div class="btn-cta">Quero conhecer o ecossistema</div>
                            <img src="https://zeph.com.br/wp-content/uploads/2023/12/seta-2.svg" class="arrow-icon" alt="Seta" />
                        </div>
                    </a>
                </div>
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

<!-- Sobre -->
<section class="section" id="sobre">
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
                <div class="card__icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="4" width="16" height="16" rx="3"></rect>
                        <path d="M8 9h8M8 12h8M8 15h5"></path>
                    </svg>
                </div>
                <h3 class="card__title">Gestão organizada</h3>
                <p class="card__text">
                    Membros, finanças, eventos, grupos, comunicação e relatórios centralizados em um painel claro e funcional.
                </p>
            </div>
            <div class="card">
                <div class="card__icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 4l7 7-7 7-7-7 7-7z"></path>
                        <path d="M12 16V8M9 11l3-3 3 3"></path>
                    </svg>
                </div>
                <h3 class="card__title">Implantação assistida</h3>
                <p class="card__text">
                    Sua organização não começa sozinha. O Elo 42 acompanha cada etapa, da configuração inicial à operação plena.
                </p>
            </div>
            <div class="card">
                <div class="card__icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3l8 4v5c0 5-3.4 8-8 9-4.6-1-8-4-8-9V7l8-4z"></path>
                        <path d="M9 12l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="card__title">Suporte contínuo</h3>
                <p class="card__text">
                    Acesso a suporte técnico, consultoria operacional e atualizações constantes sem custos extras.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Serviços — Bento Grid -->
<section class="section section--dark" id="servicos">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__badge">✦ Serviços</span>
            <h2 class="section__title">Um ecossistema completo para a sua operação</h2>
            <p class="section__subtitle">
                Cada módulo do Elo 42 resolve um problema real. Juntos, eles formam a base operacional
                que sua organização precisa para funcionar com clareza.
            </p>
        </div>

        <div class="bento-grid animate-on-scroll">
            <a href="<?= url('/servico/central-elo42') ?>" class="bento-card bento-card--central bento-card--accent">
                <div class="bento-card__icon bento-card__icon--bolt"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>
                <h3 class="bento-card__title">Central Elo 42</h3>
                <p class="bento-card__text">O Hub central onde líderes e gestores acessam recursos, relatórios, implantação e acompanhamentos da organização em tempo real.</p>
            </a>

            <a href="<?= url('/servico/plataforma-gestao') ?>" class="bento-card bento-card--large bento-card--platform bento-card--accent">
                <div class="bento-card__body">
                    <div class="bento-card__icon bento-card__icon--check"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                    <h3 class="bento-card__title">Plataforma de Gestão</h3>
                    <p class="bento-card__text">Cadastro de membros, controle financeiro, agenda de eventos, grupos, comunicação interna e relatórios em um só lugar.</p>
                </div>
                <div class="bento-card__image">
                    <img src="<?= url('/assets/img/dashboard.png') ?>" alt="Plataforma de Gestão" />
                </div>
            </a>

            <a href="<?= url('/servico/google-ad-grants') ?>" class="bento-card bento-card--tall bento-card--ads">
                <div class="bento-card__image bento-card__image--cover">
                    <img src="<?= url('/assets/img/ads.png') ?>" alt="Google para Nonprofits" />
                </div>
                <div class="bento-card__icon bento-card__icon--megaphone"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg></div>
                <h3 class="bento-card__title">Google para Nonprofits</h3>
                <p class="bento-card__text">Alcance mais pessoas com até US$ 10.000/mês em anúncios gratuitos do Google Ad Grants, com campanhas focadas no seu ministério.</p>
            </a>

            <a href="<?= url('/servico/expositor-ia') ?>" class="bento-card bento-card--expositor">
                <div class="bento-card__icon bento-card__icon--book"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
                <h3 class="bento-card__title">Central Pastoral IA</h3>
                <p class="bento-card__text">Ambiente de aprofundamento exegético com IA para pastores, onde a exegese vem primeiro e o sermão nasce do texto.</p>
            </a>

            <a href="<?= url('/servico/consultoria') ?>" class="bento-card bento-card--consultoria bento-card--accent">
                <div class="bento-card__icon bento-card__icon--lightbulb"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/></svg></div>
                <h3 class="bento-card__title">Consultoria <span class="bento-badge">(em breve)</span></h3>
                <p class="bento-card__text">Consultoria estratégica e operacional sob medida para organizações que precisam de orientação qualificada e contínua.</p>
            </a>

            <a href="<?= url('/servico/sites-prontos') ?>" class="bento-card bento-card--wide bento-card--sites">
                <div class="bento-card__content-row">
                    <div>
                        <div class="bento-card__icon bento-card__icon--globe"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></div>
                        <h3 class="bento-card__title">Site para igrejas</h3>
                        <p class="bento-card__text">Sites profissionais e responsivos, prontos para publicação, com a identidade visual e informações da sua organização.</p>
                    </div>
                    <div class="bento-card__image bento-card__image--side">
                        <img src="<?= url('/assets/img/ipporto.png') ?>" alt="Site para igrejas" />
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Projetos / Como funciona -->
<section class="section" id="projetos">
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
                <button class="steps__tab active" data-target="step-1">Cadastre-se</button>
                <button class="steps__tab" data-target="step-2">Ative seus recursos</button>
                <button class="steps__tab" data-target="step-3">Centralize tudo</button>
            </div>

            <div id="step-1" class="steps__content active">
                <div class="step-card" style="max-width: 720px; margin: 0 auto;">
                    <div class="step-card__number">1</div>
                    <div>
                        <h3 class="step-card__title">Cadastre sua organização</h3>
                        <p class="step-card__text" style="max-width: none; text-align: justify;">
                            Crie sua conta, registre sua organização e defina seu perfil.
                            Em poucos minutos, você já terá acesso ao painel completo e poderá
                            iniciar a configuração com apoio da equipe Elo 42.
                        </p>
                    </div>
                </div>
            </div>

            <div id="step-2" class="steps__content">
                <div class="step-card" style="max-width: 720px; margin: 0 auto;">
                    <div class="step-card__number">2</div>
                    <div>
                        <h3 class="step-card__title">Ative seus recursos</h3>
                        <p class="step-card__text" style="max-width: none; text-align: justify;">
                            Escolha os módulos que fazem sentido para a sua realidade:
                            gestão de membros, finanças, eventos, comunicação, benefícios e mais.
                            Cada módulo é ativado sob demanda, sem obrigatoriedade.
                        </p>
                    </div>
                </div>
            </div>

            <div id="step-3" class="steps__content">
                <div class="step-card" style="max-width: 720px; margin: 0 auto;">
                    <div class="step-card__number">3</div>
                    <div>
                        <h3 class="step-card__title">Centralize sua operação</h3>
                        <p class="step-card__text" style="max-width: none; text-align: justify;">
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

<!-- Funcionalidades -->
<section class="section section--dark" id="funcionalidades">
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
                <div class="diff-card__icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="8"></circle>
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M12 2v2M12 20v2M2 12h2M20 12h2"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="diff-card__title">Foco em operação real</h4>
                    <p class="diff-card__text">
                        Não entregamos tecnologia solta. Entregamos uma plataforma pensada para resolver
                        problemas reais de gestão, com implantação guiada e suporte contínuo.
                    </p>
                </div>
            </div>

            <div class="diff-card">
                <div class="diff-card__icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 13a5 5 0 0 1 0-7l1-1a5 5 0 1 1 7 7l-1 1"></path>
                        <path d="M14 11a5 5 0 0 1 0 7l-1 1a5 5 0 1 1-7-7l1-1"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="diff-card__title">Ecossistema integrado</h4>
                    <p class="diff-card__text">
                        Tudo conectado: membros, finanças, eventos, comunicação e relatórios
                        em um único ambiente, sem necessidade de integrar sistemas separados.
                    </p>
                </div>
            </div>

            <div class="diff-card">
                <div class="diff-card__icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3l8 4v5c0 5-3.4 8-8 9-4.6-1-8-4-8-9V7l8-4z"></path>
                        <path d="M9 12l2 2 4-4"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="diff-card__title">Segurança e confiança</h4>
                    <p class="diff-card__text">
                        Dados criptografados, controle de permissões por perfil,
                        logs de auditoria e conformidade com boas práticas de segurança.
                    </p>
                </div>
            </div>

            <div class="diff-card">
                <div class="diff-card__icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 16l6-6 4 4 6-6"></path>
                        <path d="M14 8h6v6"></path>
                    </svg>
                </div>
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
<section class="section" id="faq">
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
                <div class="glowbox glowbox-active glowbox--gold">
                    <div class="glowbox-animations">
                        <div class="glowbox-glow"></div>
                        <div class="glowbox-stars-masker">
                            <div class="glowbox-stars"></div>
                        </div>
                    </div>
                    <div class="glowbox-borders-masker">
                        <div class="glowbox-borders"></div>
                    </div>
                    <a href="<?= url('/cadastro') ?>">
                        <div class="btn-cta-box">
                            <div class="btn-cta">Criar a minha conta</div>
                            <img src="https://zeph.com.br/wp-content/uploads/2023/12/seta-2.svg" class="arrow-icon" alt="Seta" />
                        </div>
                    </a>
                </div>
                <a href="<?= url('/contato') ?>" class="btn btn--outline-light btn--lg">Falar com um especialista</a>
            </div>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
