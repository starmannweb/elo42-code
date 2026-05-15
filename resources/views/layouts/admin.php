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
<body data-hub-theme="light">
    <a href="#admin-main-content" class="skip-to-content">Pular para o conteúdo</a>

    <div class="hub-layout">
        <?php
            $user = \App\Core\Session::user() ?? [];
            $parts = explode(' ', $user['name'] ?? '');
            $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . substr(end($parts) ?: '', 0, 1));
            $uri = !empty($_GET['url']) ? '/' . trim($_GET['url'], '/') : (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/');
        ?>

        <?php
            $adminIcon = static function (string $name): string {
                $icons = [
                    'dashboard'  => '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>',
                    'users'      => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>',
                    'orgs'       => '<path d="M3 21V7l9-4 9 4v14"></path><path d="M9 21v-6h6v6"></path><path d="M3 21h18"></path>',
                    'services'   => '<path d="M14.7 6.3a4 4 0 0 1-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 1 5.4-5.4l-2.7 2.7-1.4-1.4 2.7-2.7z"></path>',
                    'gift'       => '<rect x="3" y="8" width="18" height="4"></rect><path d="M5 12v9h14v-9"></path><path d="M12 8v13"></path><path d="M7.5 8a2.5 2.5 0 0 1 0-5C9 3 12 8 12 8s3-5 4.5-5a2.5 2.5 0 0 1 0 5"></path>',
                    'card'       => '<rect x="2.5" y="5" width="19" height="14" rx="2"></rect><path d="M16 12h.01"></path><path d="M2.5 9h19"></path>',
                    'ticket'     => '<path d="M3 9a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4z"></path><path d="M13 5v2"></path><path d="M13 17v2"></path><path d="M13 11v2"></path>',
                    'logs'       => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line>',
                    'settings'   => '<circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6h.09A1.65 1.65 0 0 0 10 3.09V3a2 2 0 0 1 4 0v.09A1.65 1.65 0 0 0 15 4.6a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.14.32.23.66.26 1H21a2 2 0 0 1 0 4h-1.34c-.03.34-.12.68-.26 1z"></path>',
                    'home'       => '<path d="M3 10.5 12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V10.5z"></path>',
                    'flask'      => '<path d="M9 2h6"></path><path d="M10 2v7l-5 9a2 2 0 0 0 1.7 3h10.6a2 2 0 0 0 1.7-3l-5-9V2"></path><path d="M7 14h10"></path>',
                    'blog'       => '<path d="M4 4h16v16H4z" rx="2"></path><path d="M8 9h8"></path><path d="M8 13h5"></path>',
                ];
                return '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . ($icons[$name] ?? $icons['dashboard']) . '</svg>';
            };
        ?>

        <aside class="hub-sidebar admin-sidebar" id="hub-sidebar" role="navigation" aria-label="Menu admin">
            <div class="hub-sidebar__header">
                <a href="<?= url('/admin') ?>" class="hub-sidebar__logo">
                    <img src="<?= url('/assets/img/logo.png') ?>" alt="Elo 42 Admin" class="logo-dark">
                    <img src="<?= url('/assets/img/logo-color-new.png') ?>" alt="Elo 42 Admin" class="logo-light">
                </a>
            </div>

            <nav class="hub-sidebar__nav" aria-label="Navegação admin">
                <p class="hub-sidebar__section-title">Plataforma</p>
                <a href="<?= url('/admin') ?>" class="hub-nav-link <?= $uri === '/admin' ? 'active' : '' ?>" <?= $uri === '/admin' ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><?= $adminIcon('dashboard') ?></span> Dashboard
                </a>
                <a href="<?= url('/admin/usuarios') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/usuarios') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/usuarios') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><?= $adminIcon('users') ?></span> Usuários
                </a>
                <a href="<?= url('/admin/organizacoes') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/organizacoes') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/organizacoes') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><?= $adminIcon('orgs') ?></span> Instituições
                </a>

                <p class="hub-sidebar__section-title">Serviços</p>
                <a href="<?= url('/admin/servicos') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/servicos') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/servicos') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><?= $adminIcon('services') ?></span> Serviços
                </a>
                <a href="<?= url('/admin/cortesias') ?>" class="hub-nav-link <?= (str_starts_with($uri, '/admin/beneficios') || str_starts_with($uri, '/admin/cortesias')) ? 'active' : '' ?>" <?= (str_starts_with($uri, '/admin/beneficios') || str_starts_with($uri, '/admin/cortesias')) ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><?= $adminIcon('gift') ?></span> Cortesias
                </a>

                <p class="hub-sidebar__section-title">Financeiro</p>
                <a href="<?= url('/admin/assinaturas') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/assinaturas') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/assinaturas') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><?= $adminIcon('card') ?></span> Assinaturas
                </a>

                <p class="hub-sidebar__section-title">Conteúdo</p>
                <a href="<?= url('/admin/blog') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/blog') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/blog') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><?= $adminIcon('blog') ?></span> Blog
                </a>

                <p class="hub-sidebar__section-title">Operação</p>
                <a href="<?= url('/admin/tickets') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/tickets') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/tickets') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><?= $adminIcon('ticket') ?></span> Tickets
                </a>
                <a href="<?= url('/admin/logs') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/logs') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/logs') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><?= $adminIcon('logs') ?></span> Logs
                </a>
                <a href="<?= url('/admin/configuracoes') ?>" class="hub-nav-link <?= str_starts_with($uri, '/admin/configuracoes') ? 'active' : '' ?>" <?= str_starts_with($uri, '/admin/configuracoes') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><?= $adminIcon('settings') ?></span> Configurações
                </a>
            </nav>

            <div class="hub-sidebar__footer">
                <a href="<?= url('/hub') ?>" class="hub-nav-link admin-sidebar__hub-link mgmt-sidebar-action mgmt-sidebar-action--hub">
                    <span class="hub-nav-link__icon" aria-hidden="true"><?= $adminIcon('home') ?></span> Voltar ao Hub
                </a>
                <a href="<?= url('/hub/configuracoes') ?>" class="hub-sidebar__user" style="text-decoration: none; color: inherit;">
                    <div class="hub-sidebar__user-avatar admin-avatar" aria-hidden="true"><?= $initials ?></div>
                    <div class="hub-sidebar__user-info">
                        <div class="hub-sidebar__user-name"><?= e($user['name'] ?? '') ?></div>
                        <div class="hub-sidebar__user-role">Administrador</div>
                    </div>
                </a>
            </div>
        </aside>
        <div class="hub-sidebar-overlay" aria-hidden="true"></div>

        <div class="hub-main">
            <header class="hub-topbar admin-topbar">
                <div class="hub-topbar__left">
                    <button class="hub-topbar__mobile-toggle" id="hub-sidebar-toggle" aria-label="Abrir menu" aria-expanded="false">☰</button>
                </div>
                <div class="hub-topbar__right">
                    <form method="POST" action="<?= url('/logout') ?>" style="margin:0;">
                        <?= csrf_field() ?>
                        <button type="submit" class="hub-topbar__link hub-topbar__link--danger" style="background:none;border:none;cursor:pointer;font:inherit;">Sair</button>
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
