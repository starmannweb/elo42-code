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
    <div class="mgmt-search"><span class="mgmt-search__icon">🔍</span><input type="text" name="search" class="form-input" placeholder="Buscar por título, pregador ou referência..." value="<?= e($_GET['search'] ?? '') ?>"></div>
    <button type="submit" class="btn btn--ghost">Buscar</button>
</form>

<?php if (empty($sermons)): ?>
<div class="mgmt-empty"><div class="mgmt-empty__icon">🎤</div><h3 class="mgmt-empty__title">Nenhum sermão</h3><p class="mgmt-empty__text">Registre sermões e mensagens da sua igreja.</p></div>
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
                    <a href="<?= url('/gestao/sermoes/' . $s['id'] . '/editar') ?>" style="padding: 4px;" title="Editar">✏️</a>
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
        <h2 style="display: flex; align-items: center; gap: var(--space-2); font-size: var(--text-lg); font-weight: 700; margin-bottom: var(--space-6);">🎤 Novo Sermão</h2>
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
                <button type="submit" class="btn btn--primary">✓ Salvar</button>
            </div>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
