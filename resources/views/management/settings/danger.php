<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">Configurações</h1>
        <p class="mgmt-subtitle">Gerencie as informações principais da sua organização</p>
    </div>
</div>

<div class="mgmt-dashboard-card settings-card settings-danger-card">
    <div class="settings-action-block settings-action-block--danger">
        <div class="settings-action-block__icon settings-action-block__icon--danger">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
        </div>
        <div class="settings-action-block__content">
            <h2>Zerar dados da Gestão para Igrejas</h2>
            <p>Esta ação apaga definitivamente os dados da plataforma de igreja desta organização, como membros, financeiro, eventos, relatórios, campanhas e configurações da gestão.</p>
            <p><strong>Isso não apaga sua conta do Hub nem os dados globais da Elo 42.</strong> Gere um backup antes de continuar.</p>
        </div>
        <button type="button" class="btn btn--danger settings-action-block__button" data-open-danger-reset>Zerar dados</button>
    </div>
</div>

<div class="modal" id="danger-reset-modal" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="danger-reset-modal-title">
    <div class="modal__content">
        <div class="modal__header">
            <h2 class="modal__title" id="danger-reset-modal-title">Confirmar limpeza da gestão</h2>
            <button type="button" class="modal__close" data-close-danger-reset aria-label="Fechar">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/configuracoes/zerar') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="alert alert--danger" role="alert" style="margin:0 0 1rem;">
                    <strong>Atenção:</strong> esta operação é irreversível e afeta apenas os dados da Gestão para Igrejas desta organização.
                </div>
                <label class="settings-danger-confirm">
                    <input type="checkbox" required>
                    <span>Entendo que membros, financeiro, eventos, relatórios, campanhas e configurações da gestão serão apagados permanentemente.</span>
                </label>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" data-close-danger-reset>Cancelar</button>
                <button type="submit" class="btn btn--danger">Sim, zerar dados da gestão</button>
            </div>
        </form>
    </div>
</div>

<script>
(() => {
    const modal = document.getElementById('danger-reset-modal');
    document.querySelector('[data-open-danger-reset]')?.addEventListener('click', () => {
        if (modal) modal.style.display = 'flex';
    });
    document.querySelectorAll('[data-close-danger-reset]').forEach((button) => {
        button.addEventListener('click', () => {
            if (modal) modal.style.display = 'none';
        });
    });
})();
</script>
<?php $__view->endSection(); ?>
