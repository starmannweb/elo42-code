<!DOCTYPE html>
<?php
    $portalOrganizationCtx = is_array($organization ?? null) ? $organization : (\App\Core\Session::get('organization') ?: []);
    $portalOrganizationCtx = is_array($portalOrganizationCtx) ? $portalOrganizationCtx : [];
    $isPortalPwaEnabled = true;
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Portal do Membro') ?></title>
    <meta name="theme-color" content="<?= e((string) (($appearanceSettings['theme_color'] ?? null) ?: '#1547f5')) ?>">
    <?php if ($isPortalPwaEnabled): ?>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="<?= e((string) (($appearanceSettings['pwa_short_name'] ?? null) ?: 'Elo 42')) ?>">
        <link rel="manifest" href="<?= url('/app-manifest') ?>">
        <link rel="apple-touch-icon" href="<?= url('/assets/img/logo-color-new.png') ?>">
    <?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Saira:wght@600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('/assets/css/portal.css') ?>?v=<?= filemtime(BASE_PATH . '/public/assets/css/portal.css') ?>">
    <?php
        $appearanceSettings = is_array($appearanceSettings ?? null) ? $appearanceSettings : [];
        $safeHex = static function (mixed $value, string $fallback): string {
            $value = trim((string) $value);
            return preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? $value : $fallback;
        };
        $portalPrimary = $safeHex($appearanceSettings['appearance_primary'] ?? null, '#1547f5');
        $portalAccent = $safeHex($appearanceSettings['appearance_accent'] ?? null, '#e5b84f');
        $portalBackground = $safeHex($appearanceSettings['appearance_background'] ?? null, '#f4f7fd');
        $portalText = $safeHex($appearanceSettings['appearance_text'] ?? null, '#06183a');
    ?>
    <style>
        body[data-portal-theme="light"] {
            --portal-primary: <?= e($portalPrimary) ?>;
            --portal-primary-dark: <?= e($portalPrimary) ?>;
            --portal-gold: <?= e($portalAccent) ?>;
            --portal-bg: <?= e($portalBackground) ?>;
            --portal-text: <?= e($portalText) ?>;
        }
        body[data-portal-theme="dark"] {
            --portal-primary: <?= e($portalPrimary) ?>;
            --portal-gold: <?= e($portalAccent) ?>;
        }
    </style>
</head>
<?php
    $uri = !empty($_GET['url'])
        ? '/' . trim((string) $_GET['url'], '/')
        : (parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?? '/');
    $uri = $uri === '/' ? '/' : rtrim($uri, '/');

    $theme = $_COOKIE['elo42_portal_theme'] ?? 'light';
    $theme = $theme === 'dark' ? 'dark' : 'light';

    $linkClass = static function (string $path, string $uri, bool $startsWith = false): string {
        if ($startsWith && $path !== '/membro') {
            return str_starts_with($uri, $path) ? 'active' : '';
        }

        return $uri === $path ? 'active' : '';
    };

    $icon = static function (string $name): string {
        return match ($name) {
            'home' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 10 9-7 9 7v10a2 2 0 0 1-2 2h-4v-7H9v7H5a2 2 0 0 1-2-2z"/></svg>',
            'bible' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><path d="M10 7h6M13 5v8"/></svg>',
            'plans' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="m8 15 2 2 5-5"/></svg>',
            'sermons' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
            'courses' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>',
            'events' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>',
            'requests' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/></svg>',
            'achievements' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M8.2 13.2 7 22l5-3 5 3-1.2-8.8"/></svg>',
            'offerings' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 12h.01M17 10V6a5 5 0 0 0-10 0v4"/></svg>',
            'settings' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06A2 2 0 0 1 7.04 4.3l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.17.42.26.86.26 1.31 0 .45-.09.89-.26 1.31"/></svg>',
            'crown' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 5 5 5 5-8 5 8 5-5-3 14H5L2 5z"/><path d="M5 19h14"/></svg>',
            'menu' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>',
            'search' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>',
            'bell' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>',
            'moon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/></svg>',
            'logout' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>',
            default => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>',
        };
    };

    $parts = preg_split('/\s+/', trim((string) ($user['name'] ?? 'Usuário'))) ?: ['Usuário'];
    $initials = strtoupper(substr((string) ($parts[0] ?? 'U'), 0, 1) . substr((string) (end($parts) ?: 'U'), 0, 1));
    $portalMemberPhoto = trim((string) (($member['photo'] ?? '') ?: ($user['avatar'] ?? '')));
    $portalMemberPhotoUrl = $portalMemberPhoto !== '' ? (preg_match('#^https?://#i', $portalMemberPhoto) ? $portalMemberPhoto : url($portalMemberPhoto)) : '';
    $nav = [
        ['href' => '/membro', 'label' => 'Início', 'icon' => 'home', 'active' => $linkClass('/membro', $uri)],
        ['href' => '/membro/biblia', 'label' => 'Bíblia', 'icon' => 'bible', 'active' => $linkClass('/membro/biblia', $uri, true)],
        ['href' => '/membro/planos-leitura', 'label' => 'Planos de leitura', 'icon' => 'plans', 'active' => $linkClass('/membro/planos-leitura', $uri, true)],
        ['href' => '/membro/eventos', 'label' => 'Eventos', 'icon' => 'events', 'active' => $linkClass('/membro/eventos', $uri, true)],
        ['href' => '/membro/ministracoes', 'label' => 'Séries e Ministrações', 'icon' => 'sermons', 'premium' => true, 'active' => $linkClass('/membro/ministracoes', $uri, true)],
        ['href' => '/membro/cursos', 'label' => 'Cursos', 'icon' => 'courses', 'premium' => true, 'active' => $linkClass('/membro/cursos', $uri, true)],
        ['href' => '/membro/solicitacoes', 'label' => 'Solicitações', 'icon' => 'requests', 'premium' => true, 'active' => $linkClass('/membro/solicitacoes', $uri, true) ?: $linkClass('/membro/pedidos', $uri, true)],
        ['href' => '/membro/conquistas', 'label' => 'Conquistas', 'icon' => 'achievements', 'premium' => true, 'active' => $linkClass('/membro/conquistas', $uri, true)],
        ['href' => '/membro/ofertas', 'label' => 'Ofertas', 'icon' => 'offerings', 'premium' => true, 'active' => $linkClass('/membro/ofertas', $uri, true)],
    ];
?>
<body data-portal-theme="<?= e($theme) ?>">
    <div class="portal-layout">
        <aside class="portal-sidebar" id="portal-sidebar">
            <div class="portal-sidebar__header">
                <a href="<?= url('/membro') ?>" class="portal-sidebar__logo" aria-label="Elo 42">
                    <img class="portal-logo--light" src="<?= url('/assets/img/logo-color-new.png') ?>" alt="Elo 42">
                    <img class="portal-logo--dark" src="<?= url('/assets/img/logo.png') ?>" alt="Elo 42">
                </a>
            </div>

            <nav class="portal-sidebar__nav" aria-label="Navegação do membro">
                <?php foreach ($nav as $item): ?>
                    <?php if (isset($item['section'])): ?>
                        <div class="portal-sidebar__section"><?= e($item['section']) ?></div>
                    <?php else: ?>
                        <a href="<?= url($item['href']) ?>" class="portal-nav-link <?= !empty($item['active']) ? 'active' : '' ?>">
                            <span class="portal-nav-link__icon"><?= $icon($item['icon']) ?></span>
                            <span><?= e($item['label']) ?></span>
                            <?php if (!empty($item['premium'])): ?>
                                <span class="portal-premium-icon" title="Recurso Premium" aria-label="Recurso Premium"><?= $icon('crown') ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>

            <div class="portal-sidebar__footer">
                <a href="<?= url('/membro/configuracoes') ?>" class="portal-user-card">
                    <span class="portal-avatar">
                        <?php if ($portalMemberPhotoUrl !== ''): ?>
                            <img src="<?= e($portalMemberPhotoUrl) ?>" alt="Foto de <?= e($user['name'] ?? 'membro') ?>">
                        <?php else: ?>
                            <?= e($initials) ?>
                        <?php endif; ?>
                    </span>
                    <span class="portal-user-card__meta">
                        <span class="portal-user-card__name"><?= e($user['name'] ?? 'Usuário') ?></span>
                        <span class="portal-user-card__email"><?= e($user['email'] ?? '') ?></span>
                    </span>
                </a>
            </div>
        </aside>

        <main class="portal-main">
            <header class="portal-topbar">
                <div class="portal-topbar__left">
                    <button type="button" class="portal-icon-btn portal-mobile-toggle" data-portal-menu aria-label="Abrir menu">
                        <?= $icon('menu') ?>
                    </button>
                    <?php
                        $organizationName = trim((string) ($organization['name'] ?? ''));
                        $memberDisplayName = trim((string) ($user['name'] ?? ''));
                        if ($memberDisplayName === '') {
                            $memberDisplayName = 'Membro';
                        }
                        if ($organizationName === '' || strcasecmp($organizationName, $memberDisplayName) === 0) {
                            $organizationName = 'Sua igreja';
                        }
                    ?>
                    <div class="portal-topbar__context">
                        <span class="portal-topbar__eyebrow"><?= e($organizationName) ?></span>
                        <strong class="portal-topbar__title"><?= e($memberDisplayName) ?></strong>
                    </div>
                </div>

                <div class="portal-topbar__actions">
                    <div class="portal-notifications" data-portal-notifications>
                        <button type="button" class="portal-icon-btn portal-notification-btn" aria-label="Notificações" aria-expanded="false" data-portal-notification-toggle>
                            <?= $icon('bell') ?>
                            <span class="portal-notification-dot" aria-hidden="true"></span>
                        </button>
                        <div class="portal-notification-panel" role="status" hidden>
                            <strong>Notificações</strong>
                            <p>Nenhuma notificação nova no momento.</p>
                            <a href="<?= url('/membro/configuracoes') ?>">Configurar preferências</a>
                        </div>
                    </div>
                    <button type="button" class="portal-icon-btn portal-theme-toggle" data-portal-theme-toggle aria-label="Alternar tema"><?= $icon('moon') ?></button>
                    <a href="<?= url('/membro/configuracoes') ?>" class="portal-icon-btn portal-settings-btn" aria-label="Configura&ccedil;&otilde;es"><?= $icon('settings') ?></a>
                    <form method="POST" action="<?= url('/logout') ?>" class="portal-logout-form">
                        <?= csrf_field() ?>
                        <button type="submit" class="portal-icon-btn portal-logout-btn" aria-label="Sair"><?= $icon('logout') ?></button>
                    </form>
                </div>
            </header>

            <div class="portal-content">
                <?php if (\App\Core\Session::hasFlash('success')): ?>
                    <div class="portal-alert portal-alert--success"><?= e((string) \App\Core\Session::getFlash('success')) ?></div>
                <?php endif; ?>
                <?php if (\App\Core\Session::hasFlash('error')): ?>
                    <div class="portal-alert portal-alert--error"><?= e((string) \App\Core\Session::getFlash('error')) ?></div>
                <?php endif; ?>

                <?= $__view->yield('content'); ?>
            </div>
        </main>
    </div>

    <script>
        (function () {
            const body = document.body;
            const menuButton = document.querySelector('[data-portal-menu]');
            const themeButton = document.querySelector('[data-portal-theme-toggle]');
            const notifications = document.querySelector('[data-portal-notifications]');
            const notificationButton = document.querySelector('[data-portal-notification-toggle]');

            menuButton && menuButton.addEventListener('click', function () {
                body.classList.toggle('portal-menu-open');
            });

            document.addEventListener('click', function (event) {
                if (!body.classList.contains('portal-menu-open')) {
                    return;
                }

                const sidebar = document.getElementById('portal-sidebar');
                if (sidebar && !sidebar.contains(event.target) && menuButton && !menuButton.contains(event.target)) {
                    body.classList.remove('portal-menu-open');
                }
            });

            themeButton && themeButton.addEventListener('click', function () {
                const next = body.getAttribute('data-portal-theme') === 'dark' ? 'light' : 'dark';
                body.setAttribute('data-portal-theme', next);
                document.cookie = 'elo42_portal_theme=' + next + '; path=/; max-age=31536000; SameSite=Lax';
            });

            notificationButton && notificationButton.addEventListener('click', function () {
                const panel = notifications && notifications.querySelector('.portal-notification-panel');
                if (!panel) {
                    return;
                }
                const isOpen = panel.hasAttribute('hidden');
                panel.toggleAttribute('hidden', !isOpen);
                notificationButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            document.addEventListener('click', function (event) {
                if (!notifications || notifications.contains(event.target)) {
                    return;
                }
                const panel = notifications.querySelector('.portal-notification-panel');
                if (panel) {
                    panel.hidden = true;
                    notificationButton && notificationButton.setAttribute('aria-expanded', 'false');
                }
            });

            <?php if ($isPortalPwaEnabled): ?>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function () {
                    navigator.serviceWorker.register('<?= url('/sw.js') ?>').catch(function () {});
                });
            }
            <?php endif; ?>
        })();
    </script>
    <?= $__view->yield('scripts'); ?>
</body>
</html>
