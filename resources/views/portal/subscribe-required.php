<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Área do Membro') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('/assets/css/portal.css') ?>">
    <style>
        .sr-body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: var(--portal-bg, #f4f7fd); padding: 2rem 1rem; }
        .sr-card { background: #fff; border-radius: 18px; box-shadow: 0 4px 32px rgba(10,31,68,0.10); max-width: 520px; width: 100%; padding: 48px 40px; text-align: center; }
        .sr-card__icon { width: 72px; height: 72px; border-radius: 50%; background: #eff6ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
        .sr-card__title { font-size: 1.5rem; font-weight: 800; color: #0a1f44; margin: 0 0 12px; }
        .sr-card__text { font-size: 0.97rem; color: #5a708a; line-height: 1.6; margin: 0 0 32px; }
        .sr-card__org { font-weight: 700; color: #1547f5; }
        .sr-card__cta { display: inline-block; padding: 13px 32px; border-radius: 8px; background: #1547f5; color: #fff; font-weight: 700; font-size: 1rem; text-decoration: none; transition: background .15s; }
        .sr-card__cta:hover { background: #1035c0; }
        .sr-card__back { display: block; margin-top: 18px; font-size: 0.875rem; color: #5a708a; text-decoration: none; }
        .sr-card__back:hover { color: #1547f5; }
        @media (max-width: 600px) { .sr-card { padding: 32px 20px; } }
    </style>
</head>
<body data-portal-theme="light">
<div class="sr-body">
    <div class="sr-card">
        <div class="sr-card__icon">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#1547f5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6l8-4z"></path><path d="M9 12l2 2 4-5"></path></svg>
        </div>
        <h1 class="sr-card__title">Área do Membro</h1>
        <?php if (!empty($organization['name'])): ?>
            <p class="sr-card__text">
                A área do membro da <span class="sr-card__org"><?= e((string)($organization['name'] ?? '')) ?></span> é um recurso exclusivo para igrejas com assinatura ativa.<br><br>
                Peça ao líder responsável para ativar ou renovar o plano de gestão.
            </p>
        <?php else: ?>
            <p class="sr-card__text">
                A área do membro é um recurso exclusivo para igrejas com assinatura ativa.<br><br>
                Peça ao líder responsável para ativar ou renovar o plano de gestão.
            </p>
        <?php endif; ?>
        <a href="<?= url('/gestao/assinatura') ?>" class="sr-card__cta">Ver planos de assinatura</a>
        <a href="<?= url('/logout') ?>" class="sr-card__back">Sair da conta</a>
    </div>
</div>
</body>
</html>
