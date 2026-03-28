<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle ?? 'Elo 42') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Saira:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap">

    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/auth.css') ?>">
</head>
<body>
    <a href="#auth-main" class="skip-to-content">Pular para o conteudo</a>
    <div class="auth-page">
        <aside class="auth-page__sidebar">
            <a href="<?= url('/') ?>" class="auth-sidebar__logo">
                <img src="<?= url('/assets/img/logo.png') ?>" alt="Elo 42" height="38" onerror="this.onerror=null;this.src='<?= url('/assets/img/logo.svg') ?>'">
            </a>

            <h1 class="auth-sidebar__title">
                <?= $__view->yield('sidebar_title', 'Gestao, tecnologia e <span>impacto</span> para a sua missao.') ?>
            </h1>

            <p class="auth-sidebar__text">
                <?= $__view->yield('sidebar_text', 'A Elo 42 centraliza tudo que sua organizacao precisa para operar com ordem, clareza e eficiencia.') ?>
            </p>

            <div class="auth-sidebar__features">
                <div class="auth-sidebar__feature">
                    <span class="auth-sidebar__feature-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="4" width="16" height="16" rx="3"></rect>
                            <path d="M8 9h8M8 12h8M8 15h5"></path>
                        </svg>
                    </span>
                    Gestao centralizada de membros e financas
                </div>
                <div class="auth-sidebar__feature">
                    <span class="auth-sidebar__feature-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 4l7 7-7 7-7-7 7-7z"></path>
                            <path d="M12 16V8M9 11l3-3 3 3"></path>
                        </svg>
                    </span>
                    Implantacao assistida e acompanhamento
                </div>
                <div class="auth-sidebar__feature">
                    <span class="auth-sidebar__feature-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="5" y="11" width="14" height="9" rx="2"></rect>
                            <path d="M8 11V8a4 4 0 1 1 8 0v3"></path>
                        </svg>
                    </span>
                    Seguranca e controle de permissoes
                </div>
                <div class="auth-sidebar__feature">
                    <span class="auth-sidebar__feature-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 18V9"></path>
                            <path d="M12 18V5"></path>
                            <path d="M19 18v-6"></path>
                        </svg>
                    </span>
                    Relatorios e dashboards inteligentes
                </div>
            </div>
        </aside>

        <main class="auth-page__content" id="auth-main">
            <?= $__view->yield('content') ?>
        </main>
    </div>

    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/auth.js') ?>"></script>
</body>
</html>
