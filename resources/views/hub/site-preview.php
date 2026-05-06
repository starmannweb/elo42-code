<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>
<?php
    $currentSite = is_array($currentSite ?? null) ? $currentSite : [];
    $settings = is_array($settings ?? null) ? $settings : [];
    $organization = is_array($organization ?? null) ? $organization : [];
    $orgName = trim((string) ($organization['name'] ?? 'Sua igreja'));
    $siteTitle = trim((string) ($currentSite['site_title'] ?? $settings['seo_title'] ?? $orgName));
    $siteTitle = $siteTitle !== '' ? $siteTitle : 'Sua igreja';
    $siteDescription = trim((string) ($currentSite['site_description'] ?? $settings['seo_desc'] ?? 'Uma comunidade para servir, acolher e caminhar em fé.'));
    $template = (string) ($currentSite['template'] ?? 'Institucional Clássico');
    $aboutText = trim((string) ($currentSite['about_text'] ?? 'Conheça nossa comunidade, acompanhe os eventos e participe das campanhas cadastradas pela organização.'));
    $heroImage = trim((string) ($currentSite['hero_image'] ?? ''));
    $logoImage = trim((string) ($currentSite['logo_image'] ?? ''));
    $contactPhone = trim((string) ($currentSite['contact_phone'] ?? ''));
    $contactEmail = trim((string) ($currentSite['contact_email'] ?? ''));
    $publicUrl = trim((string) ($publishedUrl ?? $currentSite['public_url'] ?? ''));
    $safeHex = static function (?string $value, string $fallback): string {
        $value = trim((string) $value);
        return preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? $value : $fallback;
    };
    $primary = $safeHex($currentSite['theme_color'] ?? ($settings['appearance_primary'] ?? null), '#1547f5');
    $accent = $safeHex($settings['appearance_accent'] ?? null, '#e5b84f');
?>

<section class="hub-page">
    <header class="hub-page__header">
        <div class="hub-panel__row">
            <div>
                <h1 class="hub-page__title">Preview do site</h1>
                <p class="hub-page__subtitle">Visualize o rascunho gerado com dados reais da organização antes de publicar.</p>
            </div>
            <div class="hub-page__actions">
                <a href="<?= url('/hub/sites') ?>" class="btn btn--outline btn--lg">Voltar</a>
                <?php if ($publicUrl !== ''): ?>
                    <a href="<?= e($publicUrl) ?>" class="btn btn--ghost btn--lg" target="_blank" rel="noopener noreferrer">Abrir URL</a>
                <?php endif; ?>
                <form action="<?= url('/hub/sites/gerar') ?>" method="POST" data-loading>
                    <?= csrf_field() ?>
                    <input type="hidden" name="template" value="<?= e($template) ?>">
                    <button type="submit" class="btn btn--primary btn--lg">Usar este modelo</button>
                </form>
            </div>
        </div>
    </header>

    <div class="site-builder-preview" style="--preview-primary: <?= e($primary) ?>; --preview-accent: <?= e($accent) ?>;">
        <div class="site-builder-preview__bar">
            <strong>
                <?php if ($logoImage !== ''): ?>
                    <img src="<?= e($logoImage) ?>" alt="<?= e($siteTitle) ?>" style="max-height:32px;max-width:120px;object-fit:contain;vertical-align:middle;margin-right:.5rem;">
                <?php endif; ?>
                <?= e($siteTitle) ?>
            </strong>
            <nav>
                <span>Sobre</span>
                <span>Eventos</span>
                <span>Ofertas</span>
                <span>Contato</span>
            </nav>
        </div>

        <section class="site-builder-preview__hero">
            <div>
                <span class="site-builder-preview__pill"><?= e($template) ?></span>
                <h2><?= e($siteTitle) ?></h2>
                <p><?= e($siteDescription) ?></p>
                <div class="site-builder-preview__actions">
                    <span>Conheça a comunidade</span>
                    <span>Agenda da semana</span>
                </div>
            </div>
            <div class="site-builder-preview__image" <?= $heroImage !== '' ? 'style="background-image:url(\'' . e($heroImage) . '\');background-size:cover;background-position:center;"' : '' ?>>
                <?php if ($heroImage === ''): ?>
                    <span>Imagem principal da igreja</span>
                <?php endif; ?>
            </div>
        </section>

        <section class="site-builder-preview__grid">
            <article>
                <span>Ministérios</span>
                <strong>Conecte pessoas a áreas de serviço.</strong>
                <p><?= e($aboutText) ?></p>
            </article>
            <article>
                <span>Ofertas</span>
                <strong>Campanhas vinculadas ao PIX.</strong>
                <p>As ofertas do site usam as campanhas cadastradas pela igreja e o meio de pagamento configurado.</p>
            </article>
            <article>
                <span>Contato</span>
                <strong><?= e($contactPhone !== '' ? $contactPhone : ($contactEmail !== '' ? $contactEmail : 'Facilite visitas e pedidos.')) ?></strong>
                <p>WhatsApp, redes sociais e localização podem ser exibidos quando configurados.</p>
            </article>
        </section>

        <section class="site-builder-preview__section">
            <div>
                <h3>Próximos eventos</h3>
                <p>O site utiliza a agenda cadastrada pelo admin.</p>
            </div>
            <div class="site-builder-preview__list">
                <?php if (empty($previewEvents)): ?>
                    <div class="site-builder-preview__empty">Nenhum evento publicado ainda.</div>
                <?php else: ?>
                    <?php foreach ($previewEvents as $event): ?>
                        <div>
                            <strong><?= e((string) $event['title']) ?></strong>
                            <span><?= !empty($event['start_date']) ? date('d/m/Y H:i', strtotime((string) $event['start_date'])) : 'Data a definir' ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="site-builder-preview__section">
            <div>
                <h3>Ofertas e campanhas</h3>
                <p>Campanhas ativas aparecem como destinos de contribuição.</p>
            </div>
            <div class="site-builder-preview__list">
                <?php if (empty($previewCampaigns)): ?>
                    <div class="site-builder-preview__empty">Cadastre campanhas para exibir opções de ofertas.</div>
                <?php else: ?>
                    <?php foreach ($previewCampaigns as $campaign): ?>
                        <?php
                            $goal = (float) ($campaign['goal_amount'] ?? 0);
                            $raised = (float) ($campaign['raised_amount'] ?? 0);
                            $progress = $goal > 0 ? min(100, (int) round(($raised / $goal) * 100)) : 0;
                        ?>
                        <div>
                            <strong><?= e((string) $campaign['title']) ?></strong>
                            <span><?= e((string) ($campaign['designation'] ?? 'Campanha da igreja')) ?> · <?= $progress ?>%</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="site-builder-preview__assets">
            <h3>Arquivos recomendados para melhorar o site</h3>
            <ul>
                <li>Logo em PNG ou SVG</li>
                <li>Foto principal da fachada, culto ou comunidade</li>
                <li>Fotos de ministérios, eventos e campanhas</li>
                <li>Ícones do APP 192x192 e 512x512</li>
            </ul>
        </section>
    </div>
</section>

<?php $__view->endSection(); ?>
