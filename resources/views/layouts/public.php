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

    <button class="scroll-top" aria-label="Voltar ao topo">&uarr;</button>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
