<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Cursos</h1>
            <p class="mgmt-subtitle">Gerencie cursos e treinamentos</p>
        </div>
        <button type="button" class="btn btn--primary" onclick="document.getElementById('addCourseModal').style.display='flex'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:0.5rem;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Novo Curso
        </button>
    </div>

    <div class="mgmt-card">
        <div class="mgmt-card__body">
            <?php if (empty($courses)): ?>
                <div class="mgmt-empty">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin: 0 auto 1rem; opacity: 0.4;">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                    <h3 class="mgmt-empty__title">Nenhum curso cadastrado</h3>
                    <p class="mgmt-empty__text">Cadastre cursos com PDF, videoaulas e período de disponibilidade.</p>
                    <button type="button" class="btn btn--primary" onclick="document.getElementById('addCourseModal').style.display='flex'">Criar curso</button>
                </div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.5rem;">
                    <?php foreach ($courses as $course): ?>
                        <div class="mgmt-card" style="border: 1px solid var(--hub-border); background: var(--hub-bg-secondary);">
                            <div class="mgmt-card__body">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                    <div style="flex: 1;">
                                        <h3 style="margin: 0 0 0.5rem 0; font-size: 1.125rem; font-weight: 600;"><?= e($course['title'] ?? 'Sem título') ?></h3>
                                        <?php if (!empty($course['instructor'])): ?>
                                            <p style="margin: 0 0 0.5rem 0; color: var(--hub-text-secondary); font-size: 0.875rem;">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 0.25rem;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                                                <?= e($course['instructor']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div style="display: flex; gap: 0.5rem; margin-left: 1rem;">
                                        <button type="button" class="btn-icon" title="Editar" onclick="editCourse(<?= $course['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button type="button" class="btn-icon btn-icon--danger" title="Remover" onclick="removeCourse(<?= $course['id'] ?>)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <p style="margin: 0 0 1rem 0; color: var(--hub-text-secondary); font-size: 0.875rem; line-height: 1.5;">
                                    <?= e($course['description'] ?? '') ?>
                                </p>
                                
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1rem;">
                                    <div>
                                        <div style="font-size: 0.75rem; color: var(--hub-text-tertiary); margin-bottom: 0.25rem;">Duração</div>
                                        <div style="font-size: 0.875rem; font-weight: 600;">
                                            <?= !empty($course['duration_hours']) ? (int)$course['duration_hours'] . 'h' : 'Não definido' ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.75rem; color: var(--hub-text-tertiary); margin-bottom: 0.25rem;">Participantes</div>
                                        <div style="font-size: 0.875rem; font-weight: 600;">
                                            <?= (int)($course['participants_count'] ?? 0) ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if (!empty($course['start_date']) || !empty($course['end_date'])): ?>
                                    <div style="font-size: 0.875rem; color: var(--hub-text-secondary); margin-bottom: 1rem;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 0.25rem;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                        <?php if (!empty($course['start_date'])): ?>
                                            <?= date('d/m/Y', strtotime($course['start_date'])) ?>
                                        <?php endif; ?>
                                        <?php if (!empty($course['start_date']) && !empty($course['end_date'])): ?>
                                            —
                                        <?php endif; ?>
                                        <?php if (!empty($course['end_date'])): ?>
                                            <?= date('d/m/Y', strtotime($course['end_date'])) ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--hub-border);">
                                    <?php
                                        $statusClass = [
                                            'published' => 'badge badge-info',
                                            'ongoing' => 'badge badge-success',
                                            'completed' => 'badge badge-secondary',
                                            'cancelled' => 'badge badge-warning',
                                            'draft' => 'badge badge-secondary',
                                        ];
                                        $statusLabel = [
                                            'published' => 'Publicado',
                                            'ongoing' => 'Em andamento',
                                            'completed' => 'Concluído',
                                            'cancelled' => 'Cancelado',
                                            'draft' => 'Rascunho',
                                        ];
                                        $currentStatus = $course['status'] ?? 'draft';
                                    ?>
                                    <span class="<?= $statusClass[$currentStatus] ?? 'badge' ?>"><?= $statusLabel[$currentStatus] ?? 'N/A' ?></span>
                                    <div style="display:flex;gap:.5rem;flex-wrap:wrap;justify-content:flex-end;">
                                        <?php if (!empty($course['pdf_file_url'])): ?>
                                            <a class="btn btn-sm btn-secondary" href="<?= e((string) $course['pdf_file_url']) ?>" target="_blank" rel="noopener noreferrer">PDF</a>
                                        <?php endif; ?>
                                        <?php if (!empty($course['video_url'])): ?>
                                            <a class="btn btn-sm btn-secondary" href="<?= e((string) $course['video_url']) ?>" target="_blank" rel="noopener noreferrer">Vídeo</a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="viewCourse(<?= $course['id'] ?>)">
                                            Ver detalhes
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

<div id="addCourseModal" class="modal" style="display:none;">
    <div class="modal__content modal__content--wide">
        <div class="modal__header">
            <h2 class="modal__title">Novo Curso</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('addCourseModal').style.display='none'">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/cursos/novo') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="modal__body modal__body--compact">
                <div class="modal-grid">
                <div class="form-group modal-grid__full">
                    <label for="title" class="form-label">Título <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Ex: Curso de Liderança" required>
                </div>
                <div class="form-group modal-grid__full">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="instructor" class="form-label">Instrutor</label>
                    <input type="text" id="instructor" name="instructor" class="form-control">
                </div>
                <div class="form-group">
                    <label for="course_unit" class="form-label">Unidade</label>
                    <select id="course_unit" name="church_unit_id" class="form-control">
                        <option value="">Todas as unidades</option>
                        <?php foreach (($units ?? []) as $unit): ?>
                            <option value="<?= (int) $unit['id'] ?>"><?= e((string) $unit['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="duration_hours" class="form-label">Duração (horas)</label>
                    <input type="number" id="duration_hours" name="duration_hours" class="form-control" placeholder="Ex: 20">
                </div>
                <div class="form-group">
                    <label for="start_date" class="form-label">Data de Início</label>
                    <input type="date" id="start_date" name="start_date" class="form-control">
                </div>
                <div class="form-group">
                    <label for="end_date" class="form-label">Data de Término</label>
                    <input type="date" id="end_date" name="end_date" class="form-control">
                </div>
                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="draft">Rascunho</option>
                        <option value="published">Publicado</option>
                        <option value="ongoing">Em andamento</option>
                        <option value="completed">Concluído</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="material_pdf" class="form-label">Material em PDF</label>
                    <input type="file" id="material_pdf" name="material_pdf" class="form-control" accept="application/pdf,.pdf">
                </div>
                <div class="form-group modal-grid__full">
                    <label for="video_url" class="form-label">Link do vídeo</label>
                    <input type="url" id="video_url" name="video_url" class="form-control" placeholder="https://youtube.com/... ou https://vimeo.com/...">
                </div>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="document.getElementById('addCourseModal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--primary">Criar Curso</button>
            </div>
        </form>
    </div>
</div>

<script>
function viewCourse(id) {
    window.location.href = '<?= url('/gestao/cursos/') ?>' + id;
}

function editCourse(id) {
    alert('Funcionalidade de edição em desenvolvimento. ID: ' + id);
}

function removeCourse(id) {
    if (confirm('Deseja realmente remover este curso?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/gestao/cursos/') ?>' + id + '/remover';
        form.innerHTML = '<?= str_replace("'", "\\'", csrf_field()) ?>';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__view->endSection(); ?>
