<?php
/**
 * @var string $pageTitle
 * @var string $breadcrumb
 * @var string $firstName
 * @var string $planName
 */
?>
<?php ob_start(); ?>

<div class="mgmt-header">
    <div class="mgmt-header__title">
        <h1>Assinatura e Planos</h1>
        <p class="text-secondary">Gerencie o plano da sua igreja e acesse recursos avançados</p>
    </div>
</div>

<?php if (isset($_SESSION['flash_warning'])): ?>
    <div class="hub-alert hub-alert--warning" style="margin-bottom: 2rem;">
        <span class="hub-alert__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg></span>
        <div class="hub-alert__content">
            <p><?= e((string) $_SESSION['flash_warning']) ?></p>
        </div>
    </div>
    <?php unset($_SESSION['flash_warning']); ?>
<?php endif; ?>

<div class="pricing-container" style="max-width: 900px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">
    
    <!-- PLANO FREE -->
    <div class="pricing-card <?= $planName === 'free' ? 'pricing-card--active' : '' ?>" style="background: var(--surface-primary); border: 1px solid var(--border-color); border-radius: 12px; padding: 2rem; position: relative;">
        <?php if ($planName === 'free'): ?>
            <span style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--text-secondary); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">PLANO ATUAL</span>
        <?php endif; ?>
        
        <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--text-primary);">Plano Free</h2>
        <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1.5rem; min-height: 40px;">Recursos básicos para gerenciar sua comunidade incialmente.</p>
        
        <div style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 2rem;">
            R$ 0<span style="font-size: 1rem; color: var(--text-secondary); font-weight: 400;">/mês</span>
        </div>
        
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
            <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--success);"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Gestão de Membros
            </li>
            <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--success);"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Agenda e Eventos
            </li>
            <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--success);"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Ministérios (Grupos Departamentais)
            </li>
            <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-secondary); opacity: 0.6;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                Sem Gestão Financeira Completa
            </li>
            <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-secondary); opacity: 0.6;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                Sem Dízimos e Ofertas via PIX
            </li>
            <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-secondary); opacity: 0.6;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                Sem Acesso à Geração Expositor IA
            </li>
        </ul>
        
        <?php if ($planName === 'free'): ?>
            <button class="btn btn-secondary" style="width: 100%; margin-top: 2rem;" disabled>Seu Plano Atual</button>
        <?php else: ?>
             <a href="#" class="btn btn-secondary" style="width: 100%; margin-top: 2rem; display: block; text-align: center;">Fazer Downgrade</a>
        <?php endif; ?>
    </div>
    
    <!-- PLANO PREMIUM -->
    <div class="pricing-card pricing-card--premium <?= $planName === 'premium' ? 'pricing-card--active' : '' ?>" style="background: linear-gradient(145deg, var(--surface-primary) 0%, rgba(139, 92, 246, 0.05) 100%); border: 2px solid var(--accent-color); border-radius: 12px; padding: 2rem; position: relative; box-shadow: 0 10px 30px rgba(139, 92, 246, 0.15);">
        
        <span style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--accent-color); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">RECOMENDADO</span>
        
        <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--accent-color); display: flex; align-items: center; gap: 0.5rem;">
            Plano Premium
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
        </h2>
        <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1.5rem; min-height: 40px;">Desbloqueie todo o poder da Elo 42 e prepare sua igreja para crescer sem fronteiras.</p>
        
        <div style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 2rem;">
            R$ 49,90<span style="font-size: 1rem; color: var(--text-secondary); font-weight: 400;">/mês</span>
        </div>
        
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
            <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary); font-weight: 500;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--accent-color);"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Tudo do Plano Free
            </li>
            <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--accent-color);"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Painel Financeiro Completo
            </li>
            <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--accent-color);"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Gestão de Dízimos e Ofertas
            </li>
            <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--accent-color);"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Site para Igreja com Chave PIX
            </li>
            <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--accent-color);"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Criação com Expositor IA Ilimitada
            </li>
            <li style="display: flex; align-items: center; gap: 0.75rem; color: var(--text-primary);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--accent-color);"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Suporte Técnico Prioritário
            </li>
        </ul>
        
        <?php if ($planName === 'premium'): ?>
            <button class="btn btn-primary" style="width: 100%; margin-top: 2rem;" disabled>Plano Ativo</button>
        <?php else: ?>
            <!-- Lógica futura de pagamento (MercadoPago / Stripe / Gateway Próprio) -->
            <button class="btn btn-primary" style="width: 100%; margin-top: 2rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" onclick="alert('Integração de pagamento será ativada na próxima fase!');">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"></path></svg>
                Quero Assinar o Premium
            </button>
        <?php endif; ?>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .pricing-container {
        grid-template-columns: 1fr !important;
    }
}
.pricing-card.pricing-card--active {
    box-shadow: 0 0 0 2px var(--surface-primary), 0 0 0 4px var(--text-secondary);
}
.pricing-card.pricing-card--premium.pricing-card--active {
    box-shadow: 0 0 0 2px var(--surface-primary), 0 0 0 4px var(--accent-color);
}
.pricing-card:hover {
    transform: translateY(-4px);
    transition: transform 0.3s ease;
}
</style>

<?php $content = ob_get_clean(); ?>

<?php
// Layout wrapper
$layoutPath = file_exists(__DIR__ . '/../../layouts/management.php')
    ? __DIR__ . '/../../layouts/management.php'
    : __DIR__ . '/../../../layouts/management.php'; // dependendo do include_path
include $layoutPath;
?>
