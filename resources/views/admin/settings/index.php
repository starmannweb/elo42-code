<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>

<div class="mgmt-header"><div><h1 class="mgmt-header__title">Configurações da plataforma</h1><p class="mgmt-header__subtitle">Parâmetros globais da Elo 42</p></div></div>
<div class="mgmt-form-card">
    <form method="POST" action="<?= url('/admin/configuracoes') ?>"><?= csrf_field() ?>
        <?php
            $groups = [];
            foreach ($settings as $s) { $groups[$s['setting_group']][] = $s; }
        ?>
        <?php foreach ($groups as $group => $items): ?>
            <h3 style="text-transform:uppercase;font-size:var(--text-xs);font-weight:700;color:var(--color-text-muted);letter-spacing:0.1em;margin:var(--space-5) 0 var(--space-3);border-bottom:1px solid var(--color-border-light);padding-bottom:var(--space-2);"><?= e($group) ?></h3>
            <?php foreach ($items as $s): ?>
            <?php
                $settingKey = (string) ($s['setting_key'] ?? '');
                $isSecret = in_array($settingKey, ['openai_api_key'], true);
                $inputType = $isSecret ? 'password' : 'text';
                $inputValue = $isSecret ? '' : (string) ($s['setting_value'] ?? '');
                $placeholder = $isSecret && !empty($s['setting_value']) ? 'Chave configurada. Preencha apenas para substituir.' : '';
            ?>
            <div class="settings-row">
                <span class="settings-row__key"><?= e($settingKey) ?></span>
                <input type="<?= e($inputType) ?>" name="settings[<?= e($settingKey) ?>]" class="form-input" value="<?= e($inputValue) ?>" placeholder="<?= e($placeholder) ?>" autocomplete="off">
                <span class="settings-row__desc"><?= e($s['description'] ?? '') ?></span>
            </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <div class="mgmt-form-actions" style="margin-top:var(--space-5);"><button type="submit" class="btn btn--primary">Salvar configurações</button></div>
    </form>
</div>
<?php $__view->endSection(); ?>
