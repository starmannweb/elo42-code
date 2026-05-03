<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<?php $units = is_array($units ?? null) ? $units : []; ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Campanhas de Arrecadação</h1>
            <p class="mgmt-subtitle">Crie e acompanhe campanhas de doações</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addCampaignModal').style.display='flex'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.5rem;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Nova Campanha
        </button>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($campaigns)): ?>
                <div class="mgmt-empty">
                    <div class="mgmt-empty__icon">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11v2a2 2 0 0 0 2 2h2l7 4V5l-7 4H5a2 2 0 0 0-2 2z"></path><path d="M17 9.5a3.5 3.5 0 0 1 0 5"></path></svg>
                    </div>
                    <h3 class="mgmt-empty__title">Nenhuma campanha cadastrada</h3>
                    <p class="mgmt-empty__text">Crie campanhas com meta, destino da oferta e prazo para acompanhar a arrecadação.</p>
                </div>
            <?php else: ?>
                <div style="display: grid; gap: 1.5rem;">
                    <?php foreach ($campaigns as $campaign): ?>
                        <?php
                            $goal = (float)($campaign['goal_amount'] ?? 0);
                            $raised = (float)($campaign['raised_amount'] ?? 0);
                            $percentage = $goal > 0 ? min(100, ($raised / $goal) * 100) : 0;
                        ?>
                        <div class="mgmt-card" style="border: 1px solid var(--hub-border); background: var(--hub-bg-secondary);">
                            <div class="mgmt-card__body">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                    <div style="flex: 1;">
                                        <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 600;"><?= e($campaign['title'] ?? 'Sem título') ?></h3>
                                        <p style="margin: 0; color: var(--hub-text-secondary); font-size: 0.875rem;"><?= e($campaign['description'] ?? '') ?></p>
                                        <p style="margin:.5rem 0 0; color: var(--hub-text-tertiary); font-size: 0.78rem;">Destino: <?= e((string) ($campaign['designation'] ?? 'Campanha da igreja')) ?> · Unidade: <?= e((string) ($campaign['unit_name'] ?? 'Sede / todas')) ?></p>
                                    </div>
                                    <div style="display: flex; gap: 0.5rem; margin-left: 1rem;">
                                        <?php
                                            $statusClass = [
                                                'active' => 'badge badge-success',
                                                'completed' => 'badge badge-primary',
                                                'draft' => 'badge badge-secondary',
                                            ];
                                            $statusLabel = [
                                                'active' => 'Ativa',
                                                'completed' => 'Concluída',
                                                'draft' => 'Rascunho',
                                            ];
                                            $currentStatus = $campaign['status'] ?? 'draft';
                                        ?>
                                        <span class="<?= $statusClass[$currentStatus] ?? 'badge' ?>"><?= $statusLabel[$currentStatus] ?? 'N/A' ?></span>
                                    </div>
                                </div>
                                
                                <div style="margin-bottom: 1rem;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.875rem;">
                                        <span style="color: var(--hub-text-secondary);">Progresso</span>
                                        <strong><?= number_format($percentage, 1) ?>%</strong>
                                    </div>
                                    <div style="width: 100%; height: 12px; background: var(--hub-border); border-radius: 6px; overflow: hidden;">
                                        <div style="height: 100%; width: <?= $percentage ?>%; background: linear-gradient(90deg, #10b981 0%, #059669 100%); transition: width 0.3s ease;"></div>
                                    </div>
                                </div>
                                
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 1rem;">
                                    <div>
                                        <div style="font-size: 0.75rem; color: var(--hub-text-tertiary); margin-bottom: 0.25rem;">Arrecadado</div>
                                        <div style="font-size: 1.25rem; font-weight: 700; color: #10b981;">
                                            R$ <?= number_format($raised, 2, ',', '.') ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.75rem; color: var(--hub-text-tertiary); margin-bottom: 0.25rem;">Meta</div>
                                        <div style="font-size: 1.25rem; font-weight: 700;">
                                            R$ <?= number_format($goal, 2, ',', '.') ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.75rem; color: var(--hub-text-tertiary); margin-bottom: 0.25rem;">Doadores</div>
                                        <div style="font-size: 1.25rem; font-weight: 700;">
                                            <?= (int)($campaign['donors_count'] ?? 0) ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--hub-border); font-size: 0.875rem; color: var(--hub-text-secondary);">
                                    <div>
                                        <?php if (!empty($campaign['end_date'])): ?>
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 0.25rem;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                            Termina em <?= date('d/m/Y', strtotime($campaign['end_date'])) ?>
                                        <?php endif; ?>
                                    </div>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="viewCampaign(<?= $campaign['id'] ?>)">Ver Detalhes</button>
                                        <button type="button" class="btn-icon" title="Editar" onclick="editCampaign(<?= $campaign['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button type="button" class="btn-icon btn-icon--danger" title="Remover" onclick="removeCampaign(<?= $campaign['id'] ?>)">
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

<div id="addCampaignModal" class="modal" style="display:none;">
    <div class="modal__content" style="max-width: 600px;">
        <div class="modal__header">
            <h2 class="modal__title">Nova Campanha</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('addCampaignModal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/campanhas/nova') ?>">
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="form-group">
                    <label for="title" class="form-label">Título <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Ex: Reforma do Templo" required>
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Descreva o objetivo da campanha"></textarea>
                </div>
                <div class="form-group">
                    <label for="designation" class="form-label">Destino do pagamento</label>
                    <input type="text" id="designation" name="designation" class="form-control" placeholder="Ex: Missões, reforma, tesouraria geral">
                </div>
                <div class="form-group">
                    <label for="church_unit_id" class="form-label">Unidade</label>
                    <select id="church_unit_id" name="church_unit_id" class="form-control">
                        <option value="">Sede / todas as unidades</option>
                        <?php foreach ($units as $unit): ?>
                            <option value="<?= (int) $unit['id'] ?>"><?= e((string) $unit['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="goal_amount" class="form-label">Meta (R$) <span style="color: var(--danger);">*</span></label>
                        <input type="number" step="0.01" id="goal_amount" name="goal_amount" class="form-control" placeholder="0.00" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date" class="form-label">Data Final</label>
                        <input type="date" id="end_date" name="end_date" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="draft">Rascunho</option>
                        <option value="active">Ativa</option>
                        <option value="completed">Concluída</option>
                    </select>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addCampaignModal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn-primary">Criar Campanha</button>
            </div>
        </form>
    </div>
</div>

<script>
function viewCampaign(id) {
    window.location.href = '<?= url('/gestao/campanhas/') ?>' + id;
}

function editCampaign(id) {
    alert('Funcionalidade de edição em desenvolvimento. ID: ' + id);
}

function removeCampaign(id) {
    if (confirm('Deseja realmente remover esta campanha?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/gestao/campanhas/') ?>' + id + '/remover';
        form.innerHTML = '<?= str_replace("'", "\\'", csrf_field()) ?>';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__view->endSection(); ?>
