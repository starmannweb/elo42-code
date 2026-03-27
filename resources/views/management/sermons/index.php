<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Sermões</h1></div><div class="mgmt-header__actions"><a href="<?= url('/gestao/sermoes/novo') ?>" class="btn btn--primary">+ Novo sermão</a></div></div>
<form method="GET" action="<?= url('/gestao/sermoes') ?>" class="mgmt-filters">
    <div class="mgmt-search"><span class="mgmt-search__icon">🔍</span><input type="text" name="search" class="form-input" placeholder="Buscar por título, pregador ou referência..." value="<?= e($_GET['search'] ?? '') ?>"></div>
    <button type="submit" class="btn btn--ghost">Buscar</button>
</form>
<?php if (empty($sermons)): ?><div class="mgmt-empty"><div class="mgmt-empty__icon">📖</div><h3 class="mgmt-empty__title">Nenhum sermão</h3><p class="mgmt-empty__text">Registre sermões da sua igreja.</p></div>
<?php else: ?>
    <div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Título</th><th>Pregador</th><th>Referência</th><th>Data</th><th>Série</th><th>Status</th></tr></thead><tbody>
        <?php foreach ($sermons as $s): ?><tr>
            <td class="mgmt-table__name"><?= e($s['title']) ?></td>
            <td><?= e($s['preacher'] ?? '—') ?></td>
            <td class="text-muted" style="font-style:italic"><?= e($s['bible_reference'] ?? '—') ?></td>
            <td><?= $s['sermon_date'] ? date('d/m/Y', strtotime($s['sermon_date'])) : '—' ?></td>
            <td><?= e($s['series_name'] ?? '—') ?></td>
            <td><span class="badge badge--<?= $s['status'] ?>"><?= $s['status'] === 'published' ? 'Publicado' : 'Rascunho' ?></span></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
<?php endif; ?>
<?php $__view->endSection(); ?>
