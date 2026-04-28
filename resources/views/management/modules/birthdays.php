<?php $__view->extends('management', ['pageTitle' => $pageTitle ?? 'Aniversários', 'breadcrumb' => $breadcrumb ?? 'Aniversários', 'activeMenu' => $activeMenu ?? 'aniversarios']); ?>

<?php $__view->section('content'); ?>
<?php $birthdayCount = count($members ?? []); ?>

<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-header__title">Aniversariantes do Mês</h1>
            <p class="mgmt-header__subtitle"><?= e(date('F Y')) ?></p>
        </div>
        <span class="hub-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M20 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M4 21v-2a4 4 0 0 1 3-3.87"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <?= $birthdayCount ?> aniversariante<?= $birthdayCount !== 1 ? 's' : '' ?>
        </span>
    </div>

    <?php if (empty($members)): ?>
        <div class="mgmt-card">
            <div class="mgmt-empty">
                <div class="mgmt-empty__icon" aria-hidden="true">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M4 21v-2a4 4 0 0 1 3-3.87"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <h2 class="mgmt-empty__title">Nenhum aniversariante este mês</h2>
                <p class="mgmt-empty__text">Cadastre membros com data de nascimento para visualizar os aniversariantes.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert--warning" role="status">
            <strong>Lembrete pastoral:</strong> não se esqueça de parabenizar os aniversariantes do mês. Um pequeno gesto de carinho e uma oração podem fazer diferença.
        </div>

        <div class="mgmt-card">
            <div class="mgmt-card__body">
                <div class="mgmt-list">
                    <?php foreach ($members as $m): ?>
                        <?php
                            $day = date('d', strtotime($m['birth_date']));
                            $monthName = date('M', strtotime($m['birth_date']));
                            $isToday = date('m-d') === date('m-d', strtotime($m['birth_date']));
                            $parts = explode(' ', trim($m['name'] ?? 'Membro'));
                            $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . substr(end($parts) ?: '', 0, 1));
                        ?>
                        <div class="mgmt-list__item <?= $isToday ? 'mgmt-list__item--featured' : '' ?>">
                            <div class="avatar"><?= e($initials) ?></div>
                            <div class="mgmt-list__content">
                                <strong><?= e($m['name'] ?? 'Membro') ?></strong>
                                <span><?= e($m['email'] ?? $m['phone'] ?? '') ?></span>
                            </div>
                            <?php if ($isToday): ?>
                                <span class="badge badge-warning">Hoje</span>
                            <?php endif; ?>
                            <div class="mgmt-list__date">
                                <strong><?= e($day) ?></strong>
                                <span><?= e($monthName) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__view->endSection(); ?>
