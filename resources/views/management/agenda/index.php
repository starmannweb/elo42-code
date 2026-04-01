<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Agenda</h1>
        <p class="mgmt-header__subtitle">Visualize e gerencie compromissos e eventos da igreja</p>
    </div>
    <div class="mgmt-header__actions">
        <a href="<?= url('/gestao/eventos/novo') ?>" class="btn btn--primary">+ Novo Evento</a>
    </div>
</div>

<div class="mgmt-dashboard-card" style="padding: var(--space-6);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-6);">
        <h2 style="font-size: var(--text-lg); font-weight: 700;"><?= date('F Y') ?></h2>
        <div style="display: flex; gap: var(--space-2);">
            <button class="btn btn--ghost btn--sm">‹ Anterior</button>
            <button class="btn btn--ghost btn--sm">Hoje</button>
            <button class="btn btn--ghost btn--sm">Próximo ›</button>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: var(--color-border); border: 1px solid var(--color-border); border-radius: var(--radius-lg); overflow: hidden;">
        <?php 
        $diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        foreach ($diasSemana as $dia): ?>
        <div style="background: var(--color-bg-alt); padding: var(--space-3); text-align: center; font-weight: 600; font-size: var(--text-sm); color: var(--text-muted);"><?= $dia ?></div>
        <?php endforeach; ?>

        <?php
        $primeiroDia = mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y'));
        $diasNoMes = (int)date('t');
        $diaSemanaInicio = (int)date('w', $primeiroDia);
        $hoje = (int)date('j');
        
        // Dias vazios antes do primeiro dia do mês
        for ($i = 0; $i < $diaSemanaInicio; $i++): ?>
        <div style="background: var(--color-bg); padding: var(--space-3); min-height: 80px;"></div>
        <?php endfor;
        
        // Dias do mês
        for ($dia = 1; $dia <= $diasNoMes; $dia++): 
            $dataAtual = date('Y-m') . '-' . str_pad((string)$dia, 2, '0', STR_PAD_LEFT);
            $eventosNoDia = array_filter($events ?? [], fn($e) => isset($e['start_date']) && str_starts_with($e['start_date'], $dataAtual));
        ?>
        <div style="background: var(--color-bg); padding: var(--space-2); min-height: 80px; <?= $dia === $hoje ? 'background: rgba(10, 77, 255, 0.05);' : '' ?>">
            <div style="font-size: var(--text-sm); font-weight: <?= $dia === $hoje ? '700' : '500' ?>; color: <?= $dia === $hoje ? 'var(--color-primary)' : 'var(--text-body)' ?>; margin-bottom: var(--space-1);"><?= $dia ?></div>
            <?php foreach (array_slice($eventosNoDia, 0, 2) as $evento): ?>
            <div style="font-size: 10px; padding: 2px 4px; background: var(--color-primary); color: white; border-radius: 3px; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= e($evento['title'] ?? '') ?></div>
            <?php endforeach; ?>
            <?php if (count($eventosNoDia) > 2): ?>
            <div style="font-size: 10px; color: var(--text-muted);">+<?= count($eventosNoDia) - 2 ?> mais</div>
            <?php endif; ?>
        </div>
        <?php endfor;
        
        // Dias vazios após o último dia do mês
        $diasRestantes = (7 - (($diaSemanaInicio + $diasNoMes) % 7)) % 7;
        for ($i = 0; $i < $diasRestantes; $i++): ?>
        <div style="background: var(--color-bg); padding: var(--space-3); min-height: 80px;"></div>
        <?php endfor; ?>
    </div>
</div>

<?php if (!empty($events)): ?>
<div class="mgmt-dashboard-card" style="margin-top: var(--space-6);">
    <header class="mgmt-dashboard-card__header">
        <h2>Próximos Eventos</h2>
    </header>
    <div style="display: flex; flex-direction: column; gap: var(--space-3); padding: var(--space-4);">
        <?php foreach (array_slice($events, 0, 5) as $evento): ?>
        <div style="display: flex; align-items: center; gap: var(--space-4); padding: var(--space-3); background: var(--color-bg-alt); border-radius: var(--radius-md);">
            <div style="width: 48px; height: 48px; background: var(--color-primary); border-radius: var(--radius-md); display: flex; flex-direction: column; align-items: center; justify-content: center; color: white;">
                <span style="font-size: 16px; font-weight: 700; line-height: 1;"><?= date('d', strtotime($evento['start_date'] ?? 'now')) ?></span>
                <span style="font-size: 10px; text-transform: uppercase;"><?= date('M', strtotime($evento['start_date'] ?? 'now')) ?></span>
            </div>
            <div style="flex: 1;">
                <div style="font-weight: 600;"><?= e($evento['title'] ?? '') ?></div>
                <div style="font-size: var(--text-sm); color: var(--text-muted);"><?= e($evento['location'] ?? 'Local não definido') ?></div>
            </div>
            <a href="<?= url('/gestao/eventos/' . ($evento['id'] ?? '')) ?>" class="btn btn--ghost btn--sm">Ver</a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
<?php $__view->endSection(); ?>
