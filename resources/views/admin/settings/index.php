<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>

<?php
    $settings = is_array($settings ?? null) ? $settings : [];
    $groups = [];
    foreach ($settings as $setting) {
        $group = (string) ($setting['setting_group'] ?? 'general');
        $groups[$group][] = $setting;
    }

    $groupMeta = [
        'ai' => [
            'title' => 'Integrações de IA',
            'text' => 'Configuração global da Central Pastoral IA. Esta área pertence ao gestor master DEV e alimenta todo o Hub.',
        ],
        'billing' => [
            'title' => 'Planos e limites',
            'text' => 'Valores de referência dos planos, combo e limite de usuários incluídos.',
        ],
        'payments' => [
            'title' => 'Pagamentos (Pagou)',
            'text' => 'Gateway, chaves e webhooks globais para cobrança recorrente dos assinantes do Hub.',
        ],
        'sites' => [
            'title' => 'Sites e domínios',
            'text' => 'Parâmetros usados para orientar publicação, CNAME, registro A e verificação de domínio.',
        ],
        'general' => [
            'title' => 'Configurações gerais',
            'text' => 'Parâmetros adicionais da plataforma.',
        ],
    ];

    $groupOrder = ['ai', 'payments', 'billing', 'sites', 'general'];
    foreach (array_keys($groups) as $group) {
        if (!in_array($group, $groupOrder, true)) {
            $groupOrder[] = $group;
        }
    }
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Configurações da plataforma</h1>
        <p class="mgmt-header__subtitle">Parâmetros globais da Elo 42, planos, domínios e Central Pastoral IA.</p>
    </div>
</div>

<?php if (!empty($degraded)): ?>
    <div class="alert alert--warning" role="alert" style="margin-bottom:var(--space-4);">
        O banco não respondeu para carregar as configurações salvas. A tela exibiu os padrões para evitar painel vazio.
    </div>
<?php endif; ?>

<form method="POST" action="<?= url('/admin/configuracoes') ?>" class="admin-settings-grid">
    <?= csrf_field() ?>

    <?php foreach ($groupOrder as $group): ?>
        <?php if (empty($groups[$group])) continue; ?>
        <?php $meta = $groupMeta[$group] ?? ['title' => strtoupper($group), 'text' => 'Parâmetros da plataforma.']; ?>
        <section class="mgmt-form-card admin-settings-card">
            <div class="admin-settings-card__head">
                <div>
                    <h2 class="mgmt-card-title"><?= e($meta['title']) ?></h2>
                    <p class="mgmt-subtitle"><?= e($meta['text']) ?></p>
                </div>
                <span class="hub-badge"><?= e($group) ?></span>
            </div>

            <div class="admin-settings-list">
                <?php foreach ($groups[$group] as $setting): ?>
                    <?php
                        $settingKey = (string) ($setting['setting_key'] ?? '');
                        $isSecret = in_array($settingKey, ['openai_api_key', 'pagou_api_key', 'pagou_webhook_secret'], true);
                        $inputType = $isSecret ? 'password' : 'text';
                        $inputValue = $isSecret ? '' : (string) ($setting['setting_value'] ?? '');
                        $placeholder = $isSecret && !empty($setting['setting_value'])
                            ? 'Valor configurado. Preencha apenas para substituir.'
                            : '';
                    ?>
                    <label class="admin-setting-row">
                        <span class="admin-setting-row__label"><?= e($settingKey) ?></span>
                        <input
                            type="<?= e($inputType) ?>"
                            name="settings[<?= e($settingKey) ?>]"
                            class="form-input"
                            value="<?= e($inputValue) ?>"
                            placeholder="<?= e($placeholder) ?>"
                            autocomplete="off"
                        >
                        <small><?= e((string) ($setting['description'] ?? '')) ?></small>
                    </label>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>

    <div class="mgmt-form-actions admin-settings-actions">
        <button type="submit" class="btn btn--primary">Salvar configurações</button>
    </div>
</form>

<style>
    .admin-settings-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: var(--space-5); max-width: 1440px; }
    .admin-settings-card { padding: var(--space-5); }
    .admin-settings-card__head { display: flex; justify-content: space-between; gap: var(--space-4); align-items: flex-start; padding-bottom: var(--space-4); border-bottom: 1px solid var(--color-border-light); margin-bottom: var(--space-4); }
    .admin-settings-list { display: grid; gap: var(--space-4); }
    .admin-setting-row { display: grid; grid-template-columns: minmax(180px, .55fr) minmax(280px, 1fr); gap: .5rem 1rem; align-items: center; }
    .admin-setting-row__label { font-size: var(--text-sm); font-weight: 800; color: var(--color-text); word-break: break-word; }
    .admin-setting-row small { grid-column: 2; color: var(--color-text-muted); font-size: var(--text-xs); line-height: 1.45; }
    .admin-settings-actions { grid-column: 1 / -1; justify-content: flex-start; }
    .admin-settings-card:first-of-type { grid-column: 1 / -1; }
    .admin-settings-card:nth-of-type(2) { grid-column: 1 / -1; }
    @media (max-width: 760px) {
        .admin-settings-grid { grid-template-columns: 1fr; }
        .admin-setting-row { grid-template-columns: 1fr; }
        .admin-setting-row small { grid-column: auto; }
        .admin-settings-card__head { flex-direction: column; }
    }
</style>

<?php $__view->endSection(); ?>
