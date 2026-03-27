<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página não encontrada — Elo 42</title>
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: var(--color-bg-light);
            text-align: center;
            padding: 2rem;
        }
        .error-page__code {
            font-family: var(--font-heading);
            font-size: 8rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--color-primary), var(--color-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
        }
        .error-page__title {
            font-family: var(--font-heading);
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--color-text-primary);
            margin-bottom: 0.75rem;
        }
        .error-page__text {
            font-size: 1rem;
            color: var(--color-text-secondary);
            margin-bottom: 2rem;
            max-width: 420px;
        }
        .error-page__actions {
            display: flex;
            gap: 1rem;
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-page__code">404</div>
        <h1 class="error-page__title">Página não encontrada</h1>
        <p class="error-page__text">
            A página que você procura não existe ou foi movida. Verifique o endereço ou volte à página inicial.
        </p>
        <div class="error-page__actions">
            <a href="<?= url('/') ?>" class="btn btn--primary">Voltar ao início</a>
            <a href="<?= url('/contato') ?>" class="btn btn--secondary">Contato</a>
        </div>
    </div>
</body>
</html>
