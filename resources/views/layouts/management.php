<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#1e3a8a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Elo 42">
    <link rel="manifest" href="<?= url('/manifest.json') ?>">
    <link rel="apple-touch-icon" href="<?= url('/assets/img/logo-color-new.png') ?>">
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
            $activeMenu = (string) ($activeMenu ?? 'dashboard');
        $currentPlan = is_array($organization) ? (string) ($organization['plan'] ?? 'free') : 'free';
        
        // Trial grace period
        $isTrialActive = false;
        if ($currentPlan === 'free' && !empty($user['created_at'])) {
            try {
                $created = new \DateTimeImmutable($user['created_at']);
                if (new \DateTimeImmutable('now') < $created->modify('+7 days')) {
                    $isTrialActive = true;
                }
            } catch (\Throwable $e) {}
        }

        $parts = explode(' ', (string) ($user['name'] ?? ''));
        $initials = strtoupper(substr((string) ($parts[0] ?? 'U'), 0, 1) . substr((string) (end($parts) ?: 'U'), 0, 1));
        $uri = !empty($_GET['url']) ? '/' . trim((string) $_GET['url'], '/') : (parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?? '/');

        $linkClass = static function(string $path, string $uri, bool $startsWith = false): string {
            if ($startsWith) {
                return str_starts_with($uri, $path) ? 'active' : '';
            }
            return $uri === $path ? 'active' : '';
        };

        $proLock = static function() use ($currentPlan): string {
            if ($currentPlan === 'free') {
                return '<span style="margin-left:auto; font-size: 0.75rem;" title="Recurso Premium"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" style="color:#f59e0b;"><path d="M6 3h12l4 6-10 12L2 9l4-6z"></path></svg></span>';
            }
            return '';
        };
        ?>

        <aside class="hub-sidebar" id="hub-sidebar" role="navigation" aria-label="Menu do sistema da igreja">
            <div class="hub-sidebar__header">
                <a href="<?= url('/hub') ?>" class="hub-sidebar__logo" style="display:flex; align-items:center; justify-content:center; height:56px;">
                    <img src="<?= url('/assets/img/logo-color-new.png') ?>" alt="Elo 42" style="height:44px; width:auto; object-fit:contain;" class="logo-light" onerror="this.onerror=null;this.src='<?= url('/assets/img/logo.png') ?>'">
                    <img src="<?= url('/assets/img/logo.png') ?>" alt="Elo 42" style="height:44px; width:auto; object-fit:contain;" class="logo-dark" onerror="this.onerror=null;this.src='<?= url('/assets/img/logo.svg') ?>'">
                </a>
            </div>

            <nav class="hub-sidebar__nav" aria-label="Navegação da gestão">
                <p class="hub-sidebar__section-title" style="margin-top: 0;">Geral</p>
                <a href="<?= url('/gestao') ?>" class="hub-nav-link <?= $linkClass('/gestao', $uri) ?>" <?= $uri === '/gestao' ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg></span>
                    Dashboard
                </a>

                <?php 
                    $isMembrosActive = str_starts_with($uri, '/gestao/membros') || str_starts_with($uri, '/gestao/visitantes') || str_starts_with($uri, '/gestao/novos-convertidos') || str_starts_with($uri, '/gestao/aniversarios') || str_starts_with($uri, '/gestao/celulas') || str_starts_with($uri, '/gestao/equipes') || str_starts_with($uri, '/gestao/atendimento-pastoral') || str_starts_with($uri, '/gestao/jornadas') || str_starts_with($uri, '/gestao/historico');
                ?>
                <div class="sidebar-dropdown <?= $isMembrosActive ? 'active' : '' ?>">
                    <button type="button" class="hub-nav-link sidebar-dropdown-toggle" style="width: 100%; border: none; background: transparent; cursor: pointer; justify-content: space-between;">
                        <div style="display: flex; align-items: center;">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span>
                            Pessoas
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="dropdown-arrow"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="sidebar-dropdown-menu">
                        <a href="<?= url('/gestao/membros/visao-geral') ?>" class="hub-nav-link <?= $linkClass('/gestao/membros/visao-geral', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span> Visão Geral
                        </a>
                        <a href="<?= url('/gestao/membros') ?>" class="hub-nav-link <?= $linkClass('/gestao/membros', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg></span> Lista de Membros
                        </a>
                        <a href="<?= url('/gestao/visitantes') ?>" class="hub-nav-link <?= $linkClass('/gestao/visitantes', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></span> Visitantes <?= $proLock() ?>
                        </a>
                        <a href="<?= url('/gestao/novos-convertidos') ?>" class="hub-nav-link <?= $linkClass('/gestao/novos-convertidos', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></span> Novos Convertidos <?= $proLock() ?>
                        </a>
                        <a href="<?= url('/gestao/aniversarios') ?>" class="hub-nav-link <?= $linkClass('/gestao/aniversarios', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-3-3.87"></path><path d="M4 21v-2a4 4 0 0 1 3-3.87"></path><circle cx="12" cy="7" r="4"></circle></svg></span> Aniversários <?= $proLock() ?>
                        </a>
                        <a href="<?= url('/gestao/celulas') ?>" class="hub-nav-link <?= $linkClass('/gestao/celulas', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg></span> Células <?= $proLock() ?>
                        </a>
                        <a href="<?= url('/gestao/equipes') ?>" class="hub-nav-link <?= $linkClass('/gestao/equipes', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg></span> Equipes
                        </a>
                        <a href="<?= url('/gestao/atendimento-pastoral') ?>" class="hub-nav-link <?= $linkClass('/gestao/atendimento-pastoral', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg></span> Atendimento Pastoral
                        </a>
                        <a href="<?= url('/gestao/jornadas') ?>" class="hub-nav-link <?= $linkClass('/gestao/jornadas', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s-8-4.5-8-11a4 4 0 0 1 7-2.6A4 4 0 0 1 18 10c0 6.5-6 11-6 11z"></path></svg></span> Jornadas <?= $proLock() ?>
                        </a>
                        <a href="<?= url('/gestao/historico') ?>" class="hub-nav-link <?= $linkClass('/gestao/historico', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></span> Histórico <?= $proLock() ?>
                        </a>
                    </div>
                </div>

                <?php 
                    $isFinActive = str_starts_with($uri, '/gestao/receitas') || str_starts_with($uri, '/gestao/despesas') || str_starts_with($uri, '/gestao/aprovacoes-despesas') || str_starts_with($uri, '/gestao/auditoria') || str_starts_with($uri, '/gestao/contas');
                ?>
                <div class="sidebar-dropdown <?= $isFinActive ? 'active' : '' ?>">
                    <button type="button" class="hub-nav-link sidebar-dropdown-toggle" style="width: 100%; border: none; background: transparent; cursor: pointer; justify-content: space-between;">
                        <div style="display: flex; align-items: center;">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg></span>
                            Financeiro
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="dropdown-arrow"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="sidebar-dropdown-menu">
                        <a href="<?= url('/gestao/receitas') ?>" class="hub-nav-link <?= $linkClass('/gestao/receitas', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></span> Receitas
                        </a>
                        <a href="<?= url('/gestao/despesas') ?>" class="hub-nav-link <?= $linkClass('/gestao/despesas', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg></span> Despesas
                        </a>
                        <a href="<?= url('/gestao/aprovacoes-despesas') ?>" class="hub-nav-link <?= $linkClass('/gestao/aprovacoes-despesas', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></span> Aprovações de Despesas <?= $proLock() ?>
                        </a>
                        <a href="<?= url('/gestao/auditoria') ?>" class="hub-nav-link <?= $linkClass('/gestao/auditoria', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></span> Auditoria <?= $proLock() ?>
                        </a>
                        <a href="<?= url('/gestao/contas') ?>" class="hub-nav-link <?= $linkClass('/gestao/contas', $uri) ?>">
                            <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2.5" y="5" width="19" height="14" rx="2"></rect><path d="M16 12h.01"></path><path d="M2.5 9h19"></path></svg></span> Contas / Caixas <?= $proLock() ?>
                        </a>
                    </div>
                </div>

                <p class="hub-sidebar__section-title">Conteúdo</p>
                <a href="<?= url('/gestao/banners') ?>" class="hub-nav-link <?= $linkClass('/gestao/banners', $uri, true) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg></span>
                    Banners <?= $proLock() ?>
                </a>
                <a href="<?= url('/gestao/ministracoes') ?>" class="hub-nav-link <?= $linkClass('/gestao/ministracoes', $uri, true) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg></span>
                    Ministrações
                </a>
                <a href="<?= url('/gestao/agenda') ?>" class="hub-nav-link <?= $linkClass('/gestao/agenda', $uri, true) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></span>
                    Agenda / Eventos
                </a>
                <a href="<?= url('/gestao/cursos') ?>" class="hub-nav-link <?= $linkClass('/gestao/cursos', $uri, true) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M4 4.5A2.5 2.5 0 0 1 6.5 7H20"></path><path d="M6.5 7v10"></path></svg></span>
                    Cursos <?= $proLock() ?>
                </a>
                <a href="<?= url('/gestao/solicitacoes') ?>" class="hub-nav-link <?= $linkClass('/gestao/solicitacoes', $uri, true) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></span>
                    Solicitações
                </a>
                <a href="<?= url('/gestao/campanhas') ?>" class="hub-nav-link <?= $linkClass('/gestao/campanhas', $uri, true) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></span>
                    Campanhas <?= $proLock() ?>
                </a>
                <a href="<?= url('/gestao/pregadores') ?>" class="hub-nav-link <?= $linkClass('/gestao/pregadores', $uri, true) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></span>
                    Pregadores
                </a>
                <a href="<?= url('/gestao/temas') ?>" class="hub-nav-link <?= $linkClass('/gestao/temas', $uri, true) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg></span>
                    Temas
                </a>
                <a href="<?= url('/gestao/plano-leitura') ?>" class="hub-nav-link <?= $linkClass('/gestao/plano-leitura', $uri, true) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg></span>
                    Planos de Leitura
                </a>
                <a href="<?= url('/gestao/conquistas') ?>" class="hub-nav-link <?= $linkClass('/gestao/conquistas', $uri, true) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg></span>
                    Conquistas <?= $proLock() ?>
                </a>

                <p class="hub-sidebar__section-title">Usuários</p>
                <a href="<?= url('/gestao/usuarios') ?>" class="hub-nav-link <?= $linkClass('/gestao/usuarios', $uri, true) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span>
                    Papéis/Permissões <?= $proLock() ?>
                </a>

                <p class="hub-sidebar__section-title">Configurações</p>
                <a href="<?= url('/gestao/configuracoes') ?>" class="hub-nav-link <?= $linkClass('/gestao/configuracoes', $uri) ?>" <?= $uri === '/gestao/configuracoes' ? 'aria-current="page"' : '' ?>>
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg></span>
                    Igreja
                </a>
                <a href="<?= url('/gestao/categorias-financeiras') ?>" class="hub-nav-link <?= $linkClass('/gestao/categorias-financeiras', $uri) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg></span>
                    Categorias
                </a>
                <a href="<?= url('/gestao/configuracoes/pix') ?>" class="hub-nav-link <?= $linkClass('/gestao/configuracoes/pix', $uri) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg></span>
                    PIX/Ofertas
                </a>
                <a href="<?= url('/gestao/configuracoes/ia') ?>" class="hub-nav-link <?= $linkClass('/gestao/configuracoes/ia', $uri) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1a7 7 0 0 1-7 7h-2a7 7 0 0 1-7-7H3a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2z"></path><circle cx="10" cy="13" r="1"></circle><circle cx="14" cy="13" r="1"></circle></svg></span>
                    Inteligência Artificial
                </a>
                <a href="<?= url('/gestao/configuracoes/aparencia') ?>" class="hub-nav-link <?= $linkClass('/gestao/configuracoes/aparencia', $uri) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="13.5" cy="6.5" r="2.5"></circle><path d="M14.5 14.5l-6-6M9.5 17.5l-6-6"></path><path d="M17 19.5c-2.8-2.8-6.2-1.5-8-1 1.8 1.8 4.2 3.5 7 2.5 1.2-.5 1.5-1 1-1.5z"></path></svg></span>
                    Aparência
                </a>
                <a href="<?= url('/gestao/configuracoes/seo') ?>" class="hub-nav-link <?= $linkClass('/gestao/configuracoes/seo', $uri) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></span>
                    SEO
                </a>
                <a href="<?= url('/gestao/configuracoes/pwa') ?>" class="hub-nav-link <?= $linkClass('/gestao/configuracoes/pwa', $uri) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg></span>
                    PWA
                </a>
            </nav>

            <div class="hub-sidebar__footer" style="padding-top: 1rem;">
                <form action="<?= url('/logout') ?>" method="POST" style="width: 100%;">
                    <?= csrf_field() ?>
                    <button type="submit" class="hub-nav-link" style="width: 100%; border: none; background: transparent; cursor: pointer; color: var(--text-muted); justify-content: flex-start;">
                        <span class="hub-nav-link__icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg></span>
                        Sair
                    </button>
                </form>
            </div>
        </aside>

        <style>
            .sidebar-dropdown-menu {
                display: none;
                flex-direction: column;
                background: rgba(0,0,0,0.02);
                border-radius: 8px;
                margin: 0.25rem 1rem;
                padding: 0.5rem 0;
            }
            .sidebar-dropdown.active .sidebar-dropdown-menu {
                display: flex;
            }
            .sidebar-dropdown.active .dropdown-arrow {
                transform: rotate(180deg);
            }
            .dropdown-arrow {
                transition: transform 0.2s;
            }
            .sidebar-dropdown-menu .hub-nav-link {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
                margin: 0.1rem 0.5rem;
                color: var(--text-muted);
            }
            .sidebar-dropdown-menu .hub-nav-link.active {
                background: white;
                color: var(--color-primary);
                font-weight: 600;
                box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            }
        </style>
        <script>
            document.querySelectorAll('.sidebar-dropdown-toggle').forEach(btn => {
                btn.addEventListener('click', () => {
                    const parent = btn.parentElement;
                    parent.classList.toggle('active');
                });
            });
        </script>

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
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?= url('/sw.js') ?>')
                    .then(reg => console.log('ServiceWorker registered:', reg.scope))
                    .catch(err => console.log('ServiceWorker registration failed:', err));
            });
        }
    </script>
</body>
</html>
