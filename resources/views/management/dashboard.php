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

    <header class="mgmt-header mgmt-header--stack" style="margin-bottom: 24px;">
        <div class="mgmt-header__intro">
            <h1 class="mgmt-header__title" style="font-size: 1.5rem;">Bem-vindo ao Elo 42</h1>
            <p class="mgmt-header__subtitle" style="font-size: 0.95rem;">Visão geral da sua igreja hoje.</p>
        </div>
    </header>

    <section class="mgmt-kpi-grid" aria-label="Indicadores">
        <article class="mgmt-kpi-card" style="justify-content:space-between;">
            <div>
                <p class="mgmt-kpi-card__label">Total de Membros</p>
                <p class="mgmt-kpi-card__value"><?= e((string) $totalMembers) ?></p>
                <p class="mgmt-kpi-card__meta" style="color: #10b981; display:flex; align-items:center; gap: 4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg> +12% vs mês anterior</p>
            </div>
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path></svg>
            </div>
        </article>
        <article class="mgmt-kpi-card" style="justify-content:space-between;">
            <div>
                <p class="mgmt-kpi-card__label">Eventos Ativos</p>
                <p class="mgmt-kpi-card__value"><?= e((string) $activeEvents) ?></p>
                <p class="mgmt-kpi-card__meta" style="color: #10b981; display:flex; align-items:center; gap: 4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg> +2 vs mês anterior</p>
            </div>
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--indigo" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
        </article>
        <article class="mgmt-kpi-card" style="justify-content:space-between;">
            <div>
                <p class="mgmt-kpi-card__label">Saldo do Mês</p>
                <p class="mgmt-kpi-card__value">R$ <?= e(number_format($balance, 2, ',', '.')) ?></p>
                <p class="mgmt-kpi-card__meta" style="color: #10b981; display:flex; align-items:center; gap: 4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg> +8% vs mês anterior</p>
            </div>
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--green" aria-hidden="true" style="color: #0a4dff; background: rgba(10, 77, 255, 0.1);">
                <span style="font-weight: 700; font-size: 18px;">$</span>
            </div>
        </article>
        <article class="mgmt-kpi-card" style="justify-content:space-between;">
            <div>
                <p class="mgmt-kpi-card__label">Solicitações Pendentes</p>
                <p class="mgmt-kpi-card__value"><?= e((string) $openRequests) ?></p>
                <p class="mgmt-kpi-card__meta" style="color: #ef4444; display:flex; align-items:center; gap: 4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg> -3 vs mês anterior</p>
            </div>
            <div class="mgmt-kpi-card__icon mgmt-kpi-card__icon--blue" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            </div>
        </article>
    </section>

    <section class="mgmt-dashboard-grid mgmt-dashboard-grid--compact">
        <article class="mgmt-dashboard-card" style="display:flex; flex-direction:column; justify-content:space-between; min-height:180px;">
            <header class="mgmt-dashboard-card__header" style="border-bottom: none; margin-bottom: 0;">
                <div>
                    <h2 style="display:flex;align-items:center;gap:8px;">Solicitações Recentes</h2>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">Últimos pedidos de oração, batismo e visitas</p>
                </div>
                <a href="<?= url('/gestao/solicitacoes') ?>" style="font-size:13px; font-weight: 600; color: #111827; text-decoration:none; display: flex; align-items: center; gap: 4px;">Ver todas <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg></a>
            </header>
            
            <div class="mgmt-progress-list" style="margin-top:16px; display: flex; flex-direction: column; gap: 16px;">
                <!-- Mock list mimicking the image -->
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #3b82f6;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        </div>
                        <div>
                            <p style="margin: 0; font-weight: 600; font-size: 0.9rem;">Maria Silva</p>
                            <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);">Oração • Hoje, 14:30</p>
                        </div>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 700; padding: 4px 10px; border-radius: 12px; background: #fef3c7; color: #d97706;">Pendente</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #3b82f6;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        </div>
                        <div>
                            <p style="margin: 0; font-weight: 600; font-size: 0.9rem;">João Pedro</p>
                            <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);">Batismo • Hoje, 10:15</p>
                        </div>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 700; padding: 4px 10px; border-radius: 12px; background: #fef3c7; color: #d97706;">Pendente</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #3b82f6;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        </div>
                        <div>
                            <p style="margin: 0; font-weight: 600; font-size: 0.9rem;">Ana Costa</p>
                            <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);">Visita • Ontem, 18:00</p>
                        </div>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 700; padding: 4px 10px; border-radius: 12px; background: #dbeafe; color: #2563eb;">Em andamento</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #3b82f6;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        </div>
                        <div>
                            <p style="margin: 0; font-weight: 600; font-size: 0.9rem;">Carlos Souza</p>
                            <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);">Oração • Ontem, 09:45</p>
                        </div>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 700; padding: 4px 10px; border-radius: 12px; background: #d1fae5; color: #059669;">Concluído</span>
                </div>
            </div>
        </article>

        <article class="mgmt-dashboard-card" style="display:flex; flex-direction:column; justify-content:flex-start; min-height:180px;">
            <header class="mgmt-dashboard-card__header" style="border-bottom: none; margin-bottom: 0;">
                <div>
                    <h2 style="display:flex;align-items:center;gap:8px;">Próximos Eventos</h2>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">Eventos agendados para os próximos dias</p>
                </div>
                <a href="<?= url('/gestao/eventos') ?>" style="font-size:13px; font-weight: 600; color: #111827; text-decoration:none; display: flex; align-items: center; gap: 4px;">Ver todos <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg></a>
            </header>
            
            <div class="mgmt-progress-list" style="margin-top:16px; display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #e0e7ff; display: flex; align-items: center; justify-content: center; color: #4f46e5;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        <div>
                            <p style="margin: 0; font-weight: 600; font-size: 0.9rem;">Culto de Domingo</p>
                            <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);">19 Jan, 19:00</p>
                        </div>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 600; color: #111827;">45 inscritos</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #e0e7ff; display: flex; align-items: center; justify-content: center; color: #4f46e5;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        <div>
                            <p style="margin: 0; font-weight: 600; font-size: 0.9rem;">Reunião de Líderes</p>
                            <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);">20 Jan, 20:00</p>
                        </div>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 600; color: #111827;">12 inscritos</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #e0e7ff; display: flex; align-items: center; justify-content: center; color: #4f46e5;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        <div>
                            <p style="margin: 0; font-weight: 600; font-size: 0.9rem;">Encontro de Jovens</p>
                            <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);">21 Jan, 19:30</p>
                        </div>
                    </div>
                    <span style="font-size: 0.75rem; font-weight: 600; color: #111827;">28 inscritos</span>
                </div>
            </div>
        </article>
    </section>
</section>
<?php $__view->endSection(); ?>
