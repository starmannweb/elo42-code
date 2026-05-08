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

<form method="POST" action="<?= $isEdit ? url('/admin/blog/' . (int) $item['id'] . '/editar') : url('/admin/blog') ?>">
    <?= csrf_field() ?>

    <div class="blog-form-grid">
        <!-- Coluna principal -->
        <div class="blog-form-main">
            <div class="mgmt-form-card" style="max-width:none;">
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
                    <textarea name="content" class="form-input" rows="18" required><?= e($isEdit ? (string) $item['content'] : '') ?></textarea>
                    <small class="form-hint">Suporta HTML básico: &lt;p&gt;, &lt;h2&gt;, &lt;h3&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;a&gt;, &lt;blockquote&gt;.</small>
                </div>
            </div>

            <!-- SEO -->
            <div class="mgmt-form-card" style="max-width:none; margin-top: var(--space-4);">
                <h3 class="mgmt-form-card__title">SEO</h3>

                <div class="form-group">
                    <label class="form-label">Meta título <span style="font-weight:400;color:var(--color-text-secondary);">(deixe em branco para usar o título)</span></label>
                    <input type="text" name="meta_title" class="form-input" maxlength="255" value="<?= e($isEdit ? (string) ($item['meta_title'] ?? '') : '') ?>" placeholder="Título para mecanismos de busca">
                    <small class="form-hint">Recomendado: até 60 caracteres.</small>
                </div>

                <div class="mgmt-form-row">
                    <div class="form-group">
                        <label class="form-label">Meta descrição</label>
                        <textarea name="meta_description" class="form-input" rows="3" maxlength="320" placeholder="Descrição exibida nos resultados de busca"><?= e($isEdit ? (string) ($item['meta_description'] ?? '') : '') ?></textarea>
                        <small class="form-hint">Recomendado: 120–160 caracteres.</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Palavra-chave foco</label>
                        <input type="text" name="focus_keyword" class="form-input" maxlength="120" value="<?= e($isEdit ? (string) ($item['focus_keyword'] ?? '') : '') ?>" placeholder="Ex: gestão de igrejas">
                        <small class="form-hint">Palavra-chave principal do artigo.</small>
                    </div>
                </div>

                <label class="blog-noindex-row">
                    <div class="blog-noindex-row__info">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                        <div>
                            <strong>Não indexar este artigo (noindex)</strong>
                            <span>Impede que este artigo apareça nos resultados de busca do Google.</span>
                        </div>
                    </div>
                    <div class="blog-noindex-row__toggle">
                        <input type="checkbox" name="noindex" value="1" id="noindex_check" <?= $isEdit && !empty($item['noindex']) ? 'checked' : '' ?>>
                        <span class="blog-toggle-track"></span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="blog-form-sidebar">
            <div class="mgmt-form-card" style="max-width:none;">
                <h3 class="mgmt-form-card__title">Publicação</h3>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?= ($isEdit && ($item['status'] ?? '') === 'draft') || !$isEdit ? 'selected' : '' ?>>Rascunho</option>
                        <option value="published" <?= $isEdit && ($item['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publicado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Imagem de capa (URL)</label>
                    <input type="text" name="cover_image" class="form-input" value="<?= e($isEdit ? (string) ($item['cover_image'] ?? '') : '') ?>" placeholder="https://...">
                </div>

                <?php if ($isEdit && !empty($item['cover_image'])): ?>
                    <div style="margin-top: var(--space-3);">
                        <img src="<?= e((string) $item['cover_image']) ?>" alt="Capa"
                             style="width:100%;border-radius:8px;object-fit:cover;max-height:140px;display:block;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="mgmt-form-actions" style="padding:0; margin-top: var(--space-4); background:none; border:none; flex-direction:column;">
                <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;"><?= $isEdit ? 'Salvar alterações' : 'Criar artigo' ?></button>
                <a href="<?= url('/admin/blog') ?>" class="btn btn--secondary" style="width:100%;justify-content:center;margin-top:var(--space-2);">Cancelar</a>
            </div>
        </aside>
    </div>
</form>

<style>
.blog-form-grid {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: var(--space-6);
    align-items: start;
}

.blog-form-main { min-width: 0; }
.blog-form-sidebar { min-width: 0; }

.blog-noindex-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--space-4);
    padding: var(--space-4);
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius-lg);
    cursor: pointer;
    margin-top: var(--space-2);
    background: var(--color-bg-secondary, #f8fafc);
    transition: border-color 0.15s;
}

.blog-noindex-row:has(input:checked) {
    border-color: var(--color-primary);
    background: rgba(20, 85, 255, 0.04);
}

.blog-noindex-row__info {
    display: flex;
    align-items: flex-start;
    gap: var(--space-3);
    color: var(--color-text-secondary);
}

.blog-noindex-row__info svg {
    flex-shrink: 0;
    margin-top: 2px;
    color: var(--color-text-secondary);
}

.blog-noindex-row:has(input:checked) .blog-noindex-row__info svg {
    color: var(--color-primary);
}

.blog-noindex-row__info strong {
    display: block;
    font-size: var(--text-sm);
    font-weight: 600;
    color: var(--color-text-primary);
    margin-bottom: 2px;
}

.blog-noindex-row__info span {
    font-size: var(--text-xs);
    color: var(--color-text-secondary);
    line-height: 1.5;
}

.blog-noindex-row__toggle {
    position: relative;
    flex-shrink: 0;
}

.blog-noindex-row__toggle input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.blog-toggle-track {
    display: block;
    width: 44px;
    height: 24px;
    border-radius: 12px;
    background: var(--color-border);
    transition: background 0.2s;
    position: relative;
}

.blog-toggle-track::after {
    content: '';
    position: absolute;
    top: 3px;
    left: 3px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 4px rgba(0,0,0,.18);
    transition: transform 0.2s;
}

.blog-noindex-row__toggle input:checked ~ .blog-toggle-track {
    background: var(--color-primary);
}

.blog-noindex-row__toggle input:checked ~ .blog-toggle-track::after {
    transform: translateX(20px);
}

@media (max-width: 900px) {
    .blog-form-grid {
        grid-template-columns: 1fr;
    }
    .blog-form-sidebar {
        order: -1;
    }
}
</style>

<?php $__view->endSection(); ?>
