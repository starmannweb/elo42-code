<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <h1 class="page-hero__title">Política de Cookies</h1>
            <p class="page-hero__subtitle">
                Entenda como utilizamos cookies e tecnologias similares para melhorar sua experiência.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="legal-page animate-on-scroll">

            <p class="legal-page__updated">Última atualização: 27 de março de 2026</p>

            <h2>1. O que são Cookies?</h2>
            <p>Cookies são pequenos arquivos de texto armazenados no seu dispositivo quando você visita um site. Eles permitem que o site reconheça seu dispositivo em visitas futuras e mantenham preferências e informações de sessão.</p>

            <h2>2. Como Utilizamos Cookies</h2>
            <p>Utilizamos cookies para as seguintes finalidades:</p>

            <div class="cookie-table-wrap">
                <table class="cookie-table">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Finalidade</th>
                            <th>Duração</th>
                            <th>Obrigatório</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Essenciais</strong></td>
                            <td>Mantêm a sessão autenticada, preferências básicas e segurança CSRF</td>
                            <td>Sessão / 30 dias</td>
                            <td>Sim</td>
                        </tr>
                        <tr>
                            <td><strong>Preferências</strong></td>
                            <td>Lembram configurações como tema (claro/escuro) e idioma</td>
                            <td>1 ano</td>
                            <td>Não</td>
                        </tr>
                        <tr>
                            <td><strong>Análise</strong></td>
                            <td>Coletam dados anônimos sobre uso para melhoria da plataforma</td>
                            <td>2 anos</td>
                            <td>Não</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h2>3. Cookies de Terceiros</h2>
            <p>Algumas funcionalidades da plataforma podem utilizar cookies de terceiros, como:</p>
            <ul>
                <li><strong>Google Analytics:</strong> para análise de acesso e comportamento (dados anonimizados);</li>
                <li><strong>Google Ads / Ad Grants:</strong> para rastreamento de campanhas de publicidade.</li>
            </ul>
            <p>Esses terceiros têm suas próprias políticas de privacidade e cookies, sobre as quais não temos controle.</p>

            <h2>4. Controle de Cookies</h2>
            <p>Você pode controlar e gerenciar cookies das seguintes formas:</p>
            <ul>
                <li><strong>Configurações do navegador:</strong> a maioria dos navegadores permite bloquear ou excluir cookies. Consulte a ajuda do seu navegador para instruções específicas;</li>
                <li><strong>Banner de consentimento:</strong> ao acessar a plataforma pela primeira vez, você pode aceitar ou recusar cookies não essenciais;</li>
                <li><strong>Opt-out do Google Analytics:</strong> instale o complemento de desativação disponível no site do Google.</li>
            </ul>
            <p><strong>Atenção:</strong> desativar cookies essenciais pode afetar o funcionamento da plataforma.</p>

            <h2>5. Local Storage e Session Storage</h2>
            <p>Além de cookies, utilizamos armazenamento local do navegador (localStorage e sessionStorage) para salvar preferências de interface como tema e configurações de visualização. Esses dados ficam armazenados apenas no seu dispositivo e não são enviados a nossos servidores.</p>

            <h2>6. Segurança dos Cookies</h2>
            <p>Os cookies de sessão e autenticação utilizam os atributos <code>HttpOnly</code> e <code>Secure</code> para prevenir acesso via JavaScript e garantir transmissão apenas por conexões criptografadas (HTTPS).</p>

            <h2>7. Alterações nesta Política</h2>
            <p>Esta Política pode ser atualizada periodicamente. Recomendamos que você a revise regularmente. A data de última atualização estará sempre indicada no topo desta página.</p>

            <h2>8. Contato</h2>
            <p>Para dúvidas sobre o uso de cookies: <a href="mailto:suporte@elo42.com.br"><strong>suporte@elo42.com.br</strong></a></p>

        </div>
    </div>
</section>

<style>
.cookie-table-wrap {
    overflow-x: auto;
    margin: var(--space-4) 0 var(--space-8);
    border-radius: 10px;
    border: 1px solid var(--color-border-light);
}

.cookie-table {
    width: 100%;
    border-collapse: collapse;
    font-size: var(--text-sm);
}

.cookie-table th,
.cookie-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid var(--color-border-light);
    vertical-align: top;
}

.cookie-table th {
    background: var(--color-bg-light);
    font-weight: 700;
    color: var(--color-text-primary);
    font-size: var(--text-xs);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.cookie-table td { color: var(--color-text-secondary); }

.cookie-table tr:last-child td { border-bottom: none; }

.cookie-table code {
    font-family: monospace;
    font-size: var(--text-xs);
    background: var(--color-bg-light);
    padding: 2px 6px;
    border-radius: 4px;
}
</style>

<?php $__view->endSection(); ?>
