<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<?php $settings = is_array($settings ?? null) ? $settings : []; ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Redes sociais</h1>
            <p class="mgmt-subtitle">Links exibidos no site, no APP e na área de membros quando a igreja quiser divulgar canais oficiais.</p>
        </div>
        <button type="submit" form="form-social" class="btn btn--primary">Salvar redes</button>
    </div>

    <form id="form-social" method="POST" action="<?= url('/gestao/configuracoes') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="redirect_to" value="<?= url('/gestao/configuracoes/redes-sociais') ?>">

        <section class="mgmt-panel">
            <div class="mgmt-grid" style="grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem;">
                <?php
                    $fields = [
                        'social_instagram' => ['Instagram', 'https://instagram.com/suaigreja'],
                        'social_youtube' => ['YouTube', 'https://youtube.com/@suaigreja'],
                        'social_facebook' => ['Facebook', 'https://facebook.com/suaigreja'],
                        'social_whatsapp' => ['WhatsApp', 'https://wa.me/5511999999999'],
                        'social_tiktok' => ['TikTok', 'https://tiktok.com/@suaigreja'],
                        'social_website' => ['Site oficial', 'https://suaigreja.com.br'],
                    ];
                ?>
                <?php foreach ($fields as $name => [$label, $placeholder]): ?>
                    <div class="form-group">
                        <label class="form-label" for="<?= e($name) ?>"><?= e($label) ?></label>
                        <input id="<?= e($name) ?>" name="<?= e($name) ?>" type="url" class="form-control" value="<?= e((string) ($settings[$name] ?? '')) ?>" placeholder="<?= e($placeholder) ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </form>
</div>
<?php $__view->endSection(); ?>
