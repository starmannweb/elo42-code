<!DOCTYPE html>
<?php
    $hubOrganizationCtx = \App\Core\Session::get('organization');
    $hubOrganizationCtx = is_array($hubOrganizationCtx) ? $hubOrganizationCtx : [];
    $isHubPwaEnabled = true;
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#1e3a8a">
    <?php if ($isHubPwaEnabled): ?>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="Elo 42">
        <link rel="manifest" href="<?= url('/app-manifest') ?>">
        <link rel="apple-touch-icon" href="<?= url('/assets/img/logo-color-new.png') ?>">
    <?php endif; ?>
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
        $currentPlan = $organization['plan'] ?? 'free';
        $isTrialActive = false;
        $trialDaysLeft = 0;
        if ($currentPlan === 'free' && !empty($user['created_at'])) {
            try {
                $created = new \DateTimeImmutable($user['created_at']);
                $deadline = $created->modify('+7 days');
                $now = new \DateTimeImmutable('now');
                if ($now < $deadline) {
                    $isTrialActive = true;
                    $trialDaysLeft = (int) ceil(($deadline->getTimestamp() - $now->getTimestamp()) / 86400);
                }
            } catch (\Throwable $e) {}
        }
        $parts = preg_split('/\s+/', trim((string) ($user['name'] ?? '')));
        $firstInitial = strtoupper(substr((string) ($parts[0] ?? 'U'), 0, 1));
        $lastInitial = strtoupper(substr((string) (end($parts) ?: 'U'), 0, 1));
        $initials = trim($firstInitial . $lastInitial) !== '' ? $firstInitial . $lastInitial : 'U';
        $rawOrgName = trim((string) ($organization['name'] ?? ''));
        $userDisplayName = trim((string) ($user['name'] ?? ''));
        $organizationName = $rawOrgName;
        if ($organizationName === '' || strcasecmp($organizationName, $userDisplayName) === 0) {
            $organizationName = 'Sua igreja';
        }
        $isMasterAdmin = strtolower((string) ($user['email'] ?? '')) === 'ricieri@starmannweb.com.br';
        $churchAccess = is_array($churchManagementAccess ?? null) ? $churchManagementAccess : [
            'can_access' => !empty($organization['id']),
            'entry_url'  => !empty($organization['id']) ? url('/gestao') : url('/onboarding/organizacao'),
            'is_trial'   => false,
        ];
        $canAccessChurch = !empty($churchAccess['can_access']) && ($organization['role_slug'] ?? '') !== 'member';
        $churchEntryUrl = (string) ($churchAccess['entry_url'] ?? url('/onboarding/organizacao'));
        $churchIsTrial = !empty($churchAccess['is_trial']);
        $hubSiteUrl = url('/');
        if (!empty($organization['id'])) {
            try {
                $pdo = \App\Core\Database::connection();
                $stmt = $pdo->prepare('SHOW TABLES LIKE :table');
                $stmt->execute(['table' => 'organization_sites']);
                if ($stmt->fetchColumn()) {
                    $siteStmt = $pdo->prepare("SELECT domain, slug FROM organization_sites WHERE organization_id = :org_id ORDER BY CASE WHEN status = 'published' THEN 0 ELSE 1 END, updated_at DESC LIMIT 1");
                    $siteStmt->execute(['org_id' => (int) $organization['id']]);
                    $site = $siteStmt->fetch();
                    if (is_array($site)) {
                        $domain = trim((string) ($site['domain'] ?? ''));
                        $slug = trim((string) ($site['slug'] ?? ''));
                        if ($domain !== '') {
                            $hubSiteUrl = preg_match('/^https?:\/\//i', $domain) ? $domain : 'https://' . $domain;
                        } elseif ($slug !== '') {
                            $hubSiteUrl = url('/site/' . rawurlencode($slug));
                        }
                    }
                }
            } catch (\Throwable $e) {
                $hubSiteUrl = url('/');
            }
        }

        $isMenuActive = static function (string $key, string $active): string {
            return $key === $active ? 'active' : '';
        };
    ?>

    <div class="hub-layout">
        <aside class="hub-sidebar" id="hub-sidebar" role="navigation" aria-label="Menu lateral">
            <div class="hub-sidebar__header">
                <a href="<?= url('/hub') ?>" class="hub-sidebar__logo">
                    <img src="<?= url('/assets/img/logo.png') ?>" alt="Elo 42" class="logo-dark">
                    <img src="<?= url('/assets/img/logo-color-new.png') ?>" alt="Elo 42" class="logo-light">
                </a>
            </div>

            <nav class="hub-sidebar__nav" aria-label="Navegação principal">
                <a href="<?= url('/hub') ?>" class="hub-nav-link <?= e($isMenuActive('dashboard', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    </span>
                    Dashboard
                </a>
                <a href="<?= url('/hub/vitrine') ?>" class="hub-nav-link <?= e($isMenuActive('vitrine', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"></path></svg>
                    </span>
                    Catálogo
                </a>

                <?php if (($organization['role_slug'] ?? '') !== 'member'): ?>
                    <a href="<?= e($churchEntryUrl) ?>" class="hub-nav-link" target="_blank" rel="noopener noreferrer">
                        <span class="hub-nav-link__icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21h16"></path><path d="M5 21V9l7-5 7 5v12"></path><path d="M9 21v-7h6v7"></path><path d="M12 4v4"></path><path d="M10 7h4"></path></svg>
                        </span>
                        Gestão para Igrejas
                    </a>
                <?php endif; ?>

                <a href="<?= url('/hub/sites') ?>" class="hub-nav-link <?= e($isMenuActive('sites', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M3 12h18M12 3a14.5 14.5 0 0 1 0 18M12 3a14.5 14.5 0 0 0 0 18"></path></svg>
                    </span>
                    Meu site
                </a>
                <a href="<?= url('/hub/ministry-ai') ?>" class="hub-nav-link <?= e($isMenuActive('expositor', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    </span>
                    IA - Centro Pastoral
                </a>
                <a href="<?= url('/hub/creditos') ?>" class="hub-nav-link <?= e($isMenuActive('creditos', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 8A2.5 2.5 0 0 1 5 5.5h14A2.5 2.5 0 0 1 21.5 8v8A2.5 2.5 0 0 1 19 18.5H5A2.5 2.5 0 0 1 2.5 16z"></path><path d="M15 12h.01"></path><path d="M2.5 9.5h19"></path></svg>
                    </span>
                    Créditos
                </a>
                <a href="<?= url('/hub/suporte') ?>" class="hub-nav-link <?= e($isMenuActive('suporte', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    </span>
                    Ajuda
                </a>
                <a href="<?= url('/hub/usuarios') ?>" class="hub-nav-link <?= e($isMenuActive('usuarios', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </span>
                    Minha Equipe
                </a>
                <a href="<?= url('/hub/configuracoes') ?>" class="hub-nav-link <?= e($isMenuActive('configuracoes', $activeMenu)) ?>">
                    <span class="hub-nav-link__icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 .6 1.65 1.65 0 0 0-.33 1V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-.33-1 1.65 1.65 0 0 0-1-.6 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-.6-1 1.65 1.65 0 0 0-1-.33H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1-.33 1.65 1.65 0 0 0 .6-1A1.65 1.65 0 0 0 4.6 6.5l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-.6 1.65 1.65 0 0 0 .33-1V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 .33 1 1.65 1.65 0 0 0 1 .6 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.14.32.22.66.23 1.01.01.35-.07.69-.23 1.01"></path></svg>
                    </span>
                    Configurações
                </a>
                <?php if ($isMasterAdmin): ?>
                    <a href="<?= url('/admin') ?>" class="hub-nav-link hub-nav-link--boxed <?= e($isMenuActive('admin', $activeMenu)) ?>">
                        <span class="hub-nav-link__icon" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6l8-4z"></path><path d="M9 12l2 2 4-5"></path></svg>
                        </span>
                        Super Admin
                    </a>
                <?php endif; ?>

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
                    <div class="hub-topbar__context">
                        <span>Instituição</span>
                        <strong><?= e((string) ($organizationName ?: 'Sua igreja')) ?></strong>
                    </div>
                </div>
                <div class="hub-topbar__right">
                    <a href="<?= url('/hub/suporte') ?>" class="hub-topbar__link" style="display: flex; align-items: center; gap: 4px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        Ajuda
                    </a>
                    <a href="<?= e($hubSiteUrl) ?>" class="hub-topbar__link">Site</a>
                    <button type="button" class="hub-topbar__theme-toggle" id="hub-theme-toggle-top" aria-label="Alternar modo claro e escuro" data-theme-toggle>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-icon theme-icon--light"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-icon theme-icon--dark"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    </button>
                    <form method="POST" action="<?= url('/logout') ?>" style="margin:0;">
                        <?= csrf_field() ?>
                        <button type="submit" class="hub-topbar__link hub-topbar__link--danger" style="background:none;border:none;cursor:pointer;font:inherit;">Sair</button>
                    </form>
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

                <div class="hub-content__body">
                    <?= $__view->yield('content') ?>
                </div>
                <footer class="hub-page-footer">
                    <div>&copy; <?= date('Y') ?> Elo 42. Todos os direitos reservados.</div>
                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; align-items: center;">
                        <span style="display: flex; align-items: center; gap: 0.35rem;"><strong>Comercial:</strong> <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="color: #25D366;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg> <a href="https://wa.me/5513978008047" target="_blank" style="color:inherit;text-decoration:none;">(13) 97800-8047</a></span>
                        <span style="display: flex; align-items: center; gap: 0.35rem;"><strong>Suporte:</strong> <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="color: #25D366;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg> <a href="https://wa.me/5511991775458" target="_blank" style="color:inherit;text-decoration:none;">(11) 99177-5458</a></span>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <script src="<?= asset('js/app.js') ?>"></script>
    <script>
        <?php if ($isHubPwaEnabled): ?>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('<?= url('/sw.js') ?>').catch(function () {});
            });
        }
        <?php endif; ?>
    </script>
</body>
</html>
