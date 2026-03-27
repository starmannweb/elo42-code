<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle ?? 'Gestão — Elo 42') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap">

    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/hub.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/management.css') ?>">
</head>
<body>
    <a href="#mgmt-main-content" class="skip-to-content">Pular para o conteúdo</a>

    <div class="hub-layout">
        <?php
            $user = \App\Core\Session::user() ?? [];
            $organization = \App\Core\Session::get('organization');
            $parts = explode(' ', $user['name'] ?? '');
            $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . substr(end($parts) ?: '', 0, 1));
            $uri = '/' . trim($_GET['url'] ?? '', '/');
        ?>

        <aside class="hub-sidebar" id="hub-sidebar" role="navigation" aria-label="Menu de gestão">
            <div class="hub-sidebar__header">
                <a href="<?= url('/hub') ?>" class="hub-sidebar__logo">
                    <img src="<?= url('/assets/img/logo.svg') ?>" alt="Elo 42" height="28">
                </a>
            </div>

            <nav class="hub-sidebar__nav" aria-label="Navegação da gestão">
                <p class="hub-sidebar__section-title">Gestão</p>
                <a href="<?= url('/gestao') ?>" class="hub-nav-link <?= $uri === '/gestao' ? 'active' : '' ?>" <?= $uri === '/gestao' ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">📊</span> Dashboard
                </a>
                <a href="<?= url('/gestao/membros') ?>" class="hub-nav-link <?= str_starts_with($uri, '/gestao/membros') ? 'active' : '' ?>" <?= str_starts_with($uri, '/gestao/membros') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">👥</span> Membros
                </a>
                <a href="<?= url('/gestao/ministerios') ?>" class="hub-nav-link <?= str_starts_with($uri, '/gestao/ministerios') ? 'active' : '' ?>" <?= str_starts_with($uri, '/gestao/ministerios') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">⛪</span> Ministérios
                </a>
                <a href="<?= url('/gestao/eventos') ?>" class="hub-nav-link <?= str_starts_with($uri, '/gestao/eventos') ? 'active' : '' ?>" <?= str_starts_with($uri, '/gestao/eventos') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">📅</span> Eventos
                </a>
                <a href="<?= url('/gestao/financeiro') ?>" class="hub-nav-link <?= str_starts_with($uri, '/gestao/financeiro') ? 'active' : '' ?>" <?= str_starts_with($uri, '/gestao/financeiro') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">💰</span> Financeiro
                </a>

                <p class="hub-sidebar__section-title">Pastoral</p>
                <a href="<?= url('/gestao/solicitacoes') ?>" class="hub-nav-link <?= str_starts_with($uri, '/gestao/solicitacoes') ? 'active' : '' ?>" <?= str_starts_with($uri, '/gestao/solicitacoes') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">📝</span> Solicitações
                </a>
                <a href="<?= url('/gestao/visitas') ?>" class="hub-nav-link <?= str_starts_with($uri, '/gestao/visitas') ? 'active' : '' ?>" <?= str_starts_with($uri, '/gestao/visitas') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">🚪</span> Visitas
                </a>
                <a href="<?= url('/gestao/aconselhamento') ?>" class="hub-nav-link <?= str_starts_with($uri, '/gestao/aconselhamento') ? 'active' : '' ?>" <?= str_starts_with($uri, '/gestao/aconselhamento') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">💬</span> Aconselhamento
                </a>
                <a href="<?= url('/gestao/sermoes') ?>" class="hub-nav-link <?= str_starts_with($uri, '/gestao/sermoes') ? 'active' : '' ?>" <?= str_starts_with($uri, '/gestao/sermoes') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">📖</span> Sermões
                </a>

                <p class="hub-sidebar__section-title">Operacional</p>
                <a href="<?= url('/gestao/planos') ?>" class="hub-nav-link <?= str_starts_with($uri, '/gestao/planos') ? 'active' : '' ?>" <?= str_starts_with($uri, '/gestao/planos') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">🎯</span> Plano de Ação
                </a>
                <a href="<?= url('/gestao/doacoes') ?>" class="hub-nav-link <?= str_starts_with($uri, '/gestao/doacoes') ? 'active' : '' ?>" <?= str_starts_with($uri, '/gestao/doacoes') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">🤝</span> Doações
                </a>
                <a href="<?= url('/gestao/relatorios') ?>" class="hub-nav-link <?= str_starts_with($uri, '/gestao/relatorios') ? 'active' : '' ?>" <?= str_starts_with($uri, '/gestao/relatorios') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">📈</span> Relatórios
                </a>
                <a href="<?= url('/gestao/configuracoes') ?>" class="hub-nav-link <?= str_starts_with($uri, '/gestao/configuracoes') ? 'active' : '' ?>" <?= str_starts_with($uri, '/gestao/configuracoes') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true">⚙️</span> Configurações
                </a>
            </nav>

            <div class="hub-sidebar__footer">
                <div class="hub-sidebar__user">
                    <div class="hub-sidebar__user-avatar" aria-hidden="true"><?= $initials ?></div>
                    <div class="hub-sidebar__user-info">
                        <div class="hub-sidebar__user-name"><?= e($user['name'] ?? '') ?></div>
                        <div class="hub-sidebar__user-role"><?= e($organization['role_name'] ?? 'Usuário') ?></div>
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
                        <span aria-current="page"><?= e($breadcrumb ?? 'Gestão') ?></span>
                    </nav>
                </div>
                <div class="hub-topbar__right">
                    <a href="<?= url('/hub') ?>" class="hub-topbar__link">← Hub</a>
                    <a href="<?= url('/logout') ?>" class="hub-topbar__link">Sair</a>
                </div>
            </header>

            <main class="hub-content" id="mgmt-main-content">
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
