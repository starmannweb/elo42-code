<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Visitantes</h1>
            <p class="mgmt-subtitle">Gerencie os visitantes da sua igreja</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addVisitorModal').style.display='flex'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.5rem;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Adicionar Visitante
        </button>
    </div>

    <div class="mgmt-card" style="margin-bottom: 1.5rem;">
        <div class="mgmt-card__body">
            <form method="GET" action="<?= url('/gestao/visitantes') ?>" style="display: grid; grid-template-columns: 1fr 1fr 200px auto; gap: 1rem; align-items: end;">
                <div>
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Nome, telefone ou e-mail" value="<?= e($search ?? '') ?>">
                </div>
                <div>
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">Todos</option>
                        <option value="novo" <?= ($status ?? '') === 'novo' ? 'selected' : '' ?>>Novo</option>
                        <option value="contatado" <?= ($status ?? '') === 'contatado' ? 'selected' : '' ?>>Contatado</option>
                        <option value="membro" <?= ($status ?? '') === 'membro' ? 'selected' : '' ?>>Tornou-se Membro</option>
                    </select>
                </div>
                <div>
                    <label for="date" class="form-label">Período</label>
                    <input type="month" id="date" name="month" class="form-control" value="<?= e($month ?? date('Y-m')) ?>">
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-secondary">Filtrar</button>
                    <a href="<?= url('/gestao/visitantes') ?>" class="btn btn-secondary">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($visitors)): ?>
                <div style="text-align: center; padding: 3rem 1rem; color: var(--hub-text-tertiary);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 1rem; opacity: 0.4;">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <p style="font-size: 0.875rem;">Nenhum visitante encontrado</p>
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                            <th>Data da Visita</th>
                            <th>Status</th>
                            <th style="width: 120px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($visitors as $visitor): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div class="avatar">
                                            <?= strtoupper(substr($visitor['name'] ?? 'V', 0, 1)) ?>
                                        </div>
                                        <strong><?= e($visitor['name'] ?? 'N/A') ?></strong>
                                    </div>
                                </td>
                                <td><?= e($visitor['phone'] ?? '-') ?></td>
                                <td><?= e($visitor['email'] ?? '-') ?></td>
                                <td><?= !empty($visitor['visit_date']) ? date('d/m/Y', strtotime($visitor['visit_date'])) : '-' ?></td>
                                <td>
                                    <?php 
                                        $statusClass = [
                                            'novo' => 'badge badge-info',
                                            'contatado' => 'badge badge-warning',
                                            'membro' => 'badge badge-success',
                                        ];
                                        $statusLabel = [
                                            'novo' => 'Novo',
                                            'contatado' => 'Contatado',
                                            'membro' => 'Tornou-se Membro',
                                        ];
                                        $currentStatus = $visitor['status'] ?? 'novo';
                                    ?>
                                    <span class="<?= $statusClass[$currentStatus] ?? 'badge' ?>"><?= $statusLabel[$currentStatus] ?? 'N/A' ?></span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button type="button" class="btn-icon" title="Editar" onclick="editVisitor(<?= $visitor['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button type="button" class="btn-icon btn-icon--danger" title="Remover" onclick="removeVisitor(<?= $visitor['id'] ?>)">
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

<div id="addVisitorModal" class="modal" style="display:none;">
    <div class="modal__content" style="max-width: 600px;">
        <div class="modal__header">
            <h2 class="modal__title">Adicionar Visitante</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('addVisitorModal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/visitantes/novo') ?>">
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
                        <label for="visit_date" class="form-label">Data da Visita <span style="color: var(--danger);">*</span></label>
                        <input type="date" id="visit_date" name="visit_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="visitor_status" class="form-label">Status</label>
                        <select id="visitor_status" name="status" class="form-control">
                            <option value="novo">Novo</option>
                            <option value="contatado">Contatado</option>
                            <option value="membro">Tornou-se Membro</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes" class="form-label">Observações</label>
                    <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addVisitorModal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn-primary">Adicionar</button>
            </div>
        </form>
    </div>
</div>

<script>
function editVisitor(id) {
    alert('Funcionalidade de edição em desenvolvimento. ID: ' + id);
}

function removeVisitor(id) {
    if (confirm('Deseja realmente remover este visitante?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/gestao/visitantes/') ?>' + id + '/remover';
        form.innerHTML = '<input type="hidden" name="csrf_token" value="<?= e($csrf ?? '') ?>">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__view->endSection(); ?>
