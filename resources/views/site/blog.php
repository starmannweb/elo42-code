<?php $__view->extends('public'); ?>

<?php $__view->section('head'); ?>
<meta property="og:type" content="website">
<meta property="og:title" content="Blog — Elo 42">
<meta property="og:description" content="Reflexões, novidades e conteúdos da equipe Elo 42 para igrejas e organizações.">
<?php if (!empty($canonicalUrl)): ?><meta property="og:url" content="<?= e($canonicalUrl) ?>"><?php endif; ?>
<meta property="og:site_name" content="Elo 42">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Blog — Elo 42">
<meta name="twitter:description" content="Reflexões, novidades e conteúdos da equipe Elo 42 para igrejas e organizações.">
<?php $__view->endSection(); ?>

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

<?php $__view->endSection(); ?>
