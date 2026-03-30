<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle ?? 'Gestão — Elo 42') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Saira:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap">

    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/hub.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/management.css') ?>">
</head>
<body>
    <a href="#mgmt-main-content" class="skip-to-content">Pular para o conteúdo</a>

    <div class="hub-layout mgmt-layout">
        <?php
            $user = \App\Core\Session::user() ?? [];
            $organization = \App\Core\Session::get('organization');
            $organization = is_array($organization) ? $organization : [];
            $parts = explode(' ', (string) ($user['name'] ?? ''));
            $initials = strtoupper(substr((string) ($parts[0] ?? 'U'), 0, 1) . substr((string) (end($parts) ?: 'U'), 0, 1));
            $uri = !empty($_GET['url']) ? '/' . trim((string) $_GET['url'], '/') : (parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?? '/');

            $linkClass = static function(string $path, string $uri, bool $startsWith = false): string {
                if ($startsWith) {
                    return str_starts_with($uri, $path) ? 'active' : '';
                }
                return $uri === $path ? 'active' : '';
            };
        ?>

        <aside class="hub-sidebar" id="hub-sidebar" role="navigation" aria-label="Menu do sistema da igreja">
            <div class="hub-sidebar__header">
                <a href="<?= url('/hub') ?>" class="hub-sidebar__logo">
                    <img src="<?= url('/assets/img/logo.png') ?>" alt="Elo 42" height="34" onerror="this.onerror=null;this.src='<?= url('/assets/img/logo.svg') ?>'">
                </a>
                <p class="hub-sidebar__brand-subtitle">Sistema da igreja</p>
            </div>

            <nav class="hub-sidebar__nav" aria-label="Navegação da gestão">
                <p class="hub-sidebar__section-title">Geral</p>
                <a href="<?= url('/gestao') ?>" class="hub-nav-link <?= $linkClass('/gestao', $uri) ?>" <?= $uri === '/gestao' ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19V9M10 19V5M16 19v-7M22 19V3"></path></svg></span>
                    Dashboard
                </a>
                <a href="<?= url('/gestao/membros') ?>" class="hub-nav-link <?= $linkClass('/gestao/membros', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/membros') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path></svg></span>
                    Membros
                </a>
                <a href="<?= url('/gestao/financeiro') ?>" class="hub-nav-link <?= $linkClass('/gestao/financeiro', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/financeiro') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2.5" y="5" width="19" height="14" rx="2"></rect><path d="M16 12h.01"></path><path d="M2.5 9h19"></path></svg></span>
                    Financeiro
                </a>
                <a href="<?= url('/gestao/doacoes') ?>" class="hub-nav-link <?= $linkClass('/gestao/doacoes', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/doacoes') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-8-4.5-8-11a4 4 0 0 1 7-2.6A4 4 0 0 1 18 10c0 6.5-6 11-6 11z"></path></svg></span>
                    Doações PIX
                </a>
                <a href="<?= url('/gestao/eventos') ?>" class="hub-nav-link <?= $linkClass('/gestao/eventos', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/eventos') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg></span>
                    Eventos
                </a>
                <a href="<?= url('/gestao/planos') ?>" class="hub-nav-link <?= $linkClass('/gestao/planos', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/planos') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h18"></path><path d="M8 7V3"></path><path d="M16 7V3"></path><rect x="3" y="7" width="18" height="14" rx="2"></rect></svg></span>
                    Plano de ação
                </a>
                <a href="<?= url('/gestao/relatorios') ?>" class="hub-nav-link <?= $linkClass('/gestao/relatorios', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/relatorios') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19V9M10 19V5M16 19v-7M22 19V3"></path></svg></span>
                    Relatórios
                </a>

                <p class="hub-sidebar__section-title">Conteúdo</p>
                <a href="<?= url('/gestao/sermoes') ?>" class="hub-nav-link <?= $linkClass('/gestao/sermoes', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/sermoes') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M4 4.5A2.5 2.5 0 0 1 6.5 7H20"></path><path d="M6.5 7v10"></path></svg></span>
                    Sermões
                </a>
                <a href="<?= url('/hub/expositor-ia') ?>" class="hub-nav-link">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg></span>
                    Expositor IA
                </a>

                <p class="hub-sidebar__section-title">Pastoral</p>
                <a href="<?= url('/gestao/solicitacoes') ?>" class="hub-nav-link <?= $linkClass('/gestao/solicitacoes', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/solicitacoes') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 21h8"></path><path d="M12 17v4"></path><path d="M5 4h14"></path><path d="M17 4v5a5 5 0 0 1-10 0V4"></path></svg></span>
                    Solicitações
                </a>
                <a href="<?= url('/gestao/visitas') ?>" class="hub-nav-link <?= $linkClass('/gestao/visitas', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/visitas') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"></path><path d="M5 21V7l7-4 7 4v14"></path></svg></span>
                    Visitas
                </a>
                <a href="<?= url('/gestao/aconselhamento') ?>" class="hub-nav-link <?= $linkClass('/gestao/aconselhamento', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/aconselhamento') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></span>
                    Cuidado pastoral
                </a>

                <p class="hub-sidebar__section-title">Conta</p>
                <a href="<?= url('/gestao/configuracoes') ?>" class="hub-nav-link <?= $linkClass('/gestao/configuracoes', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/configuracoes') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33"></path><path d="M4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33"></path><path d="M9 3h6M9 21h6M3 9v6M21 9v6"></path></svg></span>
                    Configurações
                </a>
            </nav>

            <div class="hub-sidebar__footer">
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
                        <?php if (!empty($organization['name'])): ?>
                            <span><?= e((string) $organization['name']) ?></span>
                            <span class="hub-topbar__breadcrumb-sep" aria-hidden="true">›</span>
                        <?php endif; ?>
                        <span aria-current="page"><?= e((string) ($breadcrumb ?? 'Gestão')) ?></span>
                    </nav>
                </div>
                <div class="hub-topbar__right">
                    <a href="<?= url('/hub/suporte') ?>" class="hub-topbar__link">Suporte</a>
                    <a href="<?= url('/hub') ?>" class="hub-topbar__link">Hub</a>
                    <a href="<?= url('/logout') ?>" class="hub-topbar__link hub-topbar__link--danger">Sair</a>
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
                <?= $__view->yield('content') ?>
            </main>
        </div>
    </div>

    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
