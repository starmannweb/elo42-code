<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle ?? 'Hub — Elo 42') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap">

    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/hub.css') ?>">
</head>
<body>
    <a href="#hub-main-content" class="skip-to-content">Pular para o conteúdo</a>

    <div class="hub-layout">
        <?php
            $user = \App\Core\Session::user() ?? [];
            $organization = \App\Core\Session::get('organization');
            $initials = '';
            $parts = explode(' ', $user['name'] ?? '');
            $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . substr(end($parts) ?: '', 0, 1));
        ?>

        <aside class="hub-sidebar" id="hub-sidebar" role="navigation" aria-label="Menu lateral">
            <div class="hub-sidebar__header">
                <a href="<?= url('/hub') ?>" class="hub-sidebar__logo">
                    <span class="navbar__logo-mark">E42</span>
                    Elo 42
                </a>
            </div>

            <nav class="hub-sidebar__nav" aria-label="Navegação principal">
                <p class="hub-sidebar__section-title">Principal</p>
                <a href="<?= url('/hub') ?>" class="hub-nav-link active" aria-current="page">
                    <span class="hub-nav-link__icon" aria-hidden="true">📊</span>
                    Dashboard
                </a>

                <?php if ($organization): ?>
                    <p class="hub-sidebar__section-title">Gestão</p>
                    <a href="<?= url('/gestao') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true">📊</span>
                        Painel de Gestão
                    </a>
                    <a href="<?= url('/gestao/membros') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true">👥</span>
                        Membros
                    </a>
                    <a href="<?= url('/gestao/financeiro') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true">💰</span>
                        Finanças
                    </a>
                    <a href="<?= url('/gestao/eventos') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true">📅</span>
                        Eventos
                    </a>
                    <a href="<?= url('/gestao/ministerios') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true">⛪</span>
                        Ministérios
                    </a>

                    <p class="hub-sidebar__section-title">Organização</p>
                    <a href="<?= url('/gestao/configuracoes') ?>" class="hub-nav-link">
                        <span class="hub-nav-link__icon" aria-hidden="true">⚙️</span>
                        Configurações
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
                        <span class="hub-nav-link__icon" aria-hidden="true">🛡️</span>
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

                <?= $__view->yield('content') ?>
            </main>
        </div>
    </div>

    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
