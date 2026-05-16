<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<?php $settings = is_array($settings ?? null) ? $settings : []; ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">Configurações do PIX</h1>
        <p class="mgmt-subtitle">Gerencie as informações de pagamento via PIX</p>
    </div>
    <div class="mgmt-actions">
        <a href="<?= url('/gestao') ?>" class="btn btn--ghost">Cancelar</a>
        <button type="submit" form="form-pix" class="btn btn--primary">Salvar Configurações</button>
    </div>
</div>

<form id="form-pix" action="<?= url('/gestao/configuracoes') ?>" method="POST">
    <?= csrf_field() ?>
    <input type="hidden" name="redirect_to" value="<?= url('/gestao/configuracoes/pix') ?>">

<div class="mgmt-grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <div class="mgmt-panel">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--color-text-primary); margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Chave PIX
        </h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Configure a chave PIX para receber doações</p>
        
            <div class="form-group">
                <label for="pix_type">Tipo da Chave</label>
                <select id="pix_type" name="pix_type" class="form-control">
                    <option value="cnpj">CNPJ</option>
                    <option value="cpf">CPF</option>
                    <option value="email">E-mail</option>
                    <option value="phone">Telefone</option>
                    <option value="random">Chave Aleatória</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="pix_key">Chave PIX</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="text" id="pix_key" name="pix_key" class="form-control" value="<?= e((string) ($settings['pix_key'] ?? '')) ?>" placeholder="00.000.000/0001-00" style="flex: 1;">
                    <button type="button" class="btn btn--outline" style="padding: 0.5rem;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></button>
                </div>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Esta chave será exibida na página de ofertas para os membros copiarem</div>
            </div>
            
            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="pix_name">Nome do Beneficiário</label>
                <input type="text" id="pix_name" name="pix_name" class="form-control" value="<?= e((string) ($settings['pix_name'] ?? $settings['pix_beneficiary'] ?? ($organization['name'] ?? ''))) ?>">
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Nome que aparecerá no comprovante de pagamento</div>
            </div>
    </div>

    <div class="mgmt-panel">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--color-text-primary); margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            QR Code PIX
        </h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Faça upload do QR Code para pagamento</p>
        
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; margin-top: 2rem;">
            <div style="width: 160px; height: 160px; background: var(--color-bg-light); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px dashed var(--color-border-light);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </div>
            <button type="button" class="btn btn--outline" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                Upload QR Code
            </button>
            <div style="font-size: 0.75rem; color: var(--text-muted); text-align: center;">Gere o QR Code no app do seu banco e faça upload da imagem</div>
        </div>
    </div>
</div>

<?php if (!empty($campaigns)): ?>
<div class="mgmt-panel" style="margin-top: 1.5rem;">
    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--color-text-primary); margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"></path><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        Chaves PIX Específicas por Campanha / Obra
    </h3>
    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Configure chaves PIX exclusivas para direcionar ofertas de campanhas específicas. Se deixado em branco, a campanha usará a chave PIX principal configurada acima.</p>
    
    <div class="mgmt-grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <?php foreach ($campaigns as $campaign): ?>
            <?php 
                $settingKey = 'campaign_pix_key_' . $campaign['id'];
                $currentKey = $settings[$settingKey] ?? '';
            ?>
            <div class="form-group" style="padding: 1rem; border: 1px solid var(--color-border-light); border-radius: 8px; background: var(--color-bg-light);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label for="<?= e($settingKey) ?>" style="margin: 0;"><strong><?= e($campaign['title']) ?></strong></label>
                    <span class="badge badge-warning" style="font-size: 0.7rem;"><?= e($campaign['designation'] ?: 'Campanha') ?></span>
                </div>
                <input type="text" id="<?= e($settingKey) ?>" name="<?= e($settingKey) ?>" class="form-control" value="<?= e($currentKey) ?>" placeholder="Chave PIX exclusiva (opcional)">
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="mgmt-panel" style="margin-top: 1.5rem;">
    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--color-text-primary); margin: 0 0 1rem;">Instruções para Doação</h3>
    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Texto de orientação exibido na página de ofertas</p>
    
    <div class="form-group">
        <label for="pix_instruction">Mensagem de Instrução</label>
        <textarea id="pix_instruction" name="pix_instruction" class="form-control" rows="3"><?= e((string) ($settings['pix_instruction'] ?? 'Escolha uma campanha, copie a chave PIX e envie sua contribuição pelo aplicativo do seu banco.')) ?></textarea>
    </div>
</div>
</form>
<?php $__view->endSection(); ?>
