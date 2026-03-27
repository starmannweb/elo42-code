<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<!-- Greeting -->
<div class="dashboard-greeting">
    <h1 class="dashboard-greeting__title">
        <?= e($greeting) ?>, <?= e($firstName) ?>! 👋
    </h1>
    <p class="dashboard-greeting__subtitle">
        <?php if ($organization): ?>
            Acompanhe tudo sobre a <?= e($organization['name']) ?> no painel abaixo.
        <?php else: ?>
            Bem-vindo ao Elo 42. Configure sua organização para começar.
        <?php endif; ?>
    </p>
</div>

<!-- Status Cards -->
<div class="dashboard-status-grid">
    <div class="status-card status-card--blue">
        <div class="status-card__header">
            <span class="status-card__icon">👤</span>
            <span class="status-card__badge status-card__badge--active">Ativa</span>
        </div>
        <div class="status-card__value"><?= e($user['name']) ?></div>
        <div class="status-card__label">Sua conta</div>
    </div>

    <?php if ($organization): ?>
        <div class="status-card status-card--gold">
            <div class="status-card__header">
                <span class="status-card__icon">🏢</span>
                <span class="status-card__badge status-card__badge--<?= $organization['status'] === 'trial' ? 'trial' : 'active' ?>">
                    <?= $organization['status'] === 'trial' ? 'Trial' : 'Ativa' ?>
                </span>
            </div>
            <div class="status-card__value"><?= e($organization['name']) ?></div>
            <div class="status-card__label">Sua organização</div>
        </div>

        <div class="status-card status-card--green">
            <div class="status-card__header">
                <span class="status-card__icon">🔑</span>
            </div>
            <div class="status-card__value"><?= e($organization['role_name'] ?? 'Usuário') ?></div>
            <div class="status-card__label">Seu perfil de acesso</div>
        </div>

        <div class="status-card status-card--purple">
            <div class="status-card__header">
                <span class="status-card__icon">📋</span>
            </div>
            <div class="status-card__value">
                <?= ucfirst(e($organization['plan'] ?? 'Basic')) ?>
            </div>
            <div class="status-card__label">Plano atual</div>
        </div>
    <?php else: ?>
        <div class="status-card status-card--gold">
            <div class="status-card__header">
                <span class="status-card__icon">🏢</span>
                <span class="status-card__badge status-card__badge--pending">Pendente</span>
            </div>
            <div class="status-card__value">Nenhuma</div>
            <div class="status-card__label">
                <a href="<?= url('/onboarding/organizacao') ?>" class="text-primary font-semibold">
                    Cadastrar organização →
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Next Steps -->
<div class="dashboard-section" style="margin-bottom: var(--space-8)">
    <h2 class="dashboard-section__title">Primeiros passos</h2>
    <div class="next-steps">
        <div class="next-step-item done">
            <span class="next-step-item__check done">✓</span>
            <div class="next-step-item__content">
                <div class="next-step-item__title">Criar sua conta</div>
                <div class="next-step-item__desc">Cadastro realizado com sucesso</div>
            </div>
        </div>

        <div class="next-step-item <?= $user['email_verified_at'] ? 'done' : '' ?>">
            <span class="next-step-item__check <?= $user['email_verified_at'] ? 'done' : '' ?>">
                <?= $user['email_verified_at'] ? '✓' : '2' ?>
            </span>
            <div class="next-step-item__content">
                <div class="next-step-item__title">Verificar seu e-mail</div>
                <div class="next-step-item__desc">
                    <?= $user['email_verified_at']
                        ? 'E-mail verificado'
                        : 'Verifique o e-mail enviado para ' . e($user['email'])
                    ?>
                </div>
            </div>
        </div>

        <div class="next-step-item <?= $organization ? 'done' : '' ?>">
            <span class="next-step-item__check <?= $organization ? 'done' : '' ?>">
                <?= $organization ? '✓' : '3' ?>
            </span>
            <div class="next-step-item__content">
                <div class="next-step-item__title">Cadastrar sua organização</div>
                <div class="next-step-item__desc">
                    <?= $organization
                        ? 'Organização cadastrada: ' . e($organization['name'])
                        : 'Configure os dados da sua igreja ou organização'
                    ?>
                </div>
            </div>
        </div>

        <div class="next-step-item">
            <span class="next-step-item__check">4</span>
            <div class="next-step-item__content">
                <div class="next-step-item__title">Cadastrar os primeiros membros</div>
                <div class="next-step-item__desc">Adicione membros da sua equipe à plataforma</div>
            </div>
        </div>

        <div class="next-step-item">
            <span class="next-step-item__check">5</span>
            <div class="next-step-item__content">
                <div class="next-step-item__title">Explorar os módulos</div>
                <div class="next-step-item__desc">Conheça as ferramentas de gestão disponíveis</div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<?php if ($organization): ?>
    <div>
        <h2 class="dashboard-section__title">Atalhos rápidos</h2>
        <div class="quick-actions-grid">
            <a href="<?= url('/gestao') ?>" class="quick-action">
                <span class="quick-action__icon quick-action__icon--blue">📊</span>
                <div>
                    <div class="quick-action__text">Gestão</div>
                    <div class="quick-action__desc">Painel de gestão da igreja</div>
                </div>
            </a>

            <a href="<?= url('/gestao/membros') ?>" class="quick-action">
                <span class="quick-action__icon quick-action__icon--blue">👥</span>
                <div>
                    <div class="quick-action__text">Membros</div>
                    <div class="quick-action__desc">Gerenciar membros</div>
                </div>
            </a>

            <a href="<?= url('/gestao/financeiro') ?>" class="quick-action">
                <span class="quick-action__icon quick-action__icon--gold">💰</span>
                <div>
                    <div class="quick-action__text">Finanças</div>
                    <div class="quick-action__desc">Controle financeiro</div>
                </div>
            </a>

            <a href="<?= url('/gestao/eventos') ?>" class="quick-action">
                <span class="quick-action__icon quick-action__icon--green">📅</span>
                <div>
                    <div class="quick-action__text">Eventos</div>
                    <div class="quick-action__desc">Agenda e eventos</div>
                </div>
            </a>

            <a href="<?= url('/gestao/relatorios') ?>" class="quick-action">
                <span class="quick-action__icon quick-action__icon--purple">📊</span>
                <div>
                    <div class="quick-action__text">Relatórios</div>
                    <div class="quick-action__desc">Dashboards e dados</div>
                </div>
            </a>

            <a href="<?= url('/gestao/configuracoes') ?>" class="quick-action">
                <span class="quick-action__icon quick-action__icon--gold">⚙️</span>
                <div>
                    <div class="quick-action__text">Configurações</div>
                    <div class="quick-action__desc">Configurar gestão</div>
                </div>
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert--info" role="alert">
        <span>🏢 <strong>Cadastre sua organização</strong> para ter acesso a todos os módulos da plataforma.
        <a href="<?= url('/onboarding/organizacao') ?>" class="text-primary font-bold"> Cadastrar agora →</a></span>
    </div>
<?php endif; ?>

<?php $__view->endSection(); ?>
