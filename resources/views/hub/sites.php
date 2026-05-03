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
    $hasAppearanceSettings = ($appearancePrimary !== '' || $appearanceAccent !== '');
    $steps = [
        ['id' => 'dados-site', 'number' => '1', 'title' => 'Dados do site', 'text' => 'Informações públicas e contatos'],
        ['id' => 'modelos-site', 'number' => '2', 'title' => 'Escolher modelo', 'text' => 'Estrutura visual inicial'],
        ['id' => 'aparencia-site', 'number' => '3', 'title' => 'Definir aparência', 'text' => 'Logo, imagem e cores'],
        ['id' => 'publicar-site', 'number' => '4', 'title' => 'Publicar', 'text' => 'Preview, assinatura e domínio'],
    ];
?>

<section class="hub-page site-builder-page" data-site-builder-flow>
    <header class="hub-page__header site-builder-header">
        <div>
            <h1 class="hub-page__title">Meus sites</h1>
            <p class="hub-page__subtitle">
                Configure o site em etapas, revise o preview e publique quando a mensalidade e o domínio estiverem prontos.
            </p>
        </div>
        <div class="hub-page__actions site-builder-header__actions">
            <a href="<?= e($previewUrl) ?>" class="btn btn--outline btn--lg" target="_blank" rel="noopener noreferrer">Preview</a>
            <?php if ($canPublish && $hasSavedSite): ?>
                <button class="btn btn--primary btn--lg" type="submit" form="site-builder-form" formaction="<?= url('/hub/sites/publicar') ?>">Publicar site</button>
            <?php elseif ($canPublish): ?>
                <button class="btn btn--primary btn--lg" type="submit" form="site-builder-form">Salvar para publicar</button>
            <?php else: ?>
                <a href="#publicar-site" class="btn btn--primary btn--lg" data-site-step-link>Ver publicação</a>
            <?php endif; ?>
        </div>
    </header>

    <nav class="site-step-nav" aria-label="Etapas do site">
        <?php foreach ($steps as $index => $step): ?>
            <a href="#<?= e($step['id']) ?>" class="site-step-link <?= $index === 0 ? 'is-active' : '' ?>" data-site-step-link>
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

        <section id="dados-site" class="site-step-panel is-active" data-site-step-panel>
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

        <section id="modelos-site" class="site-step-panel" data-site-step-panel hidden>
            <div class="site-step-panel__head">
                <div>
                    <h2 class="hub-panel__title">Modelos disponíveis</h2>
                    <p class="hub-panel__text">Escolha um ponto de partida. Depois, os dados preenchidos alimentam o site automaticamente.</p>
                </div>
                <button class="btn btn--primary" type="submit">Salvar modelo</button>
            </div>

            <div class="site-template-grid">
                <?php foreach (($siteTemplates ?? []) as $template): ?>
                    <?php $name = (string) ($template['name'] ?? 'Modelo'); ?>
                    <article class="site-template-card <?= ($templateValue === $name || !empty($template['highlight'])) ? 'is-highlight' : '' ?>" data-site-template-card="<?= e($name) ?>">
                        <div>
                            <h3 class="hub-mini-card__title"><?= e($name) ?></h3>
                            <p class="hub-mini-card__text"><?= e((string) ($template['description'] ?? '')) ?></p>
                        </div>

                        <ul class="site-template-card__asset-list">
                            <?php foreach (($template['assets'] ?? []) as $asset): ?>
                                <li><?= e((string) $asset) ?></li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="hub-page__actions" style="margin-top:auto;">
                            <a href="<?= e($previewUrl . (str_contains($previewUrl, '?') ? '&' : '?') . 'template=' . rawurlencode($name)) ?>" class="btn btn--ghost" target="_blank" rel="noopener noreferrer">Ver preview</a>
                            <button type="submit" name="template" value="<?= e($name) ?>" class="btn btn--primary" data-site-template-choice="<?= e($name) ?>">Usar modelo</button>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="site-step-panel__footer">
                <button type="button" class="btn btn--ghost" data-site-step-button="#dados-site">Voltar aos dados</button>
                <button type="button" class="btn btn--outline" data-site-step-button="#aparencia-site">Definir aparência</button>
            </div>
        </section>

        <section id="aparencia-site" class="site-step-panel" data-site-step-panel hidden>
            <div class="site-step-panel__head">
                <div>
                    <h2 class="hub-panel__title">Definir aparência</h2>
                    <p class="hub-panel__text">Logo, imagem principal e cores do site são personalizados aqui. As alterações entram em vigor após salvar.</p>
                </div>
                <button class="btn btn--primary" type="submit">Salvar aparência</button>
            </div>

            <div class="form-grid form-grid--2">
                <div class="form-group">
                    <label class="form-label" for="logo_image">Logo</label>
                    <input id="logo_image" name="logo_image" class="form-input" value="<?= e((string) ($currentSite['logo_image'] ?? '')) ?>" placeholder="Cole uma URL https://... ou envie um arquivo abaixo">
                    <div style="display:flex;align-items:center;gap:.6rem;margin-top:.5rem;flex-wrap:wrap;">
                        <label class="btn btn--outline btn--sm" for="logo_image_file" style="margin:0;cursor:pointer;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:.35rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            Enviar arquivo
                        </label>
                        <input type="file" id="logo_image_file" name="logo_image" accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml" style="display:none;" onchange="document.getElementById('logo_image').value=this.files[0]?this.files[0].name:'';this.previousElementSibling?.querySelector('.file-name')?.remove();">
                        <?php if (!empty($currentSite['logo_image'])): ?>
                            <img src="<?= e((string) $currentSite['logo_image']) ?>" alt="Logo atual" style="max-height:32px;border-radius:4px;border:1px solid var(--color-border-light, #dfe7f4);">
                        <?php endif; ?>
                    </div>
                    <span class="form-hint" style="display:block;margin-top:.25rem;">PNG, JPG, WEBP, GIF ou SVG até 5 MB. O arquivo enviado tem prioridade sobre a URL.</span>
                </div>
                <div class="form-group">
                    <label class="form-label" for="hero_image">Imagem principal</label>
                    <input id="hero_image" name="hero_image" class="form-input" value="<?= e((string) ($currentSite['hero_image'] ?? '')) ?>" placeholder="Cole uma URL https://... ou envie um arquivo abaixo">
                    <div style="display:flex;align-items:center;gap:.6rem;margin-top:.5rem;flex-wrap:wrap;">
                        <label class="btn btn--outline btn--sm" for="hero_image_file" style="margin:0;cursor:pointer;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:.35rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            Enviar arquivo
                        </label>
                        <input type="file" id="hero_image_file" name="hero_image" accept="image/png,image/jpeg,image/webp" style="display:none;" onchange="document.getElementById('hero_image').value=this.files[0]?this.files[0].name:'';">
                        <?php if (!empty($currentSite['hero_image'])): ?>
                            <img src="<?= e((string) $currentSite['hero_image']) ?>" alt="Imagem atual" style="max-height:32px;border-radius:4px;border:1px solid var(--color-border-light, #dfe7f4);">
                        <?php endif; ?>
                    </div>
                    <span class="form-hint" style="display:block;margin-top:.25rem;">Recomendado: 1600×900px para o hero. PNG, JPG ou WEBP até 5 MB.</span>
                </div>
            </div>

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
                </div>
            </div>

            <script>
            (function () {
                var primary = document.getElementById('appearance_primary');
                var primaryText = document.getElementById('appearance_primary_text');
                var accent = document.getElementById('appearance_accent');
                var accentText = document.getElementById('appearance_accent_text');
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

        <section id="publicar-site" class="site-step-panel" data-site-step-panel hidden>
            <div class="site-step-panel__head">
                <div>
                    <h2 class="hub-panel__title">Publicar</h2>
                    <p class="hub-panel__text">Publicação exige assinatura ativa. Configure o domínio próprio quando o plano estiver liberado.</p>
                </div>
                <span class="hub-badge <?= e($statusClass) ?>">
                    <?= $canPublish ? 'Publicação liberada' : 'Publicação bloqueada' ?>
                </span>
            </div>

            <article class="site-status-card site-publish-card" style="margin-bottom:var(--space-4);">
                <div class="hub-panel__row">
                    <div>
                        <h3 class="hub-panel__title">Site atual</h3>
                        <p class="hub-panel__text">
                            <?= $currentSite ? ($hasSavedSite ? 'Rascunho salvo e pronto para revisão visual.' : 'Preview montado com os dados cadastrais da igreja.') : 'Nenhum site foi gerado ainda.' ?>
                        </p>
                    </div>
                </div>

                <div class="site-preview-card" aria-label="Preview visual do site">
                    <?php if (!empty($currentSite['hero_image'])): ?>
                        <div class="site-preview-card__hero" style="background-image:url('<?= e((string) $currentSite['hero_image']) ?>'); background-size:cover; background-position:center;"></div>
                    <?php else: ?>
                        <div class="site-preview-card__hero"></div>
                    <?php endif; ?>
                    <div>
                        <h3 class="hub-mini-card__title"><?= e($siteTitle) ?></h3>
                        <p class="hub-mini-card__text"><?= e($templateValue) ?></p>
                    </div>
                    <div class="site-preview-card__lines"><span></span><span></span><span></span></div>
                </div>

                <div class="site-status-card__meta" style="grid-template-columns:1fr 1fr;">
                    <div>
                        <span>Última geração</span>
                        <strong><?= e((string) ($currentSite['generated_at_label'] ?? 'Ainda não gerado')) ?></strong>
                    </div>
                    <div>
                        <span>URL de revisão</span>
                        <strong><?= e($publishedUrl !== '' ? $publishedUrl : 'Após salvar o site') ?></strong>
                    </div>
                </div>

                <div class="hub-page__actions">
                    <button class="btn btn--outline" type="submit" formaction="<?= url('/hub/sites/gerar') ?>"><?= $currentSite ? 'Atualizar rascunho' : 'Gerar rascunho' ?></button>
                    <a href="<?= e($previewUrl) ?>" class="btn btn--ghost" target="_blank" rel="noopener noreferrer">Abrir preview</a>
                </div>
            </article>

            <article class="site-readiness-card" style="margin-bottom:var(--space-4);">
                <div class="site-readiness-card__head">
                    <div>
                        <h3 class="hub-panel__title">Prontidão do site</h3>
                        <p class="hub-panel__text"><?= $doneCount ?> de <?= $totalCount ?> itens concluídos</p>
                    </div>
                    <strong><?= $completion ?>%</strong>
                </div>
                <div class="site-progress"><span style="width: <?= $completion ?>%;"></span></div>
                <ul class="site-readiness-list">
                    <?php foreach ($checklist as $item): ?>
                        <li class="<?= !empty($item['done']) ? 'is-done' : 'is-pending' ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <?= !empty($item['done']) ? '<path d="M20 6 9 17l-5-5"></path>' : '<circle cx="12" cy="12" r="9"></circle><path d="M12 8v5"></path><path d="M12 16h.01"></path>' ?>
                            </svg>
                            <div>
                                <strong><?= e((string) ($item['title'] ?? 'Item')) ?></strong>
                                <span><?= e((string) ($item['text'] ?? '')) ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </article>

            <article class="site-status-card">
                <?php if (!$canPublish): ?>
                    <div class="site-publish-cta">
                        <div>
                            <h3 class="hub-panel__title">Plano "Site para Igrejas"</h3>
                            <p class="hub-panel__text">Para publicar e usar domínio próprio é necessário assinar o plano. Os dados e o preview ficam salvos enquanto você ativa.</p>
                        </div>
                        <a href="<?= url('/gestao/assinatura') ?>" class="btn btn--primary btn--lg">Tornar-se assinante</a>
                    </div>
                <?php else: ?>
                    <p class="hub-panel__text">Sua assinatura está ativa. Configure o domínio próprio (opcional) e publique quando estiver pronto.</p>
                <?php endif; ?>

                <div class="site-status-card__meta">
                    <div>
                        <span>Plano</span>
                        <strong><?= e((string) ($siteBuilderAccess['plan_name'] ?? 'Site para Igrejas')) ?></strong>
                    </div>
                    <div>
                        <span>Mensalidade</span>
                        <strong><?= e((string) ($siteBuilderAccess['monthly_fee_label'] ?? 'Consulte valores')) ?></strong>
                    </div>
                    <div>
                        <span>Situação</span>
                        <strong><?= e((string) ($siteBuilderAccess['status_label'] ?? 'Sem assinatura ativa')) ?></strong>
                    </div>
                </div>

                <div class="form-group" style="margin-top:1.25rem;">
                    <label class="form-label" for="domain">Domínio próprio</label>
                    <input id="domain" name="domain" class="form-input" value="<?= e((string) ($currentSite['domain'] ?? '')) ?>" placeholder="www.suaigreja.org.br" <?= $canPublish ? '' : 'disabled' ?>>
                    <span class="form-hint">
                        <?php if ($canPublish): ?>
                            Aponte o DNS A/CNAME desse domínio para os servidores Elo 42. Se ficar vazio, usamos <code><?= e(url('/site/' . rawurlencode((string) ($currentSite['slug'] ?? 'minha-igreja')))) ?></code>.
                        <?php else: ?>
                            Disponível para assinantes. Ative a assinatura para liberar o domínio próprio.
                        <?php endif; ?>
                    </span>
                </div>

                <div class="hub-page__actions" style="margin-top:1rem;">
                    <button type="button" class="btn btn--ghost" data-site-step-button="#aparencia-site">Voltar à aparência</button>
                    <?php if ($canPublish && $hasSavedSite): ?>
                        <button class="btn btn--primary" type="submit" formaction="<?= url('/hub/sites/publicar') ?>">Publicar site</button>
                    <?php elseif ($canPublish): ?>
                        <button class="btn btn--primary" type="submit">Salvar para publicar</button>
                    <?php else: ?>
                        <a href="<?= url('/gestao/assinatura') ?>" class="btn btn--primary">Ativar assinatura</a>
                    <?php endif; ?>
                </div>
            </article>
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

    var initialHash = window.location.hash && root.querySelector(window.location.hash) ? window.location.hash : '#dados-site';
    showStep(initialHash, false);
});
</script>

<?php $__view->endSection(); ?>
