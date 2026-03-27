<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Plano de Ação</h1></div><div class="mgmt-header__actions"><a href="<?= url('/gestao/planos/novo') ?>" class="btn btn--primary">+ Novo plano</a></div></div>
<?php if (empty($plans)): ?><div class="mgmt-empty"><div class="mgmt-empty__icon">🎯</div><h3 class="mgmt-empty__title">Nenhum plano</h3><p class="mgmt-empty__text">Crie planos de ação com objetivos e tarefas.</p></div>
<?php else: ?>
    <?php foreach ($plans as $p):
        $progress = $p['task_count'] > 0 ? round(($p['tasks_done'] / $p['task_count']) * 100) : 0;
    ?>
    <div class="plan-objective" style="cursor:pointer;" onclick="window.location='<?= url('/gestao/planos/' . $p['id']) ?>'">
        <div class="plan-objective__header">
            <div>
                <span class="plan-objective__title"><?= e($p['title']) ?></span>
                <span class="badge badge--<?= $p['status'] ?>" style="margin-left:8px;"><?= e(match($p['status']) { 'planning'=>'Planejamento','active'=>'Ativo','completed'=>'Concluído','archived'=>'Arquivado', default=>$p['status'] }) ?></span>
            </div>
            <div style="display:flex;align-items:center;gap:var(--space-3);font-size:var(--text-sm);">
                <span style="color:var(--color-text-muted);"><?= $p['objective_count'] ?> objetivos</span>
                <span style="color:var(--color-text-muted);"><?= $p['tasks_done'] ?>/<?= $p['task_count'] ?> tarefas</span>
            </div>
        </div>
        <div class="progress-bar"><div class="progress-bar__fill progress-bar__fill--green" style="width:<?= $progress ?>%"></div></div>
        <div style="display:flex;justify-content:space-between;margin-top:var(--space-2);font-size:var(--text-xs);color:var(--color-text-muted);">
            <span><?= $progress ?>% concluído</span>
            <?php if ($p['end_date']): ?><span>Prazo: <?= date('d/m/Y', strtotime($p['end_date'])) ?></span><?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php $__view->endSection(); ?>
