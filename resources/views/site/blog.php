<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>
<?php
$articles = is_array($articles ?? null) ? $articles : [];
$pagination = is_array($pagination ?? null) ? $pagination : ['total' => 0, 'page' => 1, 'perPage' => 9, 'totalPages' => 1];
?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <h1 class="page-hero__title">Blog</h1>
            <p class="page-hero__subtitle">
                Reflexões, novidades e conteúdos da equipe Elo 42 para igrejas e organizações.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($articles)): ?>
            <div style="text-align:center; padding: var(--space-16) 0; color: var(--color-text-secondary);">
                <p style="font-size: var(--text-lg);">Nenhum artigo publicado ainda. Volte em breve.</p>
            </div>
        <?php else: ?>
            <div class="blog-grid animate-on-scroll">
                <?php foreach ($articles as $a): ?>
                    <article class="blog-card">
                        <?php if (!empty($a['cover_image'])): ?>
                            <a href="<?= url('/blog/' . e((string) $a['slug'])) ?>" class="blog-card__cover" aria-hidden="true" tabindex="-1">
                                <img src="<?= e((string) $a['cover_image']) ?>" alt="" loading="lazy">
                            </a>
                        <?php endif; ?>
                        <div class="blog-card__body">
                            <p class="blog-card__meta">
                                <?= e((string) ($a['author'] ?? 'Equipe Elo 42')) ?>
                                &middot;
                                <?= date('d/m/Y', strtotime((string) ($a['published_at'] ?? $a['created_at'] ?? 'now'))) ?>
                            </p>
                            <h2 class="blog-card__title">
                                <a href="<?= url('/blog/' . e((string) $a['slug'])) ?>"><?= e((string) $a['title']) ?></a>
                            </h2>
                            <?php if (!empty($a['summary'])): ?>
                                <p class="blog-card__summary"><?= e((string) $a['summary']) ?></p>
                            <?php endif; ?>
                            <a href="<?= url('/blog/' . e((string) $a['slug'])) ?>" class="blog-card__link">Ler artigo →</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ((int) ($pagination['totalPages'] ?? 1) > 1): ?>
                <nav class="blog-pagination" aria-label="Paginação">
                    <?php for ($i = 1; $i <= (int) $pagination['totalPages']; $i++): ?>
                        <a href="<?= url('/blog?page=' . $i) ?>"
                           class="blog-pagination__link <?= $i === (int) ($pagination['page'] ?? 1) ? 'active' : '' ?>"
                           <?= $i === (int) ($pagination['page'] ?? 1) ? 'aria-current="page"' : '' ?>>
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<style>
.blog-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: var(--space-8);
}

@media (max-width: 1024px) {
    .blog-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (max-width: 640px) {
    .blog-grid { grid-template-columns: 1fr; }
}

.blog-card {
    background: var(--color-white);
    border: 1px solid #e8eef8;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.blog-card:hover {
    box-shadow: 0 8px 32px rgba(10, 31, 68, 0.10);
    transform: translateY(-2px);
}

.blog-card__cover {
    display: block;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    background: #e8eef8;
}

.blog-card__cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.blog-card__body {
    padding: var(--space-6);
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
}

.blog-card__meta {
    font-size: var(--text-sm);
    color: var(--color-text-secondary);
    margin: 0;
}

.blog-card__title {
    font-size: var(--text-xl);
    line-height: 1.35;
    margin: 0;
}

.blog-card__title a {
    color: var(--color-text-primary);
    text-decoration: none;
}

.blog-card__title a:hover {
    color: var(--color-primary);
}

.blog-card__summary {
    font-size: var(--text-base);
    color: var(--color-text-secondary);
    line-height: 1.6;
    margin: 0;
    flex: 1;
    text-align: justify;
}

.blog-card__link {
    font-size: var(--text-sm);
    font-weight: 600;
    color: var(--color-primary);
    text-decoration: none;
    margin-top: auto;
}

.blog-card__link:hover {
    text-decoration: underline;
}

.blog-pagination {
    display: flex;
    justify-content: center;
    gap: var(--space-2);
    margin-top: var(--space-12);
}

.blog-pagination__link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: 1.5px solid #d4deef;
    color: var(--color-text-primary);
    text-decoration: none;
    font-weight: 600;
    font-size: var(--text-sm);
    transition: all 0.15s ease;
}

.blog-pagination__link:hover,
.blog-pagination__link.active {
    background: var(--color-primary);
    border-color: var(--color-primary);
    color: var(--color-white);
}
</style>

<?php $__view->endSection(); ?>
