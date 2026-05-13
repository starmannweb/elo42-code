<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">Configurações</h1>
        <p class="mgmt-subtitle">Gerencie as informações principais da sua organização</p>
    </div>
</div>

<div class="mgmt-dashboard-card" style="max-width: 100%; border: 1px solid rgba(239, 68, 68, 0.3);">
    <div style="display:flex; align-items:flex-start; gap: 16px; margin-bottom: 24px;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); display: flex; align-items: center; justify-content: center; color: #ef4444; flex-shrink: 0;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
        </div>
        <div style="flex:1;">
            <h2 style="margin:0; font-size:18px; font-weight:700; color:#ef4444;">Zerar o Sistema</h2>
            <p style="margin:8px 0 0 0; font-size:14px; color:var(--text-muted); line-height: 1.6;">
                Esta ação irá apagar <strong>definitivamente</strong> todos os dados da sua organização. 
                Isso inclui todos os membros cadastrados, registros financeiros, eventos, relatórios, campanhas e configurações.<br><br>
                <strong>Não há como reverter esta ação!</strong> Sugerimos que você faça um backup completo na aba "Backup" antes de prosseguir.
            </p>
        </div>
    </div>
    
    <div style="background: var(--color-bg-light); border: 1px solid var(--color-border-light); padding: 16px; border-radius: 8px; margin-top: 24px;">
        <form method="POST" action="<?= url('/gestao/configuracoes/zerar') ?>" onsubmit="return confirm('Tem certeza ABSOLUTA que deseja apagar todos os dados da igreja? ESTA AÇÃO NÃO PODE SER DESFEITA!');">
            <?= csrf_field() ?>
            <div style="margin-bottom: 16px;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:14px; font-weight:600; color:var(--color-text-primary);">
                    <input type="checkbox" required>
                    Eu entendo que ao clicar no botão abaixo, todos os dados da minha organização serão apagados para sempre.
                </label>
            </div>
            <button type="submit" class="btn btn--danger" style="width: 100%; justify-content: center; font-size: 16px; padding: 12px;">Sim, quero Zerar o Sistema</button>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>