<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<?php
    $currentSite = is_array($currentSite ?? null) ? $currentSite : null;
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
    $templateValue = (string) ($currentSite['template'] ?? 'Institucional Clássico');
    $defaultDescription = 'Uma comunidade para acolher, servir e caminhar em fé.';
?>

<section class="hub-page site-builder-page">
    <header class="hub-page__header">
        <div class="hub-panel__row">
            <div>
                <h1 class="hub-page__title">Meus sites</h1>
                <p class="hub-page__subtitle">
                    Configure os dados públicos da igreja, revise o preview e publique quando a mensalidade e o domínio estiverem prontos.
                </p>
            </div>
            <div class="hub-page__actions">
                <?php if ($currentSite): ?>
                    <a href="#dados-site" class="btn btn--outline btn--lg">Editar dados</a>
                <?php endif; ?>
                <?php if ($canPublish && $currentSite): ?>
                    <form action="<?= url('/hub/sites/publicar') ?>" method="POST" data-loading>
                        <?= csrf_field() ?>
                        <button class="btn btn--primary btn--lg" type="submit">Publicar site</button>
                    </form>
                <?php else: ?>
                    <a href="<?= url('/contato') ?>" class="btn btn--primary btn--lg">Ativar publicação</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="site-builder-status-grid">
        <article class="site-status-card">
            <div class="hub-panel__row">
                <div>
                    <h2 class="hub-panel__title">Status de publicação</h2>
                    <p class="hub-panel__text">
                        O rascunho pode ser preparado agora. Publicar em domínio real exige mensalidade ativa e revisão dos dados.
                    </p>
                </div>
                <span class="hub-badge <?= e($statusClass) ?>">
                    <?= $canPublish ? 'Publicação liberada' : 'Publicação bloqueada' ?>
                </span>
            </div>

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
        </article>

        <article class="site-readiness-card">
            <div class="site-readiness-card__head">
                <div>
                    <h2 class="hub-panel__title">Prontidão do site</h2>
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
    </div>

    <div class="site-builder-grid">
        <form id="dados-site" class="hub-panel site-config-form" action="<?= url('/hub/sites/configurar') ?>" method="POST" data-loading>
            <?= csrf_field() ?>

            <div class="hub-panel__row">
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
                    <label class="form-label" for="template">Modelo</label>
                    <select id="template" name="template" class="form-select">
                        <?php foreach (($siteTemplates ?? []) as $template): ?>
                            <?php $name = (string) ($template['name'] ?? 'Modelo'); ?>
                            <option value="<?= e($name) ?>" <?= $templateValue === $name ? 'selected' : '' ?>><?= e($name) ?></option>
                        <?php endforeach; ?>
                    </select>
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

            <div class="form-grid form-grid--2">
                <div class="form-group">
                    <label class="form-label" for="logo_image">URL da logo</label>
                    <input id="logo_image" name="logo_image" class="form-input" value="<?= e((string) ($currentSite['logo_image'] ?? '')) ?>" placeholder="https://.../logo.png">
                </div>
                <div class="form-group">
                    <label class="form-label" for="hero_image">URL da imagem principal</label>
                    <input id="hero_image" name="hero_image" class="form-input" value="<?= e((string) ($currentSite['hero_image'] ?? '')) ?>" placeholder="https://.../foto-culto.jpg">
                </div>
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
                    <label class="form-label" for="domain">Domínio próprio</label>
                    <input id="domain" name="domain" class="form-input" value="<?= e((string) ($currentSite['domain'] ?? '')) ?>" placeholder="www.suaigreja.org.br">
                    <span class="form-hint">Se ficar vazio, o preview usa a URL local da Elo 42.</span>
                </div>
                <div class="form-group">
                    <label class="form-label" for="theme_color">Cor principal</label>
                    <input id="theme_color" name="theme_color" type="color" class="form-input form-input--color" value="<?= e((string) ($currentSite['theme_color'] ?? '#0A4DFF')) ?>">
                </div>
            </div>

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
        </form>

        <aside class="site-builder-side">
            <article class="site-status-card">
                <div class="hub-panel__row">
                    <div>
                        <h2 class="hub-panel__title">Site atual</h2>
                        <p class="hub-panel__text">
                            <?= $currentSite ? 'Rascunho salvo e pronto para revisão visual.' : 'Nenhum site foi gerado ainda.' ?>
                        </p>
                    </div>
                    <?php if ($currentSite): ?>
                        <span class="hub-badge <?= (($currentSite['status'] ?? '') === 'published') ? 'hub-badge--success' : 'hub-badge--warning' ?>">
                            <?= e((string) ($currentSite['status_label'] ?? 'Rascunho')) ?>
                        </span>
                    <?php endif; ?>
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

                <div class="site-status-card__meta" style="grid-template-columns:1fr;">
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
                    <form action="<?= url('/hub/sites/gerar') ?>" method="POST" data-loading>
                        <?= csrf_field() ?>
                        <input type="hidden" name="template" value="<?= e($templateValue) ?>">
                        <button class="btn btn--outline" type="submit"><?= $currentSite ? 'Atualizar rascunho' : 'Gerar rascunho' ?></button>
                    </form>
                    <?php if ($currentSite): ?>
                        <a href="<?= url('/hub/sites/preview') ?>" class="btn btn--ghost" target="_blank" rel="noopener noreferrer">Preview</a>
                    <?php endif; ?>
                </div>
            </article>
        </aside>
    </div>

    <div class="hub-panel">
        <div>
            <h2 class="hub-panel__title">Modelos disponíveis</h2>
            <p class="hub-panel__text">Escolha um ponto de partida. Depois, os dados acima preenchem o site automaticamente.</p>
        </div>

        <div class="site-template-grid">
            <?php foreach (($siteTemplates ?? []) as $template): ?>
                <article class="site-template-card <?= !empty($template['highlight']) ? 'is-highlight' : '' ?>">
                    <div>
                        <h3 class="hub-mini-card__title"><?= e((string) ($template['name'] ?? 'Modelo')) ?></h3>
                        <p class="hub-mini-card__text"><?= e((string) ($template['description'] ?? '')) ?></p>
                    </div>

                    <ul class="site-template-card__asset-list">
                        <?php foreach (($template['assets'] ?? []) as $asset): ?>
                            <li><?= e((string) $asset) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="hub-page__actions" style="margin-top:auto;">
                        <a href="<?= url('/hub/sites/preview?template=' . rawurlencode((string) ($template['name'] ?? ''))) ?>" class="btn btn--ghost" target="_blank" rel="noopener noreferrer">Ver preview</a>
                        <form action="<?= url('/hub/sites/gerar') ?>" method="POST" data-loading>
                            <?= csrf_field() ?>
                            <input type="hidden" name="template" value="<?= e((string) ($template['name'] ?? '')) ?>">
                            <button type="submit" class="btn btn--primary">Usar modelo</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
