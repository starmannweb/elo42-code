<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php
$income = (float) ($financial['income'] ?? 0);
$expense = (float) ($financial['expense'] ?? 0);
$balance = (float) ($financial['balance'] ?? 0);
?>
<section class="mgmt-dashboard">
    <?php if (!empty($isTrialMode)): ?>
        <div class="alert alert--warning" role="alert">
            Você está no período de teste da Gestão de Igrejas. Restam <?= e((string) ($trialDaysLeft ?? 0)) ?> dia(s) para cadastrar a organização.
            <a href="<?= url('/onboarding/organizacao') ?>" class="text-primary font-bold">Concluir cadastro</a>
        </div>
    <?php endif; ?>

    <section class="mgmt-kpi-grid" aria-label="Indicadores">
        <article class="mgmt-kpi-card" style="justify-content:space-between;">
            <div>
                <p class="mgmt-kpi-card__label">Membros ativos</p>
                <p class="mgmt-kpi-card__value"><?= e((string) $totalMembers) ?></p>
                <p class="mgmt-kpi-card__meta"><?= e((string) $newMembers) ?> novo(s) neste mês</p>
            </div>
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path></svg>
            </div>
        </article>
        <article class="mgmt-kpi-card" style="justify-content:space-between;">
            <div>
                <p class="mgmt-kpi-card__label">Eventos ativos</p>
                <p class="mgmt-kpi-card__value"><?= e((string) $activeEvents) ?></p>
                <p class="mgmt-kpi-card__meta">Agenda e comunicação</p>
            </div>
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--indigo" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
        </article>
        <article class="mgmt-kpi-card" style="justify-content:space-between; border-color: rgba(214, 166, 70, 0.3);">
            <div>
                <p class="mgmt-kpi-card__label">Solicitações abertas</p>
                <p class="mgmt-kpi-card__value"><?= e((string) $openRequests) ?></p>
                <p class="mgmt-kpi-card__meta">Pedidos, visitas e atendimento</p>
            </div>
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--gold" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
            </div>
        </article>
        <article class="mgmt-kpi-card" style="justify-content:space-between; border-color: rgba(16, 185, 129, 0.3);">
            <div>
                <p class="mgmt-kpi-card__label">Saldo do mês</p>
                <p class="mgmt-kpi-card__value">R$ <?= e(number_format($balance, 2, ',', '.')) ?></p>
                <p class="mgmt-kpi-card__meta">Receitas menos despesas</p>
            </div>
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2.5 8A2.5 2.5 0 0 1 5 5.5h14A2.5 2.5 0 0 1 21.5 8v8A2.5 2.5 0 0 1 19 18.5H5A2.5 2.5 0 0 1 2.5 16z"></path><path d="M15 12h.01"></path><path d="M2.5 9.5h19"></path></svg>
            </div>
        </article>
    </section>

    <section class="mgmt-dashboard-grid">
        <article class="mgmt-dashboard-card">
            <header class="mgmt-dashboard-card__header" style="margin-bottom: 24px;">
                <h2 style="display:flex;align-items:center;gap:8px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path></svg> Crescimento de Membros</h2>
            </header>
            <div class="mgmt-chart-placeholder-svg" style="height:220px; position:relative; display:flex; gap:12px; padding-right:12px;">
                <div style="display:flex; flex-direction:column; justify-content:space-between; font-size:11px; color:var(--text-muted); padding-bottom:24px; align-items:flex-end; width: 24px;">
                    <span>2,0</span><span>1,8</span><span>1,6</span><span>1,4</span><span>1,2</span><span>1,0</span><span>0,8</span><span>0,6</span><span>0,4</span><span>0,2</span><span>0</span>
                </div>
                <div style="flex:1; position:relative;">
                    <svg width="100%" height="calc(100% - 24px)" preserveAspectRatio="none" viewBox="0 0 100 100" style="overflow:visible; display:block;">
                        <!-- Grid lines -->
                        <line x1="0" y1="10" x2="100" y2="10" stroke="var(--color-border-light)" stroke-width="1" vector-effect="non-scaling-stroke" stroke-dasharray="4 4"/>
                        <line x1="0" y1="20" x2="100" y2="20" stroke="var(--color-border-light)" stroke-width="1" vector-effect="non-scaling-stroke" stroke-dasharray="4 4"/>
                        <line x1="0" y1="30" x2="100" y2="30" stroke="var(--color-border-light)" stroke-width="1" vector-effect="non-scaling-stroke" stroke-dasharray="4 4"/>
                        <line x1="0" y1="40" x2="100" y2="40" stroke="var(--color-border-light)" stroke-width="1" vector-effect="non-scaling-stroke" stroke-dasharray="4 4"/>
                        <line x1="0" y1="50" x2="100" y2="50" stroke="var(--color-border-light)" stroke-width="1" vector-effect="non-scaling-stroke" stroke-dasharray="4 4"/>
                        <line x1="0" y1="60" x2="100" y2="60" stroke="var(--color-border-light)" stroke-width="1" vector-effect="non-scaling-stroke" stroke-dasharray="4 4"/>
                        <line x1="0" y1="70" x2="100" y2="70" stroke="var(--color-border-light)" stroke-width="1" vector-effect="non-scaling-stroke" stroke-dasharray="4 4"/>
                        <line x1="0" y1="80" x2="100" y2="80" stroke="var(--color-border-light)" stroke-width="1" vector-effect="non-scaling-stroke" stroke-dasharray="4 4"/>
                        <line x1="0" y1="90" x2="100" y2="90" stroke="var(--color-border-light)" stroke-width="1" vector-effect="non-scaling-stroke" stroke-dasharray="4 4"/>
                        
                        <!-- Base line X axis -->
                        <line x1="0" y1="100" x2="100" y2="100" stroke="#1e293b" stroke-width="2" vector-effect="non-scaling-stroke"/>
                        
                        <!-- Visitantes Line (Yellow/Orange) -->
                        <path d="M 0 100 L 20 100 L 40 100 C 45 100, 50 10, 60 10 C 70 10, 75 100, 80 100 C 85 100, 90 10, 100 10" fill="none" stroke="#f59e0b" stroke-width="4" vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" />
                        
                        <!-- Membros Line Dots (Dark) -->
                        <?php foreach ([0, 20, 40, 60, 80, 100] as $x): ?>
                        <circle cx="<?= $x ?>" cy="100" r="1.5" fill="#1e293b" stroke="#1e293b" stroke-width="4" vector-effect="non-scaling-stroke"/>
                        <?php endforeach; ?>
                        
                        <!-- Visitantes Line Dots -->
                        <circle cx="0" cy="100" r="1.5" fill="#f59e0b" stroke="#f59e0b" stroke-width="4" vector-effect="non-scaling-stroke"/>
                        <circle cx="20" cy="100" r="1.5" fill="#f59e0b" stroke="#f59e0b" stroke-width="4" vector-effect="non-scaling-stroke"/>
                        <circle cx="40" cy="100" r="1.5" fill="#f59e0b" stroke="#f59e0b" stroke-width="4" vector-effect="non-scaling-stroke"/>
                        <circle cx="60" cy="10" r="1.5" fill="#f59e0b" stroke="#f59e0b" stroke-width="4" vector-effect="non-scaling-stroke"/>
                        <circle cx="80" cy="100" r="1.5" fill="#f59e0b" stroke="#f59e0b" stroke-width="4" vector-effect="non-scaling-stroke"/>
                        <circle cx="100" cy="10" r="1.5" fill="#f59e0b" stroke="#f59e0b" stroke-width="4" vector-effect="non-scaling-stroke"/>
                    </svg>
                    <div style="display:flex; justify-content:space-between; margin-top:12px; font-size:11px; color:var(--text-muted); position:absolute; width:100%; left:0; bottom:0;">
                        <span style="transform:translateX(-50%);">Out</span>
                        <span style="transform:translateX(-50%); position:absolute; left:20%;">Nov</span>
                        <span style="transform:translateX(-50%); position:absolute; left:40%;">Dez</span>
                        <span style="transform:translateX(-50%); position:absolute; left:60%;">Jan</span>
                        <span style="transform:translateX(-50%); position:absolute; left:80%;">Fev</span>
                        <span style="transform:translateX(-50%); position:absolute; left:100%;">Mar</span>
                    </div>
                </div>
            </div>
            <div style="display:flex; justify-content:center; align-items:center; gap:16px; margin-top:32px; font-size:12px; color:var(--text-muted);">
                <span style="display:flex;align-items:center;gap:6px;"><span style="width:14px;height:14px;border-radius:50%;background:#1e293b;"></span> Membros</span>
                <span style="display:flex;align-items:center;gap:6px;"><span style="width:14px;height:14px;border-radius:50%;background:#f59e0b;"></span> Visitantes</span>
            </div>
        </article>

        <article class="mgmt-dashboard-card">
            <header class="mgmt-dashboard-card__header">
                <h2 style="display:flex;align-items:center;gap:8px;"><span style="color:var(--text-muted); font-weight:600;">$</span> Receitas vs Despesas</h2>
            </header>
            <div class="mgmt-chart-placeholder-svg" style="height:200px; position:relative;">
                <svg width="100%" height="100%" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <line x1="0" y1="20" x2="100" y2="20" stroke="var(--color-border-light)" stroke-width="0.5" stroke-dasharray="1 2"/>
                    <line x1="0" y1="40" x2="100" y2="40" stroke="var(--color-border-light)" stroke-width="0.5" stroke-dasharray="1 2"/>
                    <line x1="0" y1="60" x2="100" y2="60" stroke="var(--color-border-light)" stroke-width="0.5" stroke-dasharray="1 2"/>
                    <line x1="0" y1="80" x2="100" y2="80" stroke="var(--color-border-light)" stroke-width="0.5" stroke-dasharray="1 2"/>
                    <line x1="0" y1="100" x2="100" y2="100" stroke="var(--color-border-light)" stroke-width="1"/>
                </svg>
                <div style="display:flex; justify-content:center; align-items:center; gap:16px; margin-top:12px; font-size:11px; color:var(--text-muted);">
                    <span style="display:flex;align-items:center;gap:4px;"><span style="width:10px;height:10px;border-radius:50%;background:#10b981;"></span> Receitas</span>
                    <span style="display:flex;align-items:center;gap:4px;"><span style="width:10px;height:10px;border-radius:50%;background:#ef4444;"></span> Despesas</span>
                </div>
            </div>
        </article>
    </section>

    <section class="mgmt-dashboard-grid">
        <article class="mgmt-dashboard-card" style="display:flex; flex-direction:column;">
            <header class="mgmt-dashboard-card__header">
                <h2 style="display:flex;align-items:center;gap:8px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg> Progresso das Campanhas</h2>
            </header>
            <div class="mgmt-progress-list" style="margin-top:8px; gap:20px;">
                <div class="mgmt-progress-item">
                    <div class="mgmt-progress-item__head" style="margin-bottom:8px;">
                        <span style="font-weight:700; color:var(--color-text-primary);">Reforma do Templo</span>
                        <strong style="color:var(--text-muted);">58%</strong>
                    </div>
                    <div class="progress-bar" style="height:8px; background:var(--color-bg-light);"><div class="progress-bar__fill" style="width:58%; background:#1e3a8a; border-radius:4px;"></div></div>
                    <div style="font-size:11px; color:var(--text-muted); margin-top:6px;">R$ 87.500,00 de R$ 150.000,00</div>
                </div>
                <div class="mgmt-progress-item">
                    <div class="mgmt-progress-item__head" style="margin-bottom:8px;">
                        <span style="font-weight:700; color:var(--color-text-primary);">Missões 2026</span>
                        <strong style="color:var(--text-muted);">64%</strong>
                    </div>
                    <div class="progress-bar" style="height:8px; background:var(--color-bg-light);"><div class="progress-bar__fill" style="width:64%; background:#1e3a8a; border-radius:4px;"></div></div>
                    <div style="font-size:11px; color:var(--text-muted); margin-top:6px;">R$ 32.000,00 de R$ 50.000,00</div>
                </div>
                <div class="mgmt-progress-item">
                    <div class="mgmt-progress-item__head" style="margin-bottom:8px;">
                        <span style="font-weight:700; color:var(--color-text-primary);">Cesta Solidária</span>
                        <strong style="color:var(--text-muted);">85%</strong>
                    </div>
                    <div class="progress-bar" style="height:8px; background:var(--color-bg-light);"><div class="progress-bar__fill" style="width:85%; background:#1e3a8a; border-radius:4px;"></div></div>
                    <div style="font-size:11px; color:var(--text-muted); margin-top:6px;">R$ 8.500,00 de R$ 10.000,00</div>
                </div>
            </div>
        </article>

        <article class="mgmt-dashboard-card" style="display:flex; flex-direction:column;">
            <header class="mgmt-dashboard-card__header">
                <h2 style="display:flex;align-items:center;gap:8px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" color="#0a4dff"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg> Atividade Recente</h2>
            </header>
            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; flex:1; color:var(--text-muted); font-size:13px; margin-top:8px;">
                <div style="margin-bottom:8px; opacity:0.3;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg></div>
                Nenhuma atividade recente
            </div>
        </article>
    </section>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 class="mgmt-section-title" style="margin-bottom: 0;">Visão Geral</h2>
    </div>

    <section class="mgmt-dashboard-grid mgmt-dashboard-grid--compact">
        <article class="mgmt-dashboard-card" style="display:flex; flex-direction:column; justify-content:space-between; min-height:180px;">
            <header class="mgmt-dashboard-card__header">
                <h2 style="display:flex;align-items:center;gap:8px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Próximos Eventos</h2>
                <a href="<?= url('/gestao/eventos') ?>" style="font-size:12px; color:var(--text-muted); text-decoration:none;">Ver todos ›</a>
            </header>
            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; flex:1; color:var(--text-muted); font-size:13px;">
                <div style="margin-bottom:8px; opacity:0.3;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
                Nenhum evento programado
            </div>
        </article>

        <article class="mgmt-dashboard-card" style="display:flex; flex-direction:column; justify-content:space-between; min-height:180px;">
            <header class="mgmt-dashboard-card__header">
                <h2 style="display:flex;align-items:center;gap:8px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" color="#d6a646"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> Solicitações Recentes</h2>
                <a href="<?= url('/gestao/solicitacoes') ?>" style="font-size:12px; color:var(--text-muted); text-decoration:none;">Ver todas ›</a>
            </header>
            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; flex:1; color:var(--text-muted); font-size:13px;">
                <div style="margin-bottom:8px; opacity:0.3;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></div>
                Nenhuma solicitação
            </div>
        </article>
    </section>
</section>
<?php $__view->endSection(); ?>
