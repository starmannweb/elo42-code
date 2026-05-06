<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Jornadas</h1>
            <p class="mgmt-subtitle">Trilhas de crescimento espiritual para os membros</p>
        </div>
        <button type="button" class="btn btn--primary" onclick="document.getElementById('addJourneyModal').style.display='flex'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.5rem;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Nova Jornada
        </button>
    </div>

    <div class="mgmt-card mgmt-filter-card">
        <div class="mgmt-card__body">
            <form method="GET" action="<?= url('/gestao/jornadas') ?>" class="mgmt-filter-grid mgmt-filter-grid--compact">
                <div class="mgmt-filter-field">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Título ou descrição" value="<?= e($search ?? '') ?>">
                </div>
                <div class="mgmt-filter-field">
                    <label for="month" class="form-label">Período</label>
                    <input type="month" id="month" name="month" class="form-control" value="<?= e($month ?? date('Y-m')) ?>">
                </div>
                <div class="mgmt-filter-actions">
                    <button type="submit" class="btn btn--outline">Filtrar</button>
                    <a href="<?= url('/gestao/jornadas') ?>" class="btn btn--outline">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($journeys)): ?>
                <div class="mgmt-empty">
                    <div class="mgmt-empty__icon">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 19c3.5-8 8-11 14-14"></path><circle cx="5" cy="19" r="2"></circle><path d="M17.5 3.5 19 6.5l3 1.5-3 1.5-1.5 3-1.5-3-3-1.5 3-1.5 1.5-3z"></path><path d="M10 14h.01"></path><path d="M13 11h.01"></path></svg>
                    </div>
                    <h3 class="mgmt-empty__title">Nenhuma jornada cadastrada</h3>
                    <p class="mgmt-empty__text">Crie trilhas como integração de novos membros, discipulado, batismo ou liderança.</p>
                    <button type="button" class="btn btn--primary" onclick="document.getElementById('addJourneyModal').style.display='flex'">Criar jornada</button>
                </div>
            <?php else: ?>
                <div style="display: grid; gap: 1.5rem;">
                    <?php foreach ($journeys as $journey): ?>
                        <div class="mgmt-card" style="border: 1px solid var(--hub-border); background: var(--hub-bg-secondary);">
                            <div class="mgmt-card__body">
                                <div style="display: flex; justify-content: space-between; align-items: start;">
                                    <div style="flex: 1;">
                                        <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 700;"><?= e($journey['title'] ?? 'Sem título') ?></h3>
                                        <p style="margin: 0 0 1rem 0; color: var(--hub-text-secondary); font-size: 0.875rem;"><?= e($journey['description'] ?? '') ?></p>
                                        
                                        <div style="display: flex; gap: 2rem; margin-bottom: 1rem; font-size: 0.875rem; color: var(--hub-text-secondary); flex-wrap: wrap;">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                <?= !empty($journey['duration_days']) ? (int)$journey['duration_days'] . ' dias' : 'Duração flexível' ?>
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M4 4.5A2.5 2.5 0 0 1 6.5 7H20"></path><path d="M6.5 7v10"></path></svg>
                                                Criada em <?= !empty($journey['created_at']) ? date('d/m/Y', strtotime((string) $journey['created_at'])) : 'rascunho' ?>
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
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="viewJourney(<?= $journey['id'] ?>)">Ver detalhes</button>
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
    <div class="modal__content modal__content--wide">
        <div class="modal__header">
            <h2 class="modal__title">Nova Jornada</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('addJourneyModal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/jornadas/nova') ?>">
            <?= csrf_field() ?>
            <div class="modal__body modal__body--compact">
                <div class="modal-grid">
                <div class="form-group modal-grid__full">
                    <label for="title" class="form-label">Título <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Ex: Jornada do Novo Membro" required>
                </div>
                <div class="form-group modal-grid__full">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Descreva o objetivo desta jornada"></textarea>
                </div>
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
    alert('Detalhes da jornada em desenvolvimento. ID: ' + id);
}

function editJourney(id) {
    alert('Funcionalidade de edição em desenvolvimento. ID: ' + id);
}

function removeJourney(id) {
    if (confirm('Deseja realmente remover esta jornada?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/gestao/jornadas/') ?>' + id + '/remover';
        form.innerHTML = '<?= str_replace("'", "\\'", csrf_field()) ?>';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__view->endSection(); ?>
