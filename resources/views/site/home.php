<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<!-- Hero -->
<section class="hero" id="inicio">
    <div class="hero__bg"></div>
    <div class="hero__overlay"></div>
    <div class="hero__fx" aria-hidden="true">
        <span class="hero__light hero__light--one"></span>
        <span class="hero__light hero__light--two"></span>
        <span class="hero__light hero__light--three"></span>
    </div>
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
                            <div class="btn-cta">Quero falar com um especialista</div>
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
                <p class="bento-card__text">O hub central onde lideres e gestores acessam todos os recursos, relatorios e acompanhamentos da organizacao em tempo real.</p>
            </a>

            <a href="<?= url('/servico/plataforma-gestao') ?>" class="bento-card bento-card--large bento-card--platform bento-card--accent">
                <div class="bento-card__body">
                    <div class="bento-card__icon bento-card__icon--check"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
                    <h3 class="bento-card__title">Plataforma de Gestao</h3>
                    <p class="bento-card__text">Cadastro de membros, controle financeiro, agenda de eventos, grupos, comunicacao interna e relatorios em um so lugar.</p>
                </div>
                <div class="bento-card__image">
                    <img src="<?= url('/assets/img/dashboard.png') ?>" alt="Plataforma de Gestao" />
                </div>
            </a>

            <a href="<?= url('/servico/google-ad-grants') ?>" class="bento-card bento-card--tall bento-card--ads">
                <div class="bento-card__image bento-card__image--cover">
                    <img src="<?= url('/assets/img/ads.png') ?>" alt="Google para Nonprofits" />
                </div>
                <div class="bento-card__icon bento-card__icon--megaphone"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg></div>
                <h3 class="bento-card__title">Google para Nonprofits</h3>
                <p class="bento-card__text">Alcance mais pessoas com ate US$ 10.000/mes em anuncios gratuitos do Google Ad Grants, estruturamos campanhas focadas no seu ministerio.</p>
            </a>

            <a href="<?= url('/servico/hokmah-expositor') ?>" class="bento-card bento-card--expositor">
                <div class="bento-card__icon bento-card__icon--book"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
                <h3 class="bento-card__title">Expositor de IA</h3>
                <p class="bento-card__text">Ambiente de aprofundamento exegetico com IA para pastores, onde a exegese vem primeiro e o sermao nasce do texto.</p>
            </a>

            <a href="<?= url('/servico/consultoria') ?>" class="bento-card bento-card--consultoria bento-card--accent">
                <div class="bento-card__icon bento-card__icon--lightbulb"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/></svg></div>
                <h3 class="bento-card__title">Consultoria <span class="bento-badge">(em breve)</span></h3>
                <p class="bento-card__text">Consultoria estrategica e operacional sob medida para organizacoes que precisam de orientacao qualificada e continua.</p>
            </a>

            <a href="<?= url('/servico/sites-prontos') ?>" class="bento-card bento-card--wide bento-card--sites">
                <div class="bento-card__content-row">
                    <div>
                        <div class="bento-card__icon bento-card__icon--globe"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></div>
                        <h3 class="bento-card__title">Sites para Igrejas</h3>
                        <p class="bento-card__text">Sites profissionais e responsivos, prontos para publicacao, com a identidade visual e informacoes da sua organizacao.</p>
                    </div>
                    <div class="bento-card__image bento-card__image--side">
                        <img src="<?= url('/assets/img/ipporto.png') ?>" alt="Sites para Igrejas" />
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
                    <a href="<?= url('/contato') ?>">
                        <div class="btn-cta-box">
                            <div class="btn-cta">Falar com um especialista</div>
                            <img src="https://zeph.com.br/wp-content/uploads/2023/12/seta-2.svg" class="arrow-icon" alt="Seta" />
                        </div>
                    </a>
                </div>
                <a href="<?= url('/cadastro') ?>" class="btn btn--outline-light btn--lg">Criar minha conta</a>
            </div>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
