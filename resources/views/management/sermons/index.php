<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Sermões</h1>
        <p class="mgmt-header__subtitle">Gerencie os sermões e mensagens da igreja</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="document.getElementById('sermon-modal').style.display='flex'">+ Novo Sermão</button>
    </div>
</div>

<form method="GET" action="<?= url('/gestao/sermoes') ?>" class="mgmt-filters">
    <div class="mgmt-search"><span class="mgmt-search__icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></span><input type="text" name="search" class="form-input" placeholder="Buscar por título, pregador ou referência..." value="<?= e($_GET['search'] ?? '') ?>"></div>
    <button type="submit" class="btn btn--ghost">Buscar</button>
</form>

<?php if (empty($sermons)): ?>
<div class="mgmt-empty"><div class="mgmt-empty__icon"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg></div><h3 class="mgmt-empty__title">Nenhum sermão</h3><p class="mgmt-empty__text">Registre sermões e mensagens da sua igreja.</p></div>
<?php else: ?>
<div class="mgmt-table-container">
    <table class="mgmt-table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Pregador</th>
                <th>Referência</th>
                <th>Data</th>
                <th>Série</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sermons as $s): ?>
            <tr>
                <td><div class="mgmt-table__name"><?= e($s['title']) ?></div></td>
                <td><?= e($s['preacher'] ?? '—') ?></td>
                <td style="color: var(--text-muted); font-style: italic;"><?= e($s['bible_reference'] ?? '—') ?></td>
                <td><?= $s['sermon_date'] ? date('d/m/Y', strtotime($s['sermon_date'])) : '—' ?></td>
                <td><?= e($s['series_name'] ?? '—') ?></td>
                <td><span class="badge badge--<?= $s['status'] ?>"><?= $s['status'] === 'published' ? 'Publicado' : 'Rascunho' ?></span></td>
                <td class="mgmt-table__actions">
                    <a href="<?= url('/gestao/sermoes/' . $s['id'] . '/editar') ?>" style="color: var(--text-muted); display:inline-flex;" title="Editar"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<!-- Modal Novo Sermão -->
<div id="sermon-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--color-white); border-radius: var(--radius-xl); padding: var(--space-6); width: 100%; max-width: 500px; position: relative;">
        <button type="button" onclick="document.getElementById('sermon-modal').style.display='none'" style="position: absolute; top: var(--space-4); right: var(--space-4); background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted);">×</button>
        <h2 style="display: flex; align-items: center; gap: var(--space-2); font-size: var(--text-lg); font-weight: 700; margin-bottom: var(--space-6);"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg> Novo Sermão</h2>
        <form method="POST" action="<?= url('/gestao/sermoes') ?>">
            <div style="margin-bottom: var(--space-4);">
                <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-1);">Título *</label>
                <input type="text" name="title" class="form-input" required style="width: 100%;">
            </div>
            <div style="margin-bottom: var(--space-4);">
                <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-1);">Pregador</label>
                <input type="text" name="preacher" class="form-input" style="width: 100%;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4); margin-bottom: var(--space-4);">
                <div>
                    <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-1);">Data *</label>
                    <input type="date" name="sermon_date" class="form-input" required style="width: 100%;">
                </div>
                <div>
                    <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-1);">Duração (min)</label>
                    <input type="number" name="duration" class="form-input" style="width: 100%;">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4); margin-bottom: var(--space-4);">
                <div>
                    <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-1);">Tipo de Mídia</label>
                    <select name="media_type" class="form-select" style="width: 100%;">
                        <option value="youtube">YouTube</option>
                        <option value="vimeo">Vimeo</option>
                        <option value="audio">Áudio</option>
                        <option value="none">Nenhum</option>
                    </select>
                </div>
                <div>
                    <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-1);">URL da Mídia</label>
                    <input type="url" name="media_url" class="form-input" style="width: 100%;">
                </div>
            </div>
            <div style="margin-bottom: var(--space-6);">
                <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-1);">Descrição</label>
                <textarea name="description" class="form-input" rows="3" style="width: 100%; resize: vertical;"></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: var(--space-3);">
                <button type="button" class="btn btn--ghost" onclick="document.getElementById('sermon-modal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--primary">Salvar</button>
            </div>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
