<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php
    $totalTasks = 0; $doneTasks = 0;
    foreach ($plan['objectives'] as $obj) { foreach ($obj['tasks'] as $t) { $totalTasks++; if ($t['status'] === 'done') $doneTasks++; } }
    $progress = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;
?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title"><?= e($plan['title']) ?></h1>
        <p class="mgmt-header__subtitle"><span class="badge badge--<?= $plan['status'] ?>"><?= ucfirst(e($plan['status'])) ?></span> · <?= $progress ?>% concluído</p>
    </div>
</div>

<div class="progress-bar" style="margin-bottom:var(--space-6);"><div class="progress-bar__fill progress-bar__fill--green" style="width:<?= $progress ?>%"></div></div>

<?php if ($plan['description']): ?><p style="font-size:var(--text-sm);color:var(--color-text-secondary);margin-bottom:var(--space-6);"><?= nl2br(e($plan['description'])) ?></p><?php endif; ?>

<?php foreach ($plan['objectives'] as $obj): ?>
<div class="plan-objective">
    <div class="plan-objective__header">
        <span class="plan-objective__title">🎯 <?= e($obj['title']) ?></span>
    </div>

    <?php foreach ($obj['tasks'] as $t): ?>
    <div class="plan-task <?= $t['status'] === 'done' ? 'done' : '' ?>">
        <form method="POST" action="<?= url('/gestao/planos/' . $plan['id'] . '/tarefa/' . $t['id'] . '/status') ?>" style="display:inline;">
            <?= csrf_field() ?>
            <select name="status" class="form-select" style="font-size:0.7rem;padding:2px 4px;width:auto;" onchange="this.form.submit()">
                <option value="todo" <?= $t['status'] === 'todo' ? 'selected' : '' ?>>A fazer</option>
                <option value="doing" <?= $t['status'] === 'doing' ? 'selected' : '' ?>>Fazendo</option>
                <option value="done" <?= $t['status'] === 'done' ? 'selected' : '' ?>>Feito</option>
            </select>
        </form>
        <span class="plan-task__text"><?= e($t['title']) ?></span>
        <?php if ($t['assigned_name']): ?><span class="badge badge--active" style="font-size:0.6rem;"><?= e($t['assigned_name']) ?></span><?php endif; ?>
        <?php if ($t['due_date']): ?><span style="font-size:0.7rem;color:var(--color-text-muted);">📅 <?= date('d/m', strtotime($t['due_date'])) ?></span><?php endif; ?>
    </div>
    <?php endforeach; ?>

    <!-- Add task -->
    <form method="POST" action="<?= url('/gestao/planos/' . $plan['id'] . '/objetivo/' . $obj['id'] . '/tarefa') ?>" style="display:flex;gap:8px;padding:var(--space-3);border-top:1px solid var(--color-border-light);">
        <?= csrf_field() ?>
        <input type="text" name="title" class="form-input" placeholder="Nova tarefa..." style="font-size:var(--text-sm);" required>
        <select name="assigned_to" class="form-select" style="font-size:0.7rem;max-width:120px;"><option value="">—</option><?php foreach ($members as $m): ?><option value="<?= $m['id'] ?>"><?= e($m['name']) ?></option><?php endforeach; ?></select>
        <input type="date" name="due_date" class="form-input" style="max-width:140px;">
        <button type="submit" class="btn btn--primary" style="padding:4px 12px;font-size:var(--text-xs);">+</button>
    </form>
</div>
<?php endforeach; ?>

<!-- Add objective -->
<div class="plan-objective" style="background:var(--color-bg-light);">
    <form method="POST" action="<?= url('/gestao/planos/' . $plan['id'] . '/objetivo') ?>" style="display:flex;gap:var(--space-3);align-items:end;">
        <?= csrf_field() ?>
        <div class="form-group" style="flex:1;margin-bottom:0;"><label class="form-label">Novo objetivo</label><input type="text" name="title" class="form-input" placeholder="Título do objetivo" required></div>
        <button type="submit" class="btn btn--primary">Adicionar</button>
    </form>
</div>

<?php $__view->endSection(); ?>
