<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<?php
    $money = static fn (float $value): string => 'R$ ' . number_format($value, 2, ',', '.');
    $pixKey = trim((string) ($pix['pix_key'] ?? ''));
?>

<div class="portal-page portal-page--wide">
    <div class="portal-page-header">
        <div>
            <h2 class="portal-title">Ofertas</h2>
            <p class="portal-subtitle">Contribua com campanhas cadastradas pela igreja e use o meio de pagamento configurado.</p>
        </div>
    </div>

    <div class="portal-split">
        <section class="portal-grid">
            <?php foreach ($campaigns as $campaign): ?>
                <?php
                    $goal = (float) ($campaign['goal_amount'] ?? 0);
                    $raised = (float) ($campaign['raised_amount'] ?? 0);
                    $progress = (int) ($campaign['progress'] ?? 0);
                    $campaignTitle = (string) ($campaign['title'] ?? 'Campanha');
                    $qrPayload = implode(' | ', array_filter([
                        $pixKey,
                        $campaignTitle,
                        (string) ($campaign['designation'] ?? ''),
                        (string) ($pix['pix_name'] ?? ''),
                    ]));
                    $qrUrl = $pixKey !== ''
                        ? 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' . rawurlencode($qrPayload)
                        : '';
                ?>
                <article class="portal-card portal-campaign-card">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
                        <div>
                            <span class="portal-status portal-status--warning"><?= e($campaign['designation'] ?? 'Campanha') ?></span>
                            <h3 class="portal-card__title" style="margin-top:12px;"><?= e($campaign['title'] ?? 'Campanha') ?></h3>
                            <p class="portal-list-card__text"><?= e($campaign['description'] ?? '') ?></p>
                        </div>
                        <span class="portal-soft-icon" style="background:var(--portal-gold-soft);color:var(--portal-gold);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 12h.01"/></svg>
                        </span>
                    </div>
                    <?php if ($goal > 0): ?>
                        <div>
                            <div style="display:flex;justify-content:space-between;margin-bottom:8px;color:var(--portal-text-soft);font-weight:800;font-size:.85rem;">
                                <span><?= e($money($raised)) ?> arrecadados</span>
                                <span><?= e($money($goal)) ?> meta</span>
                            </div>
                            <div class="portal-progress" style="--progress: <?= $progress ?>%;"><span></span></div>
                        </div>
                    <?php endif; ?>
                    <?php if ($qrUrl !== ''): ?>
                        <div class="portal-campaign-payment">
                            <img class="portal-campaign-qr" src="<?= e($qrUrl) ?>" alt="QR Code PIX para <?= e($campaignTitle) ?>">
                            <div>
                                <strong>QR Code da campanha</strong>
                                <span>Use este código para contribuir diretamente para <?= e($campaignTitle) ?>.</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="portal-actions" style="justify-content:flex-start;">
                        <button class="portal-btn portal-btn--primary" type="button" data-copy-pix="<?= e($pixKey) ?>">Copiar PIX</button>
                        <a class="portal-btn portal-btn--secondary" href="#pix">Dados de pagamento</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <aside class="portal-card" id="pix">
            <div class="portal-card__header">
                <div>
                    <h3 class="portal-card__title">Pagamento</h3>
                    <p class="portal-card__subtitle">Chave PIX configurada pela igreja.</p>
                </div>
            </div>
            <div class="portal-card__body">
                <div class="portal-pix-box">
                    <div class="portal-field">
                        <span class="portal-label">Tipo</span>
                        <strong><?= e($pix['pix_type'] ?? 'PIX') ?></strong>
                    </div>
                    <div class="portal-field" style="margin-top:16px;">
                        <span class="portal-label">Chave PIX</span>
                        <strong style="word-break:break-word;"><?= e($pix['pix_key'] ?: 'Chave PIX ainda não configurada') ?></strong>
                    </div>
                    <div class="portal-field" style="margin-top:16px;">
                        <span class="portal-label">Beneficiário</span>
                        <strong><?= e($pix['pix_name'] ?? '') ?></strong>
                    </div>
                </div>
                <p class="portal-list-card__text" style="margin-top:16px;"><?= e($pix['pix_instruction'] ?? '') ?></p>
            </div>
        </aside>
    </div>
</div>

<?php $__view->section('scripts'); ?>
<script>
    document.querySelectorAll('[data-copy-pix]').forEach(function (button) {
        button.addEventListener('click', function () {
            const value = button.getAttribute('data-copy-pix') || '';
            if (!value || !navigator.clipboard) {
                return;
            }
            navigator.clipboard.writeText(value).then(function () {
                button.textContent = 'PIX copiado';
                setTimeout(function () {
                    button.textContent = 'Copiar PIX';
                }, 1800);
            });
        });
    });
</script>
<?php $__view->endSection(); ?>
<?php $__view->endSection(); ?>
