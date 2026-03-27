<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Solicitações</h1></div><div class="mgmt-header__actions"><a href="<?= url('/gestao/solicitacoes/nova') ?>" class="btn btn--primary">+ Nova solicitação</a></div></div>
<?php if (empty($requests)): ?>
    <div class="mgmt-empty"><div class="mgmt-empty__icon">📝</div><h3 class="mgmt-empty__title">Nenhuma solicitação</h3><p class="mgmt-empty__text">Registre solicitações da comunidade.</p></div>
<?php else: ?>
    <div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Título</th><th>Tipo</th><th>Prioridade</th><th>Membro</th><th>Status</th><th>Data</th><th>Ações</th></tr></thead><tbody>
        <?php foreach ($requests as $r): ?><tr>
            <td><div class="mgmt-table__name"><?= e($r['title']) ?></div></td>
            <td><?= e(match($r['type']) { 'prayer'=>'Oração','support'=>'Apoio','material'=>'Material','general'=>'Geral', default=>$r['type'] }) ?></td>
            <td><span class="badge badge--<?= $r['priority'] ?>"><?= ucfirst(e($r['priority'])) ?></span></td>
            <td><?= e($r['member_name'] ?? '—') ?></td>
            <td><span class="badge badge--<?= $r['status'] ?>"><?= e(match($r['status']) { 'open'=>'Aberta','in_progress'=>'Em andamento','resolved'=>'Resolvida','closed'=>'Fechada', default=>$r['status'] }) ?></span></td>
            <td><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
            <td class="mgmt-table__actions">
                <form method="POST" action="<?= url('/gestao/solicitacoes/' . $r['id'] . '/status') ?>" style="display:inline-flex;gap:4px;">
                    <?= csrf_field() ?>
                    <select name="status" class="form-select" style="font-size:0.7rem;padding:2px 4px;" onchange="this.form.submit()">
                        <?php foreach (['open'=>'Aberta','in_progress'=>'Em andamento','resolved'=>'Resolvida','closed'=>'Fechada'] as $k=>$v): ?>
                            <option value="<?= $k ?>" <?= $r['status'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
<?php endif; ?>
<?php $__view->endSection(); ?>
