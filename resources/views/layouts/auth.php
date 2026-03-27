<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle ?? 'Elo 42') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap">

    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/auth.css') ?>">
</head>
<body>
    <a href="#auth-main" class="skip-to-content">Pular para o conteúdo</a>
    <div class="auth-page">
        <aside class="auth-page__sidebar">
            <a href="<?= url('/') ?>" class="auth-sidebar__logo">
                <span class="navbar__logo-mark">E42</span>
                Elo 42
            </a>

            <h1 class="auth-sidebar__title">
                <?= $__view->yield('sidebar_title', 'Gestão, tecnologia e <span>impacto</span> para a sua missão.') ?>
            </h1>

            <p class="auth-sidebar__text">
                <?= $__view->yield('sidebar_text', 'A Elo 42 centraliza tudo que sua organização precisa para operar com ordem, clareza e eficiência.') ?>
            </p>

            <div class="auth-sidebar__features">
                <div class="auth-sidebar__feature">
                    <span class="auth-sidebar__feature-icon">📋</span>
                    Gestão centralizada de membros e finanças
                </div>
                <div class="auth-sidebar__feature">
                    <span class="auth-sidebar__feature-icon">🚀</span>
                    Implantação assistida e acompanhamento
                </div>
                <div class="auth-sidebar__feature">
                    <span class="auth-sidebar__feature-icon">🔒</span>
                    Segurança e controle de permissões
                </div>
                <div class="auth-sidebar__feature">
                    <span class="auth-sidebar__feature-icon">📊</span>
                    Relatórios e dashboards inteligentes
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
