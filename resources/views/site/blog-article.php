<?php $__view->extends('public'); ?>

<?php
$article = is_array($article ?? null) ? $article : [];
$title = (string) ($article['title'] ?? '');
$author = (string) ($article['author'] ?? 'Equipe Elo 42');
$publishedAt = (string) ($article['published_at'] ?? $article['created_at'] ?? '');
$coverImage = (string) ($article['cover_image'] ?? '');
$summary = (string) ($article['summary'] ?? '');
$content = (string) ($article['content'] ?? '');
$_ogImage = e($ogImage ?? $coverImage ?? '');
$_canonicalUrl = e($canonicalUrl ?? '');
$_baseUrl = e($baseUrl ?? '');
?>

<?php $__view->section('head'); ?>
<meta property="og:type" content="article">
<meta property="og:title" content="<?= e($pageTitle ?? $title) ?>">
<meta property="og:description" content="<?= e($metaDescription ?? $summary) ?>">
<?php if ($_canonicalUrl): ?><meta property="og:url" content="<?= $_canonicalUrl ?>"><?php endif; ?>
<?php if ($_ogImage): ?><meta property="og:image" content="<?= $_ogImage ?>"><?php endif; ?>
<meta property="og:site_name" content="Blog Elo 42">
<?php if ($publishedAt): ?><meta property="article:published_time" content="<?= e($publishedAt) ?>"><?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($pageTitle ?? $title) ?>">
<meta name="twitter:description" content="<?= e($metaDescription ?? $summary) ?>">
<?php if ($_ogImage): ?><meta name="twitter:image" content="<?= $_ogImage ?>"><?php endif; ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": <?= json_encode($title) ?>,
  "description": <?= json_encode($summary) ?>,
  <?php if ($_ogImage): ?>"image": <?= json_encode($ogImage ?? $coverImage) ?>,<?php endif; ?>
  "author": {"@type": "Person", "name": <?= json_encode($author) ?>},
  "publisher": {"@type": "Organization", "name": "Elo 42", "url": <?= json_encode($baseUrl ?? '') ?>},
  <?php if ($publishedAt): ?>"datePublished": <?= json_encode($publishedAt) ?>,<?php endif; ?>
  "url": <?= json_encode($canonicalUrl ?? '') ?>
}
</script>
<?php $__view->endSection(); ?>

<?php $__view->section('content'); ?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content" style="max-width:800px;">
            <p style="font-size:var(--text-sm);color:var(--color-text-on-dark-secondary);margin-bottom:var(--space-3);">
                <a href="<?= url('/blog') ?>" style="color:inherit;text-decoration:underline;">Blog</a> &rsaquo; Artigo
            </p>
            <h1 class="page-hero__title" style="font-size:var(--text-4xl);line-height:1.2;"><?= e($title) ?></h1>
            <?php if ($summary): ?>
                <p class="page-hero__subtitle"><?= e($summary) ?></p>
            <?php endif; ?>
            <p style="font-size:var(--text-sm);color:var(--color-text-on-dark-secondary);margin-top:var(--space-4);">
                <?= e($author) ?>
                <?php if ($publishedAt): ?>
                    &middot; <?= date('d \d\e F \d\e Y', strtotime($publishedAt)) ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
</section>

<?php if ($coverImage): ?>
<div class="container" style="margin-top: calc(-1 * var(--space-8));">
    <img src="<?= e($coverImage) ?>" alt="<?= e($title) ?>" loading="lazy"
         style="width:100%;max-height:420px;object-fit:cover;border-radius:16px;display:block;box-shadow:0 8px 40px rgba(10,31,68,0.14);">
</div>
<?php endif; ?>

<section class="section">
    <div class="container">
        <div class="article-layout">
            <article class="article-body prose">
                <?= $content ?>
            </article>

            <aside class="article-aside">
                <div class="article-aside__card">
                    <h3>Sobre o autor</h3>
                    <p><?= e($author) ?></p>
                </div>
                <div class="article-aside__card" style="margin-top:var(--space-4);">
                    <h3>Elo 42</h3>
                    <p>Gestão, tecnologia e impacto para igrejas e organizações.</p>
                    <a href="<?= url('/') ?>" class="btn btn--primary" style="margin-top:var(--space-3);width:100%;justify-content:center;">Conheça a plataforma</a>
                </div>
            </aside>
        </div>

        <div style="margin-top:var(--space-12);padding-top:var(--space-8);border-top:1px solid #e8eef8;">
            <a href="<?= url('/blog') ?>" class="btn btn--outline">← Voltar ao Blog</a>
        </div>
    </div>
</section>

<style>
.article-layout {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: var(--space-12);
    align-items: start;
}

@media (max-width: 900px) {
    .article-layout {
        grid-template-columns: 1fr;
    }
    .article-aside {
        order: -1;
    }
}

.article-body.prose {
    font-size: var(--text-lg);
    line-height: 1.8;
    color: var(--color-text-primary);
}

.article-body.prose h2 {
    font-size: var(--text-2xl);
    margin: var(--space-8) 0 var(--space-4);
    line-height: 1.3;
}

.article-body.prose h3 {
    font-size: var(--text-xl);
    margin: var(--space-6) 0 var(--space-3);
}

.article-body.prose p {
    margin-bottom: var(--space-5);
    text-align: justify;
}

.article-body.prose ul,
.article-body.prose ol {
    margin: 0 0 var(--space-5) var(--space-6);
}

.article-body.prose li {
    margin-bottom: var(--space-2);
}

.article-body.prose blockquote {
    border-left: 4px solid var(--color-primary);
    padding: var(--space-4) var(--space-6);
    margin: var(--space-6) 0;
    background: rgba(10, 77, 255, 0.04);
    border-radius: 0 8px 8px 0;
    font-style: italic;
    color: var(--color-text-secondary);
}

.article-body.prose a {
    color: var(--color-primary);
    text-decoration: underline;
}

.article-aside__card {
    background: var(--color-white);
    border: 1px solid #e8eef8;
    border-radius: 12px;
    padding: var(--space-5);
}

.article-aside__card h3 {
    font-size: var(--text-base);
    font-weight: 700;
    margin-bottom: var(--space-2);
}

.article-aside__card p {
    font-size: var(--text-sm);
    color: var(--color-text-secondary);
    line-height: 1.6;
    margin: 0;
}
</style>

<?php $__view->endSection(); ?>
