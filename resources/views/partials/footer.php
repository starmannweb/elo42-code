<footer class="site-footer">
    <div class="container">
        <div class="footer__grid">
            <div class="footer__brand">
                <div class="footer__logo">
                    <span class="navbar__logo-mark">E42</span>
                    Elo 42
                </div>
                <p class="footer__desc">
                    Gestão, implantação, benefícios e suporte reunidos em uma plataforma feita para igrejas e organizações que buscam ordem, clareza e eficiência.
                </p>
            </div>

            <div>
                <h4 class="footer__title">Plataforma</h4>
                <ul class="footer__links">
                    <li><a href="<?= url('/solucoes') ?>" class="footer__link">Soluções</a></li>
                    <li><a href="<?= url('/plataforma') ?>" class="footer__link">Gestão</a></li>
                    <li><a href="<?= url('/beneficios') ?>" class="footer__link">Benefícios</a></li>
                    <li><a href="<?= url('/funcionalidades') ?>" class="footer__link">Funcionalidades</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer__title">Empresa</h4>
                <ul class="footer__links">
                    <li><a href="<?= url('/sobre') ?>" class="footer__link">Sobre</a></li>
                    <li><a href="<?= url('/consultoria') ?>" class="footer__link">Consultoria</a></li>
                    <li><a href="<?= url('/contato') ?>" class="footer__link">Contato</a></li>
                    <li><a href="<?= url('/faq') ?>" class="footer__link">FAQ</a></li>
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
