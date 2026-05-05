<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<?php $settings = is_array($settings ?? null) ? $settings : []; ?>

<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Integrações</h1>
            <p class="mgmt-subtitle">Conecte pagamentos, comunicação, automações e serviços externos da igreja.</p>
        </div>
        <button type="submit" form="form-integrations" class="btn btn--primary">Salvar integrações</button>
    </div>

    <form id="form-integrations" method="POST" action="<?= url('/gestao/configuracoes') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="redirect_to" value="<?= url('/gestao/configuracoes/integracoes') ?>">

        <div class="mgmt-grid" style="grid-template-columns: 1fr 1fr; gap: 1.25rem;">
            <section class="mgmt-panel">
                <h3 style="display:flex;align-items:center;gap:.5rem;margin:0 0 1rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"></rect><path d="M2 10h20"></path></svg>
                    Pagamentos
                </h3>
                <p class="mgmt-subtitle" style="margin-bottom:1rem;">Prepare integrações para ofertas, mensalidades, inscrições e cursos.</p>

                <div class="form-group">
                    <label class="form-label" for="payment_gateway">Gateway principal</label>
                    <select id="payment_gateway" name="payment_gateway" class="form-control">
                        <option value="">Selecione</option>
                        <?php foreach ([
                            'mercado_pago' => 'Mercado Pago',
                            'pagarme' => 'Pagar.me',
                            'asaas' => 'Asaas',
                            'efi' => 'Efí / Gerencianet',
                            'manual' => 'Manual / PIX próprio',
                        ] as $value => $label): ?>
                            <option value="<?= e($value) ?>" <?= (($settings['payment_gateway'] ?? '') === $value) ? 'selected' : '' ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="payment_public_key">Chave pública</label>
                    <input id="payment_public_key" name="payment_public_key" class="form-control" type="text" value="<?= e((string) ($settings['payment_public_key'] ?? '')) ?>" placeholder="Cole a chave pública do provedor">
                </div>

                <div class="form-group">
                    <label class="form-label" for="payment_secret_key">Chave secreta</label>
                    <input id="payment_secret_key" name="payment_secret_key" class="form-control" type="password" value="<?= e((string) ($settings['payment_secret_key'] ?? '')) ?>" placeholder="Cole a chave secreta">
                </div>
            </section>

            <section class="mgmt-panel">
                <h3 style="display:flex;align-items:center;gap:.5rem;margin:0 0 1rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"></path></svg>
                    Comunicação
                </h3>
                <p class="mgmt-subtitle" style="margin-bottom:1rem;">Conecte canais para avisos, lembretes e acompanhamento pastoral.</p>

                <div class="form-group">
                    <label class="form-label" for="whatsapp_provider">WhatsApp</label>
                    <select id="whatsapp_provider" name="whatsapp_provider" class="form-control">
                        <option value="">Selecione</option>
                        <?php foreach ([
                            'meta' => 'Meta WhatsApp Cloud API',
                            'zapi' => 'Z-API',
                            'waba' => 'Outro provedor WABA',
                        ] as $value => $label): ?>
                            <option value="<?= e($value) ?>" <?= (($settings['whatsapp_provider'] ?? '') === $value) ? 'selected' : '' ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email_provider">E-mail transacional</label>
                    <select id="email_provider" name="email_provider" class="form-control">
                        <option value="">Selecione</option>
                        <?php foreach ([
                            'smtp' => 'SMTP',
                            'sendgrid' => 'SendGrid',
                            'mailgun' => 'Mailgun',
                            'ses' => 'Amazon SES',
                        ] as $value => $label): ?>
                            <option value="<?= e($value) ?>" <?= (($settings['email_provider'] ?? '') === $value) ? 'selected' : '' ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="webhook_url">Webhook externo</label>
                    <input id="webhook_url" name="webhook_url" class="form-control" type="url" value="<?= e((string) ($settings['webhook_url'] ?? '')) ?>" placeholder="https://exemplo.com/webhook">
                </div>
            </section>

            <section class="mgmt-panel" style="grid-column:1 / -1;">
                <h3 style="display:flex;align-items:center;gap:.5rem;margin:0 0 1rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33"></path><path d="M4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6"></path></svg>
                    IA centralizada no Admin
                </h3>
                <p class="mgmt-subtitle" style="margin-bottom:0;">Chave OpenAI, modelo e limites do Expositor IA são parâmetros globais do gestor master DEV em <strong>Admin &gt; Configurações</strong>. Esta área fica apenas com integrações da igreja.</p>
            </section>
        </div>
    </form>
</div>

<?php $__view->endSection(); ?>
