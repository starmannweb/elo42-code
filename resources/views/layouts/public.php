<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($metaDescription ?? 'Elo 42 — Gestão, tecnologia e impacto para igrejas e organizações.') ?>">
    <title><?= e($pageTitle ?? 'Elo 42 Platform') ?></title>

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

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/5511999999999" target="_blank" rel="noopener noreferrer" class="whatsapp-float" aria-label="Fale conosco pelo WhatsApp">
        <div id="whatsapp-lottie"></div>
    </a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
    <script>
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
    </script>
</body>
</html>
