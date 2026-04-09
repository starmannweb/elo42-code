<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Portal do Membro') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= url('/assets/css/hub.css') ?>?v=<?= filemtime(BASE_PATH . '/public/assets/css/hub.css') ?>">
    
    <style>
        :root {
            --portal-bg: #fdfdfd;
            --portal-sidebar: #ffffff;
            --portal-text: #1f2937;
            --portal-text-muted: #6b7280;
            --portal-border: #f3f4f6;
            --portal-primary: #1e3a8a;
            --portal-accent: #f59e0b;
        }

        body {
            background-color: var(--portal-bg);
            color: var(--portal-text);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        h1, h2, h3, .serif-heading {
            font-family: 'Playfair Display', serif;
            color: #111827;
        }

        .portal-layout {
            display: flex;
            min-height: 100vh;
        }

        .portal-sidebar {
            width: 260px;
            background: var(--portal-sidebar);
            border-right: 1px solid var(--portal-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
        }

        .portal-sidebar__header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--portal-border);
        }

        .portal-sidebar__logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--portal-primary);
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .portal-sidebar__nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
        }

        .portal-sidebar__section {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--portal-text-muted);
            margin: 1.5rem 1.5rem 0.5rem;
            font-weight: 600;
        }

        .portal-nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--portal-text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .portal-nav-link:hover {
            color: var(--portal-text);
            background: #f9fafb;
        }

        .portal-nav-link.active {
            color: var(--portal-primary);
            background: rgba(30, 58, 138, 0.05);
            border-left-color: var(--portal-primary);
            font-weight: 600;
        }

        .portal-nav-link__icon {
            margin-right: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            color: inherit;
        }

        .portal-sidebar__footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--portal-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .portal-sidebar__user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }

        .portal-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e5e7eb;
            color: #4b5563;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .portal-user-info {
            display: flex;
            flex-direction: column;
        }

        .portal-user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--portal-text);
        }

        .portal-user-email {
            font-size: 0.7rem;
            color: var(--portal-text-muted);
            max-width: 130px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .portal-main {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .portal-header {
            padding: 1.5rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .portal-header-greeting {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .portal-greeting-text h1 {
            margin: 0;
            font-size: 1.75rem;
            line-height: 1.2;
        }

        .portal-greeting-text p {
            margin: 0;
            color: var(--portal-text-muted);
            font-size: 0.875rem;
        }

        .portal-header-actions {
            display: flex;
            gap: 1rem;
        }

        .portal-btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: #fff;
            color: var(--portal-text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }

        .portal-btn-icon:hover {
            color: var(--portal-text);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .portal-content {
            padding: 0 2.5rem 2.5rem;
            flex: 1;
        }

        /* Helpers */
        .portal-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.04);
            padding: 1.5rem;
        }
    </style>
</head>
<body>

    <?php
        $uri = !empty($_GET['url']) ? '/' . trim((string) $_GET['url'], '/') : (parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?? '/');

        $linkClass = static function(string $path, string $uri, bool $startsWith = false): string {
            if ($startsWith && $path !== '/membro') {
                return str_starts_with($uri, $path) ? 'active' : '';
            }
            return $uri === $path ? 'active' : '';
        };

        $parts = explode(' ', (string) ($user['name'] ?? ''));
        $initials = strtoupper(substr((string) ($parts[0] ?? 'U'), 0, 1) . substr((string) (end($parts) ?: 'U'), 0, 1));
        $orgName = $organization['name'] ?? 'Minha Igreja';
    ?>

    <div class="portal-layout">
        <aside class="portal-sidebar">
            <div class="portal-sidebar__header">
                <a href="<?= url('/membro') ?>" class="portal-sidebar__logo">
                    <?= e($orgName) ?>
                </a>
            </div>

            <nav class="portal-sidebar__nav">
                <div class="portal-sidebar__section">Menu Principal</div>
                <a href="<?= url('/membro') ?>" class="portal-nav-link <?= $linkClass('/membro', $uri) ?>">
                    <span class="portal-nav-link__icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></span>
                    Início
                </a>
                <a href="<?= url('/membro/biblia') ?>" class="portal-nav-link <?= $linkClass('/membro/biblia', $uri, true) ?>">
                    <span class="portal-nav-link__icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M4 4.5A2.5 2.5 0 0 1 6.5 7H20"></path><path d="M6.5 7v10"></path></svg></span>
                    Bíblia
                </a>
                <a href="<?= url('/membro/planos-leitura') ?>" class="portal-nav-link <?= $linkClass('/membro/planos-leitura', $uri, true) ?>">
                    <span class="portal-nav-link__icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></span>
                    Planos de Leitura
                </a>
                <a href="<?= url('/membro/ministracoes') ?>" class="portal-nav-link <?= $linkClass('/membro/ministracoes', $uri, true) ?>">
                    <span class="portal-nav-link__icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg></span>
                    Ministrações
                </a>
                <a href="<?= url('/membro/cursos') ?>" class="portal-nav-link <?= $linkClass('/membro/cursos', $uri, true) ?>">
                    <span class="portal-nav-link__icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg></span>
                    Cursos
                </a>
                <a href="<?= url('/membro/eventos') ?>" class="portal-nav-link <?= $linkClass('/membro/eventos', $uri, true) ?>">
                    <span class="portal-nav-link__icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></span>
                    Eventos
                </a>

                <div class="portal-sidebar__section">Serviços</div>
                <a href="<?= url('/membro/pedidos') ?>" class="portal-nav-link <?= $linkClass('/membro/pedidos', $uri, true) ?>">
                    <span class="portal-nav-link__icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></span>
                    Pedidos
                </a>
                <a href="<?= url('/membro/ofertas') ?>" class="portal-nav-link <?= $linkClass('/membro/ofertas', $uri, true) ?>">
                    <span class="portal-nav-link__icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg></span>
                    Ofertas
                </a>
            </nav>

            <div class="portal-sidebar__footer">
                <a href="<?= url('/membro/configuracoes') ?>" class="portal-sidebar__user">
                    <div class="portal-user-avatar"><?= e($initials) ?></div>
                    <div class="portal-user-info">
                        <span class="portal-user-name"><?= e($user['name'] ?? 'Usuário') ?></span>
                        <span class="portal-user-email"><?= e($user['email'] ?? '') ?></span>
                    </div>
                </a>
                <a href="<?= url('/membro/configuracoes') ?>" style="color: var(--portal-text-muted);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.17.42.26.86.26 1.31 0 .45-.09.89-.26 1.31"></path></svg></a>
            </div>
        </aside>

        <main class="portal-main">
            <header class="portal-header">
                <?php if ($uri === '/membro'): ?>
                    <div class="portal-header-greeting">
                        <div class="portal-user-avatar" style="width: 48px; height: 48px; font-size: 1.2rem; background: #e0e7ff; color: var(--portal-primary);"><?= e($initials) ?></div>
                        <div class="portal-greeting-text">
                            <p><?= e($greeting) ?> 👋</p>
                            <h1>Olá, <?= e($firstName) ?>!</h1>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="portal-greeting-text">
                        <h1><?= e($breadcrumb ?? $pageTitle) ?></h1>
                    </div>
                <?php endif; ?>

                <div class="portal-header-actions">
                    <button class="portal-btn-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
                    <button class="portal-btn-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg></button>
                </div>
            </header>

            <div class="portal-content">
                <?php $__view->yield('content'); ?>
            </div>
        </main>
    </div>

    <!-- Scripts e plugins gerais do Elo 42 -->
    <script src="<?= url('/assets/js/hub.js') ?>?v=<?= filemtime(BASE_PATH . '/public/assets/js/hub.js') ?>"></script>
    <?php $__view->yield('scripts'); ?>
</body>
</html>
