<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">Configurações</h1>
        <p class="mgmt-subtitle">Gerencie as informações principais da sua organização</p>
    </div>
</div>

<div class="mgmt-dashboard-card" style="max-width: 100%;">
    <div style="display:flex; align-items:center; gap: 16px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--color-border-light);">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(16, 185, 129, 0.1); display: flex; align-items: center; justify-content: center; color: #10b981;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        </div>
        <div style="flex:1;">
            <h2 style="margin:0; font-size:18px; font-weight:700; color:var(--color-text-primary);">Backup da Organização</h2>
            <p style="margin:4px 0 0 0; font-size:13px; color:var(--text-muted);">Baixe um arquivo contendo todas as informações da sua organização, membros, histórico financeiro e eventos.</p>
        </div>
        <button type="button" class="btn btn--primary" style="background:#10b981; border-color:#10b981;">Gerar Backup</button>
    </div>

    <div style="display:flex; align-items:center; gap: 16px; margin-bottom: 24px;">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); display: flex; align-items: center; justify-content: center; color: #ef4444;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
        </div>
        <div style="flex:1;">
            <h2 style="margin:0; font-size:18px; font-weight:700; color:var(--color-text-primary);">Restaurar Backup</h2>
            <p style="margin:4px 0 0 0; font-size:13px; color:var(--text-muted);">Envie um arquivo de backup (.sql ou .json) previamente gerado para restaurar os dados da organização.</p>
        </div>
        <div>
            <label class="btn btn--danger-outline" style="cursor:pointer;">
                <input type="file" style="display:none;" accept=".sql,.json">
                Enviar arquivo
            </label>
        </div>
    </div>
    
    <div class="alert alert--danger" role="alert" style="margin:0;">
        <strong>Atenção:</strong> A restauração de um backup irá substituir todos os dados atuais da organização pelos dados contidos no arquivo. Esta ação é irreversível. Recomendamos sempre gerar um novo backup antes de realizar qualquer restauração.
    </div>
</div>
<?php $__view->endSection(); ?>