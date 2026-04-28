<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Banners</h1>
            <p class="mgmt-subtitle">Gerencie banners e anúncios da igreja</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addBannerModal').style.display='flex'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.5rem;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Novo Banner
        </button>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($banners)): ?>
                <div style="text-align: center; padding: 3rem 1rem; color: var(--hub-text-tertiary);">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 1rem; opacity: 0.4;">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                    <p style="font-size: 0.875rem;">Nenhum banner cadastrado</p>
                </div>
            <?php else: ?>
                <div style="display: grid; gap: 1.5rem;">
                    <?php foreach ($banners as $banner): ?>
                        <div class="mgmt-card" style="border: 1px solid var(--hub-border); background: var(--hub-bg-secondary);">
                            <div class="mgmt-card__body">
                                <div style="display: grid; grid-template-columns: 200px 1fr auto; gap: 1.5rem; align-items: center;">
                                    <div style="width: 200px; height: 112px; background: var(--hub-bg-tertiary); border-radius: 0.5rem; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                        <?php if (!empty($banner['image_url'])): ?>
                                            <img src="<?= e($banner['image_url']) ?>" alt="<?= e($banner['title'] ?? 'Banner') ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--hub-text-tertiary)" stroke-width="2">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                <polyline points="21 15 16 10 5 21"></polyline>
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <h3 style="margin: 0 0 0.5rem 0; font-size: 1.125rem; font-weight: 600;"><?= e($banner['title'] ?? 'Sem título') ?></h3>
                                        <p style="margin: 0 0 0.75rem 0; color: var(--hub-text-secondary); font-size: 0.875rem;"><?= e($banner['description'] ?? '') ?></p>
                                        
                                        <div style="display: flex; gap: 1rem; font-size: 0.875rem; color: var(--hub-text-secondary);">
                                            <?php if (!empty($banner['link_url'])): ?>
                                                <div style="display: flex; align-items: center; gap: 0.25rem;">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                                                    <a href="<?= e($banner['link_url']) ?>" target="_blank" style="color: var(--primary);"><?= e($banner['link_url']) ?></a>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($banner['display_order'])): ?>
                                                <div>Ordem: <strong><?= (int)$banner['display_order'] ?></strong></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div style="display: flex; flex-direction: column; gap: 0.75rem; align-items: flex-end;">
                                        <?php
                                            $statusClass = [
                                                'active' => 'badge badge-success',
                                                'inactive' => 'badge badge-secondary',
                                                'scheduled' => 'badge badge-info',
                                            ];
                                            $statusLabel = [
                                                'active' => 'Ativo',
                                                'inactive' => 'Inativo',
                                                'scheduled' => 'Agendado',
                                            ];
                                            $currentStatus = $banner['status'] ?? 'inactive';
                                        ?>
                                        <span class="<?= $statusClass[$currentStatus] ?? 'badge' ?>"><?= $statusLabel[$currentStatus] ?? 'N/A' ?></span>
                                        
                                        <div style="display: flex; gap: 0.5rem;">
                                            <button type="button" class="btn-icon" title="Editar" onclick="editBanner(<?= $banner['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </button>
                                            <button type="button" class="btn-icon btn-icon--danger" title="Remover" onclick="removeBanner(<?= $banner['id'] ?>)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </div>
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

<div id="addBannerModal" class="modal" style="display:none;">
    <div class="modal__content" style="max-width: 600px;">
        <div class="modal__header">
            <h2 class="modal__title">Novo Banner</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('addBannerModal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/banners/novo') ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= e($csrf ?? '') ?>">
            <div class="modal__body">
                <div class="form-group">
                    <label for="title" class="form-label">Título <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Ex: Culto de Celebração" required>
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea id="description" name="description" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label for="image_url" class="form-label">URL da Imagem</label>
                    <input type="url" id="image_url" name="image_url" class="form-control" placeholder="https://exemplo.com/imagem.jpg">
                </div>
                <div class="form-group">
                    <label for="link_url" class="form-label">Link (URL)</label>
                    <input type="url" id="link_url" name="link_url" class="form-control" placeholder="https://exemplo.com">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="display_order" class="form-label">Ordem de Exibição</label>
                        <input type="number" id="display_order" name="display_order" class="form-control" value="0">
                    </div>
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active">Ativo</option>
                            <option value="inactive">Inativo</option>
                            <option value="scheduled">Agendado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addBannerModal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn-primary">Criar Banner</button>
            </div>
        </form>
    </div>
</div>

<script>
function editBanner(id) {
    alert('Funcionalidade de edição em desenvolvimento. ID: ' + id);
}

function removeBanner(id) {
    if (confirm('Deseja realmente remover este banner?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/gestao/banners/') ?>' + id + '/remover';
        form.innerHTML = '<input type="hidden" name="csrf_token" value="<?= e($csrf ?? '') ?>">';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__view->endSection(); ?>
