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
<body data-hub-theme="dark">
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
                    <img src="<?= url('/assets/img/logo-color.png') ?>" alt="Elo 42" height="48" class="logo-light" onerror="this.onerror=null;this.src='<?= url('/assets/img/logo.png') ?>'">
                    <img src="<?= url('/assets/img/logo.png') ?>" alt="Elo 42" height="48" class="logo-dark" onerror="this.onerror=null;this.src='<?= url('/assets/img/logo.svg') ?>'">
                </a>
            </div>

            <nav class="hub-sidebar__nav" aria-label="Navegação da gestão">
                <p class="hub-sidebar__section-title">Geral</p>
                <a href="<?= url('/gestao') ?>" class="hub-nav-link <?= $linkClass('/gestao', $uri) ?>" <?= $uri === '/gestao' ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19V9M10 19V5M16 19v-7M22 19V3"></path></svg></span>
                    Dashboard
                </a>
                <a href="<?= url('/gestao/membros') ?>" class="hub-nav-link <?= $linkClass('/gestao/membros', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/membros') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg></span>
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
                <a href="<?= url('/gestao/agenda') ?>" class="hub-nav-link <?= $linkClass('/gestao/agenda', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/agenda') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></span>
                    Agenda
                </a>
                <a href="<?= url('/gestao/eventos') ?>" class="hub-nav-link <?= $linkClass('/gestao/eventos', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/eventos') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M16 3v4M8 3v4M3 10h18"></path></svg></span>
                    Eventos
                </a>
                <a href="<?= url('/gestao/planos') ?>" class="hub-nav-link <?= $linkClass('/gestao/planos', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/planos') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg></span>
                    Plano de Ação
                </a>
                <a href="<?= url('/gestao/relatorios') ?>" class="hub-nav-link <?= $linkClass('/gestao/relatorios', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/relatorios') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg></span>
                    Relatórios
                </a>
                <a href="<?= url('/gestao/aconselhamento') ?>" class="hub-nav-link <?= $linkClass('/gestao/aconselhamento', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/aconselhamento') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></span>
                    Cuidado Pastoral
                </a>

                <p class="hub-sidebar__section-title">Conteúdo</p>
                <a href="<?= url('/gestao/cursos') ?>" class="hub-nav-link <?= $linkClass('/gestao/cursos', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/cursos') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M4 4.5A2.5 2.5 0 0 1 6.5 7H20"></path><path d="M6.5 7v10"></path></svg></span>
                    Cursos
                </a>
                <a href="<?= url('/gestao/campanhas') ?>" class="hub-nav-link <?= $linkClass('/gestao/campanhas', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/campanhas') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></span>
                    Campanhas
                </a>
                <a href="<?= url('/gestao/ministerios') ?>" class="hub-nav-link <?= $linkClass('/gestao/ministerios', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/ministerios') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span>
                    Ministérios
                </a>
                <a href="<?= url('/gestao/planos-leitura') ?>" class="hub-nav-link <?= $linkClass('/gestao/planos-leitura', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/planos-leitura') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg></span>
                    Planos de Leitura
                </a>
                <a href="<?= url('/gestao/conquistas') ?>" class="hub-nav-link <?= $linkClass('/gestao/conquistas', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/conquistas') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg></span>
                    Conquistas
                </a>
                <a href="<?= url('/gestao/sermoes') ?>" class="hub-nav-link <?= $linkClass('/gestao/sermoes', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/sermoes') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></span>
                    Sermões
                </a>
                <a href="<?= url('/hub/expositor-ia') ?>" class="hub-nav-link">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg></span>
                    Expositor IA
                </a>

                <p class="hub-sidebar__section-title">Usuários</p>
                <a href="<?= url('/gestao/usuarios') ?>" class="hub-nav-link <?= $linkClass('/gestao/usuarios', $uri, true) ?>" <?= str_starts_with($uri, '/gestao/usuarios') ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></span>
                    Controle de Usuários
                </a>
            </nav>

            <div class="hub-sidebar__footer">
                <div class="hub-sidebar__user">
                    <div class="hub-sidebar__user-avatar" aria-hidden="true"><?= e($initials) ?></div>
                    <div class="hub-sidebar__user-info" style="overflow:hidden;">
                        <div class="hub-sidebar__user-name" title="<?= e((string) ($user['email'] ?? '')) ?>"><?= e((string) ($user['email'] ?? $user['name'] ?? 'Usuário')) ?></div>
                        <div class="hub-sidebar__user-role"><?= e((string) ($organization['role_name'] ?? 'Conta ativa')) ?></div>
                    </div>
                    <a href="<?= url('/logout') ?>" aria-label="Sair" title="Sair" style="color:rgba(255,255,255,0.4); margin-left:auto; display:flex;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    </a>
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
                <div class="hub-topbar__right" style="display:flex;align-items:center;gap:1.25rem;">
                    <a href="<?= url('/gestao/configuracoes') ?>" class="hub-topbar__link" style="background:transparent;border:none;padding:0;color:var(--text-muted);cursor:pointer;display:flex;align-items:center;" aria-label="Configurações">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    </a>
                    <button type="button" class="hub-topbar__theme-toggle" id="hub-theme-toggle" aria-label="Alternar modo claro e escuro" data-theme-toggle>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-icon theme-icon--light"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-icon theme-icon--dark"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    </button>
                    <a href="<?= url('/hub') ?>" class="hub-topbar__link" style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;background:var(--color-primary);border:1px solid rgba(0,0,0,0.1);color:var(--color-white);font-weight:600;font-size:0.875rem;" title="Ir para Hub"><?= e($initials) ?></a>
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
