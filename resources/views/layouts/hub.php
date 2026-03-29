<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle ?? 'Hub — Elo 42') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Saira:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap">

    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/hub.css') ?>">
</head>
<body data-hub-theme="dark">
    <a href="#hub-main-content" class="skip-to-content">Pular para o conteúdo</a>

    <?php
        $user = \App\Core\Session::user() ?? [];
        $organization = \App\Core\Session::get('organization');
        $organization = is_array($organization) ? $organization : null;
        $activeMenu = (string) ($activeMenu ?? 'dashboard');
        $parts = preg_split('/\s+/', trim((string) ($user['name'] ?? '')));
        $firstInitial = strtoupper(substr((string) ($parts[0] ?? 'U'), 0, 1));
        $lastInitial = strtoupper(substr((string) (end($parts) ?: 'U'), 0, 1));
        $initials = trim($firstInitial . $lastInitial) !== '' ? $firstInitial . $lastInitial : 'U';
        $organizationName = (string) ($organization['name'] ?? 'Sem organização');

        $isMenuActive = static function (string $key, string $active): string {
            return $key === $active ? 'active' : '';
        };
    ?>

    <div class="hub-layout">
        <aside class="hub-sidebar" id="hub-sidebar" role="navigation" aria-label="Menu lateral">
            <div class="hub-sidebar__header">
                <a href="<?= url('/hub') ?>" class="hub-sidebar__logo">
                    <img src="<?= url('/assets/img/logo.png') ?>" alt="Elo 42" height="34" onerror="this.onerror=null;this.src='<?= url('/assets/img/logo.svg') ?>'">
                </a>
                <p class="hub-sidebar__brand-subtitle">Hub de membros</p>
            </div>

            <nav class="hub-sidebar__nav" aria-label="Navegação principal">
                <p class="hub-sidebar__section-title">Principal</p>
                <a href="<?= url('/hub') ?>" class="hub-nav-link <?= e($isMenuActive('dashboard', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V9M10 19V5M16 19v-7M22 19V3"></path></svg>
                    </span>
                    Dashboard
                </a>
                <a href="<?= url('/hub/vitrine') ?>" class="hub-nav-link <?= e($isMenuActive('vitrine', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"></path></svg>
                    </span>
                    Vitrine
                </a>

                <p class="hub-sidebar__section-title">Ecossistema</p>
                <a href="<?= url('/hub/sites') ?>" class="hub-nav-link <?= e($isMenuActive('sites', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M3 12h18M12 3a14.5 14.5 0 0 1 0 18M12 3a14.5 14.5 0 0 0 0 18"></path></svg>
                    </span>
                    Meus sites
                </a>
                <a href="<?= url('/hub/expositor-ia') ?>" class="hub-nav-link <?= e($isMenuActive('expositor', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    </span>
                    Expositor IA
                </a>
                <a href="<?= url('/hub/creditos') ?>" class="hub-nav-link <?= e($isMenuActive('creditos', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 8A2.5 2.5 0 0 1 5 5.5h14A2.5 2.5 0 0 1 21.5 8v8A2.5 2.5 0 0 1 19 18.5H5A2.5 2.5 0 0 1 2.5 16z"></path><path d="M15 12h.01"></path><path d="M2.5 9.5h19"></path></svg>
                    </span>
                    Créditos
                </a>
                <a href="<?= url('/hub/suporte') ?>" class="hub-nav-link <?= e($isMenuActive('suporte', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 1 0-12 0v7a3 3 0 0 0 3 3h1"></path><path d="M14 19h2a3 3 0 0 0 3-3V8"></path><path d="M18 16h2"></path></svg>
                    </span>
                    Suporte
                </a>
                <a href="<?= url('/hub/configuracoes') ?>" class="hub-nav-link <?= e($isMenuActive('configuracoes', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 .6 1.65 1.65 0 0 0-.33 1V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-.33-1 1.65 1.65 0 0 0-1-.6 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-.6-1 1.65 1.65 0 0 0-1-.33H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1-.33 1.65 1.65 0 0 0 .6-1A1.65 1.65 0 0 0 4.6 6.5l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-.6 1.65 1.65 0 0 0 .33-1V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 .33 1 1.65 1.65 0 0 0 1 .6 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.14.32.22.66.23 1.01.01.35-.07.69-.23 1.01"></path></svg>
                    </span>
                    Configurações
                </a>

                <?php if (!empty($organization['id'])): ?>
                    <p class="hub-sidebar__section-title">Igreja</p>
                    <a href="<?= url('/gestao') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18M5 10h14"></path><path d="M7 21v-8h10v8"></path></svg>
                        </span>
                        Sistema da igreja
                    </a>
                <?php endif; ?>
            </nav>

            <div class="hub-sidebar__footer">
                <button type="button" class="hub-theme-toggle" id="hub-theme-toggle" aria-label="Alternar modo claro e escuro" data-theme-toggle>
                    Modo claro
                </button>

                <div class="hub-sidebar__user">
                    <div class="hub-sidebar__user-avatar" aria-hidden="true"><?= e($initials) ?></div>
                    <div class="hub-sidebar__user-info">
                        <div class="hub-sidebar__user-name"><?= e((string) ($user['name'] ?? 'Usuário')) ?></div>
                        <div class="hub-sidebar__user-role"><?= e((string) ($organization['role_name'] ?? 'Conta ativa')) ?></div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="hub-sidebar-overlay" aria-hidden="true"></div>

        <div class="hub-main">
            <header class="hub-topbar">
                <div class="hub-topbar__left">
                    <button class="hub-topbar__mobile-toggle" id="hub-sidebar-toggle" aria-label="Abrir menu" aria-expanded="false">☰</button>
                    <nav class="hub-topbar__breadcrumb" aria-label="Breadcrumb">
                        <span><?= e($organizationName) ?></span>
                        <span class="hub-topbar__breadcrumb-sep" aria-hidden="true">›</span>
                        <span aria-current="page"><?= e((string) ($breadcrumb ?? 'Dashboard')) ?></span>
                    </nav>
                </div>
                <div class="hub-topbar__right">
                    <a href="<?= url('/hub/suporte') ?>" class="hub-topbar__link">Suporte</a>
                    <a href="<?= url('/') ?>" class="hub-topbar__link">Site</a>
                    <a href="<?= url('/logout') ?>" class="hub-topbar__link hub-topbar__link--danger">Sair</a>
                </div>
            </header>

            <main class="hub-content" id="hub-main-content">
                <?php if ($successAlert = flash('success')): ?>
                    <div class="alert alert--success" role="alert"><?= e($successAlert) ?></div>
                <?php endif; ?>

                <?php if ($errorAlert = flash('error')): ?>
                    <div class="alert alert--error" role="alert"><?= e($errorAlert) ?></div>
                <?php endif; ?>

                <?php if ($warningAlert = flash('warning')): ?>
                    <div class="alert alert--warning" role="alert"><?= e($warningAlert) ?></div>
                <?php endif; ?>

                <?= $__view->yield('content') ?>
            </main>
        </div>
    </div>

    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
