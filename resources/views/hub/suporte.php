<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>
<?php
    $supportUser = is_array($user ?? null) ? $user : [];
    $supportOrganization = is_array($organization ?? null) ? $organization : [];
    $whatsappBaseUrl = (string) ($supportWhatsappUrl ?? 'https://wa.me/5511991775458');
?>

<section class="hub-page">
    <header class="hub-page__header">
        <h1 class="hub-page__title">Suporte</h1>
        <p class="hub-page__subtitle">Abra tickets e fale com a equipe pelo WhatsApp para dúvidas técnicas, financeiras e operacionais.</p>
    </header>

    <div class="hub-panel">
        <div class="hub-panel__row">
            <div>
                <h2 class="hub-panel__title">Atendimento rápido</h2>
                <p class="hub-panel__text">WhatsApp oficial: <a href="<?= e($whatsappBaseUrl) ?>" target="_blank" rel="noopener noreferrer" class="text-primary font-bold"><?= e((string) ($supportWhatsapp ?? '(11) 99177-5458')) ?></a></p>
            </div>
            <a href="<?= e($whatsappBaseUrl) ?>" target="_blank" rel="noopener noreferrer" class="btn btn--gold">Conversar no WhatsApp</a>
        </div>

        <form method="POST" action="<?= url('/hub/suporte/tickets') ?>" data-loading data-support-whatsapp-form data-whatsapp-url="<?= e($whatsappBaseUrl) ?>" data-user-name="<?= e((string) ($supportUser['name'] ?? '')) ?>" data-user-email="<?= e((string) ($supportUser['email'] ?? '')) ?>" data-organization-name="<?= e((string) ($supportOrganization['name'] ?? '')) ?>">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('[data-support-whatsapp-form]');
    if (!form) {
        return;
    }

    form.addEventListener('submit', function () {
        var subject = form.querySelector('[name="subject"]')?.value || '';
        var categoryField = form.querySelector('[name="category"]');
        var category = categoryField?.options[categoryField.selectedIndex]?.text || categoryField?.value || '';
        var message = form.querySelector('[name="message"]')?.value || '';
        var userName = form.getAttribute('data-user-name') || '';
        var userEmail = form.getAttribute('data-user-email') || '';
        var organizationName = form.getAttribute('data-organization-name') || '';
        var baseUrl = form.getAttribute('data-whatsapp-url') || 'https://wa.me/5511991775458';
        var text = [
            'Novo ticket Elo 42',
            '',
            'Assunto: ' + subject,
            'Categoria: ' + category,
            'Mensagem: ' + message,
            '',
            'Organizacao: ' + (organizationName || 'Nao informada'),
            'Solicitante: ' + (userName || 'Nao informado'),
            'E-mail: ' + (userEmail || 'Nao informado')
        ].join('\n');

        window.open(baseUrl + (baseUrl.indexOf('?') === -1 ? '?' : '&') + 'text=' + encodeURIComponent(text), '_blank', 'noopener');
    });
});
</script>

<?php $__view->endSection(); ?>
