<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Visitas</h1></div><div class="mgmt-header__actions"><a href="<?= url('/gestao/visitas/nova') ?>" class="btn btn--primary">+ Nova visita</a></div></div>
<?php if (empty($visits)): ?><div class="mgmt-empty"><div class="mgmt-empty__icon">🚪</div><h3 class="mgmt-empty__title">Nenhuma visita</h3><p class="mgmt-empty__text">Registre visitas recebidas.</p></div>
<?php else: ?>
    <div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Visitante</th><th>Contato</th><th>Data</th><th>Origem</th><th>Acompanhamento</th><th>Responsável</th><th>Ações</th></tr></thead><tbody>
        <?php foreach ($visits as $v): ?><tr>
            <td class="mgmt-table__name"><?= e($v['visitor_name']) ?></td>
            <td><?= e($v['phone'] ?? $v['email'] ?? '—') ?></td>
            <td><?= date('d/m/Y', strtotime($v['visit_date'])) ?></td>
            <td><?= e(match($v['source']) { 'invited'=>'Convidado','spontaneous'=>'Espontâneo','event'=>'Evento','online'=>'Online', default=>$v['source'] }) ?></td>
            <td><form method="POST" action="<?= url('/gestao/visitas/' . $v['id'] . '/followup') ?>" style="display:inline;"><?= csrf_field() ?><select name="follow_up" class="form-select" style="font-size:0.7rem;padding:2px 4px;" onchange="this.form.submit()">
                <?php foreach (['pending'=>'Pendente','contacted'=>'Contatado','scheduled'=>'Agendado','completed'=>'Concluído','no_response'=>'Sem resposta'] as $k=>$lbl): ?><option value="<?= $k ?>" <?= $v['follow_up'] === $k ? 'selected' : '' ?>><?= $lbl ?></option><?php endforeach; ?>
            </select></form></td>
            <td><?= e($v['assigned_name'] ?? '—') ?></td>
            <td class="mgmt-table__actions"><?php if ($v['notes']): ?><span title="<?= e($v['notes']) ?>">📋</span><?php endif; ?></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
<?php endif; ?>
<?php $__view->endSection(); ?>
