<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle ?? 'Admin — Elo 42') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Saira:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap">

    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/hub.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/management.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body>
    <a href="#admin-main-content" class="skip-to-content">Pular para o conteúdo</a>

    <div class="hub-layout">
        <?php
            $user = \App\Core\Session::user() ?? [];
            $parts = explode(' ', $user['name'] ?? '');
            $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . substr(end($parts) ?: '', 0, 1));
            $uri = !empty($_GET['url']) ? '/' . trim($_GET['url'], '/') : (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/');
        ?>

        <aside class="hub-sidebar admin-sidebar" id="hub-sidebar" role="navigation" aria-label="Menu admin">
            <div class="hub-sidebar__header">
                <a href="<?= url('/admin') ?>" class="hub-sidebar__logo">
                    <img src="<?= url('/assets/img/logo.png') ?>" alt="Elo 42 Admin" height="28" onerror="this.onerror=null;this.src='<?= url('/assets/img/logo.svg') ?>'">
                </a>
            </div>

            <nav class="hub-sidebar__nav" aria-label="Navegação admin">
                <p class="hub-sidebar__section-title">Plataforma</p>
                <a href="<?= url('/admin') ?>" class="hub-nav-link <?= $uri === '/admin' ? 'active' : '' ?>" <?= $uri === '/admin' ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">📊</span> Dashboard
                </a>
                <a href="<?= url('/admin/usuarios') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/usuarios') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/usuarios') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">👤</span> Usuários
                </a>
                <a href="<?= url('/admin/organizacoes') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/organizacoes') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/organizacoes') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">🏢</span> Organizações
                </a>

                <p class="hub-sidebar__section-title">Catálogo</p>
                <a href="<?= url('/admin/produtos') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/produtos') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/produtos') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">📦</span> Produtos
                </a>
                <a href="<?= url('/admin/servicos') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/servicos') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/servicos') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">🔧</span> Serviços
                </a>
                <a href="<?= url('/admin/beneficios') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/beneficios') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/beneficios') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">🎁</span> Benefícios
                </a>
                <a href="<?= url('/admin/assinaturas') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/assinaturas') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/assinaturas') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">💳</span> Assinaturas
                </a>

                <p class="hub-sidebar__section-title">Operação</p>
                <a href="<?= url('/admin/tickets') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/tickets') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/tickets') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">🎫</span> Tickets
                </a>
                <a href="<?= url('/admin/relatorios') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/relatorios') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/relatorios') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">📈</span> Relatórios
                </a>
                <a href="<?= url('/admin/logs') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/logs') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/logs') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">📋</span> Logs
                </a>
                <a href="<?= url('/admin/configuracoes') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/configuracoes') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/configuracoes') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">⚙️</span> Configurações
                </a>
            </nav>

            <div class="hub-sidebar__footer">
                <div class="hub-sidebar__user">
                    <div class="hub-sidebar__user-avatar admin-avatar" aria-hidden="true"><?= $initials ?></div>
                    <div class="hub-sidebar__user-info">
                        <div class="hub-sidebar__user-name"><?= e($user['name'] ?? '') ?></div>
                        <div class="hub-sidebar__user-role">Administrador</div>
                    </div>
                </div>
            </div>
        </aside>
        <div class="hub-sidebar-overlay" aria-hidden="true"></div>

        <div class="hub-main">
            <header class="hub-topbar admin-topbar">
                <div class="hub-topbar__left">
                    <button class="hub-topbar__mobile-toggle" id="hub-sidebar-toggle" aria-label="Abrir menu" aria-expanded="false">☰</button>
                    <nav class="hub-topbar__breadcrumb" aria-label="Breadcrumb">
                        <span class="admin-badge-top">Admin</span>
                        <span class="hub-topbar__breadcrumb-sep" aria-hidden="true">›</span>
                        <span aria-current="page"><?= e($breadcrumb ?? 'Dashboard') ?></span>
                    </nav>
                </div>
                <div class="hub-topbar__right">
                    <a href="<?= url('/hub') ?>" class="hub-topbar__link">← Hub</a>
                    <form method="POST" action="<?= url('/logout') ?>" style="margin:0;">
                        <?= csrf_field() ?>
                        <button type="submit" class="hub-topbar__link" style="background:none;border:none;cursor:pointer;font:inherit;">Sair</button>
                    </form>
                </div>
            </header>

            <main class="hub-content" id="admin-main-content">
                <?php if ($alert = flash('success')): ?>
                    <div class="alert alert--success" role="alert"><?= e($alert) ?></div>
                <?php endif; ?>
                <?php if ($alert = flash('error')): ?>
                    <div class="alert alert--error" role="alert"><?= e($alert) ?></div>
                <?php endif; ?>
                <?= $__view->yield('content') ?>
            </main>
        </div>
    </div>

    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
