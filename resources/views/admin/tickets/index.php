<?php $__view->extends('admin'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Tickets</h1></div></div>
<form method="GET" action="<?= url('/admin/tickets') ?>" class="mgmt-filters">
    <select name="status" class="form-select"><option value="">Todos</option><?php foreach (['open'=>'Abertos','in_progress'=>'Em andamento','waiting'=>'Aguardando','resolved'=>'Resolvidos','closed'=>'Fechados'] as $k=>$v): ?><option value="<?= $k ?>" <?= ($filters['status']??'')===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select>
    <select name="priority" class="form-select"><option value="">Todas prioridades</option><?php foreach (['urgent'=>'Urgente','high'=>'Alta','normal'=>'Normal','low'=>'Baixa'] as $k=>$v): ?><option value="<?= $k ?>" <?= ($filters['priority']??'')===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select>
    <button type="submit" class="btn btn--secondary">Filtrar</button>
</form>
<?php if (empty($tickets)): ?><div class="mgmt-empty"><div class="mgmt-empty__icon">🎫</div><h3 class="mgmt-empty__title">Nenhum ticket</h3></div>
<?php else: ?>
    <div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Assunto</th><th>Usuário</th><th>Organização</th><th>Prioridade</th><th>Status</th><th>Criação</th><th>Ações</th></tr></thead><tbody>
        <?php foreach ($tickets as $t): ?><tr>
            <td><div class="mgmt-table__name"><?= e($t['subject']) ?></div><div class="mgmt-table__sub"><?= e($t['category']) ?></div></td>
            <td><?= e($t['user_name']) ?></td>
            <td><?= e($t['org_name'] ?? '—') ?></td>
            <td><span class="badge badge--<?= $t['priority'] ?>"><?= ucfirst(e($t['priority'])) ?></span></td>
            <td><span class="badge badge--<?= $t['status'] ?>"><?= e(match($t['status']) { 'open'=>'Aberto','in_progress'=>'Andamento','waiting'=>'Aguardando','resolved'=>'Resolvido','closed'=>'Fechado', default=>$t['status'] }) ?></span></td>
            <td><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
            <td class="mgmt-table__actions"><a href="<?= url('/admin/tickets/' . $t['id']) ?>">Ver</a></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
<?php endif; ?>
<?php $__view->endSection(); ?>
