<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($metaDescription ?? 'Elo 42 — Gestão, tecnologia e impacto para igrejas e organizações.') ?>">
    <?php if (!empty($metaRobots)): ?><meta name="robots" content="<?= e($metaRobots) ?>"><?php else: ?><meta name="robots" content="index, follow"><?php endif; ?>
    <?php if (!empty($canonicalUrl)): ?><link rel="canonical" href="<?= e($canonicalUrl) ?>"><?php endif; ?>
    <meta name="theme-color" content="#1455FF">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Elo 42">
    <link rel="manifest" href="<?= url('/app-manifest') ?>">
    <link rel="apple-touch-icon" href="<?= url('/assets/img/logo-color-new.png') ?>">
    <title><?= e($pageTitle ?? 'Elo 42 Platform') ?></title>
    <?= $__view->yield('head') ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Saira:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap">

    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/layout.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/pages.css') ?>">
</head>
<body>
    <a href="#main-content" class="skip-to-content">Pular para o conteúdo</a>

    <?= $__view->partial('header') ?>

    <main id="main-content">
        <?= $__view->yield('content') ?>
    </main>

    <?= $__view->partial('footer') ?>

    <a href="https://wa.me/5513978008047" target="_blank" rel="noopener noreferrer" class="whatsapp-float" aria-label="Fale conosco pelo WhatsApp">
        <div id="whatsapp-lottie"></div>
    </a>

    <!-- Cookie Consent Banner (LGPD) -->
    <div id="cookie-banner" class="cookie-banner" role="alertdialog" aria-label="Aviso de cookies" aria-live="polite" style="display:none">
        <div class="cookie-banner__inner">
            <div class="cookie-banner__text">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                <p>
                    Usamos cookies essenciais e de análise para melhorar sua experiência.
                    Leia nossa <a href="<?= url('/politica-de-cookies') ?>">Política de Cookies</a> e <a href="<?= url('/privacidade') ?>">Política de Privacidade</a>.
                </p>
            </div>
            <div class="cookie-banner__actions">
                <button id="cookie-reject" class="cookie-btn cookie-btn--outline">Apenas essenciais</button>
                <button id="cookie-accept" class="cookie-btn cookie-btn--primary">Aceitar todos</button>
            </div>
        </div>
    </div>

    <style>
    .cookie-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 9999;
        background: #0a1f44;
        border-top: 1px solid rgba(255,255,255,0.1);
        padding: 16px 24px;
        box-shadow: 0 -4px 24px rgba(0,0,0,0.18);
    }

    .cookie-banner__inner {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }

    .cookie-banner__text {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        color: #cbd5e1;
        font-size: 0.875rem;
        line-height: 1.5;
        flex: 1;
        min-width: 0;
    }

    .cookie-banner__text svg { flex-shrink: 0; margin-top: 2px; color: #60a5fa; }

    .cookie-banner__text p { margin: 0; }

    .cookie-banner__text a { color: #93c5fd; text-decoration: underline; }

    .cookie-banner__actions {
        display: flex;
        gap: 10px;
        flex-shrink: 0;
    }

    .cookie-btn {
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: opacity 0.15s ease;
        white-space: nowrap;
    }

    .cookie-btn:hover { opacity: 0.85; }

    .cookie-btn--primary { background: #0a4dff; color: #fff; }

    .cookie-btn--outline {
        background: transparent;
        color: #94a3b8;
        border: 1.5px solid rgba(255,255,255,0.15);
    }

    @media (max-width: 640px) {
        .cookie-banner__inner { flex-direction: column; gap: 12px; }
        .cookie-banner__actions { width: 100%; }
        .cookie-btn { flex: 1; text-align: center; }
    }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
    <script>
        (function() {
            var consent = localStorage.getItem('elo42_cookie_consent');
            if (!consent) {
                var banner = document.getElementById('cookie-banner');
                if (banner) {
                    banner.style.display = 'block';
                    document.getElementById('cookie-accept').addEventListener('click', function() {
                        localStorage.setItem('elo42_cookie_consent', 'all');
                        banner.style.display = 'none';
                    });
                    document.getElementById('cookie-reject').addEventListener('click', function() {
                        localStorage.setItem('elo42_cookie_consent', 'essential');
                        banner.style.display = 'none';
                    });
                }
            }
        })();

        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('whatsapp-lottie')) {
                lottie.loadAnimation({
                    container: document.getElementById('whatsapp-lottie'),
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    path: '<?= asset('data/whatsapp-lottie.json') ?>'
                });
            }
        });
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('<?= url('/sw.js') ?>').catch(function () {});
            });
        }
    </script>
</body>
</html>
