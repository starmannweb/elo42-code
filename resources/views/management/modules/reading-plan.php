<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Planos de Leitura Bíblica</h1>
            <p class="mgmt-subtitle">Engaje a igreja com planos de leitura estruturados</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addPlanModal').style.display='flex'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.5rem;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Novo Plano
        </button>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($plans)): ?>
                <div style="text-align: center; padding: 3rem 1rem; color: var(--hub-text-tertiary);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 1rem; opacity: 0.4;">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                    <p style="font-size: 0.875rem;">Nenhum plano de leitura cadastrado</p>
                </div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
                    <?php foreach ($plans as $plan): ?>
                        <div class="mgmt-card" style="border: 1px solid var(--hub-border); background: var(--hub-bg-secondary);">
                            <div class="mgmt-card__body">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                    <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600; flex: 1;"><?= e($plan['title'] ?? 'Sem título') ?></h3>
                                    <div style="display: flex; gap: 0.5rem; margin-left: 1rem;">
                                        <button type="button" class="btn-icon" title="Editar" onclick="editPlan(<?= $plan['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button type="button" class="btn-icon btn-icon--danger" title="Remover" onclick="removePlan(<?= $plan['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <p style="margin: 0 0 1rem 0; color: var(--hub-text-secondary); font-size: 0.875rem; line-height: 1.5;">
                                    <?= e($plan['description'] ?? '') ?>
                                </p>
                                
                                <div style="display: grid; gap: 0.75rem; margin-bottom: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--hub-text-secondary);">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        <strong><?= (int)($plan['duration_days'] ?? 0) ?> dias</strong> de leitura
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--hub-text-secondary);">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                        <strong><?= (int)($plan['participants_count'] ?? 0) ?></strong> participantes
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--hub-text-secondary);">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                        <?= e($plan['book_range'] ?? 'Bíblia completa') ?>
                                    </div>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--hub-border);">
                                    <?php
                                        $statusClass = [
                                            'active' => 'badge badge-success',
                                            'draft' => 'badge badge-secondary',
                                            'archived' => 'badge badge-secondary',
                                        ];
                                        $statusLabel = [
                                            'active' => 'Ativo',
                                            'draft' => 'Rascunho',
                                            'archived' => 'Arquivado',
                                        ];
                                        $currentStatus = $plan['status'] ?? 'draft';
                                    ?>
                                    <span class="<?= $statusClass[$currentStatus] ?? 'badge' ?>"><?= $statusLabel[$currentStatus] ?? 'N/A' ?></span>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="viewPlan(<?= $plan['id'] ?>)">
                                        Ver Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="addPlanModal" class="modal" style="display:none;">
    <div class="modal__content" style="max-width: 600px;">
        <div class="modal__header">
            <h2 class="modal__title">Novo Plano de Leitura</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('addPlanModal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/plano-leitura/novo') ?>">
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="form-group">
                    <label for="title" class="form-label">Título <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Ex: Leia a Bíblia em 1 Ano" required>
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Descreva o plano de leitura"></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="duration_days" class="form-label">Duração (dias) <span style="color: var(--danger);">*</span></label>
                        <input type="number" id="duration_days" name="duration_days" class="form-control" placeholder="Ex: 365" required>
                    </div>
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="draft">Rascunho</option>
                            <option value="active">Ativo</option>
                            <option value="archived">Arquivado</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="book_range" class="form-label">Livros/Passagens</label>
                    <input type="text" id="book_range" name="book_range" class="form-control" placeholder="Ex: Novo Testamento">
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addPlanModal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn-primary">Criar Plano</button>
            </div>
        </form>
    </div>
</div>

<script>
function viewPlan(id) {
    window.location.href = '<?= url('/gestao/plano-leitura/') ?>' + id;
}

function editPlan(id) {
    alert('Funcionalidade de edição em desenvolvimento. ID: ' + id);
}

function removePlan(id) {
    if (confirm('Deseja realmente remover este plano de leitura?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/gestao/plano-leitura/') ?>' + id + '/remover';
        form.innerHTML = '<?= str_replace("'", "\\'", csrf_field()) ?>';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__view->endSection(); ?>
