<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Novos Convertidos</h1>
            <p class="mgmt-subtitle">Acompanhe decisões de fé e o processo de discipulado</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addConvertModal').style.display='flex'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.5rem;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Registrar Decisão
        </button>
    </div>

    <div class="mgmt-card" style="margin-bottom: 1.5rem;">
        <div class="mgmt-card__body">
            <form method="GET" action="<?= url('/gestao/novos-convertidos') ?>" style="display: grid; grid-template-columns: 1fr 200px 200px auto; gap: 1rem; align-items: end;">
                <div>
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Nome ou telefone" value="<?= e($search ?? '') ?>">
                </div>
                <div>
                    <label for="status" class="form-label">Status de Acompanhamento</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">Todos</option>
                        <option value="novo" <?= ($status ?? '') === 'novo' ? 'selected' : '' ?>>Novo</option>
                        <option value="acompanhamento" <?= ($status ?? '') === 'acompanhamento' ? 'selected' : '' ?>>Em Acompanhamento</option>
                        <option value="batizado" <?= ($status ?? '') === 'batizado' ? 'selected' : '' ?>>Batizado</option>
                        <option value="membro" <?= ($status ?? '') === 'membro' ? 'selected' : '' ?>>Membro Ativo</option>
                    </select>
                </div>
                <div>
                    <label for="month" class="form-label">Período</label>
                    <input type="month" id="month" name="month" class="form-control" value="<?= e($month ?? date('Y-m')) ?>">
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-secondary">Filtrar</button>
                    <a href="<?= url('/gestao/novos-convertidos') ?>" class="btn btn-secondary">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mgmt-stats" style="margin-bottom: 1.5rem;">
        <div class="stat-card">
            <div class="stat-card__icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            </div>
            <div>
                <div class="stat-card__label">Total de Decisões</div>
                <div class="stat-card__value"><?= count($converts) ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div>
                <div class="stat-card__label">Em Acompanhamento</div>
                <div class="stat-card__value"><?= count(array_filter($converts, fn($c) => ($c['status'] ?? '') === 'acompanhamento')) ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2s6 6.4 6 11a6 6 0 0 1-12 0c0-4.6 6-11 6-11z"></path>
                    <path d="m9 13 2 2 4-4"></path>
                </svg>
            </div>
            <div>
                <div class="stat-card__label">Batizados</div>
                <div class="stat-card__value"><?= count(array_filter($converts, fn($c) => ($c['status'] ?? '') === 'batizado')) ?></div>
            </div>
        </div>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($converts)): ?>
                <div style="text-align: center; padding: 3rem 1rem; color: var(--hub-text-tertiary);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 1rem; opacity: 0.4;">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    <p style="font-size: 0.875rem;">Nenhuma decisão de fé registrada</p>
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Data da Decisão</th>
                            <th>Responsável</th>
                            <th>Status</th>
                            <th style="width: 120px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($converts as $convert): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div class="avatar">
                                            <?= strtoupper(substr($convert['name'] ?? 'N', 0, 1)) ?>
                                        </div>
                                        <strong><?= e($convert['name'] ?? 'N/A') ?></strong>
                                    </div>
                                </td>
                                <td><?= e($convert['phone'] ?? '-') ?></td>
                                <td><?= !empty($convert['decision_date']) ? date('d/m/Y', strtotime($convert['decision_date'])) : '-' ?></td>
                                <td><?= e($convert['counselor_name'] ?? '-') ?></td>
                                <td>
                                    <?php 
                                        $statusClass = [
                                            'novo' => 'badge badge-info',
                                            'acompanhamento' => 'badge badge-warning',
                                            'batizado' => 'badge badge-primary',
                                            'membro' => 'badge badge-success',
                                        ];
                                        $statusLabel = [
                                            'novo' => 'Novo',
                                            'acompanhamento' => 'Em Acompanhamento',
                                            'batizado' => 'Batizado',
                                            'membro' => 'Membro Ativo',
                                        ];
                                        $currentStatus = $convert['status'] ?? 'novo';
                                    ?>
                                    <span class="<?= $statusClass[$currentStatus] ?? 'badge' ?>"><?= $statusLabel[$currentStatus] ?? 'N/A' ?></span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button type="button" class="btn-icon" title="Editar" onclick="editConvert(<?= $convert['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button type="button" class="btn-icon btn-icon--danger" title="Remover" onclick="removeConvert(<?= $convert['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="addConvertModal" class="modal" style="display:none;">
    <div class="modal__content" style="max-width: 600px;">
        <div class="modal__header">
            <h2 class="modal__title">Registrar Decisão de Fé</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('addConvertModal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/novos-convertidos/novo') ?>">
            <input type="hidden" name="csrf_token" value="<?= e($csrf ?? '') ?>">
            <div class="modal__body">
                <div class="form-group">
                    <label for="name" class="form-label">Nome <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="phone" class="form-label">Telefone</label>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="(11) 99999-9999">
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="decision_date" class="form-label">Data da Decisão <span style="color: var(--danger);">*</span></label>
                        <input type="date" id="decision_date" name="decision_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="convert_status" class="form-label">Status</label>
                        <select id="convert_status" name="status" class="form-control">
                            <option value="novo">Novo</option>
                            <option value="acompanhamento">Em Acompanhamento</option>
                            <option value="batizado">Batizado</option>
                            <option value="membro">Membro Ativo</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="counselor_name" class="form-label">Responsável pelo Acompanhamento</label>
                    <input type="text" id="counselor_name" name="counselor_name" class="form-control">
                </div>
                <div class="form-group">
                    <label for="notes" class="form-label">Observações</label>
                    <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addConvertModal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>
</div>

<script>
function editConvert(id) {
    alert('Funcionalidade de edição em desenvolvimento. ID: ' + id);
}

function removeConvert(id) {
    if (confirm('Deseja realmente remover este registro?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/gestao/novos-convertidos/') ?>' + id + '/remover';
        form.innerHTML = '<input type="hidden" name="csrf_token" value="<?= e($csrf ?? '') ?>">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__view->endSection(); ?>
