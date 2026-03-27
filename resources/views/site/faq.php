<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <h1 class="page-hero__title">Perguntas Frequentes</h1>
            <p class="page-hero__subtitle">
                Se você tem dúvidas sobre o Elo 42, é provável que encontre a resposta aqui.
                Se não, fale conosco — estamos à disposição.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="faq-list animate-on-scroll">
            <div class="faq-item">
                <button class="faq-item__question">
                    O Elo 42 é indicado para qual tipo de organização?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    O Elo 42 foi construído para igrejas, ministérios, associações e organizações do terceiro setor.
                    Se sua organização precisa de gestão centralizada, acompanhamento e estrutura operacional, a plataforma é para você.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Preciso de conhecimento técnico para usar?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Não. A plataforma foi projetada para ser intuitiva. Qualquer líder, secretário ou colaborador consegue
                    usar com facilidade. Além disso, o processo de implantação inclui treinamento e acompanhamento.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Como funciona a implantação?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Após o cadastro, sua organização entra em um processo de implantação acompanhado: configuração inicial,
                    migração de dados, treinamento da equipe e acompanhamento por etapas até a operação plena.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Quais módulos estão inclusos no plano?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    O Elo 42 inclui gestão de membros, controle financeiro, agenda de eventos, comunicação interna,
                    grupos e células, relatórios, hub de implantação, benefícios, site institucional e suporte contínuo.
                    Cada módulo pode ser ativado conforme a necessidade.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Os dados da minha organização ficam seguros?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Sim. Utilizamos criptografia de dados, controle de acesso por perfil e permissões,
                    logs de auditoria completos e backups regulares. Seguimos as melhores práticas de segurança.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Como funciona o suporte?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    O suporte é acessível diretamente pela plataforma: abertura de chamados, base de conhecimento
                    e, nos planos avançados, atendimento prioritário. Atualizações e correções são contínuas.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Posso testar antes de contratar?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Sim. Oferecemos um período de avaliação para que sua organização conheça a plataforma na prática,
                    sem compromisso. Entre em contato para saber mais.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    É possível migrar dados de outro sistema?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Sim. Nossa equipe de implantação auxilia na migração de dados de planilhas e outros sistemas,
                    garantindo que nada seja perdido durante a transição.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    A plataforma funciona em celular?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Sim. O Elo 42 é totalmente responsivo e funciona em qualquer dispositivo com navegador:
                    computador, tablet e celular, sem necessidade de instalar aplicativo.
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-section__inner animate-on-scroll">
            <h2 class="cta-section__title">Ainda tem dúvidas?</h2>
            <p class="cta-section__text">
                Fale diretamente com nossa equipe. Estamos prontos para ajudar.
            </p>
            <div class="cta-section__actions">
                <a href="<?= url('/contato') ?>" class="btn btn--gold btn--lg">Entrar em contato</a>
            </div>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
