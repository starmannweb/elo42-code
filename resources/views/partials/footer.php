<footer class="site-footer">
    <div class="container">
        <div class="footer__grid">
            <div class="footer__brand">
                <a href="<?= url('/') ?>" class="footer__logo">
                    <img src="<?= url('/assets/img/logo.png') ?>" alt="Elo 42" height="40" onerror="this.onerror=null;this.src='<?= url('/assets/img/logo.svg') ?>'">
                </a>
                <p class="footer__desc">
                    Gestão, implantação, benefícios e suporte reunidos em uma plataforma feita para igrejas e organizações que buscam ordem, clareza e eficiência.
                </p>
            </div>

            <div>
                <h4 class="footer__title">Funcionalidades</h4>
                <ul class="footer__links">
                    <li><a href="<?= url('/servico/central-elo42') ?>" class="footer__link">Central Elo 42</a></li>
                    <li><a href="<?= url('/servico/plataforma-gestao') ?>" class="footer__link">Plataforma de Gestão</a></li>
                    <li><a href="<?= url('/servico/google-ad-grants') ?>" class="footer__link">Google para Nonprofits</a></li>
                    <li><a href="<?= url('/servico/expositor-ia') ?>" class="footer__link">Expositor IA</a></li>
                    <li><a href="<?= url('/servico/consultoria') ?>" class="footer__link">Consultoria</a></li>
                    <li><a href="<?= url('/servico/sites-prontos') ?>" class="footer__link">Site para igrejas</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer__title">Navegação</h4>
                <ul class="footer__links">
                    <li><a href="<?= url('/') ?>#sobre" class="footer__link">Sobre</a></li>
                    <li><a href="<?= url('/') ?>#funcionalidades" class="footer__link">Funcionalidades</a></li>
                    <li><a href="<?= url('/') ?>#projetos" class="footer__link">Como funciona</a></li>
                    <li><a href="<?= url('/') ?>#faq" class="footer__link">FAQ</a></li>
                    <li><a href="<?= url('/contato') ?>" class="footer__link">Contato</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer__title">Acesso</h4>
                <ul class="footer__links">
                    <li><a href="<?= url('/login') ?>" class="footer__link">Entrar</a></li>
                    <li><a href="<?= url('/cadastro') ?>" class="footer__link">Criar conta</a></li>
                </ul>
            </div>
        </div>

        <div class="footer__bottom">
            <p>&copy; <?= date('Y') ?> Elo 42. Todos os direitos reservados.</p>
            <p>Feito com propósito e tecnologia.</p>
        </div>
    </div>
</footer>
