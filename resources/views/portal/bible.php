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

<div class="portal-page portal-page--wide" data-bible-root data-version="<?= e($selectedVersion) ?>" data-book="<?= e($selectedBook) ?>" data-chapter="<?= (int) $selectedChapter ?>">
    <div class="portal-page-header">
        <div>
            <h2 class="portal-title">Bíblia</h2>
            <p class="portal-subtitle">Leia, favorite versículos, grife trechos importantes e salve anotações bíblicas.</p>
        </div>
        <div class="portal-actions">
            <input form="bible-nav" class="portal-input" style="width:min(100%,320px);" type="search" name="q" placeholder="Buscar livro ou versículo">
        </div>
    </div>

    <div class="portal-bible-tabs" role="tablist" aria-label="Recursos da Bíblia">
        <button type="button" class="active" data-bible-tab="reader">Livros</button>
        <button type="button" data-bible-tab="favorites">Favoritos</button>
        <button type="button" data-bible-tab="highlights">Grifados</button>
        <button type="button" data-bible-tab="notes">Anotações</button>
    </div>

    <div class="portal-bible-reader" data-bible-panel="reader">
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
                        <?php
                            $number = (int) ($verse['number'] ?? 0);
                            $text = (string) ($verse['text'] ?? '');
                            $reference = $selectedBook . ' ' . $selectedChapter . ':' . $number;
                        ?>
                        <div class="portal-verse" data-verse="<?= $number ?>" data-reference="<?= e($reference) ?>" data-text="<?= e($text) ?>">
                            <p><strong><?= $number ?></strong> <span class="portal-verse__text"><?= e($text) ?></span></p>
                            <div class="portal-verse-actions" aria-label="Ações do versículo <?= e($reference) ?>">
                                <button type="button" data-bible-action="favorite">Favoritar</button>
                                <button type="button" data-bible-action="highlight">Grifar</button>
                                <button type="button" data-bible-action="note">Anotar</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>

    <section class="portal-card portal-bible-panel" data-bible-panel="favorites" hidden>
        <div class="portal-card__header"><div><h3 class="portal-card__title">Favoritos</h3><p class="portal-card__subtitle">Versículos salvos para acessar rapidamente.</p></div></div>
        <div class="portal-card__body"><div class="portal-bible-saved-list" data-bible-list="favorites"></div></div>
    </section>

    <section class="portal-card portal-bible-panel" data-bible-panel="highlights" hidden>
        <div class="portal-card__header"><div><h3 class="portal-card__title">Grifados</h3><p class="portal-card__subtitle">Trechos destacados para revisão.</p></div></div>
        <div class="portal-card__body"><div class="portal-bible-saved-list" data-bible-list="highlights"></div></div>
    </section>

    <section class="portal-card portal-bible-panel" data-bible-panel="notes" hidden>
        <div class="portal-card__header"><div><h3 class="portal-card__title">Anotações</h3><p class="portal-card__subtitle">Observações bíblicas registradas por versículo.</p></div></div>
        <div class="portal-card__body"><div class="portal-bible-saved-list" data-bible-list="notes"></div></div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('bible-nav');
    var bookSelect = document.getElementById('book');
    var chapterSelect = document.getElementById('chapter');
    var root = document.querySelector('[data-bible-root]');
    var storageKey = 'elo42_bible_marks_v1';

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

    function readMarks() {
        try {
            return JSON.parse(localStorage.getItem(storageKey) || '{}');
        } catch (error) {
            return {};
        }
    }

    function writeMarks(data) {
        localStorage.setItem(storageKey, JSON.stringify(data));
    }

    function keyFor(verse) {
        return [root.dataset.version, root.dataset.book, root.dataset.chapter, verse.dataset.verse].join('|');
    }

    function versePayload(verse) {
        return {
            key: keyFor(verse),
            reference: verse.dataset.reference,
            text: verse.dataset.text,
            version: root.dataset.version
        };
    }

    function setActiveStates() {
        var marks = readMarks();
        document.querySelectorAll('.portal-verse').forEach(function (verse) {
            var key = keyFor(verse);
            verse.classList.toggle('is-highlighted', !!(marks.highlights && marks.highlights[key]));
            verse.querySelector('[data-bible-action="favorite"]').classList.toggle('active', !!(marks.favorites && marks.favorites[key]));
            verse.querySelector('[data-bible-action="highlight"]').classList.toggle('active', !!(marks.highlights && marks.highlights[key]));
            verse.querySelector('[data-bible-action="note"]').classList.toggle('active', !!(marks.notes && marks.notes[key]));
        });
        renderLists();
    }

    function toggleCollection(collection, payload) {
        var marks = readMarks();
        marks[collection] = marks[collection] || {};
        if (marks[collection][payload.key]) {
            delete marks[collection][payload.key];
        } else {
            marks[collection][payload.key] = payload;
        }
        writeMarks(marks);
    }

    function renderLists() {
        var marks = readMarks();
        ['favorites', 'highlights', 'notes'].forEach(function (collection) {
            var target = document.querySelector('[data-bible-list="' + collection + '"]');
            if (!target) return;
            var items = Object.values(marks[collection] || {});
            if (!items.length) {
                target.innerHTML = '<div class="portal-bible-empty">Nenhum registro ainda.</div>';
                return;
            }
            target.innerHTML = items.map(function (item) {
                var note = item.note ? '<p class="portal-note-box">' + escapeHtml(item.note) + '</p>' : '';
                return '<article class="portal-bible-saved-item"><strong>' + escapeHtml(item.reference) + ' · ' + escapeHtml(item.version) + '</strong><p>' + escapeHtml(item.text) + '</p>' + note + '</article>';
            }).join('');
        });
    }

    function escapeHtml(value) {
        return String(value || '').replace(/[&<>"']/g, function (char) {
            return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char];
        });
    }

    document.querySelectorAll('[data-bible-tab]').forEach(function (button) {
        button.addEventListener('click', function () {
            var tab = button.dataset.bibleTab;
            document.querySelectorAll('[data-bible-tab]').forEach(function (item) { item.classList.toggle('active', item === button); });
            document.querySelectorAll('[data-bible-panel]').forEach(function (panel) { panel.hidden = panel.dataset.biblePanel !== tab; });
        });
    });

    document.querySelectorAll('.portal-verse-actions button').forEach(function (button) {
        button.addEventListener('click', function () {
            var verse = button.closest('.portal-verse');
            var payload = versePayload(verse);
            var action = button.dataset.bibleAction;
            if (action === 'favorite') {
                toggleCollection('favorites', payload);
            }
            if (action === 'highlight') {
                toggleCollection('highlights', payload);
            }
            if (action === 'note') {
                var marks = readMarks();
                marks.notes = marks.notes || {};
                var current = marks.notes[payload.key] ? marks.notes[payload.key].note || '' : '';
                var note = window.prompt('Anotação para ' + payload.reference, current);
                if (note !== null) {
                    if (note.trim() === '') {
                        delete marks.notes[payload.key];
                    } else {
                        marks.notes[payload.key] = Object.assign(payload, { note: note.trim() });
                    }
                    writeMarks(marks);
                }
            }
            setActiveStates();
        });
    });

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

    setActiveStates();
});
</script>
<?php $__view->endSection(); ?>
