<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>
<?php
    $supportUser = is_array($user ?? null) ? $user : [];
    $supportOrganization = is_array($organization ?? null) ? $organization : [];
?>

<section class="hub-page">
    <header class="hub-page__header">
        <h1 class="hub-page__title">Suporte</h1>
        <p class="hub-page__subtitle">Abra tickets para dúvidas técnicas, financeiras e operacionais e nossa equipe responderá em seguida.</p>
    </header>

    <div class="hub-panel">
        <div>
            <h2 class="hub-panel__title">Atendimento rápido</h2>
            <p class="hub-panel__text">Preencha o formulário abaixo e a equipe Elo 42 retornará por e-mail.</p>
        </div>

        <form method="POST" action="<?= url('/hub/suporte/tickets') ?>" data-loading>
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="support-subject">Assunto</label>
                <input id="support-subject" type="text" name="subject" class="form-input" placeholder="Descreva brevemente" required>
            </div>

            <div class="hub-form-grid">
                <div class="form-group">
                    <label class="form-label" for="support-category">Categoria</label>
                    <select id="support-category" name="category" class="form-select">
                        <?php foreach (($categories ?? []) as $category): ?>
                            <option value="<?= e((string) ($category['value'] ?? 'general')) ?>"><?= e((string) ($category['label'] ?? 'Geral')) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div></div>
            </div>

            <div class="form-group">
                <label class="form-label" for="support-message">Mensagem</label>
                <textarea id="support-message" name="message" class="form-textarea" placeholder="Descreva o problema ou dúvida" required></textarea>
            </div>

            <button type="submit" class="btn btn--gold">Enviar ticket</button>
        </form>
    </div>

    <div class="hub-panel">
        <h2 class="hub-panel__title">Tickets</h2>

        <?php if (!empty($tickets)): ?>
            <div class="table-responsive">
                <table class="table table--hub">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Assunto</th>
                            <th>Categoria</th>
                            <th>Status</th>
                            <th>Abertura</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (($tickets ?? []) as $ticket): ?>
                            <tr>
                                <td>#<?= e((string) ($ticket['id'] ?? '-')) ?></td>
                                <td><?= e((string) ($ticket['subject'] ?? '-')) ?></td>
                                <td><?= e((string) (($ticket['category_label'] ?? $ticket['category']) ?? '-')) ?></td>
                                <td><?= e((string) (($ticket['status_label'] ?? $ticket['status']) ?? '-')) ?></td>
                                <td><?= e((string) ($ticket['created_at'] ?? '-')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="hub-mini-card__text">Nenhum ticket aberto ainda.</p>
        <?php endif; ?>
    </div>
</section>

<?php $__view->endSection(); ?>
