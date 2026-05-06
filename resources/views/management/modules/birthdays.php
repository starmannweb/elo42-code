<?php $__view->extends('management', ['pageTitle' => $pageTitle ?? 'Aniversários', 'breadcrumb' => $breadcrumb ?? 'Aniversários', 'activeMenu' => $activeMenu ?? 'aniversarios']); ?>

<?php $__view->section('content'); ?>
<?php
    $birthdayCount = count($members ?? []);
    $months = [1 => 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
    $monthAbbr = [1 => 'jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
    $monthLabel = ucfirst($months[(int) date('n')]) . ' de ' . date('Y');
?>

<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-header__title">Aniversariantes do Mês</h1>
            <p class="mgmt-header__subtitle"><?= e($monthLabel) ?></p>
        </div>
        <span class="hub-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M4 21h16"></path><path d="M5 12h14v9H5z"></path><path d="M7 12V9a5 5 0 0 1 10 0v3"></path><path d="M9 7c0-1 .7-2 1.5-3C11.3 5 12 6 12 7"></path><path d="M14 7c0-1 .7-2 1.5-3C16.3 5 17 6 17 7"></path>
            </svg>
            <?= $birthdayCount ?> aniversariante<?= $birthdayCount !== 1 ? 's' : '' ?>
        </span>
    </div>

    <?php if (empty($members)): ?>
        <div class="mgmt-card">
            <div class="mgmt-empty">
                <div class="mgmt-empty__icon" aria-hidden="true">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 21h16"></path><path d="M5 12h14v9H5z"></path><path d="M7 12V9a5 5 0 0 1 10 0v3"></path><path d="M9 7c0-1 .7-2 1.5-3C11.3 5 12 6 12 7"></path><path d="M14 7c0-1 .7-2 1.5-3C16.3 5 17 6 17 7"></path><path d="M5 16c1.5 1 3 1 4.5 0s3-1 4.5 0 3 1 5 0"></path>
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
                            $monthName = $monthAbbr[(int) date('n', strtotime($m['birth_date']))] ?? date('m', strtotime($m['birth_date']));
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
