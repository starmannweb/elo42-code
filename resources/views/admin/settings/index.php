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
        'email' => [
            'title' => 'E-mails (Resend)',
            'text' => 'Chaves e remetente oficial para disparos transacionais da plataforma.',
        ],
        'whatsapp' => [
            'title' => 'WhatsApp (Evolution)',
            'text' => 'Conexão global da Evolution API para disparos, instâncias e webhooks de mensagens.',
        ],
        'webhooks' => [
            'title' => 'Webhooks',
            'text' => 'Endpoints e segredos para integrações externas, automações e eventos da plataforma.',
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

    $groupOrder = ['ai', 'email', 'whatsapp', 'webhooks', 'payments', 'billing', 'sites', 'general'];
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

<form method="POST" action="<?= url('/admin/configuracoes') ?>" class="admin-settings-shell" data-admin-settings-tabs>
    <?= csrf_field() ?>

    <nav class="admin-settings-tabs" aria-label="Seções de configuração">
        <?php $firstVisibleGroup = null; ?>
        <?php foreach ($groupOrder as $group): ?>
            <?php if (empty($groups[$group])) continue; ?>
            <?php
                $firstVisibleGroup = $firstVisibleGroup ?? $group;
                $meta = $groupMeta[$group] ?? ['title' => strtoupper($group), 'text' => 'Parâmetros da plataforma.'];
                $count = count($groups[$group]);
            ?>
            <button
                type="button"
                class="admin-settings-tab <?= $firstVisibleGroup === $group ? 'is-active' : '' ?>"
                data-settings-tab="<?= e($group) ?>"
                aria-controls="settings-panel-<?= e($group) ?>"
                aria-selected="<?= $firstVisibleGroup === $group ? 'true' : 'false' ?>"
            >
                <span><?= e($meta['title']) ?></span>
                <small><?= $count ?> <?= $count === 1 ? 'item' : 'itens' ?></small>
            </button>
        <?php endforeach; ?>
    </nav>

    <div class="admin-settings-panels">
    <?php foreach ($groupOrder as $group): ?>
        <?php if (empty($groups[$group])) continue; ?>
        <?php $meta = $groupMeta[$group] ?? ['title' => strtoupper($group), 'text' => 'Parâmetros da plataforma.']; ?>
        <section
            id="settings-panel-<?= e($group) ?>"
            class="mgmt-form-card admin-settings-card <?= ($firstVisibleGroup ?? '') === $group ? 'is-active' : '' ?>"
            data-settings-panel="<?= e($group) ?>"
            <?= ($firstVisibleGroup ?? '') === $group ? '' : 'hidden' ?>
        >
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
                        $isSecret = in_array($settingKey, ['openai_api_key', 'pagou_api_key', 'pagou_webhook_secret', 'resend_api_key', 'evolution_api_key', 'evolution_webhook_secret', 'platform_webhook_secret'], true);
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
    </div>

    <div class="mgmt-form-actions admin-settings-actions">
        <button type="submit" class="btn btn--primary">Salvar configurações</button>
    </div>
</form>

<style>
    .admin-settings-shell { display: grid; gap: var(--space-5); max-width: 980px; }
    .admin-settings-tabs { display: flex; gap: .65rem; flex-wrap: wrap; align-items: stretch; padding: .55rem; border: 1px solid var(--color-border); border-radius: 18px; background: #fff; box-shadow: 0 12px 30px rgba(15,35,75,.05); }
    .admin-settings-tab { appearance: none; border: 1px solid transparent; background: transparent; color: var(--color-text-muted); border-radius: 12px; padding: .85rem 1rem; min-width: 148px; cursor: pointer; display: grid; gap: .2rem; text-align: left; transition: background .18s ease, border-color .18s ease, color .18s ease, box-shadow .18s ease; }
    .admin-settings-tab span { font-weight: 800; color: inherit; }
    .admin-settings-tab small { color: inherit; opacity: .72; font-size: var(--text-xs); }
    .admin-settings-tab:hover { background: rgba(10,77,255,.06); color: var(--color-text); border-color: rgba(10,77,255,.18); }
    .admin-settings-tab.is-active { background: #1455FF; border-color: #1455FF; color: #fff; box-shadow: 0 12px 24px rgba(20,85,255,.18); }
    .admin-settings-panels { display: grid; }
    .admin-settings-card { padding: var(--space-5); }
    .admin-settings-card__head { display: flex; justify-content: space-between; gap: var(--space-4); align-items: flex-start; padding-bottom: var(--space-4); border-bottom: 1px solid var(--color-border-light); margin-bottom: var(--space-4); }
    .admin-settings-list { display: grid; gap: var(--space-4); }
    .admin-setting-row { display: grid; grid-template-columns: minmax(180px, .55fr) minmax(280px, 1fr); gap: .5rem 1rem; align-items: center; }
    .admin-setting-row__label { font-size: var(--text-sm); font-weight: 800; color: var(--color-text); word-break: break-word; }
    .admin-setting-row small { grid-column: 2; color: var(--color-text-muted); font-size: var(--text-xs); line-height: 1.45; }
    .admin-settings-actions { justify-content: flex-start; padding-top: var(--space-2); border-top: 1px solid var(--color-border-light); }
    @media (max-width: 760px) {
        .admin-settings-shell { max-width: none; }
        .admin-settings-tabs { display: grid; grid-template-columns: 1fr; }
        .admin-settings-tab { min-width: 0; }
        .admin-setting-row { grid-template-columns: 1fr; }
        .admin-setting-row small { grid-column: auto; }
        .admin-settings-card__head { flex-direction: column; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var root = document.querySelector('[data-admin-settings-tabs]');
    if (!root) {
        return;
    }

    var tabs = Array.prototype.slice.call(root.querySelectorAll('[data-settings-tab]'));
    var panels = Array.prototype.slice.call(root.querySelectorAll('[data-settings-panel]'));

    function activate(group) {
        tabs.forEach(function (tab) {
            var active = tab.getAttribute('data-settings-tab') === group;
            tab.classList.toggle('is-active', active);
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
        });

        panels.forEach(function (panel) {
            var active = panel.getAttribute('data-settings-panel') === group;
            panel.hidden = !active;
            panel.classList.toggle('is-active', active);
        });
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            activate(tab.getAttribute('data-settings-tab') || '');
        });
    });
});
</script>

<?php $__view->endSection(); ?>
