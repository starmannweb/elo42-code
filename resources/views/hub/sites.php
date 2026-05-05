<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<?php
    $currentSite = is_array($currentSite ?? null) ? $currentSite : null;
    $hasSavedSite = $currentSite && empty($currentSite['is_preview_only']);
    $canPublish = !empty($siteBuilderAccess['can_publish']);
    $checklist = is_array($publishChecklist ?? null) ? $publishChecklist : [];
    $doneCount = count(array_filter($checklist, static fn ($item) => !empty($item['done'])));
    $totalCount = max(1, count($checklist));
    $completion = (int) round(($doneCount / $totalCount) * 100);
    $statusClass = $canPublish ? 'hub-badge--success' : 'hub-badge--warning';
    $organizationName = (string) (($organization['name'] ?? null) ?: 'sua organização');
    $siteTitle = (string) ($currentSite['site_title'] ?? $organizationName);
    $siteDescription = (string) ($currentSite['site_description'] ?? '');
    $publishedUrl = (string) ($publishedUrl ?? ($currentSite['public_url'] ?? ''));
    $previewUrl = $publishedUrl !== '' ? $publishedUrl : url('/site/' . rawurlencode((string) ($currentSite['slug'] ?? 'preview')));
    $templateValue = (string) ($currentSite['template'] ?? 'Institucional Clássico');
    $defaultDescription = 'Uma comunidade para acolher, servir e caminhar em fé.';
    $appearance = is_array($appearanceSettings ?? null) ? $appearanceSettings : [];
    $appearancePrimary = trim((string) ($appearance['appearance_primary'] ?? ''));
    $appearanceAccent = trim((string) ($appearance['appearance_accent'] ?? ''));
    $appearanceBackground = trim((string) ($appearance['appearance_background'] ?? ''));
    $appearanceText = trim((string) ($appearance['appearance_text'] ?? ''));
    $appearanceTitleFont = trim((string) ($appearance['appearance_title_font'] ?? ''));
    $appearanceBodyFont = trim((string) ($appearance['appearance_body_font'] ?? ''));
    $hasAppearanceSettings = ($appearancePrimary !== '' || $appearanceAccent !== '');
    $initialSiteStep = (string) ($initialSiteStep ?? 'dados-site');
    $validSiteSteps = ['dados-site', 'modelos-site', 'aparencia-site', 'publicar-site'];
    if (!in_array($initialSiteStep, $validSiteSteps, true)) {
        $initialSiteStep = 'dados-site';
    }
    $galleryRaw = trim((string) ($currentSite['gallery_images'] ?? ''));
    $galleryDecoded = $galleryRaw !== '' ? json_decode($galleryRaw, true) : null;
    $galleryValue = is_array($galleryDecoded) ? implode("\n", array_map('strval', $galleryDecoded)) : $galleryRaw;
    $steps = [
        ['id' => 'dados-site', 'number' => '1', 'title' => 'Dados do site', 'text' => 'Informações públicas e contatos'],
        ['id' => 'modelos-site', 'number' => '2', 'title' => 'Escolher modelo', 'text' => 'Estrutura visual inicial'],
        ['id' => 'aparencia-site', 'number' => '3', 'title' => 'Definir aparência', 'text' => 'Logo, imagem e cores'],
        ['id' => 'publicar-site', 'number' => '4', 'title' => 'Publicar', 'text' => 'Preview, assinatura e domínio'],
    ];
?>

<section class="hub-page site-builder-page" data-site-builder-flow data-initial-step="<?= e($initialSiteStep) ?>">
    <header class="hub-page__header site-builder-header">
        <div>
            <h1 class="hub-page__title">Meu site</h1>
            <p class="hub-page__subtitle">
                Configure o site em etapas, revise o preview e publique quando a mensalidade e o domínio estiverem prontos.
            </p>
        </div>
        <div class="hub-page__actions site-builder-header__actions">
            <a href="<?= e($previewUrl) ?>" class="btn btn--outline btn--lg" target="_blank" rel="noopener noreferrer">Abrir preview</a>
            <a href="#publicar-site" class="btn btn--primary btn--lg" data-site-step-link>Ir para publicação</a>
        </div>
    </header>

    <nav class="site-step-nav" aria-label="Etapas do site">
        <?php foreach ($steps as $index => $step): ?>
            <a href="#<?= e($step['id']) ?>" class="site-step-link <?= $initialSiteStep === $step['id'] ? 'is-active' : '' ?>" data-site-step-link>
                <span class="site-step-link__number"><?= e($step['number']) ?></span>
                <span>
                    <strong><?= e($step['title']) ?></strong>
                    <small><?= e($step['text']) ?></small>
                </span>
            </a>
        <?php endforeach; ?>
    </nav>

    <form id="site-builder-form" class="site-step-form" action="<?= url('/hub/sites/configurar') ?>" method="POST" enctype="multipart/form-data" data-loading>
        <?= csrf_field() ?>
        <input type="hidden" id="site-template-value" name="template" value="<?= e($templateValue) ?>">

        <section id="dados-site" class="site-step-panel <?= $initialSiteStep === 'dados-site' ? 'is-active' : '' ?>" data-site-step-panel <?= $initialSiteStep === 'dados-site' ? '' : 'hidden' ?>>
            <div class="site-step-panel__head">
                <div>
                    <h2 class="hub-panel__title">Dados do site</h2>
                    <p class="hub-panel__text">Essas informações alimentam o preview e a página publicada.</p>
                </div>
                <button class="btn btn--primary" type="submit">Salvar dados</button>
            </div>

            <div class="form-grid form-grid--2">
                <div class="form-group">
                    <label class="form-label" for="site_title">Nome exibido no site</label>
                    <input id="site_title" name="site_title" class="form-input" value="<?= e($siteTitle) ?>" placeholder="Ex.: Comunidade Esperança">
                </div>
                <div class="form-group">
                    <label class="form-label">Modelo selecionado</label>
                    <div class="site-selected-template" data-site-template-label><?= e($templateValue) ?></div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="site_description">Chamada principal</label>
                <textarea id="site_description" name="site_description" class="form-textarea" rows="3" placeholder="<?= e($defaultDescription) ?>"><?= e($siteDescription) ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="about_text">Sobre a igreja</label>
                <textarea id="about_text" name="about_text" class="form-textarea" rows="4" placeholder="Conte em poucas linhas a história, missão e rotina da comunidade."><?= e((string) ($currentSite['about_text'] ?? '')) ?></textarea>
            </div>

            <div class="form-grid form-grid--3">
                <div class="form-group">
                    <label class="form-label" for="contact_phone">Telefone</label>
                    <input id="contact_phone" name="contact_phone" class="form-input" value="<?= e((string) ($currentSite['contact_phone'] ?? '')) ?>" placeholder="(11) 99999-9999">
                </div>
                <div class="form-group">
                    <label class="form-label" for="contact_email">E-mail</label>
                    <input id="contact_email" name="contact_email" class="form-input" value="<?= e((string) ($currentSite['contact_email'] ?? '')) ?>" placeholder="contato@igreja.org">
                </div>
                <div class="form-group">
                    <label class="form-label" for="whatsapp_url">WhatsApp</label>
                    <input id="whatsapp_url" name="whatsapp_url" class="form-input" value="<?= e((string) ($currentSite['whatsapp_url'] ?? '')) ?>" placeholder="https://wa.me/55...">
                </div>
            </div>

            <div class="form-grid form-grid--3">
                <div class="form-group">
                    <label class="form-label" for="address_line">Endereço</label>
                    <input id="address_line" name="address_line" class="form-input" value="<?= e((string) ($currentSite['address_line'] ?? '')) ?>" placeholder="Rua, número e bairro">
                </div>
                <div class="form-group">
                    <label class="form-label" for="city">Cidade</label>
                    <input id="city" name="city" class="form-input" value="<?= e((string) ($currentSite['city'] ?? '')) ?>" placeholder="Cidade">
                </div>
                <div class="form-group">
                    <label class="form-label" for="state">Estado</label>
                    <input id="state" name="state" class="form-input" value="<?= e((string) ($currentSite['state'] ?? '')) ?>" placeholder="UF">
                </div>
            </div>

            <div class="form-grid form-grid--2">
                <div class="form-group">
                    <label class="form-label" for="cta_label">Texto do botão principal</label>
                    <input id="cta_label" name="cta_label" class="form-input" value="<?= e((string) ($currentSite['cta_label'] ?? 'Falar com a igreja')) ?>" placeholder="Falar com a igreja">
                </div>
                <div class="form-group">
                    <label class="form-label" for="cta_url">Link do botão principal</label>
                    <input id="cta_url" name="cta_url" class="form-input" value="<?= e((string) ($currentSite['cta_url'] ?? '')) ?>" placeholder="https://wa.me/55...">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="service_times">Horários de cultos e encontros</label>
                <textarea id="service_times" name="service_times" class="form-textarea" rows="3" placeholder="Ex.: Domingo às 10h e 18h30. Pequenos grupos durante a semana."><?= e((string) ($currentSite['service_times'] ?? '')) ?></textarea>
                <span class="form-hint">O site público usa este texto nos blocos de contato e rodapé.</span>
            </div>

            <h3 class="hub-panel__title" style="margin-top:1.5rem;">Redes sociais</h3>
            <p class="hub-panel__text">Esses links aparecem no rodapé e cabeçalho do site público.</p>
            <div class="form-grid form-grid--3">
                <div class="form-group">
                    <label class="form-label" for="instagram_url">Instagram</label>
                    <input id="instagram_url" name="instagram_url" class="form-input" value="<?= e((string) ($currentSite['instagram_url'] ?? '')) ?>" placeholder="https://instagram.com/...">
                </div>
                <div class="form-group">
                    <label class="form-label" for="facebook_url">Facebook</label>
                    <input id="facebook_url" name="facebook_url" class="form-input" value="<?= e((string) ($currentSite['facebook_url'] ?? '')) ?>" placeholder="https://facebook.com/...">
                </div>
                <div class="form-group">
                    <label class="form-label" for="youtube_url">YouTube</label>
                    <input id="youtube_url" name="youtube_url" class="form-input" value="<?= e((string) ($currentSite['youtube_url'] ?? '')) ?>" placeholder="https://youtube.com/...">
                </div>
            </div>

            <div class="site-step-panel__footer">
                <span class="hub-panel__text">Os dados cadastrais da organização já entram como base quando estiverem preenchidos.</span>
                <button type="button" class="btn btn--outline" data-site-step-button="#modelos-site">Escolher modelo</button>
            </div>
        </section>

        <section id="modelos-site" class="site-step-panel <?= $initialSiteStep === 'modelos-site' ? 'is-active' : '' ?>" data-site-step-panel <?= $initialSiteStep === 'modelos-site' ? '' : 'hidden' ?>>
            <div class="site-step-panel__head">
                <div>
                    <h2 class="hub-panel__title">Modelos disponíveis</h2>
                    <p class="hub-panel__text">Escolha um ponto de partida. Depois, os dados preenchidos alimentam o site automaticamente.</p>
                </div>
                <button class="btn btn--primary" type="submit">Salvar modelo</button>
            </div>

            <?php
                $templateThumbs = [
                    'Institucional Clássico' => ['gradient' => 'linear-gradient(135deg,#f8fafc 0%,#dbeafe 50%,#334155 100%)', 'tone' => 'editorial claro com hierarquia serena'],
                    'Comunidade Engajada'    => ['gradient' => 'linear-gradient(135deg,#7c1d3a 0%,#9f1239 50%,#1e3a8a 100%)', 'tone' => 'bordô · multimídia e calorosa'],
                    'Campanhas e Eventos'    => ['gradient' => 'linear-gradient(135deg,#111827 0%,#e11d48 45%,#facc15 100%)', 'tone' => 'evento vibrante com urgência visual'],
                    'Captação para ONGs'     => ['gradient' => 'linear-gradient(135deg,#0f766e 0%,#14b8a6 50%,#fcd34d 100%)', 'tone' => 'verde · impacto social'],
                ];
                $templateLayouts = [
                    'Institucional Clássico' => ['Hero centralizado', 'Sobre · Ministérios · Agenda', 'Footer com contato e mapa'],
                    'Comunidade Engajada'    => ['Hero pleno c/ overlay', 'Princípios + Destaques + Séries', 'Bloco "Conecte-se" + cultos'],
                    'Campanhas e Eventos'    => ['Hero com contagem regressiva', 'Programação por dia + palestrantes', 'Inscrição direta no topo'],
                    'Captação para ONGs'     => ['Hero institucional', 'Projetos + Impacto + Histórias', 'Doação recorrente em destaque'],
                ];
            ?>

            <div class="site-template-grid">
                <?php foreach (($siteTemplates ?? []) as $template): ?>
                    <?php
                        $name = (string) ($template['name'] ?? 'Modelo');
                        $thumb = $templateThumbs[$name] ?? ['gradient' => 'linear-gradient(135deg,#1e3a8a,#0a4dff)', 'tone' => 'modelo institucional'];
                        $layout = $templateLayouts[$name] ?? [];
                        $initial = strtoupper(mb_substr($name, 0, 1, 'UTF-8'));
                    ?>
                    <article class="site-template-card <?= ($templateValue === $name || !empty($template['highlight'])) ? 'is-highlight' : '' ?>" data-site-template-card="<?= e($name) ?>">
                        <div class="site-template-card__thumb" style="background: <?= e($thumb['gradient']) ?>;">
                            <span class="site-template-card__thumb-frame">
                                <span class="site-template-card__thumb-bar"></span>
                                <span class="site-template-card__thumb-bar site-template-card__thumb-bar--short"></span>
                                <span class="site-template-card__thumb-tag"><?= e($initial) ?></span>
                            </span>
                            <small><?= e($thumb['tone']) ?></small>
                        </div>
                        <div>
                            <h3 class="hub-mini-card__title"><?= e($name) ?></h3>
                            <p class="hub-mini-card__text"><?= e((string) ($template['description'] ?? '')) ?></p>
                        </div>

                        <?php if (!empty($layout)): ?>
                            <ul class="site-template-card__asset-list site-template-card__asset-list--layout">
                                <?php foreach ($layout as $section): ?>
                                    <li><?= e($section) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <ul class="site-template-card__asset-list">
                            <?php foreach (($template['assets'] ?? []) as $asset): ?>
                                <li><?= e((string) $asset) ?></li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="hub-page__actions" style="margin-top:auto;">
                            <a href="<?= e($previewUrl . (str_contains($previewUrl, '?') ? '&' : '?') . 'template=' . rawurlencode($name)) ?>" class="btn btn--ghost" target="_blank" rel="noopener noreferrer">Ver preview</a>
                            <button type="submit" name="template" value="<?= e($name) ?>" formaction="<?= url('/hub/sites/gerar') ?>" class="btn btn--primary" data-site-template-choice="<?= e($name) ?>">Usar modelo</button>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <style>
                .site-template-card { display: flex; flex-direction: column; gap: .75rem; }
                .site-template-card__thumb { position: relative; aspect-ratio: 16/9; border-radius: 12px; overflow: hidden; padding: 1rem; color: #fff; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 12px 28px rgba(15,35,75,.18); }
                .site-template-card__thumb small { font-size: .72rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; opacity: .85; }
                .site-template-card__thumb-frame { position: relative; height: 100%; flex: 1; display: flex; flex-direction: column; justify-content: flex-end; gap: .35rem; }
                .site-template-card__thumb-bar { display: block; height: 8px; width: 70%; background: rgba(255,255,255,.45); border-radius: 4px; }
                .site-template-card__thumb-bar--short { width: 38%; background: rgba(255,255,255,.3); }
                .site-template-card__thumb-tag { position: absolute; top: 0; right: 0; background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.4); border-radius: 6px; width: 26px; height: 26px; display: grid; place-items: center; font-family: 'Saira'; font-weight: 800; font-size: .85rem; }
                .site-template-card__asset-list--layout { background: rgba(10,77,255,.05); border-left: 3px solid var(--color-bright-blue, #0a4dff); padding: .55rem .8rem; border-radius: 0 8px 8px 0; }
                .site-template-card__asset-list--layout li::marker { color: var(--color-bright-blue, #0a4dff); }
            </style>

            <div class="site-step-panel__footer">
                <button type="button" class="btn btn--ghost" data-site-step-button="#dados-site">Voltar aos dados</button>
                <button type="button" class="btn btn--outline" data-site-step-button="#aparencia-site">Definir aparência</button>
            </div>
        </section>

        <section id="aparencia-site" class="site-step-panel <?= $initialSiteStep === 'aparencia-site' ? 'is-active' : '' ?>" data-site-step-panel <?= $initialSiteStep === 'aparencia-site' ? '' : 'hidden' ?>>
            <div class="site-step-panel__head">
                <div>
                    <h2 class="hub-panel__title">Definir aparência</h2>
                    <p class="hub-panel__text">Logo, imagem principal e cores do site são personalizados aqui. As alterações entram em vigor após salvar.</p>
                </div>
                <button class="btn btn--primary" type="submit">Salvar aparência</button>
            </div>

            <div class="form-grid form-grid--2">
                <div class="form-group" data-image-uploader>
                    <label class="form-label" for="logo_image">Marca / logo</label>
                    <input id="logo_image" name="logo_image" class="form-input" value="<?= e((string) ($currentSite['logo_image'] ?? '')) ?>" placeholder="Cole uma URL https://... ou envie um arquivo abaixo">
                    <div style="display:flex;align-items:center;gap:.6rem;margin-top:.5rem;flex-wrap:wrap;">
                        <label class="btn btn--outline btn--sm" for="logo_image_file" style="margin:0;cursor:pointer;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:.35rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            Enviar arquivo
                        </label>
                        <input type="file" id="logo_image_file" name="logo_image" accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml" style="display:none;" data-file-input>
                        <span class="file-chip" data-file-chip hidden>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"></path></svg>
                            <span data-file-name>Arquivo selecionado</span>
                        </span>
                    </div>
                    <div class="image-preview" data-image-preview <?= empty($currentSite['logo_image']) ? 'hidden' : '' ?>>
                        <?php if (!empty($currentSite['logo_image'])): ?>
                            <img src="<?= e((string) $currentSite['logo_image']) ?>" alt="Logo atual">
                        <?php endif; ?>
                    </div>
                    <span class="form-hint" style="display:block;margin-top:.35rem;">PNG, JPG, WEBP, GIF ou SVG até 5 MB. O arquivo enviado tem prioridade sobre a URL.</span>
                </div>
                <div class="form-group" data-image-uploader>
                    <label class="form-label" for="hero_image">Banner principal (hero)</label>
                    <input id="hero_image" name="hero_image" class="form-input" value="<?= e((string) ($currentSite['hero_image'] ?? '')) ?>" placeholder="Cole uma URL https://... ou envie um arquivo abaixo">
                    <div style="display:flex;align-items:center;gap:.6rem;margin-top:.5rem;flex-wrap:wrap;">
                        <label class="btn btn--outline btn--sm" for="hero_image_file" style="margin:0;cursor:pointer;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:.35rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            Enviar arquivo
                        </label>
                        <input type="file" id="hero_image_file" name="hero_image" accept="image/png,image/jpeg,image/webp" style="display:none;" data-file-input>
                        <span class="file-chip" data-file-chip hidden>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"></path></svg>
                            <span data-file-name>Arquivo selecionado</span>
                        </span>
                    </div>
                    <div class="image-preview image-preview--hero" data-image-preview <?= empty($currentSite['hero_image']) ? 'hidden' : '' ?>>
                        <?php if (!empty($currentSite['hero_image'])): ?>
                            <img src="<?= e((string) $currentSite['hero_image']) ?>" alt="Banner atual">
                        <?php endif; ?>
                    </div>
                    <span class="form-hint" style="display:block;margin-top:.35rem;">Recomendado: 1600×900px. PNG, JPG ou WEBP até 5 MB. O arquivo enviado tem prioridade sobre a URL.</span>
                </div>
            </div>

            <style>
                .file-chip { display:inline-flex; align-items:center; gap:.35rem; padding:.35rem .7rem; border-radius:999px; background: rgba(16,185,129,.1); color:#0e9f6e; font-size:.78rem; font-weight:700; }
                .image-preview { margin-top:.7rem; border:1px dashed var(--color-border-light, #dfe7f4); border-radius:12px; overflow:hidden; background: var(--color-bg-light, #f4f7fd); padding:.5rem; max-width:100%; }
                .image-preview img { display:block; max-width:100%; max-height:140px; margin:0 auto; object-fit:contain; }
                .image-preview--hero img { max-height:180px; width:100%; object-fit:cover; border-radius:8px; }
                .image-preview[hidden] { display:none; }
            </style>
            <div class="site-appearance-card">
                <div>
                    <h3 class="hub-panel__title" style="margin:0;">Galeria e imagens de apoio</h3>
                    <p class="hub-panel__text" style="margin:0;">Cole uma URL por linha para fotos de cultos, comunidade, ministérios ou eventos. O site usa essas imagens como apoio visual quando houver conteúdo para destacar.</p>
                </div>
                <div class="form-group" style="margin-top:1rem;">
                    <label class="form-label" for="gallery_images">Galeria do site</label>
                    <textarea id="gallery_images" name="gallery_images" class="form-textarea" rows="4" placeholder="https://.../foto-culto.jpg&#10;https://.../ministerio.jpg"><?= e($galleryValue) ?></textarea>
                </div>
            </div>

            <script>
            (function () {
                document.querySelectorAll('[data-image-uploader]').forEach(function (group) {
                    var input = group.querySelector('[data-file-input]');
                    var chip = group.querySelector('[data-file-chip]');
                    var chipName = group.querySelector('[data-file-name]');
                    var preview = group.querySelector('[data-image-preview]');
                    if (!input) return;
                    input.addEventListener('change', function () {
                        var file = input.files && input.files[0];
                        if (!file) return;
                        if (chip && chipName) {
                            chipName.textContent = file.name;
                            chip.hidden = false;
                        }
                        if (preview) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                preview.innerHTML = '<img src="' + e.target.result + '" alt="Pré-visualização">';
                                preview.hidden = false;
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                });
            })();
            </script>

            <?php
                $primaryValue = $appearancePrimary !== '' ? $appearancePrimary : (string) ($currentSite['theme_color'] ?? '#1e3a8a');
                $accentValue = $appearanceAccent !== '' ? $appearanceAccent : '#f59e0b';
                $colorPresets = [
                    'Azul royal'   => ['#1e3a8a', '#f59e0b'],
                    'Bordô'        => ['#9f1239', '#fbbf24'],
                    'Verde igreja' => ['#0f766e', '#fbbf24'],
                    'Roxo majestoso' => ['#5b21b6', '#fcd34d'],
                    'Preto e ouro' => ['#0f172a', '#d4af37'],
                    'Marinho moderno' => ['#0a4dff', '#22d3ee'],
                ];
            ?>

            <input type="hidden" name="theme_color" id="theme_color_input" value="<?= e($primaryValue) ?>">

            <div class="site-appearance-card">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
                    <div>
                        <h3 class="hub-panel__title" style="margin:0;">Cores do site</h3>
                        <p class="hub-panel__text" style="margin:0;">Escolha as cores que serão aplicadas no site público — escolha um preset ou personalize abaixo.</p>
                    </div>
                </div>

                <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-top:1rem;" data-color-presets>
                    <?php foreach ($colorPresets as $label => $pair): ?>
                        <button type="button" class="site-color-preset" data-primary="<?= e($pair[0]) ?>" data-accent="<?= e($pair[1]) ?>" title="<?= e($label) ?>" style="display:flex;align-items:center;gap:.4rem;padding:.45rem .65rem;border:1px solid rgba(148,178,230,.25);border-radius:999px;background:transparent;cursor:pointer;font-size:.8rem;color:inherit;">
                            <span style="display:inline-flex;width:32px;height:14px;border-radius:7px;overflow:hidden;border:1px solid rgba(0,0,0,.08);">
                                <span style="flex:1;background:<?= e($pair[0]) ?>;"></span>
                                <span style="flex:1;background:<?= e($pair[1]) ?>;"></span>
                            </span>
                            <?= e($label) ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-top:1rem;">
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" for="appearance_primary">Cor primária</label>
                        <div style="display:flex;align-items:center;gap:.6rem;">
                            <input type="color" id="appearance_primary" name="appearance_primary" value="<?= e($primaryValue) ?>" style="width:48px;height:42px;border:1px solid var(--color-border-light, #dfe7f4);border-radius:8px;padding:2px;cursor:pointer;background:transparent;">
                            <input type="text" class="form-input" id="appearance_primary_text" value="<?= e($primaryValue) ?>" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$" style="flex:1;font-family:ui-monospace,Menlo,Consolas,monospace;">
                        </div>
                        <span class="form-hint">Aplicada em botões, links e cabeçalhos.</span>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" for="appearance_accent">Cor de destaque</label>
                        <div style="display:flex;align-items:center;gap:.6rem;">
                            <input type="color" id="appearance_accent" name="appearance_accent" value="<?= e($accentValue) ?>" style="width:48px;height:42px;border:1px solid var(--color-border-light, #dfe7f4);border-radius:8px;padding:2px;cursor:pointer;background:transparent;">
                            <input type="text" class="form-input" id="appearance_accent_text" value="<?= e($accentValue) ?>" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$" style="flex:1;font-family:ui-monospace,Menlo,Consolas,monospace;">
                        </div>
                        <span class="form-hint">Aplicada em CTAs e elementos de destaque.</span>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" for="appearance_background">Cor de fundo</label>
                        <div style="display:flex;align-items:center;gap:.6rem;">
                            <input type="color" id="appearance_background" name="appearance_background" value="<?= e($appearanceBackground !== '' ? $appearanceBackground : '#f4f7fd') ?>" style="width:48px;height:42px;border:1px solid var(--color-border-light, #dfe7f4);border-radius:8px;padding:2px;cursor:pointer;background:transparent;">
                            <input type="text" class="form-input" id="appearance_background_text" value="<?= e($appearanceBackground !== '' ? $appearanceBackground : '#f4f7fd') ?>" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$" style="flex:1;font-family:ui-monospace,Menlo,Consolas,monospace;">
                        </div>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" for="appearance_text">Cor dos textos</label>
                        <div style="display:flex;align-items:center;gap:.6rem;">
                            <input type="color" id="appearance_text" name="appearance_text" value="<?= e($appearanceText !== '' ? $appearanceText : '#06183a') ?>" style="width:48px;height:42px;border:1px solid var(--color-border-light, #dfe7f4);border-radius:8px;padding:2px;cursor:pointer;background:transparent;">
                            <input type="text" class="form-input" id="appearance_text_text" value="<?= e($appearanceText !== '' ? $appearanceText : '#06183a') ?>" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$" style="flex:1;font-family:ui-monospace,Menlo,Consolas,monospace;">
                        </div>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" for="appearance_title_font">Fonte de títulos</label>
                        <select id="appearance_title_font" name="appearance_title_font" class="form-select">
                            <?php foreach (['Saira' => 'Saira', 'Inter' => 'Inter', 'Merriweather' => 'Merriweather', 'Montserrat' => 'Montserrat'] as $fontValue => $fontLabel): ?>
                                <option value="<?= e($fontValue) ?>" <?= ($appearanceTitleFont ?: 'Saira') === $fontValue ? 'selected' : '' ?>><?= e($fontLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group" style="margin:0;">
                        <label class="form-label" for="appearance_body_font">Fonte de textos</label>
                        <select id="appearance_body_font" name="appearance_body_font" class="form-select">
                            <?php foreach (['Inter' => 'Inter', 'Saira' => 'Saira', 'Lato' => 'Lato', 'Source Sans 3' => 'Source Sans 3'] as $fontValue => $fontLabel): ?>
                                <option value="<?= e($fontValue) ?>" <?= ($appearanceBodyFont ?: 'Inter') === $fontValue ? 'selected' : '' ?>><?= e($fontLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <script>
            (function () {
                var primary = document.getElementById('appearance_primary');
                var primaryText = document.getElementById('appearance_primary_text');
                var accent = document.getElementById('appearance_accent');
                var accentText = document.getElementById('appearance_accent_text');
                var background = document.getElementById('appearance_background');
                var backgroundText = document.getElementById('appearance_background_text');
                var text = document.getElementById('appearance_text');
                var textText = document.getElementById('appearance_text_text');
                var hidden = document.getElementById('theme_color_input');
                if (!primary) return;

                function syncTextFromColor(colorInput, textInput) {
                    textInput.value = colorInput.value.toUpperCase();
                    if (colorInput === primary && hidden) hidden.value = colorInput.value;
                }
                function syncColorFromText(textInput, colorInput) {
                    var v = textInput.value.trim();
                    if (/^#[0-9A-Fa-f]{6}$/.test(v)) {
                        colorInput.value = v;
                        if (colorInput === primary && hidden) hidden.value = v;
                    }
                }
                primary.addEventListener('input', function () { syncTextFromColor(primary, primaryText); });
                accent.addEventListener('input', function () { syncTextFromColor(accent, accentText); });
                primaryText.addEventListener('input', function () { syncColorFromText(primaryText, primary); });
                accentText.addEventListener('input', function () { syncColorFromText(accentText, accent); });
                if (background && backgroundText) {
                    background.addEventListener('input', function () { syncTextFromColor(background, backgroundText); });
                    backgroundText.addEventListener('input', function () { syncColorFromText(backgroundText, background); });
                }
                if (text && textText) {
                    text.addEventListener('input', function () { syncTextFromColor(text, textText); });
                    textText.addEventListener('input', function () { syncColorFromText(textText, text); });
                }

                document.querySelectorAll('[data-color-presets] .site-color-preset').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var p = btn.getAttribute('data-primary');
                        var a = btn.getAttribute('data-accent');
                        if (p) { primary.value = p; primaryText.value = p.toUpperCase(); if (hidden) hidden.value = p; }
                        if (a) { accent.value = a; accentText.value = a.toUpperCase(); }
                    });
                });
            })();
            </script>

            <div class="site-preview-card" aria-label="Preview visual do site">
                <?php if (!empty($currentSite['hero_image'])): ?>
                    <div class="site-preview-card__hero" style="background-image:url('<?= e((string) $currentSite['hero_image']) ?>'); background-size:cover; background-position:center;"></div>
                <?php else: ?>
                    <div class="site-preview-card__hero" style="background:linear-gradient(135deg, <?= e($appearancePrimary !== '' ? $appearancePrimary : '#1e3a8a') ?>, <?= e($appearanceAccent !== '' ? $appearanceAccent : '#f59e0b') ?>);"></div>
                <?php endif; ?>
                <div>
                    <h3 class="hub-mini-card__title"><?= e($siteTitle) ?></h3>
                    <p class="hub-mini-card__text"><?= e($templateValue) ?></p>
                </div>
                <div class="site-preview-card__lines"><span></span><span></span><span></span></div>
            </div>

            <div class="site-step-panel__footer">
                <button type="button" class="btn btn--ghost" data-site-step-button="#modelos-site">Voltar aos modelos</button>
                <button type="button" class="btn btn--outline" data-site-step-button="#publicar-site">Avançar para publicação</button>
            </div>
        </section>

        <section id="publicar-site" class="site-step-panel <?= $initialSiteStep === 'publicar-site' ? 'is-active' : '' ?>" data-site-step-panel <?= $initialSiteStep === 'publicar-site' ? '' : 'hidden' ?>>
            <?php
                $isPublished = ($currentSite['status'] ?? 'draft') === 'published';
                $publishStateLabel = $isPublished ? 'Site publicado' : ($hasSavedSite ? 'Rascunho salvo' : 'Aguardando geração');
                $publishStateClass = $isPublished ? 'hub-badge--success' : ($hasSavedSite ? 'hub-badge--warning' : 'hub-badge--neutral');
                $slugFallback = (string) ($currentSite['slug'] ?? 'minha-igreja');
                $publicSiteUrl = $publishedUrl !== '' ? $publishedUrl : url('/site/' . rawurlencode($slugFallback));
                $organizationLabel = trim((string) ($organization['name'] ?? 'sua organização'));
                $checklistAnchors = [
                    'Dados da organização' => '#dados-site',
                    'Texto do site'        => '#dados-site',
                    'Imagens'              => '#aparencia-site',
                    'Contato'              => '#dados-site',
                    'Redes sociais'        => '#dados-site',
                    'Mensalidade de publicação' => null,
                ];
                $monthlyFee = trim((string) ($siteBuilderAccess['monthly_fee_label'] ?? ''));
                $isCourtesy = strcasecmp($monthlyFee, 'Cortesia Elo 42') === 0 || ($siteBuilderAccess['status'] ?? '') === 'granted';
            ?>

            <div class="site-step-panel__head">
                <div>
                    <h2 class="hub-panel__title">Publicação</h2>
                    <p class="hub-panel__text">Revise o material, garanta os ajustes pendentes e publique o site na URL pública.</p>
                </div>
                <span class="hub-badge <?= e($publishStateClass) ?>"><?= e($publishStateLabel) ?></span>
            </div>

            <article class="site-publish-summary" style="margin-bottom:var(--space-4);">
                <div class="site-publish-summary__preview" style="--preview-primary: <?= e($appearancePrimary !== '' ? $appearancePrimary : ((string) ($currentSite['theme_color'] ?? '#1e3a8a'))) ?>; --preview-accent: <?= e($appearanceAccent !== '' ? $appearanceAccent : '#f59e0b') ?>;">
                    <?php if (!empty($currentSite['hero_image'])): ?>
                        <div class="site-publish-summary__hero" style="background-image:url('<?= e((string) $currentSite['hero_image']) ?>');"></div>
                    <?php else: ?>
                        <div class="site-publish-summary__hero site-publish-summary__hero--gradient"></div>
                    <?php endif; ?>
                    <div class="site-publish-summary__overlay">
                        <div class="site-publish-summary__brand">
                            <?php if (!empty($currentSite['logo_image'])): ?>
                                <img src="<?= e((string) $currentSite['logo_image']) ?>" alt="Logo">
                            <?php else: ?>
                                <span class="site-publish-summary__initial"><?= e(strtoupper(mb_substr($siteTitle, 0, 1, 'UTF-8'))) ?></span>
                            <?php endif; ?>
                            <div>
                                <strong><?= e($siteTitle) ?></strong>
                                <span><?= e($templateValue) ?></span>
                            </div>
                        </div>
                        <p><?= e(mb_substr($siteDescription !== '' ? $siteDescription : $defaultDescription, 0, 160, 'UTF-8')) ?><?= mb_strlen($siteDescription) > 160 ? '…' : '' ?></p>
                    </div>
                </div>

                <div class="site-publish-summary__body">
                    <h3 class="hub-panel__title" style="margin:0;">Visão geral</h3>
                    <p class="hub-panel__text" style="margin:.4rem 0 1rem;">
                        <?= $currentSite ? ($hasSavedSite ? 'Rascunho atualizado e pronto para revisão pública.' : 'Pré-visualização montada com os dados cadastrais da organização.') : 'Nenhum rascunho foi gerado ainda — escolha um modelo para começar.' ?>
                    </p>
                    <dl class="site-publish-summary__meta">
                        <div>
                            <dt>Última atualização</dt>
                            <dd><?= e((string) ($currentSite['generated_at_label'] ?? 'Ainda não gerado')) ?></dd>
                        </div>
                        <div>
                            <dt>Endereço público</dt>
                            <dd>
                                <span class="site-publish-summary__url" data-publish-url><?= e($publicSiteUrl) ?></span>
                                <button type="button" class="site-publish-summary__copy" data-copy-url="<?= e($publicSiteUrl) ?>" aria-label="Copiar URL">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                </button>
                            </dd>
                        </div>
                    </dl>
                    <div class="hub-page__actions">
                        <a href="<?= e($previewUrl) ?>" class="btn btn--outline" target="_blank" rel="noopener noreferrer">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:.4rem;"><path d="M14 3h7v7"/><path d="M21 3l-9 9"/><path d="M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5"/></svg>
                            Abrir preview
                        </a>
                        <button class="btn btn--ghost" type="submit" formaction="<?= url('/hub/sites/gerar') ?>"><?= $currentSite ? 'Regenerar rascunho' : 'Gerar rascunho' ?></button>
                    </div>
                </div>
            </article>

            <article class="site-readiness-card" style="margin-bottom:var(--space-4);">
                <div class="site-readiness-card__head">
                    <div>
                        <h3 class="hub-panel__title">Prontidão do site</h3>
                        <p class="hub-panel__text"><?= $doneCount ?> de <?= $totalCount ?> itens concluídos · clique para ajustar</p>
                    </div>
                    <strong><?= $completion ?>%</strong>
                </div>
                <div class="site-progress"><span style="width: <?= $completion ?>%;"></span></div>
                <ul class="site-readiness-list">
                    <?php foreach ($checklist as $item):
                        $title = (string) ($item['title'] ?? 'Item');
                        $anchor = $checklistAnchors[$title] ?? null;
                        $tag = $anchor ? 'button' : 'div';
                    ?>
                        <li class="<?= !empty($item['done']) ? 'is-done' : 'is-pending' ?>">
                            <<?= $tag ?>
                                class="site-readiness-list__row"
                                <?= $tag === 'button' ? 'type="button" data-site-step-button="' . e($anchor) . '"' : '' ?>
                            >
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <?= !empty($item['done']) ? '<path d="M20 6 9 17l-5-5"></path>' : '<circle cx="12" cy="12" r="9"></circle><path d="M12 8v5"></path><path d="M12 16h.01"></path>' ?>
                                </svg>
                                <div>
                                    <strong><?= e($title) ?></strong>
                                    <span><?= e((string) ($item['text'] ?? '')) ?></span>
                                </div>
                                <?php if ($anchor && empty($item['done'])): ?>
                                    <span class="site-readiness-list__chevron" aria-hidden="true">→</span>
                                <?php endif; ?>
                            </<?= $tag ?>>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </article>

            <article class="site-publish-controls">
                <?php if (!$canPublish): ?>
                    <div class="site-publish-cta">
                        <div>
                            <h3 class="hub-panel__title">Ative a mensalidade do construtor</h3>
                            <p class="hub-panel__text">A publicação em domínio real depende da mensalidade ativa. Os dados e o preview ficam salvos enquanto você ativa o plano "Site para Igrejas".</p>
                        </div>
                        <a href="<?= url('/gestao/assinatura') ?>" class="btn btn--primary btn--lg">Ativar assinatura</a>
                    </div>
                <?php else: ?>
                    <div class="site-publish-controls__head">
                        <h3 class="hub-panel__title">Pronto para publicar</h3>
                        <span class="hub-panel__text">
                            <?= $isCourtesy ? 'Publicação cortesia Elo 42 ativa.' : 'Mensalidade ' . e($monthlyFee !== '' ? $monthlyFee : 'do construtor') . ' ativa.' ?>
                            <?= $isPublished ? ' Última publicação: ' . e((string) ($currentSite['published_at_label'] ?? '')) : '' ?>
                        </span>
                    </div>
                <?php endif; ?>

                <div class="form-group" style="margin-top:<?= $canPublish ? '1.25rem' : '0' ?>;">
                    <label class="form-label" for="domain">Domínio próprio (opcional)</label>
                    <input id="domain" name="domain" class="form-input" value="<?= e((string) ($currentSite['domain'] ?? '')) ?>" placeholder="www.suaigreja.org.br" <?= $canPublish ? '' : 'disabled' ?>>
                    <?php
                        $dnsCname = (string) env('SITES_DNS_CNAME', 'sites.elo42.com.br');
                        $dnsA = (string) env('SITES_DNS_A', '185.158.133.1');
                        $verifyTxtName = '_elo42-verify';
                        $verifyTxtValue = 'elo42-org-' . (int) ($organization['id'] ?? 0);
                    ?>
                    <?php if ($canPublish): ?>
                        <details class="site-publish-dns">
                            <summary>Como apontar o DNS do domínio próprio</summary>
                            <p style="margin-bottom:.7rem;">Adicione os registros abaixo no painel da sua hospedagem. A propagação pode levar até 24h. Enquanto isso, o site continua acessível em <code><?= e($publicSiteUrl) ?></code>.</p>
                            <div class="site-publish-dns__row"><span>Tipo</span><span>Nome</span><span>Valor</span></div>
                            <div class="site-publish-dns__row site-publish-dns__row--data"><strong>CNAME</strong><code>www</code><code><?= e($dnsCname) ?></code></div>
                            <div class="site-publish-dns__row site-publish-dns__row--data"><strong>A</strong><code>@</code><code><?= e($dnsA) ?></code></div>
                            <div class="site-publish-dns__row site-publish-dns__row--data"><strong>TXT</strong><code><?= e($verifyTxtName) ?></code><code><?= e($verifyTxtValue) ?></code></div>
                            <p style="margin-top:.7rem;font-size:.8rem;color:#6b7892;">Use CNAME quando seu provedor permitir flatten do registro raiz, caso contrário use o A. O TXT é opcional e ajuda a confirmar a posse do domínio.</p>
                        </details>
                    <?php else: ?>
                        <span class="form-hint">Disponível para assinantes. Ative o plano para configurar domínio próprio.</span>
                    <?php endif; ?>
                </div>

                <div class="hub-page__actions" style="margin-top:1rem;justify-content:flex-end;">
                    <button type="button" class="btn btn--ghost" data-site-step-button="#aparencia-site">Voltar à aparência</button>
                    <?php if ($canPublish && $hasSavedSite): ?>
                        <button class="btn btn--primary btn--lg" type="submit" formaction="<?= url('/hub/sites/publicar') ?>">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="margin-right:.4rem;"><path d="M5 12l5 5L20 7"/></svg>
                            <?= $isPublished ? 'Republicar site' : 'Publicar site' ?>
                        </button>
                    <?php elseif ($canPublish): ?>
                        <button class="btn btn--primary btn--lg" type="submit">Salvar e gerar rascunho</button>
                    <?php else: ?>
                        <a href="<?= url('/gestao/assinatura') ?>" class="btn btn--primary btn--lg">Ativar assinatura</a>
                    <?php endif; ?>
                </div>
            </article>

            <style>
                .site-publish-summary { display: grid; grid-template-columns: minmax(0, 1.05fr) minmax(0, .95fr); border: 1px solid var(--color-border); border-radius: 18px; overflow: hidden; background: #fff; box-shadow: 0 14px 32px rgba(15,35,75,.06); }
                .site-publish-summary__preview { position: relative; min-height: 220px; color: #fff; overflow: hidden; }
                .site-publish-summary__hero { position: absolute; inset: 0; background-size: cover; background-position: center; }
                .site-publish-summary__hero--gradient { background: linear-gradient(135deg, var(--preview-primary, #1e3a8a) 0%, color-mix(in srgb, var(--preview-primary, #1e3a8a) 70%, #000 30%) 60%, var(--preview-accent, #f59e0b) 100%); }
                .site-publish-summary__overlay { position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; justify-content: flex-end; padding: 1.4rem; gap: .8rem; background: linear-gradient(180deg, rgba(8,18,45,.05) 0%, rgba(8,18,45,.7) 100%); }
                .site-publish-summary__brand { display: flex; align-items: center; gap: .8rem; }
                .site-publish-summary__brand img { width: 44px; height: 44px; object-fit: contain; background: rgba(255,255,255,.92); border-radius: 10px; padding: 4px; }
                .site-publish-summary__initial { width: 44px; height: 44px; border-radius: 10px; background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.4); display: grid; place-items: center; font-family: 'Saira'; font-weight: 800; font-size: 1.1rem; }
                .site-publish-summary__brand strong { display: block; font-family: 'Saira'; font-size: 1.05rem; color: #fff; }
                .site-publish-summary__brand span { display: block; font-size: .78rem; color: rgba(255,255,255,.78); }
                .site-publish-summary__overlay p { margin: 0; color: rgba(255,255,255,.85); font-size: .9rem; line-height: 1.5; }
                .site-publish-summary__body { padding: 1.5rem; display: flex; flex-direction: column; }
                .site-publish-summary__meta { display: grid; gap: .8rem; margin: 0 0 1rem; }
                .site-publish-summary__meta div { display: grid; gap: 4px; }
                .site-publish-summary__meta dt { color: #6b7892; font-size: .76rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; margin: 0; }
                .site-publish-summary__meta dd { margin: 0; display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
                .site-publish-summary__url { font-family: ui-monospace, Menlo, Consolas, monospace; font-size: .85rem; color: var(--color-bright-blue, #0a4dff); word-break: break-all; }
                .site-publish-summary__copy { background: var(--color-bg-light, #f4f7fd); border: 1px solid var(--color-border-light, #dfe7f4); border-radius: 6px; padding: 4px 6px; cursor: pointer; color: #6b7892; transition: background .18s ease, color .18s ease; }
                .site-publish-summary__copy:hover { background: rgba(10,77,255,.12); color: var(--color-bright-blue, #0a4dff); }
                .site-publish-summary__copy.is-copied { background: rgba(16,185,129,.18); color: #0e9f6e; }

                .site-readiness-list__row { display: flex; align-items: flex-start; gap: 10px; width: 100%; background: transparent; border: none; padding: 0; cursor: default; text-align: left; color: inherit; font: inherit; }
                .site-readiness-list li button.site-readiness-list__row { cursor: pointer; }
                .site-readiness-list li:has(button.site-readiness-list__row):hover { background: rgba(10,77,255,.06); border-color: rgba(10,77,255,.22); }
                .site-readiness-list__chevron { margin-left: auto; color: #6b7892; font-weight: 800; font-size: 1.05rem; }

                .site-publish-controls { border: 1px solid var(--color-border); border-radius: 16px; background: #fff; padding: var(--space-5); }
                .site-publish-controls__head { display: flex; flex-direction: column; gap: .25rem; }
                .site-publish-controls__head .hub-panel__title { margin: 0; }
                .site-publish-dns { margin-top: .5rem; font-size: .85rem; }
                .site-publish-dns summary { cursor: pointer; color: var(--color-bright-blue, #0a4dff); font-weight: 700; outline: none; }
                .site-publish-dns summary:focus-visible { box-shadow: 0 0 0 3px rgba(10,77,255,.18); border-radius: 6px; }
                .site-publish-dns p { color: #4b5d7c; line-height: 1.6; margin: .55rem 0 0; }
                .site-publish-dns code { background: var(--color-bg-light, #f4f7fd); padding: 1px 6px; border-radius: 4px; font-family: ui-monospace, Menlo, Consolas, monospace; }
                .site-publish-dns__row { display: grid; grid-template-columns: 80px 110px 1fr; gap: .6rem; padding: .45rem .6rem; border-bottom: 1px solid var(--color-border-light, #dfe7f4); align-items: center; font-size: .78rem; color: #6b7892; }
                .site-publish-dns__row:first-of-type { font-weight: 700; text-transform: uppercase; letter-spacing: .04em; background: var(--color-bg-light, #f4f7fd); border-radius: 6px 6px 0 0; }
                .site-publish-dns__row--data { font-family: inherit; color: #11284f; }
                .site-publish-dns__row--data strong { font-weight: 700; }

                @media (max-width: 860px) {
                    .site-publish-summary { grid-template-columns: 1fr; }
                    .site-publish-summary__preview { min-height: 180px; }
                }
            </style>

            <script>
            (function () {
                document.querySelectorAll('[data-copy-url]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var value = btn.getAttribute('data-copy-url') || '';
                        if (!value) return;
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            navigator.clipboard.writeText(value).then(function () {
                                btn.classList.add('is-copied');
                                setTimeout(function () { btn.classList.remove('is-copied'); }, 1500);
                            });
                        }
                    });
                });
            })();
            </script>
        </section>
    </form>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var root = document.querySelector('[data-site-builder-flow]');
    if (!root) {
        return;
    }

    var links = Array.prototype.slice.call(root.querySelectorAll('[data-site-step-link], [data-site-step-button]'));
    var panels = Array.prototype.slice.call(root.querySelectorAll('[data-site-step-panel]'));
    var templateInput = document.getElementById('site-template-value');
    var templateLabel = root.querySelector('[data-site-template-label]');

    function showStep(id, updateHash) {
        var target = id.replace('#', '');
        panels.forEach(function (panel) {
            var active = panel.id === target;
            panel.hidden = !active;
            panel.classList.toggle('is-active', active);
        });
        root.querySelectorAll('.site-step-link').forEach(function (link) {
            link.classList.toggle('is-active', link.getAttribute('href') === '#' + target);
        });
        if (updateHash !== false && window.location.hash !== '#' + target) {
            history.replaceState(null, '', '#' + target);
        }
    }

    links.forEach(function (link) {
        link.addEventListener('click', function (event) {
            var target = link.getAttribute('href') || link.getAttribute('data-site-step-button');
            if (!target || target.charAt(0) !== '#') {
                return;
            }
            event.preventDefault();
            showStep(target, true);
        });
    });

    root.querySelectorAll('[data-site-template-choice]').forEach(function (button) {
        button.addEventListener('click', function () {
            var name = button.getAttribute('data-site-template-choice') || button.value || '';
            if (templateInput && name !== '') {
                templateInput.value = name;
            }
            if (templateLabel && name !== '') {
                templateLabel.textContent = name;
            }
        });
    });

    var initialStep = root.getAttribute('data-initial-step') || 'dados-site';
    var initialHash = window.location.hash && root.querySelector(window.location.hash) ? window.location.hash : '#' + initialStep;
    showStep(initialHash, false);
});
</script>

<?php $__view->endSection(); ?>
