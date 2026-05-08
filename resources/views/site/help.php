<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <h1 class="page-hero__title">Central de Ajuda</h1>
            <p class="page-hero__subtitle">
                Encontre respostas, tutoriais e recursos para usar a plataforma Elo 42.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="help-grid animate-on-scroll">

            <a href="#primeiros-passos" class="help-category-card">
                <div class="help-category-card__icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                    </svg>
                </div>
                <h3>Primeiros Passos</h3>
                <p>Criação de conta, configuração da organização e ativação de módulos.</p>
            </a>

            <a href="#gestao" class="help-category-card">
                <div class="help-category-card__icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <path d="M3 9h18M9 21V9"/>
                    </svg>
                </div>
                <h3>Gestão</h3>
                <p>Membros, finanças, eventos, grupos e relatórios.</p>
            </a>

            <a href="#comunicacao" class="help-category-card">
                <div class="help-category-card__icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <h3>Comunicação</h3>
                <p>Envio de avisos, notificações e canais de comunicação interna.</p>
            </a>

            <a href="#site" class="help-category-card">
                <div class="help-category-card__icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="2" y1="12" x2="22" y2="12"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                </div>
                <h3>Site Público</h3>
                <p>Configuração e publicação do site da sua organização.</p>
            </a>

            <a href="#ia" class="help-category-card">
                <div class="help-category-card__icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                </div>
                <h3>Central Pastoral IA</h3>
                <p>Recursos de exegese, sermões e ferramentas pastorais com IA.</p>
            </a>

            <a href="#conta" class="help-category-card">
                <div class="help-category-card__icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <h3>Conta e Perfil</h3>
                <p>Configurações de conta, senha, permissões e notificações.</p>
            </a>

        </div>
    </div>
</section>

<!-- Primeiros Passos -->
<section class="section section--light" id="primeiros-passos">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__badge">✦ Primeiros Passos</span>
            <h2 class="section__title">Começando com o Elo 42</h2>
        </div>

        <div class="help-faq animate-on-scroll">

            <div class="faq-item">
                <button class="faq-item__question">
                    Como criar minha conta na plataforma?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Acesse a página de cadastro em <a href="<?= url('/cadastro') ?>">elo42.com.br/cadastro</a>, preencha seus dados pessoais e os dados da sua organização. Após confirmar o e-mail, você terá acesso imediato ao painel.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Quais informações preciso ter em mãos para o cadastro?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Para o cadastro você precisará de: nome completo, e-mail válido, telefone, nome da organização e tipo de organização (Igreja, Ministério, ONG, etc.). CNPJs e dados adicionais podem ser preenchidos depois.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Como ativar os módulos da plataforma?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Após o cadastro, acesse o Hub → Configurações → Módulos. Cada módulo pode ser ativado individualmente conforme a necessidade da sua organização. Nossa equipe também pode auxiliar na ativação durante a implantação.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    O que é a implantação assistida?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    A implantação assistida é o processo em que a equipe Elo 42 acompanha sua organização na configuração inicial da plataforma, definição de fluxos, cadastro de dados e treinamento dos usuários. O acompanhamento é feito via checklist no próprio Hub.
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Gestão -->
<section class="section" id="gestao">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__badge">✦ Gestão</span>
            <h2 class="section__title">Módulos de Gestão</h2>
        </div>

        <div class="help-faq animate-on-scroll">

            <div class="faq-item">
                <button class="faq-item__question">
                    Como cadastrar membros na plataforma?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Acesse Gestão → Membros → Novo Membro. Preencha os dados básicos (nome, e-mail, telefone, data de nascimento) e salve. O membro receberá um convite por e-mail para acessar a área de membros, se configurado.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Como registrar receitas e despesas financeiras?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Acesse Gestão → Finanças → Nova Transação. Selecione o tipo (receita ou despesa), categoria, valor, data e descrição. O sistema gerará relatórios automáticos com base nos lançamentos registrados.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Como criar e gerenciar eventos?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Acesse Gestão → Eventos → Novo Evento. Defina título, data, local, descrição e se o evento é público (aparecerá no site). Eventos podem ter controle de presença e inscrições.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Como criar grupos e células?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Acesse Gestão → Grupos → Novo Grupo. Defina o nome, líder, dia e horário de reunião, local e tipo. Membros podem ser adicionados individualmente ou em lote.
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Conta e Perfil -->
<section class="section section--light" id="conta">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__badge">✦ Conta</span>
            <h2 class="section__title">Conta e Configurações</h2>
        </div>

        <div class="help-faq animate-on-scroll">

            <div class="faq-item">
                <button class="faq-item__question">
                    Como alterar minha senha?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Acesse seu perfil no canto superior direito → Configurações → Segurança → Alterar Senha. Informe a senha atual e a nova senha. Para redefinir uma senha esquecida, use o link "Esqueci minha senha" na tela de login.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Como adicionar outros usuários à minha organização?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    No Hub, acesse Configurações → Usuários → Convidar Usuário. Informe o e-mail da pessoa e selecione o perfil de acesso (Administrador, Líder, Secretário, etc.). O convite será enviado por e-mail.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-item__question">
                    Como solicitar suporte técnico?
                    <span class="faq-item__icon">+</span>
                </button>
                <div class="faq-item__answer">
                    Você pode abrir um chamado de suporte diretamente pelo Hub → Suporte → Novo Chamado, ou entrar em contato pelo e-mail <a href="mailto:suporte@elo42.com.br">suporte@elo42.com.br</a>. Nossa equipe retornará em até 1 dia útil.
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA de contato -->
<section class="cta-section">
    <div class="container">
        <div class="cta-section__inner animate-on-scroll">
            <h2 class="cta-section__title">Não encontrou o que precisava?</h2>
            <p class="cta-section__text">
                Nossa equipe está disponível para ajudar com qualquer dúvida sobre a plataforma.
            </p>
            <div class="cta-section__actions">
                <a href="<?= url('/contato') ?>" class="btn btn--primary btn--lg">Falar com o suporte</a>
            </div>
        </div>
    </div>
</section>

<style>
.help-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: var(--space-6);
    margin-bottom: var(--space-6);
}

@media (max-width: 1024px) {
    .help-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (max-width: 640px) {
    .help-grid { grid-template-columns: 1fr; }
}

.help-category-card {
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
    padding: var(--space-6);
    background: var(--color-white);
    border: 1px solid var(--color-border-light);
    border-radius: 16px;
    text-decoration: none;
    color: inherit;
    transition: box-shadow 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
}

.help-category-card:hover {
    box-shadow: 0 8px 32px rgba(10, 31, 68, 0.08);
    transform: translateY(-2px);
    border-color: var(--color-primary);
}

.help-category-card__icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(10, 77, 255, 0.07);
    color: var(--color-primary);
    border-radius: 12px;
}

.help-category-card h3 {
    font-size: var(--text-lg);
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0;
}

.help-category-card p {
    font-size: var(--text-sm);
    color: var(--color-text-secondary);
    line-height: 1.55;
    margin: 0;
}

.help-faq { max-width: 780px; margin: 0 auto; }
</style>

<?php $__view->endSection(); ?>
