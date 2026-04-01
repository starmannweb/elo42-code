<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php
$mesesPt = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
$mesAtual = (int)date('m');
$anoAtual = (int)date('Y');
$nomeMes = $mesesPt[$mesAtual - 1];
?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Agenda</h1>
        <p class="mgmt-header__subtitle">Visão unificada de eventos, visitas e aconselhamentos</p>
    </div>
    <div class="mgmt-header__actions" style="display:flex; align-items:center; gap: var(--space-4);">
        <button class="btn btn--ghost btn--sm" style="width:36px; height:36px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:50%;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
        <span style="font-size: var(--text-lg); font-weight: 700; color: var(--color-primary);"><?= $nomeMes ?> / <?= $anoAtual ?></span>
        <button class="btn btn--ghost btn--sm" style="width:36px; height:36px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:50%;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
    </div>
</div>

<div style="display:flex; gap: var(--space-3); margin-bottom: var(--space-5);">
    <span style="display:inline-flex; align-items:center; gap:4px; padding:5px 12px; border-radius:6px; font-size:11px; font-weight:700; background:rgba(10,77,255,0.08); color:#0a4dff; text-transform:uppercase; letter-spacing:0.03em;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line></svg> Eventos</span>
    <span style="display:inline-flex; align-items:center; gap:4px; padding:5px 12px; border-radius:6px; font-size:11px; font-weight:700; background:rgba(16,185,129,0.08); color:#10b981; text-transform:uppercase; letter-spacing:0.03em;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg> Visitas</span>
    <span style="display:inline-flex; align-items:center; gap:4px; padding:5px 12px; border-radius:6px; font-size:11px; font-weight:700; background:rgba(124,58,237,0.08); color:#7c3aed; text-transform:uppercase; letter-spacing:0.03em;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg> Aconselhamento</span>
</div>

<div class="mgmt-dashboard-card" style="padding: 0; overflow: hidden;">
    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 0; border-bottom: 1px solid var(--color-border-light);">
        <?php 
        $diasSemana = ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SÁB'];
        foreach ($diasSemana as $dia): ?>
        <div style="padding: 10px; text-align: center; font-weight: 700; font-size: 11px; color: var(--text-muted); letter-spacing:0.05em; border-right: 1px solid var(--color-border-light);"><?= $dia ?></div>
        <?php endforeach; ?>
    </div>

    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 0;">
        <?php
        $primeiroDia = mktime(0, 0, 0, $mesAtual, 1, $anoAtual);
        $diasNoMes = (int)date('t', $primeiroDia);
        $diaSemanaInicio = (int)date('w', $primeiroDia);
        $hoje = (int)date('j');
        $eventColors = ['#0a4dff', '#10b981', '#7c3aed', '#f59e0b', '#ef4444'];
        
        for ($i = 0; $i < $diaSemanaInicio; $i++): ?>
        <div style="padding: 8px; min-height: 90px; border-right: 1px solid var(--color-border-light); border-bottom: 1px solid var(--color-border-light); color: var(--text-muted); opacity:0.4;"></div>
        <?php endfor;
        
        for ($dia = 1; $dia <= $diasNoMes; $dia++): 
            $dataAtual = date('Y-m', $primeiroDia) . '-' . str_pad((string)$dia, 2, '0', STR_PAD_LEFT);
            $eventosNoDia = array_values(array_filter($events ?? [], fn($e) => isset($e['start_date']) && str_starts_with($e['start_date'], $dataAtual)));
            $isHoje = ($dia === $hoje && $mesAtual === (int)date('m') && $anoAtual === (int)date('Y'));
        ?>
        <div style="padding: 8px; min-height: 90px; border-right: 1px solid var(--color-border-light); border-bottom: 1px solid var(--color-border-light); <?= $isHoje ? 'background: rgba(10, 77, 255, 0.04);' : '' ?>">
            <div style="margin-bottom: 4px; <?= $isHoje ? 'display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:50%; background:#0a4dff; color:white; font-weight:700; font-size:13px;' : 'font-size:13px; font-weight:500; color:var(--text-body);' ?>"><?= $dia ?></div>
            <?php foreach (array_slice($eventosNoDia, 0, 2) as $idx => $evento): 
                $evColor = $eventColors[$idx % count($eventColors)];
            ?>
            <div style="font-size: 10px; padding: 2px 6px; background: <?= $evColor ?>; color: white; border-radius: 4px; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display:flex; align-items:center; gap:3px;">
                <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><rect x="3" y="4" width="18" height="18" rx="2"></rect></svg>
                <?= e($evento['title'] ?? '') ?>
            </div>
            <?php endforeach; ?>
            <?php if (count($eventosNoDia) > 2): ?>
            <div style="font-size: 10px; color: var(--text-muted);">+<?= count($eventosNoDia) - 2 ?> mais</div>
            <?php endif; ?>
        </div>
        <?php endfor;
        
        $diasRestantes = (7 - (($diaSemanaInicio + $diasNoMes) % 7)) % 7;
        for ($i = 0; $i < $diasRestantes; $i++): ?>
        <div style="padding: 8px; min-height: 90px; border-right: 1px solid var(--color-border-light); border-bottom: 1px solid var(--color-border-light); opacity:0.4;"></div>
        <?php endfor; ?>
    </div>
</div>
<?php $__view->endSection(); ?>
