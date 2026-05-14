<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">Configuracoes</h1>
        <p class="mgmt-subtitle">Gerencie as informacoes principais da sua organizacao</p>
    </div>
</div>

<div class="mgmt-dashboard-card settings-card">
    <div class="settings-action-block">
        <div class="settings-action-block__icon settings-action-block__icon--success">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        </div>
        <div class="settings-action-block__content">
            <h2>Backup da Organizacao</h2>
            <p>Baixe um arquivo contendo todas as informacoes da sua organizacao, membros, historico financeiro e eventos.</p>
        </div>
        <button type="button" class="btn btn--primary settings-action-block__button">Gerar Backup</button>
    </div>

    <div class="settings-action-block settings-action-block--last">
        <div class="settings-action-block__icon settings-action-block__icon--danger">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
        </div>
        <div class="settings-action-block__content">
            <h2>Restaurar Backup</h2>
            <p>Envie um arquivo de backup (.sql ou .json) previamente gerado para restaurar os dados da organizacao.</p>
        </div>
        <label class="btn btn--danger-outline settings-action-block__button">
            <input type="file" hidden accept=".sql,.json">
            Enviar arquivo
        </label>
    </div>

    <div class="alert alert--danger" role="alert" style="margin:0;">
        <strong>Atencao:</strong> A restauracao de um backup ira substituir todos os dados atuais da organizacao pelos dados contidos no arquivo. Esta acao e irreversivel. Recomendamos sempre gerar um novo backup antes de realizar qualquer restauracao.
    </div>
</div>
<?php $__view->endSection(); ?>
