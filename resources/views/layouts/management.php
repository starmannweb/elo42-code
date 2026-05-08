<!DOCTYPE html>
<?php
    $mgmtOrganization = \App\Core\Session::get('organization');
    $mgmtOrganization = is_array($mgmtOrganization) ? $mgmtOrganization : [];
    $isPwaEnabled = true;
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#1e3a8a">
    <?php if ($isPwaEnabled): ?>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Elo 42">
        <link rel="manifest" href="<?= url('/app-manifest') ?>">
        <link rel="apple-touch-icon" href="<?= url('/assets/img/logo-color-new.png') ?>">
    <?php endif; ?>
    <title><?= e($pageTitle ?? 'Gestão - Elo 42') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Saira:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap">

    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/hub.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/management.css') ?>">
    <style>
        .hub-sidebar__nav-extra { padding-bottom: 1rem; margin-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .hub-sidebar__nav-main { margin-bottom: 0; }
    </style>
</head>
<body data-hub-theme="dark">
    <a href="#mgmt-main-content" class="skip-to-content">Pular para o conteúdo</a>

    <div class="hub-layout mgmt-layout">
        <?php
            $user = \App\Core\Session::user() ?? [];
            $organization = \App\Core\Session::get('organization');
            $organization = is_array($organization) ? $organization : [];
            $uri = !empty($_GET['url']) ? '/' . trim((string) $_GET['url'], '/') : (parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?? '/');
            $parts = explode(' ', trim((string) ($user['name'] ?? 'Usuário')));
            $initials = strtoupper(substr((string) ($parts[0] ?? 'U'), 0, 1) . substr((string) (end($parts) ?: 'U'), 0, 1));
            $roleSlug = (string) ($organization['role_slug'] ?? '');
            $permissions = is_array($user['permissions'] ?? null) ? $user['permissions'] : [];
            $isSystemAdmin = in_array($roleSlug, ['super-admin', 'admin-elo42'], true)
                || in_array('admin.access', $permissions, true)
                || strtolower((string) ($user['email'] ?? '')) === 'ricieri@starmannweb.com.br';

            $isActive = static function(array|string $paths, string $uri): bool {
                foreach ((array) $paths as $path) {
                    if ($path === '/gestao') {
                        if ($uri === '/gestao') {
                            return true;
                        }
                        continue;
                    }
                    if ($uri === $path || str_starts_with($uri, rtrim($path, '/') . '/')) {
                        return true;
                    }
                }
                return false;
            };

            $premiumIcon = static function(): string {
                return '<span class="premium-feature-icon" title="Recurso Premium" aria-label="Recurso Premium"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 5 5 5 5-8 5 8 5-5-3 14H5L2 5z"></path><path d="M5 19h14"></path></svg></span>';
            };

            $icon = static function(string $name): string {
                $icons = [
                    'dashboard' => '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>',
                    'admin' => '<path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6l8-4z"></path><path d="M9 12l2 2 4-5"></path>',
                    'users' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>',
                    'home' => '<path d="M3 10.5 12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V10.5z"></path>',
                    'smallgroup' => '<circle cx="8" cy="8" r="3"></circle><circle cx="17" cy="9" r="2.5"></circle><path d="M3 21v-1a5 5 0 0 1 10 0v1"></path><path d="M14 21v-1a4 4 0 0 1 7 0v1"></path>',
                    'birthday' => '<path d="M4 21h16"></path><path d="M5 12h14v9H5z"></path><path d="M7 12V9a5 5 0 0 1 10 0v3"></path><path d="M9 7c0-1 .7-2 1.5-3C11.3 5 12 6 12 7"></path><path d="M14 7c0-1 .7-2 1.5-3C16.3 5 17 6 17 7"></path><path d="M5 16c1.5 1 3 1 4.5 0s3-1 4.5 0 3 1 5 0"></path>',
                    'journey' => '<path d="M5 21V4"></path><path d="M5 4h12l-2 4 2 4H5"></path>',
                    'ministries' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M19 8v6"></path><path d="M22 11h-6"></path>',
                    'income' => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline>',
                    'expense' => '<polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline>',
                    'check' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>',
                    'audit' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line>',
                    'wallet' => '<rect x="2.5" y="5" width="19" height="14" rx="2"></rect><path d="M16 12h.01"></path><path d="M2.5 9h19"></path>',
                    'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>',
                    'book' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>',
                    'image' => '<rect x="3" y="3" width="18" height="18" rx="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline>',
                    'courses' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M4 4.5A2.5 2.5 0 0 1 6.5 7H20"></path><path d="M6.5 7v10"></path>',
                    'message' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>',
                    'campaign' => '<path d="M3 11v2a2 2 0 0 0 2 2h2l7 4V5l-7 4H5a2 2 0 0 0-2 2z"></path><path d="M17 9.5a3.5 3.5 0 0 1 0 5"></path>',
                    'award' => '<circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>',
                    'sermon' => '<path d="M12 2v20"></path><path d="M5 6h14"></path><path d="M7 22h10"></path>',
                    'reports' => '<path d="M3 3v18h18"></path><rect x="7" y="12" width="3" height="5"></rect><rect x="12" y="8" width="3" height="9"></rect><rect x="17" y="5" width="3" height="12"></rect>',
                    'category' => '<line x1="4" y1="7" x2="20" y2="7"></line><line x1="4" y1="12" x2="20" y2="12"></line><line x1="4" y1="17" x2="20" y2="17"></line>',
                    'pix' => '<rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M7 12h10"></path><path d="M12 8v8"></path>',
                    'ai' => '<path d="M12 3v3"></path><path d="M12 18v3"></path><path d="M3 12h3"></path><path d="M18 12h3"></path><circle cx="12" cy="12" r="5"></circle><path d="m15.5 8.5 2-2"></path><path d="m6.5 17.5 2-2"></path>',
                    'palette' => '<circle cx="13.5" cy="6.5" r=".5"></circle><circle cx="17.5" cy="10.5" r=".5"></circle><circle cx="8.5" cy="7.5" r=".5"></circle><circle cx="6.5" cy="12.5" r=".5"></circle><path d="M12 2a10 10 0 0 0 0 20h1.5a2.5 2.5 0 0 0 0-5H12a2 2 0 0 1 0-4h1a9 9 0 0 0 9-9 2 2 0 0 0-2-2h-8z"></path>',
                    'seo' => '<circle cx="11" cy="11" r="7"></circle><path d="m21 21-4.3-4.3"></path>',
                    'pwa' => '<rect x="7" y="2" width="10" height="20" rx="2"></rect><path d="M11 18h2"></path>',
                    'integration' => '<path d="M10 13a5 5 0 0 0 7.1 0l2.8-2.8a5 5 0 0 0-7.1-7.1L11 4.9"></path><path d="M14 11a5 5 0 0 0-7.1 0L4.1 13.8a5 5 0 0 0 7.1 7.1L13 19.1"></path>',
                    'share' => '<circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><path d="M8.6 10.7 15.4 6.3"></path><path d="M8.6 13.3l6.8 4.4"></path>',
                    'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line>',
                    'settings' => '<circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6h.09A1.65 1.65 0 0 0 10 3.09V3a2 2 0 0 1 4 0v.09A1.65 1.65 0 0 0 15 4.6a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.14.32.23.66.26 1H21a2 2 0 0 1 0 4h-1.34c-.03.34-.12.68-.26 1z"></path>',
                ];
                return '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . ($icons[$name] ?? $icons['dashboard']) . '</svg>';
            };

            $navItem = static function(string $path, string $label, string $iconName, bool $premium = false, array $activePaths = []) use ($uri, $isActive, $premiumIcon, $icon): string {
                $activePaths = $activePaths ?: [$path];
                $active = $isActive($activePaths, $uri) ? ' active' : '';
                $aria = $active ? ' aria-current="page"' : '';
                return '<a href="' . url($path) . '" class="hub-nav-link' . $active . '"' . $aria . '><span class="hub-nav-link__icon" aria-hidden="true">' . $icon($iconName) . '</span><span class="hub-nav-link__label">' . e($label) . '</span>' . ($premium ? $premiumIcon() : '') . '</a>';
            };

            $subNavItem = static function(string $path, string $label, bool $premium = false, array $activePaths = []) use ($uri, $isActive, $premiumIcon): string {
                $activePaths = $activePaths ?: [$path];
                $active = $isActive($activePaths, $uri) ? ' active' : '';
                $aria = $active ? ' aria-current="page"' : '';
                return '<a href="' . url($path) . '" class="hub-nav-sublink' . $active . '"' . $aria . '><span class="hub-nav-sublink__dot" aria-hidden="true"></span><span class="hub-nav-sublink__label">' . e($label) . '</span>' . ($premium ? $premiumIcon() : '') . '</a>';
            };

            $navGroup = static function(string $key, string $label, string $iconName, array $children, array $activePaths) use ($uri, $isActive, $icon): string {
                $isOpen = $isActive($activePaths, $uri);
                $openClass = $isOpen ? ' is-open' : '';
                $html = '<div class="hub-nav-group' . $openClass . '" data-nav-group="' . e($key) . '">';
                $html .= '<button type="button" class="hub-nav-link hub-nav-link--group" aria-expanded="' . ($isOpen ? 'true' : 'false') . '" data-nav-toggle>';
                $html .= '<span class="hub-nav-link__icon" aria-hidden="true">' . $icon($iconName) . '</span>';
                $html .= '<span class="hub-nav-link__label">' . e($label) . '</span>';
                $html .= '<svg class="hub-nav-link__chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>';
                $html .= '</button>';
                $html .= '<div class="hub-nav-sublist">' . implode('', $children) . '</div>';
                $html .= '</div>';
                return $html;
            };

            $renderSection = static function(string $title): void {
                echo '<p class="hub-sidebar__section-title">' . e($title) . '</p>';
            };

            $renderTabs = static function(array $tabs) use ($uri, $premiumIcon, $icon): void {
                echo '<div class="mgmt-tabs-frame"><nav class="mgmt-tabs" aria-label="Abas internas">';
                foreach ($tabs as $tab) {
                    $isTabActive = false;
                    foreach ((array) ($tab['active'] ?? [$tab['href']]) as $path) {
                        if ($uri === $path || ($path !== '/gestao/configuracoes' && str_starts_with($uri, rtrim($path, '/') . '/'))) {
                            $isTabActive = true;
                            break;
                        }
                    }
                    $active = $isTabActive ? ' active' : '';
                    $tabIcon = !empty($tab['icon']) ? '<span class="mgmt-tabs__icon" aria-hidden="true">' . $icon((string) $tab['icon']) . '</span>' : '';
                    echo '<a class="mgmt-tabs__link' . $active . '" href="' . url($tab['href']) . '">' . $tabIcon . '<span>' . e($tab['label']) . '</span>' . (!empty($tab['premium']) ? $premiumIcon() : '') . '</a>';
                }
                echo '</nav></div>';
            };

            $peopleTabs = [
                ['href' => '/gestao/membros', 'label' => 'Membros', 'icon' => 'users', 'active' => ['/gestao/membros']],
                ['href' => '/gestao/visitantes', 'label' => 'Visitantes', 'icon' => 'users', 'premium' => true],
                ['href' => '/gestao/novos-convertidos', 'label' => 'Novos Convertidos', 'icon' => 'award', 'premium' => true],
                ['href' => '/gestao/aniversarios', 'label' => 'Aniversariantes', 'icon' => 'birthday', 'premium' => true],
                ['href' => '/gestao/jornadas', 'label' => 'Jornada espiritual', 'icon' => 'journey', 'premium' => true],
                ['href' => '/gestao/historico', 'label' => 'Histórico', 'icon' => 'audit', 'premium' => true],
            ];
            $treasuryTabs = [
                ['href' => '/gestao/receitas', 'label' => 'Receitas', 'icon' => 'income', 'active' => ['/gestao/receitas', '/gestao/doacoes']],
                ['href' => '/gestao/despesas', 'label' => 'Despesas', 'icon' => 'expense'],
                ['href' => '/gestao/categorias-financeiras', 'label' => 'Categorias', 'icon' => 'category'],
                ['href' => '/gestao/aprovacoes-despesas', 'label' => 'Aprovações', 'icon' => 'check', 'premium' => true],
                ['href' => '/gestao/auditoria', 'label' => 'Auditoria', 'icon' => 'audit', 'premium' => true],
                ['href' => '/gestao/contas', 'label' => 'Contas / Caixas', 'icon' => 'wallet', 'premium' => true],
            ];
            $settingsTabs = [
                ['href' => '/gestao/configuracoes/usuarios', 'label' => 'Usuários', 'icon' => 'users', 'active' => ['/gestao/configuracoes', '/gestao/configuracoes/usuarios', '/gestao/usuarios']],
                ['href' => '/gestao/configuracoes/unidades', 'label' => 'Unidades', 'icon' => 'home'],
                ['href' => '/gestao/configuracoes/pix', 'label' => 'PIX / Ofertas', 'icon' => 'pix', 'premium' => true],
                ['href' => '/gestao/configuracoes/seo', 'label' => 'SEO', 'icon' => 'seo', 'premium' => true],
                ['href' => '/gestao/configuracoes/pwa', 'label' => 'APP', 'icon' => 'pwa', 'premium' => true],
            ];
            $adminTabs = [
                ['href' => '/gestao/sermoes', 'label' => 'Séries e Sermões', 'icon' => 'sermon', 'active' => ['/gestao/sermoes']],
                ['href' => '/gestao/pregadores', 'label' => 'Pregadores', 'icon' => 'users'],
                ['href' => '/gestao/relatorios', 'label' => 'Relatórios', 'icon' => 'reports'],
            ];
        ?>

        <aside class="hub-sidebar" id="hub-sidebar" role="navigation" aria-label="Menu de gestão">
            <div class="hub-sidebar__header">
                <a href="<?= url('/hub') ?>" class="hub-sidebar__logo">
                    <img src="<?= url('/assets/img/logo.png') ?>" alt="Elo 42" class="logo-dark">
                    <img src="<?= url('/assets/img/logo-color-new.png') ?>" alt="Elo 42" class="logo-light">
                </a>
            </div>

            <nav class="hub-sidebar__nav" aria-label="Navegação da gestão">
                <div class="hub-sidebar__nav-main">
                    <?= $navItem('/gestao', 'Dashboard', 'dashboard', false, ['/gestao']) ?>
                </div>

                <?php
                $peoplePaths = [
                    '/gestao/membros',
                    '/gestao/visitantes',
                    '/gestao/novos-convertidos',
                    '/gestao/aniversarios',
                    '/gestao/jornadas',
                    '/gestao/historico',
                    '/gestao/atendimento-pastoral',
                    '/gestao/celulas',
                    '/gestao/ministerios',
                ];
                echo $navGroup('membros', 'Membros', 'users', [
                    $subNavItem('/gestao/membros', 'Servos', false, ['/gestao/membros', '/gestao/visitantes', '/gestao/novos-convertidos', '/gestao/aniversarios', '/gestao/jornadas', '/gestao/historico']),
                    $subNavItem('/gestao/atendimento-pastoral', 'Atendimento pastoral', true, ['/gestao/atendimento-pastoral']),
                    $subNavItem('/gestao/celulas', 'Grupos Pequenos', true, ['/gestao/celulas']),
                    $subNavItem('/gestao/ministerios', 'Ministérios', true, ['/gestao/ministerios']),
                ], $peoplePaths);

                $treasuryPaths = [
                    '/gestao/receitas',
                    '/gestao/despesas',
                    '/gestao/categorias-financeiras',
                    '/gestao/aprovacoes-despesas',
                    '/gestao/auditoria',
                    '/gestao/contas',
                    '/gestao/doacoes',
                ];
                echo $navGroup('tesouraria', 'Tesouraria', 'wallet', [
                    $subNavItem('/gestao/receitas', 'Receitas', false, ['/gestao/receitas', '/gestao/doacoes']),
                    $subNavItem('/gestao/despesas', 'Despesas', false, ['/gestao/despesas']),
                    $subNavItem('/gestao/categorias-financeiras', 'Categorias', false, ['/gestao/categorias-financeiras']),
                    $subNavItem('/gestao/aprovacoes-despesas', 'Aprovações', true, ['/gestao/aprovacoes-despesas']),
                    $subNavItem('/gestao/auditoria', 'Auditoria', true, ['/gestao/auditoria']),
                    $subNavItem('/gestao/contas', 'Contas / Caixas', true, ['/gestao/contas']),
                ], $treasuryPaths);

                $communicationPaths = [
                    '/gestao/agenda',
                    '/gestao/eventos',
                    '/gestao/plano-leitura',
                    '/gestao/banners',
                    '/gestao/cursos',
                    '/gestao/campanhas',
                    '/gestao/conquistas',
                ];
                echo $navGroup('comunicacao', 'Comunicação', 'message', [
                    $subNavItem('/gestao/agenda', 'Agenda', false, ['/gestao/agenda']),
                    $subNavItem('/gestao/eventos', 'Eventos', false, ['/gestao/eventos']),
                    $subNavItem('/gestao/plano-leitura', 'Planos de Leitura', false, ['/gestao/plano-leitura']),
                    $subNavItem('/gestao/banners', 'Banners', true, ['/gestao/banners']),
                    $subNavItem('/gestao/cursos', 'Cursos', true, ['/gestao/cursos']),
                    $subNavItem('/gestao/campanhas', 'Campanhas', true, ['/gestao/campanhas']),
                    $subNavItem('/gestao/conquistas', 'Conquistas', true, ['/gestao/conquistas']),
                ], $communicationPaths);

                $administrationPaths = [
                    '/gestao/pregadores',
                    '/gestao/sermoes',
                    '/gestao/relatorios',
                    '/gestao/sermoes/expositor-ia',
                ];
                echo $navGroup('administracao', 'Administração', 'admin', [
                    $subNavItem('/gestao/sermoes', 'Séries e Sermões', false, ['/gestao/sermoes']),
                    $subNavItem('/gestao/pregadores', 'Pregadores', false, ['/gestao/pregadores']),
                    $subNavItem('/gestao/relatorios', 'Relatórios', true, ['/gestao/relatorios']),
                ], $administrationPaths);
                ?>

                <?= $navItem('/gestao/configuracoes/usuarios', 'Configurações', 'settings', false, ['/gestao/configuracoes', '/gestao/usuarios']) ?>
            </nav>

            <div class="hub-sidebar__footer">
                <a href="<?= url('/hub') ?>" class="hub-nav-link mgmt-sidebar-action">
                    <span class="hub-nav-link__icon" aria-hidden="true"><?= $icon('home') ?></span>
                    <span class="hub-nav-link__label">Voltar ao Hub</span>
                </a>
                <form action="<?= url('/logout') ?>" method="POST" class="mgmt-sidebar-logout">
                    <?= csrf_field() ?>
                    <button type="submit" class="hub-nav-link mgmt-sidebar-action mgmt-sidebar-action--logout">
                        <span class="hub-nav-link__icon" aria-hidden="true"><?= $icon('logout') ?></span>
                        <span class="hub-nav-link__label">Sair</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="hub-sidebar-overlay" aria-hidden="true"></div>

        <div class="hub-main">
            <header class="hub-topbar">
                <div class="hub-topbar__left">
                    <button class="hub-topbar__mobile-toggle" id="hub-sidebar-toggle" aria-label="Abrir menu" aria-expanded="false">&#9776;</button>
                    <?php
                        $orgDisplayName = trim((string) ($organization['name'] ?? ''));
                        $userDisplayName = trim((string) ($user['name'] ?? ''));
                        if ($orgDisplayName === '' || strcasecmp($orgDisplayName, $userDisplayName) === 0) {
                            $orgDisplayName = 'Sua igreja';
                        }
                    ?>
                    <div class="hub-topbar__context">
                        <span>Gest&atilde;o para Igrejas</span>
                        <strong><?= e($orgDisplayName) ?></strong>
                    </div>
                </div>
                <div class="hub-topbar__right" style="display:flex;align-items:center;gap:1rem;">
                    <?php if ($isSystemAdmin): ?>
                        <a href="<?= url('/membro') ?>" class="hub-topbar__link mgmt-member-shortcut" title="Abrir área do membro" style="display:inline-flex;align-items:center;gap:6px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none" style="color:#f59e0b;flex-shrink:0;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            Área do membro
                        </a>
                    <?php endif; ?>
                    <button type="button" class="hub-topbar__theme-toggle" id="hub-theme-toggle" aria-label="Alternar tema" data-theme-toggle>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="theme-icon theme-icon--light"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="theme-icon theme-icon--dark"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    </button>
                    <a href="<?= url('/hub/configuracoes') ?>" class="hub-topbar__link" style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;background:var(--color-primary);color:var(--color-white);font-weight:700;" title="Configurações do Hub"><?= e($initials) ?></a>
                </div>
            </header>

            <main class="hub-content" id="mgmt-main-content">
                <?php if ($alert = flash('success')): ?>
                    <div class="alert alert--success" role="alert"><?= e($alert) ?></div>
                <?php endif; ?>
                <?php if ($alert = flash('error')): ?>
                    <div class="alert alert--error" role="alert"><?= e($alert) ?></div>
                <?php endif; ?>
                <?php if ($alert = flash('warning')): ?>
                    <div class="alert alert--warning" role="alert"><?= e($alert) ?></div>
                <?php endif; ?>
                <?php
                    $mgmtTrialExpired = false;
                    $mgmtAdminEmail   = 'ricieri@starmannweb.com.br';
                    $mgmtUserEmail    = strtolower((string) ($user['email'] ?? ''));
                    $mgmtCreatedAt    = (string) ($user['created_at'] ?? '');
                    $mgmtOrgId        = (int) ($organization['id'] ?? 0);
                    $mgmtPlan         = (string) ($organization['plan'] ?? 'free');
                    $mgmtPremiumPlans = ['premium', 'professional', 'enterprise'];

                    if ($mgmtUserEmail !== $mgmtAdminEmail
                        && !in_array($mgmtPlan, $mgmtPremiumPlans, true)
                        && $mgmtCreatedAt !== ''
                        && $mgmtOrgId > 0
                    ) {
                        try {
                            $mgmtDeadline = (new \DateTimeImmutable($mgmtCreatedAt))->modify('+7 days');
                            if (new \DateTimeImmutable('now') >= $mgmtDeadline) {
                                $mgmtPdo  = \App\Core\Database::connection();
                                $mgmtStmt = $mgmtPdo->prepare("SELECT COUNT(*) FROM subscriptions WHERE organization_id = :oid AND status IN ('active','trialing')");
                                $mgmtStmt->execute(['oid' => $mgmtOrgId]);
                                if ((int) $mgmtStmt->fetchColumn() === 0) {
                                    $mgmtTrialExpired = true;
                                }
                            }
                        } catch (\Throwable $mgmtEx) {}
                    }
                ?>
                <?php if ($mgmtTrialExpired): ?>
                    <div class="alert" role="alert" style="background:rgba(217,119,6,0.14);border:1px solid #d97706;border-radius:10px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            <div>
                                <strong style="color:#fcd34d;display:block;">Período de teste encerrado</strong>
                                <span style="font-size:0.875rem;color:rgba(255,255,255,0.72);">Assine o plano de gestão para continuar usando o sistema sem interrupções.</span>
                            </div>
                        </div>
                        <a href="<?= url('/gestao/assinatura') ?>" style="flex-shrink:0;padding:8px 18px;border-radius:6px;background:#d97706;color:#fff;font-weight:600;font-size:0.875rem;text-decoration:none;">Ver planos</a>
                    </div>
                <?php endif; ?>

                <?php
                    if ($isActive(['/gestao/membros', '/gestao/visitantes', '/gestao/novos-convertidos', '/gestao/aniversarios', '/gestao/jornadas', '/gestao/historico'], $uri)) {
                        $renderTabs($peopleTabs);
                    } elseif ($isActive(['/gestao/configuracoes', '/gestao/usuarios'], $uri)) {
                        $renderTabs($settingsTabs);
                    }
                ?>

                <?= $__view->yield('content') ?>
            </main>
        </div>
    </div>

    <script src="<?= asset('js/app.js') ?>"></script>
    <script>
        document.querySelectorAll('[data-nav-group]').forEach((group) => {
            const toggle = group.querySelector('[data-nav-toggle]');
            if (!toggle) return;
            toggle.addEventListener('click', () => {
                const open = group.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        });

        document.querySelectorAll('form[data-auto-submit], form[method="GET"]').forEach((form) => {
            if (!form.matches('[data-auto-submit]') && !form.querySelector('input[type="date"], input[type="month"], select')) {
                return;
            }
            let timer = null;
            const submit = () => {
                window.clearTimeout(timer);
                timer = window.setTimeout(() => {
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                }, 180);
            };
            form.querySelectorAll('select, input[type="date"], input[type="month"]').forEach((field) => {
                field.addEventListener('change', submit);
            });
        });

        <?php if ($isPwaEnabled): ?>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?= url('/sw.js') ?>').catch(() => {});
            });
        }
        <?php endif; ?>
    </script>
</body>
</html>
