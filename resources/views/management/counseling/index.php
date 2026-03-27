<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Aconselhamento</h1></div><div class="mgmt-header__actions"><a href="<?= url('/gestao/aconselhamento/novo') ?>" class="btn btn--primary">+ Novo atendimento</a></div></div>
<?php if (empty($sessions)): ?><div class="mgmt-empty"><div class="mgmt-empty__icon">💬</div><h3 class="mgmt-empty__title">Nenhum atendimento</h3><p class="mgmt-empty__text">Registre sessões de aconselhamento.</p></div>
<?php else: ?>
    <div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Assunto</th><th>Membro</th><th>Conselheiro</th><th>Data</th><th>Status</th><th>🔒</th></tr></thead><tbody>
        <?php foreach ($sessions as $s): ?><tr>
            <td class="mgmt-table__name"><?= e($s['subject']) ?></td>
            <td><?= e($s['member_name'] ?? '—') ?></td>
            <td><?= e($s['counselor_name'] ?? '—') ?></td>
            <td><?= date('d/m/Y', strtotime($s['session_date'])) ?></td>
            <td><span class="badge badge--<?= $s['status'] ?>"><?= e(match($s['status']) { 'scheduled'=>'Agendada','completed'=>'Concluída','cancelled'=>'Cancelada','no_show'=>'Não compareceu', default=>$s['status'] }) ?></span></td>
            <td><?= $s['is_confidential'] ? '🔒' : '—' ?></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
<?php endif; ?>
<?php $__view->endSection(); ?>
