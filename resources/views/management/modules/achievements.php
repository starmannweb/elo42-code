<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Conquistas</h1>
            <p class="mgmt-subtitle">Sistema de gamificação e reconhecimento</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addAchievementModal').style.display='flex'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.5rem;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Nova Conquista
        </button>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($achievements)): ?>
                <div style="text-align: center; padding: 3rem 1rem; color: var(--hub-text-tertiary);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 1rem; opacity: 0.4;">
                        <circle cx="12" cy="8" r="7"></circle>
                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                    </svg>
                    <p style="font-size: 0.875rem;">Nenhuma conquista cadastrada</p>
                </div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                    <?php foreach ($achievements as $achievement): ?>
                        <div class="mgmt-card" style="border: 1px solid var(--hub-border); background: var(--hub-bg-secondary); text-align: center;">
                            <div class="mgmt-card__body">
                                <div style="display: flex; justify-content: flex-end; margin-bottom: 0.5rem;">
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button type="button" class="btn-icon" title="Editar" onclick="editAchievement(<?= $achievement['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button type="button" class="btn-icon btn-icon--danger" title="Remover" onclick="removeAchievement(<?= $achievement['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div style="margin: 0 auto 1rem; width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                    <?php if (!empty($achievement['icon'])): ?>
                                        <span style="font-size: 2.5rem;"><?= e($achievement['icon']) ?></span>
                                    <?php else: ?>
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                                            <circle cx="12" cy="8" r="7"></circle>
                                            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                        </svg>
                                    <?php endif; ?>
                                </div>
                                
                                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.125rem; font-weight: 600;"><?= e($achievement['title'] ?? 'Sem título') ?></h3>
                                <p style="margin: 0 0 1rem 0; color: var(--hub-text-secondary); font-size: 0.875rem; line-height: 1.5;">
                                    <?= e($achievement['description'] ?? '') ?>
                                </p>
                                
                                <div style="margin-bottom: 1rem;">
                                    <?php
                                        $typeLabels = [
                                            'attendance' => 'Frequência',
                                            'reading' => 'Leitura',
                                            'service' => 'Serviço',
                                            'giving' => 'Doação',
                                            'growth' => 'Crescimento',
                                        ];
                                        $typeColors = [
                                            'attendance' => '#3b82f6',
                                            'reading' => '#8b5cf6',
                                            'service' => '#10b981',
                                            'giving' => '#f59e0b',
                                            'growth' => '#ef4444',
                                        ];
                                        $achType = $achievement['type'] ?? $achievement['criteria_type'] ?? 'growth';
                                    ?>
                                    <span class="badge" style="background: <?= $typeColors[$achType] ?? '#6b7280' ?>;">
                                        <?= $typeLabels[$achType] ?? 'Conquista' ?>
                                    </span>
                                </div>
                                
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1rem;">
                                    <div>
                                        <div style="font-size: 0.75rem; color: var(--hub-text-tertiary); margin-bottom: 0.25rem;">Pontos</div>
                                        <div style="font-size: 1.25rem; font-weight: 700; color: #f59e0b;">
                                            <?= (int)($achievement['points'] ?? 0) ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.75rem; color: var(--hub-text-tertiary); margin-bottom: 0.25rem;">Conquistadas</div>
                                        <div style="font-size: 1.25rem; font-weight: 700;">
                                            <?= (int)($achievement['earned_count'] ?? 0) ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if (!empty($achievement['requirement'])): ?>
                                    <div style="font-size: 0.75rem; color: var(--hub-text-secondary); padding-top: 1rem; border-top: 1px solid var(--hub-border);">
                                        <strong>Requisito:</strong> <?= e($achievement['requirement']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="addAchievementModal" class="modal" style="display:none;">
    <div class="modal__content" style="max-width: 600px;">
        <div class="modal__header">
            <h2 class="modal__title">Nova Conquista</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('addAchievementModal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/conquistas/nova') ?>">
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="form-group">
                    <label for="title" class="form-label">Título <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Ex: Guerreiro da Oração" required>
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea id="description" name="description" class="form-control" rows="2" placeholder="Descrição da conquista"></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="icon" class="form-label">Ícone (Emoji)</label>
                        <input type="text" id="icon" name="icon" class="form-control" placeholder="🏆" maxlength="2">
                    </div>
                    <div class="form-group">
                        <label for="points" class="form-label">Pontos</label>
                        <input type="number" id="points" name="points" class="form-control" value="10">
                    </div>
                    <div class="form-group">
                        <label for="type" class="form-label">Tipo</label>
                        <select id="type" name="type" class="form-control">
                            <option value="growth">Crescimento</option>
                            <option value="attendance">Frequência</option>
                            <option value="reading">Leitura</option>
                            <option value="service">Serviço</option>
                            <option value="giving">Doação</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="requirement" class="form-label">Requisito</label>
                    <input type="text" id="requirement" name="requirement" class="form-control" placeholder="Ex: Participar de 10 cultos consecutivos">
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addAchievementModal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn-primary">Criar Conquista</button>
            </div>
        </form>
    </div>
</div>

<script>
function editAchievement(id) {
    alert('Funcionalidade de edição em desenvolvimento. ID: ' + id);
}

function removeAchievement(id) {
    if (confirm('Deseja realmente remover esta conquista?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/gestao/conquistas/') ?>' + id + '/remover';
        form.innerHTML = '<input type="hidden" name="_csrf_token" value="<?= e(csrf_token()) ?>">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__view->endSection(); ?>
