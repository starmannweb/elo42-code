<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Hub — Elo 42') ?></title>
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/layout.css') ?>">
</head>
<body class="layout-authenticated">
    <aside class="authenticated-sidebar">
        <?= $__view->partial('sidebar') ?>
    </aside>
    <div class="authenticated-main">
        <header class="authenticated-topbar">
            <?= $__view->partial('nav') ?>
        </header>
        <main class="authenticated-content">
            <?= $__view->yield('content') ?>
        </main>
    </div>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
