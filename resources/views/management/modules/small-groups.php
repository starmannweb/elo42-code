<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-header__title">Grupos Pequenos</h1>
            <p class="mgmt-header__subtitle">Gerencie grupos, líderes e encontros semanais</p>
        </div>
        <div class="mgmt-header__actions">
            <button type="button" class="btn btn--primary" onclick="document.getElementById('addGroupModal').style.display='flex'">+ Novo Grupo</button>
        </div>
    </div>

    <div class="mgmt-kpi-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: var(--space-5);">
        <div class="mgmt-kpi-card" style="justify-content:space-between;">
            <div>
                <div class="mgmt-kpi-card__label">Total de Grupos</div>
                <div class="mgmt-kpi-card__value"><?= count($groups) ?></div>
            </div>
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><circle cx="5" cy="6" r="2"></circle><circle cx="19" cy="6" r="2"></circle><circle cx="5" cy="18" r="2"></circle><circle cx="19" cy="18" r="2"></circle><path d="M9.5 10.5 6.5 7.5"></path><path d="M14.5 10.5 17.5 7.5"></path><path d="M9.5 13.5 6.5 16.5"></path><path d="M14.5 13.5 17.5 16.5"></path></svg>
            </div>
        </div>
        <div class="mgmt-kpi-card" style="justify-content:space-between;">
            <div>
                <div class="mgmt-kpi-card__label">Participantes</div>
                <div class="mgmt-kpi-card__value"><?= array_sum(array_column($groups, 'members_count')) ?></div>
            </div>
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
            </div>
        </div>
        <div class="mgmt-kpi-card" style="justify-content:space-between;">
            <div>
                <div class="mgmt-kpi-card__label">Líderes Ativos</div>
                <div class="mgmt-kpi-card__value"><?= count(array_filter($groups, fn($g) => !empty($g['leader_name']))) ?></div>
            </div>
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--gold" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
            </div>
        </div>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($groups)): ?>
                <div class="mgmt-empty">
                    <div class="mgmt-empty__icon">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h3 class="mgmt-empty__title">Nenhum grupo pequeno cadastrado</h3>
                    <p class="mgmt-empty__text">Crie o primeiro grupo, vincule um líder e organize os encontros semanais da comunidade.</p>
                    <button type="button" class="btn btn--primary" onclick="document.getElementById('addGroupModal').style.display='flex'">+ Novo Grupo</button>
                </div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
                    <?php foreach ($groups as $group): ?>
                        <div class="mgmt-card" style="border: 1px solid var(--hub-border); background: var(--hub-bg-secondary);">
                            <div class="mgmt-card__body">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                    <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600;"><?= e($group['name'] ?? 'Sem nome') ?></h3>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button type="button" class="btn-icon" title="Editar" onclick="editGroup(<?= $group['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button type="button" class="btn-icon btn-icon--danger" title="Remover" onclick="removeGroup(<?= $group['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; color: var(--hub-text-secondary); font-size: 0.875rem;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                    <strong>Líder:</strong> <?= e($group['leader_name'] ?? 'Não definido') ?>
                                </div>
                                
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; color: var(--hub-text-secondary); font-size: 0.875rem;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    <strong>Encontros:</strong> <?= e($group['meeting_day'] ?? 'Não definido') ?>
                                </div>
                                
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; color: var(--hub-text-secondary); font-size: 0.875rem;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s-8-4.5-8-11a4 4 0 0 1 7-2.6A4 4 0 0 1 18 10c0 6.5-6 11-6 11z"></path></svg>
                                    <strong>Local:</strong> <?= e($group['location'] ?? 'Não definido') ?>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--hub-border);">
                                    <div style="font-size: 0.875rem; color: var(--hub-text-secondary);">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 0.25rem;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                        <strong><?= (int)($group['members_count'] ?? 0) ?></strong> participantes
                                    </div>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="viewGroupDetails(<?= $group['id'] ?>)">
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

<div id="addGroupModal" class="modal" style="display:none;">
    <div class="modal__content" style="max-width: 600px;">
        <div class="modal__header">
            <h2 class="modal__title">Novo Grupo Pequeno</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('addGroupModal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/celulas/novo') ?>">
            <input type="hidden" name="csrf_token" value="<?= e($csrf ?? '') ?>">
            <div class="modal__body">
                <div class="form-group">
                    <label for="name" class="form-label">Nome do Grupo <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Ex: Grupo Centro" required>
                </div>
                <div class="form-group">
                    <label for="leader_name" class="form-label">Líder <span style="color: var(--danger);">*</span></label>
                    <select id="leader_name" name="leader_name" class="form-control" required>
                        <option value="">Selecione o líder...</option>
                        <?php foreach ($members ?? [] as $member): ?>
                            <option value="<?= e($member['name']) ?>"><?= e($member['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="meeting_day" class="form-label">Dia dos Encontros</label>
                        <select id="meeting_day" name="meeting_day" class="form-control">
                            <option value="">Selecione</option>
                            <option value="Segunda-feira">Segunda-feira</option>
                            <option value="Terça-feira">Terça-feira</option>
                            <option value="Quarta-feira">Quarta-feira</option>
                            <option value="Quinta-feira">Quinta-feira</option>
                            <option value="Sexta-feira">Sexta-feira</option>
                            <option value="Sábado">Sábado</option>
                            <option value="Domingo">Domingo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="meeting_time" class="form-label">Horário</label>
                        <input type="time" id="meeting_time" name="meeting_time" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="location" class="form-label">Local dos Encontros</label>
                    <input type="text" id="location" name="location" class="form-control" placeholder="Ex: Rua das Flores, 123">
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addGroupModal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn-primary">Criar Grupo</button>
            </div>
        </form>
    </div>
</div>

<script>
function editGroup(id) {
    alert('Funcionalidade de edição em desenvolvimento. ID: ' + id);
}

function removeGroup(id) {
    if (confirm('Deseja realmente remover este grupo?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/gestao/celulas/') ?>' + id + '/remover';
        form.innerHTML = '<input type="hidden" name="csrf_token" value="<?= e($csrf ?? '') ?>">';
        document.body.appendChild(form);
        form.submit();
    }
}

function viewGroupDetails(id) {
    window.location.href = '<?= url('/gestao/celulas/') ?>' + id;
}
</script>
<?php $__view->endSection(); ?>
