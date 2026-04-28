<?php
$site = is_array($site ?? null) ? $site : [];
$events = is_array($events ?? null) ? $events : [];
$campaigns = is_array($campaigns ?? null) ? $campaigns : [];
$title = trim((string) ($site['site_title'] ?? $site['organization_name'] ?? 'Site institucional'));
$title = $title !== '' ? $title : 'Site institucional';
$description = trim((string) ($site['site_description'] ?? 'Uma comunidade para acolher, servir e caminhar em fé.'));
$description = $description !== '' ? $description : 'Uma comunidade para acolher, servir e caminhar em fé.';
$about = trim((string) ($site['about_text'] ?? 'Conheça nossa comunidade, acompanhe os eventos e participe das campanhas cadastradas pela organização.'));
$primary = trim((string) ($site['theme_color'] ?? '#0A4DFF'));
$primary = preg_match('/^#[0-9a-fA-F]{6}$/', $primary) ? $primary : '#0A4DFF';
$accent = '#d6a646';
$heroImage = trim((string) ($site['hero_image'] ?? ''));
$logoImage = trim((string) ($site['logo_image'] ?? ''));
$contactEmail = trim((string) ($site['contact_email'] ?? ''));
$contactPhone = trim((string) ($site['contact_phone'] ?? ''));
$whatsappUrl = trim((string) ($site['whatsapp_url'] ?? ''));
$addressParts = array_filter([
    trim((string) ($site['address_line'] ?? '')),
    trim((string) ($site['city'] ?? '')),
    trim((string) ($site['state'] ?? '')),
]);
$address = implode(' - ', $addressParts);
$ctaLabel = trim((string) ($site['cta_label'] ?? 'Falar com a igreja'));
$ctaUrl = trim((string) ($site['cta_url'] ?? ''));
$ctaUrl = $ctaUrl !== '' ? $ctaUrl : ($whatsappUrl !== '' ? $whatsappUrl : '#contato');
$socialLinks = array_filter([
    'Instagram' => trim((string) ($site['instagram_url'] ?? '')),
    'Facebook' => trim((string) ($site['facebook_url'] ?? '')),
    'YouTube' => trim((string) ($site['youtube_url'] ?? '')),
]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($description) ?>">
    <title><?= e($title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Saira:wght@600;700;800&family=Inter:wght@400;500;600;700;800&display=swap">
    <style>
        :root { --primary: <?= e($primary) ?>; --accent: <?= e($accent) ?>; --ink: #071837; --muted: #596a88; --line: #dfe7f4; --soft: #f4f7fd; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Inter, sans-serif; color: var(--ink); background: #fff; }
        h1, h2, h3 { font-family: Saira, Inter, sans-serif; letter-spacing: 0; margin: 0; }
        a { color: inherit; }
        .site-shell { min-height: 100vh; background: linear-gradient(180deg, #f7faff 0%, #fff 42%); }
        .site-nav { display: flex; align-items: center; justify-content: space-between; gap: 1rem; max-width: 1180px; margin: 0 auto; padding: 1.2rem clamp(1rem, 3vw, 2rem); }
        .site-logo { display: inline-flex; align-items: center; gap: .7rem; font-weight: 900; text-decoration: none; }
        .site-logo img { max-width: 132px; max-height: 56px; object-fit: contain; }
        .site-logo__fallback { width: 44px; height: 44px; display: grid; place-items: center; border-radius: 12px; background: var(--primary); color: #fff; }
        .site-nav nav { display: flex; gap: 1rem; color: var(--muted); font-size: .92rem; font-weight: 800; }
        .site-hero { max-width: 1180px; margin: 0 auto; padding: clamp(2rem, 5vw, 5rem) clamp(1rem, 3vw, 2rem); display: grid; grid-template-columns: minmax(0, 1fr) minmax(280px, .8fr); gap: clamp(1.5rem, 4vw, 4rem); align-items: center; }
        .site-pill { display: inline-flex; align-items: center; padding: .35rem .75rem; border-radius: 999px; background: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary); font-weight: 900; font-size: .78rem; text-transform: uppercase; }
        .site-hero h1 { margin-top: 1rem; font-size: clamp(2.6rem, 6vw, 5.2rem); line-height: .94; }
        .site-hero p { color: var(--muted); font-size: 1.1rem; line-height: 1.75; max-width: 680px; }
        .site-actions { display: flex; flex-wrap: wrap; gap: .85rem; margin-top: 1.5rem; }
        .site-btn { min-height: 46px; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; padding: 0 1.1rem; font-weight: 900; text-decoration: none; border: 1px solid var(--primary); }
        .site-btn--primary { background: var(--primary); color: #fff; box-shadow: 0 16px 28px color-mix(in srgb, var(--primary) 18%, transparent); }
        .site-btn--light { background: #fff; color: var(--primary); }
        .site-visual { min-height: 350px; border-radius: 24px; overflow: hidden; background: linear-gradient(135deg, var(--primary), var(--accent)); box-shadow: 0 24px 60px rgba(15, 35, 75, .18); display: grid; place-items: center; color: #fff; font-weight: 900; text-align: center; padding: 2rem; }
        .site-visual img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .site-section { max-width: 1180px; margin: 0 auto; padding: 2rem clamp(1rem, 3vw, 2rem); }
        .site-section h2 { font-size: clamp(1.9rem, 3vw, 2.8rem); }
        .site-section__intro { color: var(--muted); line-height: 1.7; max-width: 760px; }
        .site-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; margin-top: 1.2rem; }
        .site-card { border: 1px solid var(--line); border-radius: 18px; background: #fff; padding: 1.3rem; box-shadow: 0 10px 26px rgba(15, 35, 75, .05); }
        .site-card strong { display: block; margin-bottom: .35rem; font-size: 1.05rem; }
        .site-card p, .site-card span { color: var(--muted); line-height: 1.55; }
        .site-contact { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .site-socials { display: flex; flex-wrap: wrap; gap: .75rem; margin-top: 1rem; }
        .site-socials a { border: 1px solid var(--line); background: var(--soft); border-radius: 999px; padding: .65rem .9rem; text-decoration: none; font-weight: 800; color: var(--primary); }
        footer { color: var(--muted); text-align: center; padding: 2.5rem 1rem; border-top: 1px solid var(--line); margin-top: 2rem; }
        @media (max-width: 860px) {
            .site-hero, .site-contact { grid-template-columns: 1fr; }
            .site-grid { grid-template-columns: 1fr; }
            .site-nav { align-items: flex-start; flex-direction: column; }
            .site-nav nav { flex-wrap: wrap; }
        }
    </style>
</head>
<body>
    <div class="site-shell">
        <header class="site-nav">
            <a class="site-logo" href="#">
                <?php if ($logoImage !== ''): ?>
                    <img src="<?= e($logoImage) ?>" alt="<?= e($title) ?>">
                <?php else: ?>
                    <span class="site-logo__fallback"><?= e(strtoupper(substr($title, 0, 1))) ?></span>
                <?php endif; ?>
                <span><?= e($title) ?></span>
            </a>
            <nav aria-label="Navegação">
                <a href="#sobre">Sobre</a>
                <a href="#eventos">Eventos</a>
                <a href="#ofertas">Ofertas</a>
                <a href="#contato">Contato</a>
            </nav>
        </header>

        <main>
            <section class="site-hero">
                <div>
                    <span class="site-pill"><?= e((string) ($site['template'] ?? 'Institucional')) ?></span>
                    <h1><?= e($title) ?></h1>
                    <p><?= e($description) ?></p>
                    <div class="site-actions">
                        <a class="site-btn site-btn--primary" href="<?= e($ctaUrl) ?>"><?= e($ctaLabel) ?></a>
                        <a class="site-btn site-btn--light" href="#eventos">Ver eventos</a>
                    </div>
                </div>
                <div class="site-visual">
                    <?php if ($heroImage !== ''): ?>
                        <img src="<?= e($heroImage) ?>" alt="Imagem principal">
                    <?php else: ?>
                        <span>Imagem principal da comunidade</span>
                    <?php endif; ?>
                </div>
            </section>

            <section id="sobre" class="site-section">
                <h2>Sobre nós</h2>
                <p class="site-section__intro"><?= e($about) ?></p>
                <div class="site-grid">
                    <article class="site-card"><strong>Comunidade</strong><p>Um espaço para acolher pessoas e conectar famílias à vida da igreja.</p></article>
                    <article class="site-card"><strong>Ministérios</strong><p>Áreas de serviço, cuidado e desenvolvimento ministerial integradas à rotina da organização.</p></article>
                    <article class="site-card"><strong>Agenda</strong><p>Eventos e encontros cadastrados pela administração aparecem aqui para visitantes e membros.</p></article>
                </div>
            </section>

            <section id="eventos" class="site-section">
                <h2>Próximos eventos</h2>
                <div class="site-grid">
                    <?php if (empty($events)): ?>
                        <article class="site-card"><strong>Nenhum evento publicado</strong><p>Acompanhe esta página para ver os próximos encontros.</p></article>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <article class="site-card">
                                <strong><?= e((string) ($event['title'] ?? 'Evento')) ?></strong>
                                <span><?= !empty($event['start_date']) ? date('d/m/Y H:i', strtotime((string) $event['start_date'])) : 'Data a definir' ?></span>
                                <p><?= e((string) ($event['description'] ?? $event['location'] ?? '')) ?></p>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <section id="ofertas" class="site-section">
                <h2>Ofertas e campanhas</h2>
                <div class="site-grid">
                    <?php if (empty($campaigns)): ?>
                        <article class="site-card"><strong>Nenhuma campanha ativa</strong><p>As campanhas cadastradas pela igreja aparecerão aqui com o destino da contribuição.</p></article>
                    <?php else: ?>
                        <?php foreach ($campaigns as $campaign): ?>
                            <?php
                                $goal = (float) ($campaign['goal_amount'] ?? 0);
                                $raised = (float) ($campaign['raised_amount'] ?? 0);
                                $progress = $goal > 0 ? min(100, (int) round(($raised / $goal) * 100)) : 0;
                            ?>
                            <article class="site-card">
                                <strong><?= e((string) ($campaign['title'] ?? 'Campanha')) ?></strong>
                                <span><?= e((string) ($campaign['designation'] ?? 'Destino da oferta')) ?> · <?= $progress ?>%</span>
                                <p><?= e((string) ($campaign['description'] ?? '')) ?></p>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <section id="contato" class="site-section site-contact">
                <div>
                    <h2>Contato</h2>
                    <p class="site-section__intro">Entre em contato para visitas, informações sobre eventos e atendimento da comunidade.</p>
                </div>
                <article class="site-card">
                    <?php if ($contactPhone !== ''): ?><p><strong>Telefone</strong><?= e($contactPhone) ?></p><?php endif; ?>
                    <?php if ($contactEmail !== ''): ?><p><strong>E-mail</strong><?= e($contactEmail) ?></p><?php endif; ?>
                    <?php if ($address !== ''): ?><p><strong>Endereço</strong><?= e($address) ?></p><?php endif; ?>
                    <div class="site-socials">
                        <?php foreach ($socialLinks as $label => $href): ?>
                            <a href="<?= e($href) ?>" target="_blank" rel="noopener noreferrer"><?= e($label) ?></a>
                        <?php endforeach; ?>
                    </div>
                </article>
            </section>
        </main>

        <footer>
            <span><?= e($title) ?> · Site publicado pela Elo 42</span>
        </footer>
    </div>
</body>
</html>
