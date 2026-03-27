<header class="site-header" id="site-header" role="banner">
    <nav class="navbar container" aria-label="Navegação principal">
        <a href="<?= url('/') ?>" class="navbar__logo" aria-label="Elo 42 — Página inicial">
            <img src="<?= url('/assets/img/logo.svg') ?>" alt="Elo 42" height="32">
        </a>

        <ul class="navbar__menu" id="nav-menu" role="menubar">
            <li role="none"><a href="<?= url('/') ?>" class="navbar__link <?= active_class('/') ?>" role="menuitem">Home</a></li>
            <li role="none"><a href="<?= url('/sobre') ?>" class="navbar__link <?= active_class('/sobre') ?>" role="menuitem">Sobre</a></li>
            <li role="none"><a href="<?= url('/solucoes') ?>" class="navbar__link <?= active_class('/solucoes') ?>" role="menuitem">Soluções</a></li>
            <li role="none"><a href="<?= url('/plataforma') ?>" class="navbar__link <?= active_class('/plataforma') ?>" role="menuitem">Plataforma</a></li>
            <li role="none"><a href="<?= url('/funcionalidades') ?>" class="navbar__link <?= active_class('/funcionalidades') ?>" role="menuitem">Funcionalidades</a></li>
            <li role="none"><a href="<?= url('/faq') ?>" class="navbar__link <?= active_class('/faq') ?>" role="menuitem">FAQ</a></li>
            <li role="none"><a href="<?= url('/contato') ?>" class="navbar__link <?= active_class('/contato') ?>" role="menuitem">Contato</a></li>
        </ul>

        <div class="navbar__actions" id="nav-actions">
            <a href="<?= url('/login') ?>" class="navbar__login">Entrar</a>
            <a href="<?= url('/cadastro') ?>" class="navbar__cta">Começar agora</a>
        </div>

        <button class="navbar__toggle" id="nav-toggle" aria-label="Abrir menu de navegação" aria-expanded="false" aria-controls="nav-menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </nav>
</header>
