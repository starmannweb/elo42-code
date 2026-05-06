<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<?php
    $settings = is_array($settings ?? null) ? $settings : [];
    $appName = (string) ($settings['pwa_name'] ?? ($organization['name'] ?? 'App da Igreja'));
    $shortName = (string) ($settings['pwa_short_name'] ?? 'IGREJA');
    $themeColor = (string) ($settings['theme_color'] ?? '#1547f5');
    $backgroundColor = (string) ($settings['background_color'] ?? '#f4f7fd');
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">APP</h1>
        <p class="mgmt-subtitle">Configure como o aplicativo aparece quando instalado no celular.</p>
    </div>
    <div class="mgmt-actions">
        <a href="<?= url('/gestao/configuracoes/usuarios') ?>" class="btn btn--ghost">Cancelar</a>
        <button type="submit" form="form-pwa" class="btn btn--primary">Salvar APP</button>
    </div>
</div>

<div class="alert alert--info" style="margin-bottom: 1.5rem;">
    O APP já possui manifesto e service worker. Depois de alterar nome, cores ou ícones, alguns celulares podem exigir reinstalação para atualizar o atalho.
</div>

<form id="form-pwa" action="<?= url('/gestao/configuracoes') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="redirect_to" value="<?= url('/gestao/configuracoes/pwa') ?>">

    <div class="mgmt-grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <section class="mgmt-panel">
            <h3 class="mgmt-panel__title">Informações do app</h3>
            <p class="mgmt-subtitle" style="margin-bottom:1.25rem;">Esses dados aparecem na tela de instalação e na tela inicial do aparelho.</p>

            <div class="form-group">
                <label class="form-label" for="pwa_name">Nome completo</label>
                <input type="text" id="pwa_name" name="pwa_name" class="form-control" value="<?= e($appName) ?>">
                <div class="form-hint">Ex.: <?= e((string) ($organization['name'] ?? 'Igreja Central')) ?></div>
            </div>

            <div class="form-group">
                <label class="form-label" for="pwa_short_name">Nome curto</label>
                <input type="text" id="pwa_short_name" name="pwa_short_name" class="form-control" value="<?= e($shortName) ?>" maxlength="12">
                <div class="form-hint">Máximo recomendado: 12 caracteres.</div>
            </div>

            <div class="form-group">
                <label class="form-label" for="pwa_desc">Descrição</label>
                <textarea id="pwa_desc" name="pwa_desc" class="form-control" rows="3"><?= e((string) ($settings['pwa_desc'] ?? 'Aplicativo oficial da igreja.')) ?></textarea>
            </div>

            <div class="mgmt-form-row">
                <div class="form-group">
                    <label class="form-label">Cor do tema</label>
                    <div style="display:flex;gap:.65rem;align-items:center;">
                        <input type="color" name="theme_color_picker" value="<?= e($themeColor) ?>" style="width:48px;height:48px;padding:0;border:0;border-radius:12px;">
                        <input type="text" name="theme_color" class="form-control" value="<?= e($themeColor) ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Cor de fundo</label>
                    <div style="display:flex;gap:.65rem;align-items:center;">
                        <input type="color" name="background_color_picker" value="<?= e($backgroundColor) ?>" style="width:48px;height:48px;padding:0;border:0;border-radius:12px;">
                        <input type="text" name="background_color" class="form-control" value="<?= e($backgroundColor) ?>">
                    </div>
                </div>
            </div>
        </section>

        <section class="mgmt-panel">
            <h3 class="mgmt-panel__title">Ícones e instalação</h3>
            <p class="mgmt-subtitle" style="margin-bottom:1.25rem;">Use ícones quadrados em PNG para uma instalação bonita no Android, iOS e desktop.</p>

            <div class="mgmt-grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="mgmt-card" style="padding:1rem;">
                    <strong>Ícone 192x192</strong>
                    <p class="text-muted" style="margin:.35rem 0 1rem;">Atalho padrão em Android.</p>
                    <input type="text" name="pwa_icon_192" class="form-control" value="<?= e((string) ($settings['pwa_icon_192'] ?? '/assets/img/logo-color-new.png')) ?>" placeholder="/assets/img/icone-192.png">
                    <label class="pwa-upload-field">
                        <span>Enviar PNG 192x192</span>
                        <input type="file" name="pwa_icon_192_file" accept="image/png,image/webp,image/jpeg">
                    </label>
                </div>
                <div class="mgmt-card" style="padding:1rem;">
                    <strong>Ícone 512x512</strong>
                    <p class="text-muted" style="margin:.35rem 0 1rem;">Splash screen e alta resolução.</p>
                    <input type="text" name="pwa_icon_512" class="form-control" value="<?= e((string) ($settings['pwa_icon_512'] ?? '/assets/img/logo-color-new.png')) ?>" placeholder="/assets/img/icone-512.png">
                    <label class="pwa-upload-field">
                        <span>Enviar PNG 512x512</span>
                        <input type="file" name="pwa_icon_512_file" accept="image/png,image/webp,image/jpeg">
                    </label>
                </div>
            </div>

            <div style="margin-top:1.25rem;border-radius:18px;background:#111827;padding:2rem;display:grid;place-items:center;color:#fff;text-align:center;">
                <div style="width:72px;height:72px;border-radius:18px;background:<?= e($themeColor) ?>;display:grid;place-items:center;font-size:1.65rem;font-weight:900;margin-bottom:.85rem;">
                    <?= e(strtoupper(substr($shortName, 0, 1))) ?>
                </div>
                <strong><?= e($shortName) ?></strong>
                <span style="color:#cbd5e1;font-size:.86rem;margin-top:.25rem;"><?= e($appName) ?></span>
            </div>

            <div class="site-readiness-list" style="margin-top:1.25rem;">
                <li><strong>Manifesto</strong><span>Arquivo dinâmico configurado em <code>/app-manifest</code>.</span></li>
                <li><strong>Service worker</strong><span>Cache básico ativo para telas e estilos principais.</span></li>
                <li><strong>HTTPS em produção</strong><span>Obrigatório para instalação real fora do ambiente local.</span></li>
            </div>
        </section>
    </div>
</form>

<?php $__view->endSection(); ?>
