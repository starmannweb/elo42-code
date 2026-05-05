<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php
    $preachers = is_array($preachers ?? null) ? $preachers : [];
    $units = is_array($units ?? null) ? $units : [];
    $sermons = is_array($sermons ?? null) ? $sermons : [];
    $seriesList = is_array($seriesList ?? null) ? $seriesList : [];
    $seriesGroups = [];
    foreach ($seriesList as $series) {
        $seriesName = trim((string) ($series['title'] ?? ''));
        if ($seriesName === '') {
            continue;
        }
        $seriesGroups[$seriesName] = [
            'name' => $seriesName,
            'count' => 0,
            'latest' => null,
            'reference' => trim((string) ($series['bible_reference'] ?? '')),
        ];
    }
    foreach ($sermons as $sermon) {
        $seriesName = trim((string) ($sermon['series_name'] ?? ''));
        if ($seriesName === '') {
            continue;
        }
        if (!isset($seriesGroups[$seriesName])) {
            $seriesGroups[$seriesName] = ['name' => $seriesName, 'count' => 0, 'latest' => null, 'reference' => ''];
        }
        $seriesGroups[$seriesName]['count']++;
        $date = (string) ($sermon['sermon_date'] ?? '');
        if ($date !== '' && ($seriesGroups[$seriesName]['latest'] === null || strtotime($date) > strtotime((string) $seriesGroups[$seriesName]['latest']))) {
            $seriesGroups[$seriesName]['latest'] = $date;
        }
    }
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Séries e Sermões</h1>
        <p class="mgmt-header__subtitle">Cadastre mensagens, séries, pregadores e referências bíblicas para aparecerem como Séries e Ministrações na área do membro.</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--outline" onclick="document.getElementById('series-modal').style.display='flex'">+ Nova série</button>
        <a href="<?= url('/gestao/pregadores') ?>" class="btn btn--outline">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:.4rem;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path></svg>
            Pregadores
        </a>
        <button type="button" class="btn btn--primary" onclick="document.getElementById('sermon-modal').style.display='flex'">+ Novo sermão</button>
    </div>
</div>

<form method="GET" action="<?= url('/gestao/sermoes') ?>" class="mgmt-filters">
    <div class="mgmt-search">
        <span class="mgmt-search__icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </span>
        <input type="text" name="search" class="form-input" placeholder="Buscar por título, pregador ou referência..." value="<?= e($_GET['search'] ?? '') ?>">
    </div>
    <button type="submit" class="btn btn--primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" style="margin-right:.4rem;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        Buscar
    </button>
</form>

<?php if (!empty($seriesGroups)): ?>
    <section class="mgmt-grid mgmt-grid--3" style="margin-bottom:1rem;">
        <?php foreach ($seriesGroups as $series): ?>
            <article class="mgmt-card">
                <h3 class="mgmt-card__title"><?= e((string) $series['name']) ?></h3>
                <p class="mgmt-card__text"><?= (int) $series['count'] ?> sermão(ões) nesta série</p>
                <small class="text-muted">
                    Última mensagem: <?= !empty($series['latest']) ? date('d/m/Y', strtotime((string) $series['latest'])) : 'sem data' ?>
                </small>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

<?php if (empty($sermons)): ?>
    <div class="mgmt-empty">
        <div class="mgmt-empty__icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
        </div>
        <h3 class="mgmt-empty__title">Nenhum sermão</h3>
        <p class="mgmt-empty__text">Registre as mensagens da igreja para alimentar a área de ministrações.</p>
        <button type="button" class="btn btn--primary" onclick="document.getElementById('sermon-modal').style.display='flex'">Criar sermão</button>
    </div>
<?php else: ?>
    <div class="mgmt-table-container">
        <table class="mgmt-table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Pregador</th>
                    <th>Unidade</th>
                    <th>Referência</th>
                    <th>Data</th>
                    <th>Série</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sermons as $sermon): ?>
                    <tr>
                        <td><div class="mgmt-table__name"><?= e((string) $sermon['title']) ?></div></td>
                        <td><?= e((string) ($sermon['preacher'] ?? '—')) ?></td>
                        <td><?= e((string) ($sermon['unit_name'] ?? 'Sede')) ?></td>
                        <td style="color: var(--text-muted); font-style: italic;"><?= e((string) ($sermon['bible_reference'] ?? '—')) ?></td>
                        <td><?= !empty($sermon['sermon_date']) ? date('d/m/Y', strtotime((string) $sermon['sermon_date'])) : '—' ?></td>
                        <td><?= e((string) ($sermon['series_name'] ?? '—')) ?></td>
                        <td><span class="badge badge--<?= e((string) $sermon['status']) ?>"><?= ($sermon['status'] ?? '') === 'published' ? 'Publicado' : 'Rascunho' ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div id="series-modal" class="modal" style="display:none;">
    <div class="modal__content">
        <div class="modal__header">
            <h2 class="modal__title">Nova série</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('series-modal').style.display='none'" aria-label="Fechar">×</button>
        </div>
        <form method="POST" action="<?= url('/gestao/sermoes/series') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="form-group">
                    <label class="form-label">Nome da série *</label>
                    <input type="text" name="title" class="form-input" placeholder="Ex.: Sermão do Monte" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Referência bíblica</label>
                    <input type="text" name="bible_reference" class="form-input" placeholder="Ex.: Mateus 5-7">
                </div>
                <div class="form-group">
                    <label class="form-label">Descrição</label>
                    <textarea name="description" class="form-input" rows="3" placeholder="Objetivo pastoral, quantidade prevista de mensagens e observações."></textarea>
                </div>
                <input type="hidden" name="status" value="active">
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="document.getElementById('series-modal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--primary">Salvar série</button>
            </div>
        </form>
    </div>
</div>

<div id="sermon-modal" class="modal" style="display:none;">
    <div class="modal__content modal__content--wide">
        <div class="modal__header">
            <h2 class="modal__title">Novo sermão</h2>
            <button type="button" class="modal__close" onclick="document.getElementById('sermon-modal').style.display='none'" aria-label="Fechar">×</button>
        </div>
        <form method="POST" action="<?= url('/gestao/sermoes') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body modal__body--compact">
                <div class="modal-grid">
                    <div class="form-group modal-grid__full">
                        <label class="form-label">Título *</label>
                        <input type="text" name="title" class="form-input" placeholder="Ex.: O poder da fé" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pregador cadastrado</label>
                        <select name="preacher_id" class="form-select">
                            <option value="">Selecionar pregador</option>
                            <?php foreach ($preachers as $preacher): ?>
                                <option value="<?= (int) $preacher['id'] ?>"><?= e((string) $preacher['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ou informe manualmente</label>
                        <input type="text" name="preacher" class="form-input" placeholder="Nome do pregador">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Unidade</label>
                        <select name="church_unit_id" class="form-select">
                            <option value="">Sede / todas as unidades</option>
                            <?php foreach ($units as $unit): ?>
                                <option value="<?= (int) $unit['id'] ?>"><?= e((string) $unit['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Data</label>
                        <input type="date" name="sermon_date" class="form-input" value="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Referência bíblica</label>
                        <input type="text" name="bible_reference" class="form-input" placeholder="Ex.: João 3:16">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Série</label>
                        <input type="text" name="series_name" class="form-input" list="sermon-series-list" placeholder="Nome da série">
                        <datalist id="sermon-series-list">
                            <?php foreach (array_keys($seriesGroups) as $seriesName): ?>
                                <option value="<?= e((string) $seriesName) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tags</label>
                        <input type="text" name="tags" class="form-input" placeholder="fé, esperança, família">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft">Rascunho</option>
                            <option value="published">Publicado</option>
                        </select>
                    </div>

                    <div class="form-group modal-grid__full">
                        <label class="form-label">Resumo</label>
                        <textarea name="summary" class="form-input" rows="3" placeholder="Resumo que será exibido para os membros em Ministrações."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="document.getElementById('sermon-modal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--primary">Salvar sermão</button>
            </div>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
