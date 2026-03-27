<header class="site-header" id="site-header" role="banner">
    <nav class="navbar container" aria-label="Navegação principal">
        <a href="<?= url('/') ?>" class="navbar__logo" aria-label="Elo 42 — Página inicial">
            <img src="<?= url('/assets/img/logo.png') ?>" alt="Elo 42" height="36" onerror="this.onerror=null;this.src='<?= url('/assets/img/logo.svg') ?>'">
        </a>

        <ul class="navbar__menu" id="nav-menu" role="menubar">
            <li role="none"><a href="<?= url('/') ?>" class="navbar__link <?= active_class('/') ?>" role="menuitem">Início</a></li>
            <li role="none"><a href="<?= url('/sobre') ?>" class="navbar__link <?= active_class('/sobre') ?>" role="menuitem">Sobre</a></li>
            <li role="none"><a href="<?= url('/solucoes') ?>" class="navbar__link <?= active_class('/solucoes') ?>" role="menuitem">Serviços</a></li>
            <li role="none"><a href="<?= url('/plataforma') ?>" class="navbar__link <?= active_class('/plataforma') ?>" role="menuitem">Projetos</a></li>
            <li role="none"><a href="<?= url('/funcionalidades') ?>" class="navbar__link <?= active_class('/funcionalidades') ?>" role="menuitem">Funcionalidades</a></li>
            <li role="none"><a href="<?= url('/faq') ?>" class="navbar__link <?= active_class('/faq') ?>" role="menuitem">FAQ</a></li>
        </ul>

        <div class="navbar__actions" id="nav-actions">
            <a href="<?= url('/cadastro') ?>" class="navbar__register">Cadastre-se</a>
            <a href="<?= url('/login') ?>" class="navbar__cta">Login</a>
        </div>

        <button class="navbar__toggle" id="nav-toggle" aria-label="Abrir menu de navegação" aria-expanded="false" aria-controls="nav-menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </nav>
</header>
