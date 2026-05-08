<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<?php $isEdit = $item !== null; ?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title"><?= $isEdit ? 'Editar artigo' : 'Novo artigo' ?></h1>
        <p class="mgmt-header__subtitle"><?= $isEdit ? 'Atualize as informações do artigo.' : 'Preencha os dados do novo artigo.' ?></p>
    </div>
    <div class="mgmt-header__actions">
        <a href="<?= url('/admin/blog') ?>" class="btn btn--outline">← Voltar</a>
    </div>
</div>

<div class="mgmt-form-card" style="max-width:860px;">
    <form method="POST" action="<?= $isEdit ? url('/admin/blog/' . (int) $item['id'] . '/editar') : url('/admin/blog') ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label class="form-label">Título *</label>
            <input type="text" name="title" class="form-input" value="<?= e($isEdit ? (string) $item['title'] : '') ?>" required>
        </div>

        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-input" value="<?= e($isEdit ? (string) $item['slug'] : '') ?>" placeholder="Gerado automaticamente pelo título">
            </div>
            <div class="form-group">
                <label class="form-label">Autor</label>
                <input type="text" name="author" class="form-input" value="<?= e($isEdit ? (string) $item['author'] : 'Equipe Elo 42') ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Resumo</label>
            <textarea name="summary" class="form-input" rows="2" placeholder="Breve descrição exibida na listagem do blog"><?= e($isEdit ? (string) $item['summary'] : '') ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Conteúdo *</label>
            <textarea name="content" class="form-input" rows="16" required><?= e($isEdit ? (string) $item['content'] : '') ?></textarea>
            <small class="form-hint">Suporta HTML básico: &lt;p&gt;, &lt;h2&gt;, &lt;h3&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;a&gt;, &lt;blockquote&gt;.</small>
        </div>

        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">Imagem de capa (URL)</label>
                <input type="text" name="cover_image" class="form-input" value="<?= e($isEdit ? (string) ($item['cover_image'] ?? '') : '') ?>" placeholder="https://...">
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="draft" <?= ($isEdit && ($item['status'] ?? '') === 'draft') || !$isEdit ? 'selected' : '' ?>>Rascunho</option>
                    <option value="published" <?= $isEdit && ($item['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publicado</option>
                </select>
            </div>
        </div>

        <div class="mgmt-form-actions">
            <button type="submit" class="btn btn--primary"><?= $isEdit ? 'Salvar alterações' : 'Criar artigo' ?></button>
            <a href="<?= url('/admin/blog') ?>" class="btn btn--secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php $__view->endSection(); ?>
