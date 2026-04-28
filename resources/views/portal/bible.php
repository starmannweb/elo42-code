<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<?php
    $versions = is_array($versions ?? null) ? $versions : ['ARA', 'ARC', 'ACF', 'NAA', 'NVI', 'NTLH', 'KJA', 'A21', 'NVT', 'NBV-P', 'VFL', 'TB'];
    $books = is_array($books ?? null) ? $books : [];
    $selectedVersion = (string) ($selectedVersion ?? ($versions[0] ?? 'ARA'));
    $selectedBook = (string) ($selectedBook ?? (array_key_first($books) ?: 'João'));
    $selectedChapter = (int) ($selectedChapter ?? 3);
    $chapterCount = (int) ($chapterCount ?? ($books[$selectedBook] ?? 1));
    $passage = is_array($passage ?? null) ? $passage : [];
?>

<div class="portal-page portal-page--wide">
    <div class="portal-page-header">
        <div>
            <h2 class="portal-title">Bíblia</h2>
            <p class="portal-subtitle">Leitor bíblico com versões em português brasileiro, livros e capítulos organizados para navegação.</p>
        </div>
        <div class="portal-actions">
            <input form="bible-nav" class="portal-input" style="width:min(100%,320px);" type="search" name="q" placeholder="Buscar passagem">
        </div>
    </div>

    <div class="portal-bible-reader">
        <aside class="portal-card">
            <div class="portal-card__header">
                <div>
                    <h3 class="portal-card__title">Navegação</h3>
                    <p class="portal-card__subtitle">Escolha versão, livro e capítulo.</p>
                </div>
            </div>
            <div class="portal-card__body">
                <form id="bible-nav" method="GET" action="<?= url('/membro/biblia') ?>" class="portal-form">
                    <div class="portal-field">
                        <label class="portal-label" for="version">Versão</label>
                        <select class="portal-select" id="version" name="version">
                            <?php foreach ($versions as $version): ?>
                                <option value="<?= e((string) $version) ?>" <?= $selectedVersion === (string) $version ? 'selected' : '' ?>>
                                    <?= e((string) $version) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="portal-field">
                        <label class="portal-label" for="book">Livro</label>
                        <select class="portal-select" id="book" name="book">
                            <?php foreach ($books as $book => $count): ?>
                                <option value="<?= e((string) $book) ?>" data-chapters="<?= (int) $count ?>" <?= $book === $selectedBook ? 'selected' : '' ?>>
                                    <?= e((string) $book) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="portal-field">
                        <label class="portal-label" for="chapter">Capítulo</label>
                        <select class="portal-select" id="chapter" name="chapter" data-selected="<?= (int) $selectedChapter ?>">
                            <?php for ($chapter = 1; $chapter <= max(1, $chapterCount); $chapter++): ?>
                                <option value="<?= $chapter ?>" <?= $chapter === $selectedChapter ? 'selected' : '' ?>>
                                    <?= $chapter ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </form>
            </div>
        </aside>

        <section class="portal-card">
            <div class="portal-card__header">
                <div>
                    <h3 class="portal-card__title"><?= e($selectedBook) ?> <?= (int) $selectedChapter ?></h3>
                    <p class="portal-card__subtitle">Leitura em português brasileiro · <?= e($selectedVersion) ?></p>
                </div>
                <a class="portal-btn portal-btn--secondary" href="<?= url('/membro/planos-leitura') ?>">Ver planos</a>
            </div>
            <div class="portal-card__body">
                <div class="portal-reading-text">
                    <?php foreach ($passage as $verse): ?>
                        <p><strong><?= (int) ($verse['number'] ?? 0) ?></strong> <?= e((string) ($verse['text'] ?? '')) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('bible-nav');
    var bookSelect = document.getElementById('book');
    var chapterSelect = document.getElementById('chapter');

    function rebuildChapters() {
        var selectedBook = bookSelect.options[bookSelect.selectedIndex];
        var chapters = parseInt(selectedBook.getAttribute('data-chapters') || '1', 10);
        var selected = parseInt(chapterSelect.getAttribute('data-selected') || '1', 10);

        chapterSelect.innerHTML = '';
        for (var index = 1; index <= chapters; index++) {
            var option = document.createElement('option');
            option.value = String(index);
            option.textContent = String(index);
            if (index === Math.min(selected, chapters)) {
                option.selected = true;
            }
            chapterSelect.appendChild(option);
        }
    }

    if (form && bookSelect && chapterSelect) {
        rebuildChapters();
        bookSelect.addEventListener('change', function () {
            chapterSelect.setAttribute('data-selected', '1');
            rebuildChapters();
            form.submit();
        });
        document.getElementById('version').addEventListener('change', function () { form.submit(); });
        chapterSelect.addEventListener('change', function () { form.submit(); });
    }
});
</script>
<?php $__view->endSection(); ?>
