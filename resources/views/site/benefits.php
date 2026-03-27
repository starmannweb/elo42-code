<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <h1 class="page-hero__title">Benefícios</h1>
            <p class="page-hero__subtitle">
                O Elo 42 vai além da tecnologia. Oferecemos benefícios reais para organizações,
                membros e colaboradores conectados à plataforma.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__badge">✦ Vantagens</span>
            <h2 class="section__title">O que sua organização ganha com o Elo 42</h2>
        </div>

        <div class="benefit-list animate-on-scroll">
            <div class="benefit-item">
                <div class="benefit-item__check">✓</div>
                <div>
                    <h3 class="card__title">Operação centralizada</h3>
                    <p class="card__text">Tudo em um só lugar: membros, finanças, eventos, comunicação e relatórios. Sem alternar entre ferramentas.</p>
                </div>
            </div>

            <div class="benefit-item">
                <div class="benefit-item__check">✓</div>
                <div>
                    <h3 class="card__title">Implantação assistida</h3>
                    <p class="card__text">Sua organização não começa sozinha. Acompanhamos cada etapa com reuniões, checklists e suporte técnico dedicado.</p>
                </div>
            </div>

            <div class="benefit-item">
                <div class="benefit-item__check">✓</div>
                <div>
                    <h3 class="card__title">Suporte contínuo incluso</h3>
                    <p class="card__text">Acesso permanente a suporte técnico, base de conhecimento e atualizações da plataforma sem custos adicionais.</p>
                </div>
            </div>

            <div class="benefit-item">
                <div class="benefit-item__check">✓</div>
                <div>
                    <h3 class="card__title">Consultoria sob demanda</h3>
                    <p class="card__text">Orientação especializada para decisões estratégicas, reestruturação de processos e planejamento organizacional.</p>
                </div>
            </div>

            <div class="benefit-item">
                <div class="benefit-item__check">✓</div>
                <div>
                    <h3 class="card__title">Programas para membros</h3>
                    <p class="card__text">Convênios, capacitações e programas de desenvolvimento exclusivos para membros e colaboradores da organização.</p>
                </div>
            </div>

            <div class="benefit-item">
                <div class="benefit-item__check">✓</div>
                <div>
                    <h3 class="card__title">Site institucional incluso</h3>
                    <p class="card__text">Site profissional e responsivo com a identidade da sua organização, pronto para publicação.</p>
                </div>
            </div>

            <div class="benefit-item">
                <div class="benefit-item__check">✓</div>
                <div>
                    <h3 class="card__title">Segurança e conformidade</h3>
                    <p class="card__text">Dados protegidos com criptografia, controle de acesso por perfil e logs completos de auditoria.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-section__inner animate-on-scroll">
            <h2 class="cta-section__title">Quer acessar todos esses benefícios?</h2>
            <p class="cta-section__text">
                Cadastre sua organização e comece a operar com mais estrutura.
            </p>
            <div class="cta-section__actions">
                <a href="<?= url('/cadastro') ?>" class="btn btn--gold btn--lg">Começar agora</a>
                <a href="<?= url('/contato') ?>" class="btn btn--secondary btn--lg">Falar com a equipe</a>
            </div>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
