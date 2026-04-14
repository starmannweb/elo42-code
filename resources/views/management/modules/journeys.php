<?php $__view->extend('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Jornadas</h1>
            <p class="mgmt-subtitle">Trilhas de crescimento espiritual para os membros</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addJourneyModal').style.display='flex'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.5rem;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Nova Jornada
        </button>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($journeys)): ?>
                <div style="text-align: center; padding: 3rem 1rem; color: var(--hub-text-tertiary);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 1rem; opacity: 0.4;">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <p style="font-size: 0.875rem;">Nenhuma jornada cadastrada</p>
                </div>
            <?php else: ?>
                <div style="display: grid; gap: 1.5rem;">
                    <?php foreach ($journeys as $journey): ?>
                        <div class="mgmt-card" style="border: 1px solid var(--hub-border); background: var(--hub-bg-secondary);">
                            <div class="mgmt-card__body">
                                <div style="display: flex; justify-content: space-between; align-items: start;">
                                    <div style="flex: 1;">
                                        <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 600;"><?= e($journey['title'] ?? 'Sem título') ?></h3>
                                        <p style="margin: 0 0 1rem 0; color: var(--hub-text-secondary); font-size: 0.875rem;"><?= e($journey['description'] ?? '') ?></p>
                                        
                                        <div style="display: flex; gap: 2rem; margin-bottom: 1rem; font-size: 0.875rem; color: var(--hub-text-secondary);">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                                <strong><?= (int)($journey['participants_count'] ?? 0) ?></strong> participantes
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                                                <strong><?= (int)($journey['steps_count'] ?? 0) ?></strong> etapas
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                <?= !empty($journey['duration_days']) ? (int)$journey['duration_days'] . ' dias' : 'Duração flexível' ?>
                                            </div>
                                        </div>
                                        
                                        <div style="display: flex; gap: 0.5rem;">
                                            <?php
                                                $statusClass = [
                                                    'active' => 'badge badge-success',
                                                    'draft' => 'badge badge-secondary',
                                                    'archived' => 'badge badge-secondary',
                                                ];
                                                $statusLabel = [
                                                    'active' => 'Ativa',
                                                    'draft' => 'Rascunho',
                                                    'archived' => 'Arquivada',
                                                ];
                                                $currentStatus = $journey['status'] ?? 'draft';
                                            ?>
                                            <span class="<?= $statusClass[$currentStatus] ?? 'badge' ?>"><?= $statusLabel[$currentStatus] ?? 'N/A' ?></span>
                                        </div>
                                    </div>
                                    
                                    <div style="display: flex; gap: 0.5rem; margin-left: 1rem;">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="viewJourney(<?= $journey['id'] ?>)">Ver Detalhes</button>
                                        <button type="button" class="btn-icon" title="Editar" onclick="editJourney(<?= $journey['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button type="button" class="btn-icon btn-icon--danger" title="Remover" onclick="removeJourney(<?= $journey['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="addJourneyModal" class="modal" style="display:none;">
    <div class="modal__content" style="max-width: 600px;">
        <div class="modal__header">
            <h2 class="modal__title">Nova Jornada</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('addJourneyModal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/jornadas/nova') ?>">
            <input type="hidden" name="csrf_token" value="<?= e($csrf ?? '') ?>">
            <div class="modal__body">
                <div class="form-group">
                    <label for="title" class="form-label">Título <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Ex: Jornada do Novo Membro" required>
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Descreva o objetivo desta jornada"></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="duration_days" class="form-label">Duração (dias)</label>
                        <input type="number" id="duration_days" name="duration_days" class="form-control" placeholder="Ex: 30">
                    </div>
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="draft">Rascunho</option>
                            <option value="active">Ativa</option>
                            <option value="archived">Arquivada</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addJourneyModal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn-primary">Criar Jornada</button>
            </div>
        </form>
    </div>
</div>

<script>
function viewJourney(id) {
    window.location.href = '<?= url('/gestao/jornadas/') ?>' + id;
}

function editJourney(id) {
    alert('Funcionalidade de edição em desenvolvimento. ID: ' + id);
}

function removeJourney(id) {
    if (confirm('Deseja realmente remover esta jornada?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/gestao/jornadas/') ?>' + id + '/remover';
        form.innerHTML = '<input type="hidden" name="csrf_token" value="<?= e($csrf ?? '') ?>">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__view->endSection(); ?>
