<?php $__view->extend('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Histórico</h1>
            <p class="mgmt-subtitle">Timeline completo de atividades e eventos</p>
        </div>
    </div>

    <div class="mgmt-card" style="margin-bottom: 1.5rem;">
        <div class="mgmt-card__body">
            <form method="GET" action="<?= url('/gestao/historico') ?>" style="display: grid; grid-template-columns: 1fr 200px 200px 200px auto; gap: 1rem; align-items: end;">
                <div>
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Buscar por evento ou usuário" value="<?= e($search ?? '') ?>">
                </div>
                <div>
                    <label for="type" class="form-label">Tipo</label>
                    <select id="type" name="type" class="form-control">
                        <option value="">Todos</option>
                        <option value="membro" <?= ($type ?? '') === 'membro' ? 'selected' : '' ?>>Membros</option>
                        <option value="financeiro" <?= ($type ?? '') === 'financeiro' ? 'selected' : '' ?>>Financeiro</option>
                        <option value="evento" <?= ($type ?? '') === 'evento' ? 'selected' : '' ?>>Eventos</option>
                        <option value="grupo" <?= ($type ?? '') === 'grupo' ? 'selected' : '' ?>>Grupos</option>
                        <option value="sistema" <?= ($type ?? '') === 'sistema' ? 'selected' : '' ?>>Sistema</option>
                    </select>
                </div>
                <div>
                    <label for="start_date" class="form-label">Data Inicial</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="<?= e($startDate ?? date('Y-m-01')) ?>">
                </div>
                <div>
                    <label for="end_date" class="form-label">Data Final</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="<?= e($endDate ?? date('Y-m-d')) ?>">
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-secondary">Filtrar</button>
                    <a href="<?= url('/gestao/historico') ?>" class="btn btn-secondary">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($activities)): ?>
                <div style="text-align: center; padding: 3rem 1rem; color: var(--hub-text-tertiary);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 1rem; opacity: 0.4;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <p style="font-size: 0.875rem;">Nenhuma atividade encontrada</p>
                </div>
            <?php else: ?>
                <div class="timeline">
                    <?php foreach ($activities as $activity): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker" style="background: <?= ($activity['type'] ?? '') === 'membro' ? '#3b82f6' : (($activity['type'] ?? '') === 'financeiro' ? '#10b981' : '#6b7280') ?>"></div>
                            <div class="timeline-content">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                    <div>
                                        <h4 style="margin: 0; font-size: 0.9375rem; font-weight: 600;"><?= e($activity['title'] ?? 'Sem título') ?></h4>
                                        <p style="margin: 0.25rem 0 0; color: var(--hub-text-secondary); font-size: 0.875rem;"><?= e($activity['description'] ?? '') ?></p>
                                    </div>
                                    <span style="font-size: 0.75rem; color: var(--hub-text-tertiary); white-space: nowrap; margin-left: 1rem;">
                                        <?= !empty($activity['created_at']) ? date('d/m/Y H:i', strtotime($activity['created_at'])) : '' ?>
                                    </span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.75rem; color: var(--hub-text-tertiary);">
                                    <?php if (!empty($activity['user_name'])): ?>
                                        <div style="display: flex; align-items: center; gap: 0.25rem;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                            <?= e($activity['user_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php
                                        $typeLabels = [
                                            'membro' => 'Membros',
                                            'financeiro' => 'Financeiro',
                                            'evento' => 'Eventos',
                                            'grupo' => 'Grupos',
                                            'sistema' => 'Sistema',
                                        ];
                                        $typeColors = [
                                            'membro' => '#3b82f6',
                                            'financeiro' => '#10b981',
                                            'evento' => '#f59e0b',
                                            'grupo' => '#8b5cf6',
                                            'sistema' => '#6b7280',
                                        ];
                                        $activityType = $activity['type'] ?? 'sistema';
                                    ?>
                                    <span class="badge" style="background: <?= $typeColors[$activityType] ?? '#6b7280' ?>;">
                                        <?= $typeLabels[$activityType] ?? 'Sistema' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.375rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--hub-border);
}

.timeline-item {
    position: relative;
    padding-bottom: 2rem;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -1.625rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    border: 2px solid var(--hub-bg-primary);
}

.timeline-content {
    background: var(--hub-bg-secondary);
    border: 1px solid var(--hub-border);
    border-radius: 0.5rem;
    padding: 1rem;
}
</style>
<?php $__view->endSection(); ?>
