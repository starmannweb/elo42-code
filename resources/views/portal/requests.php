<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<?php
    $statusLabel = [
        'open' => 'Aberta',
        'in_progress' => 'Em acompanhamento',
        'resolved' => 'Resolvida',
        'closed' => 'Encerrada',
    ];
    $statusClass = [
        'open' => 'portal-status--warning',
        'in_progress' => 'portal-status--neutral',
        'resolved' => 'portal-status--success',
        'closed' => 'portal-status--neutral',
    ];
    $requestIcon = static function (string $name): string {
        return match ($name) {
            'heart' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/></svg>',
            'droplet' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2.5S6 9 6 14a6 6 0 0 0 12 0c0-5-6-11.5-6-11.5z"/></svg>',
            'package' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21 8-9-5-9 5 9 5 9-5z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/></svg>',
            'home' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 10.5 9-7 9 7V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/></svg>',
            'users' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            default => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/></svg>',
        };
    };
?>

<div class="portal-page portal-page--wide">
    <div class="portal-page-header">
        <div>
            <h2 class="portal-title">Solicitações</h2>
            <p class="portal-subtitle">Envie pedidos de oração, batismo, apoio, visita ou atendimento pastoral.</p>
        </div>
    </div>

    <div class="portal-split">
        <section class="portal-grid">
            <div class="portal-grid portal-grid--2">
                <?php foreach ($requestTypes as $key => $type): ?>
                    <button type="button" class="portal-list-card" data-request-type="<?= e($key) ?>" style="text-align:left;cursor:pointer;">
                        <span class="portal-soft-icon">
                            <?= $requestIcon((string) ($type['icon'] ?? 'message')) ?>
                        </span>
                        <span class="portal-list-card__content">
                            <strong class="portal-list-card__title"><?= e($type['title']) ?></strong>
                            <span class="portal-list-card__text"><?= e($type['subtitle']) ?></span>
                        </span>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="portal-card">
                <div class="portal-card__header">
                    <div>
                        <h3 class="portal-card__title">Minhas solicitações</h3>
                        <p class="portal-card__subtitle">Acompanhe o andamento dos pedidos enviados.</p>
                    </div>
                </div>
                <div class="portal-card__body">
                    <?php if (empty($requests)): ?>
                        <div class="portal-empty" style="min-height:190px;">
                            <div>
                                <span class="portal-empty__icon">
                                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 15h6"/></svg>
                                </span>
                                <h4 class="portal-empty__title">Nenhuma solicitação enviada</h4>
                                <p class="portal-empty__text">Escolha um tipo de pedido e envie as informações para a equipe da igreja.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="portal-list">
                            <?php foreach ($requests as $item): ?>
                                <?php $status = (string) ($item['status'] ?? 'open'); ?>
                                <?php $type = (string) ($item['type'] ?? 'general'); ?>
                                <?php $iconName = (string) ($requestTypes[$type]['icon'] ?? 'message'); ?>
                                <article class="portal-list-card">
                                    <span class="portal-soft-icon">
                                        <?= $requestIcon($iconName) ?>
                                    </span>
                                    <div class="portal-list-card__content">
                                        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                                            <h4 class="portal-list-card__title"><?= e($item['title'] ?? 'Solicitação') ?></h4>
                                            <span class="portal-status <?= e($statusClass[$status] ?? 'portal-status--neutral') ?>"><?= e($statusLabel[$status] ?? $status) ?></span>
                                        </div>
                                        <p class="portal-list-card__text"><?= e($item['description'] ?? '') ?></p>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <aside class="portal-card">
            <div class="portal-card__header">
                <div>
                    <h3 class="portal-card__title">Nova solicitação</h3>
                    <p class="portal-card__subtitle">Descreva o pedido com clareza para facilitar o atendimento.</p>
                </div>
            </div>
            <div class="portal-card__body">
                <form class="portal-form" method="POST" action="<?= url('/membro/solicitacoes') ?>">
                    <?= csrf_field() ?>
                    <div class="portal-field">
                        <label class="portal-label" for="type">Tipo</label>
                        <select class="portal-select" id="type" name="type">
                            <?php foreach ($requestTypes as $key => $type): ?>
                                <option value="<?= e($key) ?>"><?= e($type['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="portal-field">
                        <label class="portal-label" for="title">Título</label>
                        <input class="portal-input" id="title" name="title" type="text" placeholder="Ex: Pedido de oração pela família">
                    </div>
                    <div class="portal-field">
                        <label class="portal-label" for="description">Detalhes</label>
                        <textarea class="portal-textarea" id="description" name="description" required placeholder="Escreva aqui sua solicitação"></textarea>
                    </div>
                    <div class="portal-field">
                        <label class="portal-label" for="priority">Prioridade</label>
                        <select class="portal-select" id="priority" name="priority">
                            <option value="normal">Normal</option>
                            <option value="high">Alta</option>
                            <option value="urgent">Urgente</option>
                            <option value="low">Baixa</option>
                        </select>
                    </div>
                    <button class="portal-btn portal-btn--primary" type="submit">Enviar solicitação</button>
                </form>
            </div>
        </aside>
    </div>
</div>

<?php $__view->section('scripts'); ?>
<script>
    document.querySelectorAll('[data-request-type]').forEach(function (button) {
        button.addEventListener('click', function () {
            const select = document.getElementById('type');
            if (select) {
                select.value = button.getAttribute('data-request-type');
                document.getElementById('description')?.focus();
            }
        });
    });
</script>
<?php $__view->endSection(); ?>
<?php $__view->endSection(); ?>
