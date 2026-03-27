<?php $__view->extends('public'); ?>

<?php $__view->section('content'); ?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <h1 class="page-hero__title">Contato</h1>
            <p class="page-hero__subtitle">
                Quer conhecer melhor a plataforma, tirar dúvidas ou iniciar uma parceria?
                Preencha o formulário e nossa equipe retornará em breve.
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-grid animate-on-scroll">
            <div>
                <h2 style="font-size: var(--text-2xl); margin-bottom: var(--space-6);">Fale conosco</h2>

                <div class="contact-info-card">
                    <div class="contact-info-card__icon">📧</div>
                    <div>
                        <h4 class="contact-info-card__title">E-mail</h4>
                        <p class="contact-info-card__text">contato@elo42.com.br</p>
                    </div>
                </div>

                <div class="contact-info-card">
                    <div class="contact-info-card__icon">📱</div>
                    <div>
                        <h4 class="contact-info-card__title">WhatsApp</h4>
                        <p class="contact-info-card__text">(11) 99999-0042</p>
                    </div>
                </div>

                <div class="contact-info-card">
                    <div class="contact-info-card__icon">🕐</div>
                    <div>
                        <h4 class="contact-info-card__title">Horário de atendimento</h4>
                        <p class="contact-info-card__text">Seg a Sex, 9h às 18h</p>
                    </div>
                </div>
            </div>

            <div>
                <?php if ($success = flash('success')): ?>
                    <div class="alert alert--success"><?= e($success) ?></div>
                <?php endif; ?>

                <?php if ($error = flash('error')): ?>
                    <div class="alert alert--error"><?= e($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= url('/contato') ?>">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="form-label" for="name">Nome completo</label>
                        <input type="text" id="name" name="name" class="form-input" value="<?= e(old('name')) ?>" required>
                        <?php if ($errors = flash('errors')): ?>
                            <?php foreach ($errors['name'] ?? [] as $error): ?>
                                <p class="form-error"><?= e($error) ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4);">
                        <div class="form-group">
                            <label class="form-label" for="email">E-mail</label>
                            <input type="email" id="email" name="email" class="form-input" value="<?= e(old('email')) ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="phone">Telefone</label>
                            <input type="tel" id="phone" name="phone" class="form-input" value="<?= e(old('phone')) ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="subject">Assunto</label>
                        <select id="subject" name="subject" class="form-select" required>
                            <option value="">Selecione...</option>
                            <option value="conhecer" <?= old('subject') === 'conhecer' ? 'selected' : '' ?>>Quero conhecer a plataforma</option>
                            <option value="demonstracao" <?= old('subject') === 'demonstracao' ? 'selected' : '' ?>>Solicitar demonstração</option>
                            <option value="consultoria" <?= old('subject') === 'consultoria' ? 'selected' : '' ?>>Consultoria</option>
                            <option value="parceria" <?= old('subject') === 'parceria' ? 'selected' : '' ?>>Proposta de parceria</option>
                            <option value="suporte" <?= old('subject') === 'suporte' ? 'selected' : '' ?>>Suporte técnico</option>
                            <option value="outro" <?= old('subject') === 'outro' ? 'selected' : '' ?>>Outro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="message">Mensagem</label>
                        <textarea id="message" name="message" class="form-textarea" required><?= e(old('message')) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn--primary btn--lg" style="width: 100%;">Enviar mensagem</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php $__view->endSection(); ?>
