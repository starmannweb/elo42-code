<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<?php
    $settings = is_array($settings ?? null) ? $settings : [];
    $slug = trim((string) ($settings['public_registration_slug'] ?? 'cadastro-membro')) ?: 'cadastro-membro';
    $publicUrl = 'https://elo42.com.br/' . trim($slug, '/') . '/';
    $isActive = ($settings['public_registration_active'] ?? '0') === '1';

    // Parse current field settings
    $fieldSettings = $settings['public_registration_fields'] ?? '';
    if (is_string($fieldSettings) && !empty($fieldSettings)) {
        $fieldSettings = json_decode($fieldSettings, true) ?: [];
    } else {
        $fieldSettings = is_array($fieldSettings) ? $fieldSettings : [];
    }

    $fields = [
        'Data de nascimento' => 'birth_date',
        'Sexo' => 'gender',
        'Telefone / WhatsApp' => 'phone',
        'Cidade' => 'city',
        'UF' => 'state',
        'Email' => 'email',
        'Estado civil' => 'marital_status',
        'Endereço' => 'address',
    ];
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">Configurações</h1>
        <p class="mgmt-subtitle">Gerencie as informações principais da sua organização</p>
    </div>
    <div class="mgmt-actions">
        <a href="<?= url('/gestao') ?>" class="btn btn--ghost">Voltar</a>
        <button type="submit" form="form-settings" class="btn btn--primary">Salvar Alterações</button>
    </div>
</div>

<div class="mgmt-dashboard-card settings-card">
    <form id="form-settings" method="POST" action="<?= url('/gestao/configuracoes') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="redirect_to" value="<?= url('/gestao/configuracoes/cadastro-publico') ?>">
        <input type="hidden" name="public_registration_active" value="0">

        <div class="settings-card__head">
            <div>
                <h2>Cadastro Público de Membros</h2>
                <p>Ative uma rota pública onde visitantes preenchem o formulário sem login. Os cadastros chegam com status inativo para você aprovar.</p>
            </div>
            <label class="settings-switch" aria-label="Ativar cadastro público">
                <input type="checkbox" name="public_registration_active" id="registration-active-toggle" value="1" <?= $isActive ? 'checked' : '' ?>>
                <span></span>
            </label>
        </div>

        <div id="settings-content-wrapper" class="<?= $isActive ? '' : 'is-disabled' ?>">
            <div class="form-group">
                <label class="form-label">Slug da rota pública</label>
                <div class="settings-url-field">
                    <span>https://elo42.com.br/</span>
                    <input type="text" name="public_registration_slug" class="form-input" value="<?= e($slug) ?>">
                </div>
                <div class="settings-copy-row">
                    <span>Link completo: <strong id="public-registration-url"><?= e($publicUrl) ?></strong></span>
                    <button type="button" id="copy-public-registration-url">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                        Copiar
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mensagem de boas-vindas</label>
                <textarea name="public_registration_welcome" class="form-input" rows="3" placeholder="Ex: Preencha com atenção. Logo entraremos em contato."><?= e($settings['public_registration_welcome'] ?? '') ?></textarea>
            </div>

            <div class="settings-warning">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                Proteções ativas: token de sessão 30min, honeypot anti-bot, tempo mínimo de 8s, rate limit de 3 cadastros/hora por IP. Após salvar, o link pode demorar alguns segundos para ficar ativo.
            </div>

            <div class="settings-section-head">
                <div>
                    <h3>Campos do formulário</h3>
                    <p>Marque cada campo como Obrigatório ou Opcional. O campo Nome é sempre obrigatório.</p>
                </div>
            </div>

            <div class="settings-fields-list">
                <?php foreach ($fields as $label => $key): ?>
                <?php $currentStatus = $fieldSettings[$key] ?? 'required'; ?>
                <div class="settings-field-row" data-field-key="<?= e($key) ?>">
                    <div>
                        <span><?= e($label) ?></span>
                        <small class="field-status-label"><?= $currentStatus === 'required' ? 'Obrigatório' : ($currentStatus === 'optional' ? 'Opcional' : 'Oculto') ?></small>
                    </div>
                    <div class="settings-field-row__actions">
                        <input type="hidden" name="public_registration_fields[<?= e($key) ?>]" value="<?= e($currentStatus) ?>">
                        <button type="button" data-status="hidden" class="<?= $currentStatus === 'hidden' ? 'is-active' : '' ?>">Oculto</button>
                        <button type="button" data-status="optional" class="<?= $currentStatus === 'optional' ? 'is-active' : '' ?>">Opcional</button>
                        <button type="button" data-status="required" class="<?= $currentStatus === 'required' ? 'is-active' : '' ?>">* Obrigatório</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </form>
</div>

<style>
#settings-content-wrapper.is-disabled {
    opacity: 0.5;
    pointer-events: none;
    filter: grayscale(0.6);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Toggle Activation ---
    const toggle = document.getElementById('registration-active-toggle');
    const wrapper = document.getElementById('settings-content-wrapper');
    
    toggle?.addEventListener('change', () => {
        wrapper.classList.toggle('is-disabled', !toggle.checked);
    });

    // --- Copy URL ---
    document.getElementById('copy-public-registration-url')?.addEventListener('click', async () => {
        const text = document.getElementById('public-registration-url')?.textContent || '';
        try {
            await navigator.clipboard.writeText(text);
            Elo42.toast('Link copiado com sucesso!', 'success');
        } catch (error) {}
    });

    // --- Field Status Actions ---
    document.querySelectorAll('.settings-field-row__actions button').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('.settings-field-row');
            const status = this.dataset.status;
            const input = row.querySelector('input[type="hidden"]');
            const label = row.querySelector('.field-status-label');
            const buttons = row.querySelectorAll('button');

            // Update input and label
            input.value = status;
            label.textContent = this.textContent.replace('* ', '');

            // Update active state
            buttons.forEach(b => b.classList.remove('is-active'));
            this.classList.add('is-active');
        });
    });
});
</script>
<?php $__view->endSection(); ?>
