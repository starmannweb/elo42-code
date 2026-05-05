<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php
    $preachers = is_array($preachers ?? null) ? $preachers : [];
    $units = is_array($units ?? null) ? $units : [];
    $seriesList = is_array($seriesList ?? null) ? $seriesList : [];
?>

<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Novo sermão</h1>
        <p class="mgmt-header__subtitle">Registre a mensagem para disponibilizar depois na área de Ministrações.</p>
    </div>
</div>

<div class="mgmt-form-card">
    <form method="POST" action="<?= url('/gestao/sermoes') ?>" data-loading>
        <?= csrf_field() ?>

        <div class="form-group">
            <label class="form-label">Título *</label>
            <input type="text" name="title" class="form-input" required>
        </div>

        <div class="mgmt-form-row">
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
        </div>

        <div class="mgmt-form-row">
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
        </div>

        <div class="mgmt-form-row">
            <div class="form-group">
                <label class="form-label">Referência bíblica</label>
                <input type="text" name="bible_reference" class="form-input" placeholder="Ex.: João 3:16">
            </div>
            <div class="form-group">
                <label class="form-label">Série</label>
                <input type="text" name="series_name" class="form-input" list="sermon-series-form-list" placeholder="Escolha ou digite o nome da série">
                <datalist id="sermon-series-form-list">
                    <?php foreach ($seriesList as $series): ?>
                        <?php $seriesTitle = trim((string) ($series['title'] ?? '')); ?>
                        <?php if ($seriesTitle !== ''): ?>
                            <option value="<?= e($seriesTitle) ?>"></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </datalist>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Resumo</label>
            <textarea name="summary" class="form-input" rows="4"></textarea>
        </div>

        <div class="mgmt-form-row">
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
        </div>

        <div class="mgmt-form-actions">
            <button type="submit" class="btn btn--primary">Registrar sermão</button>
            <a href="<?= url('/gestao/sermoes') ?>" class="btn btn--ghost">Cancelar</a>
        </div>
    </form>
</div>
<?php $__view->endSection(); ?>
