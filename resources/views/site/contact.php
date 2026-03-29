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
                    <div class="contact-info-card__icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <path d="M3 7l9 6 9-6"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="contact-info-card__title">E-mail</h4>
                        <p class="contact-info-card__text">
                            <a class="contact-info-card__link" href="mailto:suporte@elo42.com.br">suporte@elo42.com.br</a>
                        </p>
                    </div>
                </div>

                <div class="contact-info-card">
                    <div class="contact-info-card__icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M16.75 13.96C17 14.09 18.24 14.7 18.5 14.83C18.76 14.96 18.95 15.02 19 15.11C19.05 15.2 19.05 15.65 18.88 16.1C18.71 16.55 17.88 16.96 17.5 17.03C17.13 17.1 16.66 17.13 15.5 16.64C14.07 16.04 13.16 15.44 12.26 14.53C11.56 13.83 10.95 12.98 10.35 11.95C9.75 10.93 9.84 10.38 10.22 10C10.59 9.63 11.03 9.06 11.14 8.8C11.25 8.54 11.2 8.31 11.12 8.16C11.03 8 10.35 6.37 10.08 5.72C9.82 5.08 9.56 5.16 9.36 5.16C9.16 5.16 8.93 5.14 8.7 5.14C8.47 5.14 8.08 5.23 7.74 5.57C7.4 5.91 6.44 6.81 6.44 8.63C6.44 10.45 7.77 12.22 7.96 12.47C8.15 12.72 10.62 16.47 14.5 18.13C15.43 18.53 16.15 18.77 16.72 18.95C17.65 19.24 18.5 19.2 19.17 19.1C19.91 19 21.43 18.2 21.75 17.29C22.06 16.38 22.06 15.6 21.97 15.45C21.88 15.3 21.66 15.22 21.41 15.09C21.16 14.96 19.94 14.36 19.71 14.27C19.47 14.18 19.31 14.14 19.14 14.4C18.97 14.65 18.5 15.2 18.35 15.38C18.2 15.56 18.04 15.58 17.79 15.45C17.54 15.32 16.75 13.96 16.75 13.96M12.04 2C6.53 2 2.04 6.49 2.04 12C2.04 13.76 2.5 15.46 3.38 16.97L2 22L7.19 20.64C8.65 21.43 10.3 21.96 12.04 21.96C17.55 21.96 22.04 17.47 22.04 11.96C22.04 6.45 17.55 2 12.04 2Z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="contact-info-card__title">WhatsApp</h4>
                        <p class="contact-info-card__text">
                            <a class="contact-info-card__link" href="https://wa.me/5513978008047" target="_blank" rel="noopener noreferrer">(13) 97800-8047</a>
                        </p>
                    </div>
                </div>

                <div class="contact-info-card">
                    <div class="contact-info-card__icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v5l3 3"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="contact-info-card__title">Horário de atendimento</h4>
                        <p class="contact-info-card__text">Segunda a sexta, das 9h às 18h</p>
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
