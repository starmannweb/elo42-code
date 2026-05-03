<?php
$site = is_array($site ?? null) ? $site : [];
$events = is_array($events ?? null) ? $events : [];
$campaigns = is_array($campaigns ?? null) ? $campaigns : [];
$ministries = is_array($ministries ?? null) ? $ministries : [];
$smallGroups = is_array($smallGroups ?? null) ? $smallGroups : [];
$sermons = is_array($sermons ?? null) ? $sermons : [];

$title = trim((string) ($site['site_title'] ?? $site['organization_name'] ?? 'Site institucional'));
$title = $title !== '' ? $title : 'Site institucional';
$description = trim((string) ($site['site_description'] ?? 'Uma comunidade para acolher, servir e caminhar em fé.'));
$description = $description !== '' ? $description : 'Uma comunidade para acolher, servir e caminhar em fé.';
$about = trim((string) ($site['about_text'] ?? 'Conheça nossa comunidade, acompanhe os eventos e participe das campanhas cadastradas pela organização.'));
$primary = trim((string) ($site['theme_color'] ?? '#0A4DFF'));
$primary = preg_match('/^#[0-9a-fA-F]{6}$/', $primary) ? $primary : '#0A4DFF';
$accent = trim((string) ($site['accent_color'] ?? '#d6a646'));
$accent = preg_match('/^#[0-9a-fA-F]{6}$/', $accent) ? $accent : '#d6a646';
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
$ctaLabel = trim((string) ($site['cta_label'] ?? 'Quero visitar'));
$ctaLabel = $ctaLabel !== '' ? $ctaLabel : 'Quero visitar';
$ctaUrl = trim((string) ($site['cta_url'] ?? ''));
$ctaUrl = $ctaUrl !== '' ? $ctaUrl : ($whatsappUrl !== '' ? $whatsappUrl : '#contato');
$socialLinks = array_filter([
    'Instagram' => trim((string) ($site['instagram_url'] ?? '')),
    'Facebook' => trim((string) ($site['facebook_url'] ?? '')),
    'YouTube' => trim((string) ($site['youtube_url'] ?? '')),
]);

$principles = [
    ['icon' => 'M3 8h18M3 16h18M9 4l-2 16M17 4l-2 16', 'title' => 'Começamos diferentes', 'text' => 'Cada pessoa é valorizada, cada história importa e todo mundo tem um lugar.'],
    ['icon' => 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2|M9 7a4 4 0 1 0 0 8 4 4 0 0 0 0-8|M22 21v-2a4 4 0 0 0-3-3.87|M16 3.13a4 4 0 0 1 0 7.75', 'title' => 'Um lugar para recomeçar', 'text' => 'Você está cansado? Aqui você encontra acolhimento, comunhão e um coração disposto.'],
    ['icon' => 'M3 12c0-4.97 4.03-9 9-9s9 4.03 9 9-4.03 9-9 9-9-4.03-9-9z|M9 12l2 2 4-4', 'title' => 'Um evangelho que escuta', 'text' => 'Falamos sobre temas atuais com simplicidade e profundidade, mostrando como a fé tem sentido no mundo de hoje.'],
    ['icon' => 'M12 2v20M5 6h14M7 22h10', 'title' => 'Fé e Prática', 'text' => 'Aqui, a espiritualidade não é abstrata: ela transforma decisões, relacionamentos e o jeito de viver no dia a dia.'],
];

$highlights = [
    ['title' => 'Pequenos Grupos', 'text' => 'Conexão, partilha e estudo da Palavra durante a semana.', 'gradient' => 'linear-gradient(135deg,#9f1239,#7c1d3a)'],
    ['title' => 'Kids', 'text' => 'Ensinar e divertir as crianças com histórias bíblicas, brincadeiras e músicas.', 'gradient' => 'linear-gradient(135deg,#1d4ed8,#0f3878)'],
    ['title' => 'Encontro de Casais', 'text' => 'Movimentamos nossa fé em projetos sociais que alcançam vidas e geram transformação consistente.', 'gradient' => 'linear-gradient(135deg,#92400e,#78350f)'],
    ['title' => 'Ações Sociais', 'text' => 'Movimentamos nossa fé em projetos sociais que alcançam vidas e geram transformação consistente.', 'gradient' => 'linear-gradient(135deg,#0f766e,#134e4a)'],
];

$connectActions = [
    ['title' => 'Visite a nossa igreja', 'text' => 'Chegue alguns minutos antes para encontrar um café e conhecer gente nova!', 'cta' => 'Clique aqui', 'href' => $ctaUrl, 'gradient' => 'linear-gradient(135deg,#1e3a8a,#0b1d52)'],
    ['title' => 'Seja um voluntário', 'text' => 'Sirva com seus dons: recepção, louvor, infantil, organização e mais.', 'cta' => 'Clique aqui', 'href' => $whatsappUrl !== '' ? $whatsappUrl : '#contato', 'gradient' => 'linear-gradient(135deg,#9f1239,#6d1b2a)'],
    ['title' => 'Agende um discipulado', 'text' => 'Faça um acompanhamento espiritual.', 'cta' => 'Clique aqui', 'href' => $whatsappUrl !== '' ? $whatsappUrl : '#contato', 'gradient' => 'linear-gradient(135deg,#0f766e,#134e4a)'],
    ['title' => 'Marcar um café com pastor', 'text' => 'Um espaço leve para tirar dúvidas, conversar e orar com o pastor.', 'cta' => 'Clique aqui', 'href' => $whatsappUrl !== '' ? $whatsappUrl : '#contato', 'gradient' => 'linear-gradient(135deg,#312e81,#1e1b4b)'],
];

$serviceTimes = trim((string) ($site['service_times'] ?? 'Cultos: Domingo às 18h30. Grupos Pequenos: Segunda, Quarta e Sexta às 20h.'));

$templateName = (string) ($site['template'] ?? 'Institucional Clássico');
$templateKey = match (true) {
    str_contains(mb_strtolower($templateName), 'comunidade') => 'comunidade',
    str_contains(mb_strtolower($templateName), 'campanha')   => 'campanha',
    str_contains(mb_strtolower($templateName), 'ong')        => 'ong',
    default                                                  => 'classico',
};

$templateBadges = [
    'classico'    => 'Comunidade de fé',
    'comunidade'  => 'Igreja em comunidade',
    'campanha'    => 'Inscrições abertas',
    'ong'         => 'Causa social',
];
$heroBadge = $templateBadges[$templateKey];
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Saira:wght@600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap">
    <style>
        :root {
            --primary: <?= e($primary) ?>;
            --primary-dark: color-mix(in srgb, <?= e($primary) ?> 78%, #000 22%);
            --accent: <?= e($accent) ?>;
            /* Tipografia e cores alinhadas com a Área do Membro */
            --ink: #06183a;
            --ink-soft: #4b5d7c;
            --muted: #7a89a4;
            --line: #dfe7f4;
            --line-strong: #c8d7ee;
            --soft: #f4f7fd;
            --surface: #ffffff;
            --bg: #f4f7fd;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; color: var(--ink); background: var(--bg); -webkit-font-smoothing: antialiased; }
        h1, h2, h3, h4 { font-family: 'Saira', 'Inter', sans-serif; letter-spacing: -0.01em; margin: 0; color: var(--ink); }
        p { color: var(--ink-soft); }
        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; display: block; }

        .container { max-width: 1180px; margin: 0 auto; padding: 0 clamp(1rem, 3vw, 2rem); }

        /* === Top bar === */
        .top-bar { background: var(--ink); color: rgba(255,255,255,.78); font-size: .8rem; }
        .top-bar__inner { display: flex; align-items: center; justify-content: space-between; padding: .55rem 0; gap: 1rem; flex-wrap: wrap; }
        .top-bar__inner span { display: inline-flex; align-items: center; gap: .4rem; }

        /* === Site nav === */
        .site-nav { background: #fff; border-bottom: 1px solid var(--line); position: sticky; top: 0; z-index: 50; }
        .site-nav__inner { display: flex; align-items: center; justify-content: space-between; padding: 1rem 0; gap: 1.5rem; flex-wrap: wrap; }
        .site-logo { display: inline-flex; align-items: center; gap: .7rem; font-weight: 900; font-size: 1.1rem; }
        .site-logo img { max-width: 132px; max-height: 56px; object-fit: contain; }
        .site-logo__fallback { width: 44px; height: 44px; display: grid; place-items: center; border-radius: 12px; background: var(--primary); color: #fff; font-family: 'Saira'; font-size: 1.2rem; }
        .site-nav nav { display: flex; gap: 1.6rem; color: var(--ink); font-size: .92rem; font-weight: 600; }
        .site-nav nav a { transition: color .18s ease; }
        .site-nav nav a:hover { color: var(--primary); }
        .site-nav__cta { display: inline-flex; align-items: center; gap: .5rem; padding: .7rem 1.2rem; border-radius: 999px; background: var(--primary); color: #fff; font-weight: 700; font-size: .9rem; transition: transform .18s ease, box-shadow .18s ease; }
        .site-nav__cta:hover { transform: translateY(-1px); box-shadow: 0 12px 24px color-mix(in srgb, var(--primary) 35%, transparent); }

        /* === Hero === */
        .hero { position: relative; overflow: hidden; min-height: clamp(420px, 70vh, 720px); display: grid; align-items: end; color: #fff; }
        .hero::before { content: ''; position: absolute; inset: 0; background-size: cover; background-position: center; <?php if ($heroImage !== ''): ?>background-image: url('<?= e($heroImage) ?>');<?php else: ?>background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, var(--accent) 100%);<?php endif; ?> }
        .hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(6,24,58,.05) 0%, rgba(6,24,58,.6) 55%, rgba(6,24,58,.88) 100%); }
        .hero--classico { min-height: clamp(360px, 55vh, 540px); align-items: center; text-align: center; }
        .hero--classico .hero__content { max-width: 780px; margin: 0 auto; padding-top: clamp(3rem, 8vw, 5rem); padding-bottom: clamp(3rem, 8vw, 5rem); }
        .hero--classico .hero__actions { justify-content: center; }
        .hero--campanha::after { background: linear-gradient(135deg, rgba(15,23,42,.85) 0%, rgba(10,77,255,.6) 60%, rgba(252,211,77,.4) 100%); }
        .hero--ong::after { background: linear-gradient(180deg, rgba(15,118,110,.55) 0%, rgba(6,24,58,.85) 100%); }
        .hero__content { position: relative; z-index: 2; padding: clamp(3rem, 8vw, 6rem) 0 clamp(2rem, 5vw, 4rem); max-width: 760px; }
        .hero__pill { display: inline-flex; align-items: center; padding: .35rem .85rem; border-radius: 999px; background: rgba(255,255,255,.18); color: #fff; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,.3); font-weight: 700; font-size: .78rem; text-transform: uppercase; letter-spacing: .08em; }
        .hero__title { margin-top: 1.2rem; font-size: clamp(2.2rem, 5.5vw, 4.4rem); line-height: 1; font-weight: 800; }
        .hero__text { margin-top: 1.2rem; font-size: clamp(1rem, 1.5vw, 1.15rem); line-height: 1.7; max-width: 580px; opacity: .92; }
        .hero__actions { display: flex; flex-wrap: wrap; gap: .85rem; margin-top: 1.6rem; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: .9rem 1.5rem; border-radius: 12px; font-weight: 700; font-size: .95rem; transition: all .2s ease; border: none; cursor: pointer; }
        .btn--primary { background: var(--primary); color: #fff; }
        .btn--primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 14px 30px color-mix(in srgb, var(--primary) 30%, transparent); }
        .btn--ghost { background: rgba(255,255,255,.12); color: #fff; border: 1px solid rgba(255,255,255,.4); backdrop-filter: blur(6px); }
        .btn--ghost:hover { background: rgba(255,255,255,.22); }

        /* === Sections === */
        section { padding: clamp(3rem, 6vw, 5rem) 0; }
        .section-eyebrow { display: inline-block; color: var(--primary); font-weight: 800; text-transform: uppercase; letter-spacing: .12em; font-size: .8rem; margin-bottom: .8rem; }
        .section-title { font-size: clamp(1.8rem, 3.4vw, 2.8rem); font-weight: 800; max-width: 760px; line-height: 1.1; }
        .section-lede { color: var(--muted); margin-top: 1rem; line-height: 1.7; max-width: 720px; font-size: 1.02rem; }

        /* === Principles (4 columns with icons) === */
        .principles { background: #fff; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
        .principles__grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: clamp(1rem, 2vw, 2rem); margin-top: 2rem; }
        .principle { text-align: left; }
        .principle__icon { width: 56px; height: 56px; border-radius: 14px; display: grid; place-items: center; background: color-mix(in srgb, var(--primary) 12%, #fff); color: var(--primary); margin-bottom: 1rem; }
        .principle h3 { font-size: 1.1rem; font-weight: 800; margin-bottom: .4rem; color: var(--primary-dark); }
        .principle p { color: var(--muted); line-height: 1.6; font-size: .92rem; }

        /* === Highlights (4 cards with gradient backgrounds) === */
        .highlights { background: var(--soft); }
        .highlights__grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1.1rem; margin-top: 2rem; }
        .highlight { position: relative; aspect-ratio: 4/5; border-radius: 18px; overflow: hidden; color: #fff; display: flex; flex-direction: column; justify-content: flex-end; padding: 1.4rem; box-shadow: 0 14px 32px rgba(15,35,75,.12); transition: transform .2s ease; }
        .highlight:hover { transform: translateY(-3px); }
        .highlight::before { content: ''; position: absolute; inset: 0; }
        .highlight::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 35%, rgba(0,0,0,.7) 100%); }
        .highlight > * { position: relative; z-index: 2; }
        .highlight strong { font-size: 1.15rem; font-family: 'Saira'; }
        .highlight p { font-size: .85rem; line-height: 1.45; opacity: .92; margin-top: .4rem; }

        /* === Series === */
        .series { background: #fff; }
        .series__grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.2rem; margin-top: 2rem; }
        .series-card { aspect-ratio: 16/10; border-radius: 16px; overflow: hidden; position: relative; color: #fff; padding: 1.4rem; display: flex; align-items: flex-end; box-shadow: 0 14px 32px rgba(15,35,75,.1); }
        .series-card::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); }
        .series-card::after { content: ''; position: absolute; inset: 0; background: linear-gradient(0deg, rgba(0,0,0,.55), rgba(0,0,0,.15)); }
        .series-card > * { position: relative; z-index: 2; }
        .series-card strong { font-size: 1.15rem; font-family: 'Saira'; }
        .series-card span { font-size: .82rem; opacity: .9; }

        /* === Events === */
        .events { background: var(--soft); }
        .events__grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.2rem; margin-top: 2rem; }
        .event-card { background: #fff; border: 1px solid var(--line); border-radius: 16px; overflow: hidden; box-shadow: 0 8px 22px rgba(15,35,75,.06); transition: transform .2s ease, box-shadow .2s ease; }
        .event-card:hover { transform: translateY(-3px); box-shadow: 0 14px 32px rgba(15,35,75,.1); }
        .event-card__media { aspect-ratio: 16/9; background: linear-gradient(135deg, var(--primary), var(--accent)); display: grid; place-items: center; color: #fff; font-family: 'Saira'; font-size: 1.8rem; font-weight: 900; }
        .event-card__body { padding: 1.2rem 1.3rem; }
        .event-card__date { font-size: .82rem; color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: .06em; margin-bottom: .4rem; }
        .event-card strong { font-size: 1.05rem; display: block; margin-bottom: .5rem; }
        .event-card p { color: var(--muted); font-size: .9rem; line-height: 1.55; }

        /* === Connect === */
        .connect { background: #fff; border-top: 1px solid var(--line); }
        .connect__grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1.1rem; margin-top: 2rem; }
        .connect-card { aspect-ratio: 3/4; border-radius: 18px; overflow: hidden; color: #fff; display: flex; flex-direction: column; justify-content: flex-end; padding: 1.4rem; position: relative; box-shadow: 0 14px 32px rgba(15,35,75,.12); }
        .connect-card::before { content: ''; position: absolute; inset: 0; }
        .connect-card::after { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,.1) 0%, rgba(0,0,0,.7) 100%); }
        .connect-card > * { position: relative; z-index: 2; }
        .connect-card strong { font-size: 1.1rem; font-family: 'Saira'; }
        .connect-card p { font-size: .85rem; line-height: 1.5; opacity: .9; margin-top: .35rem; }
        .connect-card a { display: inline-flex; align-items: center; gap: .35rem; margin-top: .9rem; padding: .55rem 1rem; background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.4); border-radius: 999px; font-weight: 700; font-size: .85rem; backdrop-filter: blur(6px); transition: background .2s ease; }
        .connect-card a:hover { background: rgba(255,255,255,.32); }

        /* === Contact / Footer === */
        .contact-section { background: var(--ink); color: #fff; padding: clamp(3rem, 6vw, 5rem) 0; }
        .contact-section .section-eyebrow { color: var(--accent); }
        .contact-section .section-title, .contact-section h3 { color: #fff; }
        .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-top: 2rem; align-items: start; }
        .contact-info p { color: rgba(255,255,255,.78); line-height: 1.7; margin: .5rem 0; }
        .contact-info strong { display: block; color: var(--accent); font-size: .8rem; text-transform: uppercase; letter-spacing: .08em; margin-top: 1rem; }
        .contact-socials { display: flex; flex-wrap: wrap; gap: .6rem; margin-top: 1rem; }
        .contact-socials a { padding: .55rem 1rem; border-radius: 999px; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.18); color: #fff; font-weight: 700; font-size: .85rem; }
        .contact-socials a:hover { background: rgba(255,255,255,.18); }
        .contact-card { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.12); border-radius: 18px; padding: 1.6rem; }
        .contact-card h3 { font-size: 1.2rem; margin-bottom: 1rem; }
        .contact-card p { color: rgba(255,255,255,.85); line-height: 1.6; margin: .4rem 0; }

        footer.site-footer { background: #060d1f; color: rgba(255,255,255,.6); padding: 1.5rem 0; text-align: center; font-size: .85rem; }

        @media (max-width: 1024px) {
            .principles__grid, .highlights__grid, .connect__grid { grid-template-columns: repeat(2, 1fr); }
            .events__grid, .series__grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 720px) {
            .principles__grid, .highlights__grid, .connect__grid, .events__grid, .series__grid, .contact-grid { grid-template-columns: 1fr; }
            .site-nav__inner { gap: .8rem; }
            .site-nav nav { gap: 1rem; font-size: .85rem; }
        }
    </style>
</head>
<body>
    <?php if ($contactPhone !== '' || $address !== ''): ?>
    <div class="top-bar">
        <div class="container top-bar__inner">
            <span><?php if ($address !== ''): ?>📍 <?= e($address) ?><?php endif; ?></span>
            <span><?php if ($contactPhone !== ''): ?>📞 <?= e($contactPhone) ?><?php endif; ?><?= $contactEmail !== '' ? ' · ' . e($contactEmail) : '' ?></span>
        </div>
    </div>
    <?php endif; ?>

    <header class="site-nav">
        <div class="container site-nav__inner">
            <a class="site-logo" href="#topo">
                <?php if ($logoImage !== ''): ?>
                    <img src="<?= e($logoImage) ?>" alt="<?= e($title) ?>">
                <?php else: ?>
                    <span class="site-logo__fallback"><?= e(strtoupper(substr($title, 0, 1))) ?></span>
                <?php endif; ?>
                <span><?= e($title) ?></span>
            </a>
            <nav aria-label="Navegação principal">
                <a href="#sobre">Quem Somos</a>
                <a href="#agenda">Agenda</a>
                <a href="#conexoes">Conexões</a>
                <a href="#mensagens">Mensagens</a>
                <a href="#contato">Contato</a>
            </nav>
            <a class="site-nav__cta" href="<?= e($ctaUrl) ?>"><?= e($ctaLabel) ?></a>
        </div>
    </header>

    <main id="topo">
        <section class="hero hero--<?= e($templateKey) ?>">
            <div class="container hero__content">
                <span class="hero__pill"><?= e($heroBadge) ?></span>
                <h1 class="hero__title"><?= e($title) ?></h1>
                <p class="hero__text"><?= e($description) ?></p>
                <div class="hero__actions">
                    <a class="btn btn--primary" href="<?= e($ctaUrl) ?>"><?= e($ctaLabel) ?></a>
                    <a class="btn btn--ghost" href="#agenda">
                        <?= match ($templateKey) {
                            'campanha' => 'Inscreva-se',
                            'ong'      => 'Conhecer projetos',
                            'classico' => 'Saiba mais',
                            default    => 'Ver eventos',
                        } ?>
                    </a>
                </div>
            </div>
        </section>

        <section id="sobre" class="principles">
            <div class="container">
                <span class="section-eyebrow"><?= $templateKey === 'ong' ? 'Quem somos' : ($templateKey === 'campanha' ? 'Por que participar' : 'Princípios') ?></span>
                <h2 class="section-title">
                    <?= match ($templateKey) {
                        'ong'      => 'Causa, propósito e impacto',
                        'campanha' => 'Quatro motivos para você estar lá',
                        'classico' => 'Sobre a nossa comunidade',
                        default    => 'Nossa essência em quatro convicções',
                    } ?>
                </h2>
                <p class="section-lede"><?= e($about) ?></p>
                <div class="principles__grid">
                    <?php foreach ($principles as $principle): ?>
                        <article class="principle">
                            <div class="principle__icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <?php foreach (explode('|', (string) $principle['icon']) as $path): ?>
                                        <path d="<?= e($path) ?>"></path>
                                    <?php endforeach; ?>
                                </svg>
                            </div>
                            <h3><?= e($principle['title']) ?></h3>
                            <p><?= e($principle['text']) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section id="conexoes" class="highlights">
            <div class="container">
                <span class="section-eyebrow">
                    <?= match ($templateKey) {
                        'ong'      => 'Projetos em andamento',
                        'campanha' => 'Atividades do evento',
                        default    => 'Destaques',
                    } ?>
                </span>
                <h2 class="section-title">
                    <?= match ($templateKey) {
                        'ong'      => 'Onde a sua doação chega',
                        'campanha' => 'Programação e palestrantes',
                        'classico' => 'Ministérios e atividades',
                        default    => 'Áreas que movem nossa comunidade',
                    } ?>
                </h2>
                <div class="highlights__grid">
                    <?php
                    $cardsToRender = $highlights;
                    if (!empty($ministries)) {
                        $cardsToRender = [];
                        $palette = ['linear-gradient(135deg,#9f1239,#7c1d3a)', 'linear-gradient(135deg,#1d4ed8,#0f3878)', 'linear-gradient(135deg,#92400e,#78350f)', 'linear-gradient(135deg,#0f766e,#134e4a)'];
                        foreach (array_slice($ministries, 0, 4) as $idx => $ministry) {
                            $cardsToRender[] = [
                                'title'    => (string) ($ministry['name'] ?? 'Ministério'),
                                'text'     => (string) ($ministry['description'] ?? 'Conheça este ministério da nossa igreja.'),
                                'gradient' => $palette[$idx % count($palette)],
                            ];
                        }
                        while (count($cardsToRender) < 4) {
                            $cardsToRender[] = $highlights[count($cardsToRender)];
                        }
                    }
                    foreach ($cardsToRender as $highlight): ?>
                        <article class="highlight" style="--bg: <?= e($highlight['gradient']) ?>;">
                            <span style="position:absolute;inset:0;background:<?= e($highlight['gradient']) ?>;"></span>
                            <strong><?= e($highlight['title']) ?></strong>
                            <p><?= e($highlight['text']) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php if ($templateKey !== 'ong' && $templateKey !== 'campanha'): ?>
        <section id="mensagens" class="series">
            <div class="container">
                <span class="section-eyebrow">Séries e devocionais</span>
                <h2 class="section-title">Continue caminhando na Palavra</h2>
                <div class="series__grid">
                    <?php
                    $sermonsForCards = !empty($sermons) ? array_slice($sermons, 0, 3) : [
                        ['title' => 'Vivendo com saúde mental na era digital', 'subtitle' => 'Especial · Comunhão e cuidado'],
                        ['title' => 'Efésios — Uma vida de santidade em meio à cultura libertina', 'subtitle' => 'Série em curso'],
                        ['title' => 'Provérbios — Sabedoria para todos os dias', 'subtitle' => 'Devocional pastoral'],
                    ];
                    foreach ($sermonsForCards as $idx => $sermon): ?>
                        <a href="#mensagens" class="series-card">
                            <div>
                                <span><?= e((string) ($sermon['subtitle'] ?? ($sermon['series_name'] ?? 'Mensagem'))) ?></span>
                                <strong><?= e((string) ($sermon['title'] ?? 'Mensagem')) ?></strong>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <section id="agenda" class="events">
            <div class="container">
                <span class="section-eyebrow">
                    <?= match ($templateKey) {
                        'campanha' => 'Programação completa',
                        'ong'      => 'Próximas ações',
                        default    => 'Próximos eventos',
                    } ?>
                </span>
                <h2 class="section-title">
                    <?= match ($templateKey) {
                        'campanha' => 'Garanta sua participação',
                        'ong'      => 'Como participar dos projetos',
                        default    => 'Encontros para participar',
                    } ?>
                </h2>
                <div class="events__grid">
                    <?php if (empty($events)): ?>
                        <article class="event-card">
                            <div class="event-card__media">Em breve</div>
                            <div class="event-card__body">
                                <div class="event-card__date">Aguardando programação</div>
                                <strong>Nenhum evento publicado</strong>
                                <p>Acompanhe esta página para ver os próximos encontros da igreja.</p>
                            </div>
                        </article>
                    <?php else: ?>
                        <?php foreach ($events as $event):
                            $eventDate = !empty($event['start_date']) ? date('d \\d\\e M', strtotime((string) $event['start_date'])) : 'A definir';
                        ?>
                            <article class="event-card">
                                <div class="event-card__media"><?= e($eventDate) ?></div>
                                <div class="event-card__body">
                                    <div class="event-card__date"><?= !empty($event['start_date']) ? e(date('d/m/Y H:i', strtotime((string) $event['start_date']))) : 'Data a definir' ?></div>
                                    <strong><?= e((string) ($event['title'] ?? 'Evento')) ?></strong>
                                    <p><?= e((string) ($event['description'] ?? $event['location'] ?? 'Mais informações em breve.')) ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php if (!empty($campaigns)): ?>
        <section id="ofertas" class="series" style="background: var(--soft);">
            <div class="container">
                <span class="section-eyebrow">Ofertas e campanhas</span>
                <h2 class="section-title">Apoie a vida da nossa comunidade</h2>
                <div class="series__grid">
                    <?php foreach ($campaigns as $campaign):
                        $goal = (float) ($campaign['goal_amount'] ?? 0);
                        $raised = (float) ($campaign['raised_amount'] ?? 0);
                        $progress = $goal > 0 ? min(100, (int) round(($raised / $goal) * 100)) : 0;
                    ?>
                        <article class="series-card">
                            <div>
                                <span><?= e((string) ($campaign['designation'] ?? 'Destino da oferta')) ?> · <?= $progress ?>%</span>
                                <strong><?= e((string) ($campaign['title'] ?? 'Campanha')) ?></strong>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($templateKey !== 'campanha'): ?>
        <section class="connect">
            <div class="container">
                <span class="section-eyebrow">
                    <?= match ($templateKey) {
                        'ong'      => 'Apoie',
                        'classico' => 'Próximos passos',
                        default    => 'Conecte-se',
                    } ?>
                </span>
                <h2 class="section-title">
                    <?= match ($templateKey) {
                        'ong'      => 'Maneiras de fazer parte',
                        'classico' => 'Como participar da comunidade',
                        default    => 'Caminhos para começar com a gente',
                    } ?>
                </h2>
                <div class="connect__grid">
                    <?php foreach ($connectActions as $action): ?>
                        <article class="connect-card">
                            <span style="position:absolute;inset:0;background:<?= e($action['gradient']) ?>;"></span>
                            <strong><?= e($action['title']) ?></strong>
                            <p><?= e($action['text']) ?></p>
                            <a href="<?= e($action['href']) ?>" target="_blank" rel="noopener noreferrer"><?= e($action['cta']) ?></a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <section id="contato" class="contact-section">
            <div class="container">
                <span class="section-eyebrow">Contato</span>
                <h2 class="section-title">Estamos aqui para te receber</h2>
                <div class="contact-grid">
                    <div class="contact-info">
                        <p><?= e($description) ?></p>
                        <?php if ($contactPhone !== ''): ?><strong>Telefone</strong><p><?= e($contactPhone) ?></p><?php endif; ?>
                        <?php if ($contactEmail !== ''): ?><strong>E-mail</strong><p><?= e($contactEmail) ?></p><?php endif; ?>
                        <?php if ($address !== ''): ?><strong>Endereço</strong><p><?= e($address) ?></p><?php endif; ?>
                        <?php if (!empty($socialLinks)): ?>
                            <strong>Redes sociais</strong>
                            <div class="contact-socials">
                                <?php foreach ($socialLinks as $label => $href): ?>
                                    <a href="<?= e($href) ?>" target="_blank" rel="noopener noreferrer"><?= e($label) ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <article class="contact-card">
                        <h3>Horários e cultos</h3>
                        <p><?= e($serviceTimes) ?></p>
                        <p style="margin-top:1rem;">Quer agendar uma visita ou conversar com um pastor? Fale com a gente pelo WhatsApp.</p>
                        <?php if ($whatsappUrl !== ''): ?>
                            <a class="btn btn--primary" href="<?= e($whatsappUrl) ?>" style="margin-top:1.2rem;">Falar pelo WhatsApp</a>
                        <?php endif; ?>
                    </article>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <span><?= e($title) ?> · Site publicado pela Elo 42</span>
        </div>
    </footer>
</body>
</html>
