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
<body>
    <a href="#hub-main-content" class="skip-to-content">Pular para o conteúdo</a>

    <div class="hub-layout">
        <?php
            $user = \App\Core\Session::user() ?? [];
            $organization = \App\Core\Session::get('organization');
            $organization = is_array($organization) ? $organization : null;
            $initials = '';
            $parts = explode(' ', $user['name'] ?? '');
            $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . substr(end($parts) ?: '', 0, 1));
        ?>

        <aside class="hub-sidebar" id="hub-sidebar" role="navigation" aria-label="Menu lateral">
            <div class="hub-sidebar__header">
                <a href="<?= url('/hub') ?>" class="hub-sidebar__logo">
                    <img src="<?= url('/assets/img/logo.png') ?>" alt="Elo 42" height="28" onerror="this.onerror=null;this.src='<?= url('/assets/img/logo.svg') ?>'">
                </a>
            </div>

            <nav class="hub-sidebar__nav" aria-label="Navegação principal">
                <p class="hub-sidebar__section-title">Principal</p>
                <a href="<?= url('/hub') ?>" class="hub-nav-link active" aria-current="page">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V9M10 19V5M16 19v-7M22 19V3"></path></svg>
                    </span>
                    Dashboard
                </a>
                <a href="<?= url('/hub/#vitrine') ?>" class="hub-nav-link">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"></path></svg>
                    </span>
                    Vitrine
                </a>

                <?php if ($organization): ?>
                    <p class="hub-sidebar__section-title">Gestão</p>
                    <a href="<?= url('/gestao') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V9M10 19V5M16 19v-7M22 19V3"></path></svg></span>
                        Painel de Gestao
                    </a>
                    <a href="<?= url('/gestao/membros') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span>
                        Membros
                    </a>
                    <a href="<?= url('/gestao/financeiro') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="5" width="19" height="14" rx="2"></rect><path d="M16 12h.01M2.5 9h19"></path></svg></span>
                        Financas
                    </a>
                    <a href="<?= url('/gestao/eventos') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg></span>
                        Eventos
                    </a>
                    <a href="<?= url('/gestao/ministerios') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg></span>
                        Ministerios
                    </a>

                    <p class="hub-sidebar__section-title">Organização</p>
                    <a href="<?= url('/gestao/configuracoes') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.65 1.65 0 0 0 15 19.4a1.65 1.65 0 0 0-1 .6 1.65 1.65 0 0 0-.33 1V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-.33-1 1.65 1.65 0 0 0-1-.6 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-.6-1 1.65 1.65 0 0 0-1-.33H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1-.33 1.65 1.65 0 0 0 .6-1A1.65 1.65 0 0 0 4.6 6.5l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-.6 1.65 1.65 0 0 0 .33-1V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 .33 1 1.65 1.65 0 0 0 1 .6 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.14.32.22.66.23 1.01.01.35-.07.69-.23 1.01"></path></svg></span>
                        Configuracoes
                    </a>
                <?php endif; ?>

                <?php
                    $roleSlug = $organization['role_slug'] ?? '';
                    $perms = \App\Core\Session::get('permissions') ?? [];
                    $isAdmin = in_array($roleSlug, ['super-admin', 'admin-elo42']) || in_array('admin.access', $perms);
                ?>
                <?php if ($isAdmin): ?>
                    <p class="hub-sidebar__section-title">Admin</p>
                    <a href="<?= url('/admin') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 4v5c0 5-3.4 8-8 9-4.6-1-8-4-8-9V7l8-4z"></path><path d="M9 12l2 2 4-4"></path></svg></span>
                        Painel Admin
                    </a>
                <?php endif; ?>
            </nav>

            <div class="hub-sidebar__footer">
                <div class="hub-sidebar__user">
                    <div class="hub-sidebar__user-avatar" aria-hidden="true"><?= $initials ?></div>
                    <div class="hub-sidebar__user-info">
                        <div class="hub-sidebar__user-name"><?= e($user['name'] ?? '') ?></div>
                        <div class="hub-sidebar__user-role">
                            <?= e($organization['role_name'] ?? 'Usuário') ?>
                        </div>
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
                        <?php if ($organization): ?>
                            <span><?= e($organization['name']) ?></span>
                            <span class="hub-topbar__breadcrumb-sep" aria-hidden="true">›</span>
                        <?php endif; ?>
                        <span aria-current="page"><?= e($breadcrumb ?? 'Dashboard') ?></span>
                    </nav>
                </div>
                <div class="hub-topbar__right">
                    <a href="<?= url('/') ?>" class="hub-topbar__link">← Site</a>
                    <a href="<?= url('/logout') ?>" class="hub-topbar__link">Sair</a>
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
