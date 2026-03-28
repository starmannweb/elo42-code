<?php $__view->extends('auth'); ?>

<?php $__view->section('sidebar_title'); ?>
Comece agora.<br>Sua organização merece <span>estrutura</span>.
<?php $__view->endSection(); ?>

<?php $__view->section('sidebar_text'); ?>
Crie sua conta em menos de 2 minutos e tenha acesso a todas as ferramentas do ecossistema Elo 42.
<?php $__view->endSection(); ?>

<?php $__view->section('content'); ?>

<div class="auth-form-container">

    <div class="onboarding-steps">
        <div class="onboarding-step active">
            <span class="onboarding-step__number">1</span>
            <span class="hide-mobile">Sua conta</span>
        </div>
        <div class="onboarding-step__connector"></div>
        <div class="onboarding-step">
            <span class="onboarding-step__number">2</span>
            <span class="hide-mobile">Organização</span>
        </div>
        <div class="onboarding-step__connector"></div>
        <div class="onboarding-step">
            <span class="onboarding-step__number">3</span>
            <span class="hide-mobile">Dashboard</span>
        </div>
    </div>

    <div class="auth-form-container__header">
        <h2 class="auth-form-container__title">Criar sua conta</h2>
        <p class="auth-form-container__subtitle">Preencha os dados abaixo para começar.</p>
    </div>

    <?php if ($error = flash('error')): ?>
        <div class="alert alert--error"><?= e($error) ?></div>
    <?php endif; ?>

    <?php $errors = flash('errors') ?? []; ?>

    <div class="auth-form">
        <form method="POST" action="<?= url('/cadastro') ?>">
            <?= csrf_field() ?>

            <div class="auth-form__row">
                <div class="form-group">
                    <label class="form-label" for="first_name">Nome</label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        class="form-input"
                        value="<?= e(old('first_name')) ?>"
                        placeholder="João"
                        required
                        autofocus
                    >
                    <?php foreach ($errors['first_name'] ?? [] as $err): ?>
                        <p class="form-error"><?= e($err) ?></p>
                    <?php endforeach; ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="last_name">Sobrenome</label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        class="form-input"
                        value="<?= e(old('last_name')) ?>"
                        placeholder="Silva"
                        required
                    >
                    <?php foreach ($errors['last_name'] ?? [] as $err): ?>
                        <p class="form-error"><?= e($err) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input"
                    value="<?= e(old('email')) ?>"
                    placeholder="seu@email.com"
                    required
                >
                <?php foreach ($errors['email'] ?? [] as $err): ?>
                    <p class="form-error"><?= e($err) ?></p>
                <?php endforeach; ?>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Telefone</label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    class="form-input"
                    value="<?= e(old('phone')) ?>"
                    placeholder="(13) 97800-8047"
                    required
                >
                <?php foreach ($errors['phone'] ?? [] as $err): ?>
                    <p class="form-error"><?= e($err) ?></p>
                <?php endforeach; ?>
            </div>

            <div class="auth-form__row">
                <div class="form-group">
                    <label class="form-label" for="password">Senha</label>
                    <div class="form-password">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Mínimo 8 caracteres"
                            required
                            minlength="8"
                        >
                        <button type="button" class="form-password__toggle" data-toggle-password="password" aria-label="Mostrar senha">
                            <svg class="icon-eye" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="icon-eye-off" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirmar senha</label>
                    <div class="form-password">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-input"
                            placeholder="Repita a senha"
                            required
                        >
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="terms" value="1" required>
                    <span class="form-checkbox__label">
                        Li e aceito os <a href="#" onclick="event.preventDefault();document.getElementById('modal-termos').classList.add('active')">Termos de Uso</a> e a <a href="#" onclick="event.preventDefault();document.getElementById('modal-privacidade').classList.add('active')">Política de Privacidade</a> da Elo 42.
                    </span>
                </label>
            </div>

            <div class="auth-form__actions">
                <button type="submit" class="btn btn--primary auth-form__submit">Criar minha conta</button>
            </div>
        </form>
    </div>

    <p class="auth-form__footer">
        Já tem uma conta? <a href="<?= url('/login') ?>">Entrar</a>
    </p>
</div>

<!-- Modal: Termos de Uso -->
<div class="legal-modal" id="modal-termos">
    <div class="legal-modal__backdrop" onclick="this.parentElement.classList.remove('active')"></div>
    <div class="legal-modal__content">
        <div class="legal-modal__header">
            <h2>Termos de Uso</h2>
            <button class="legal-modal__close" onclick="this.closest('.legal-modal').classList.remove('active')" aria-label="Fechar">&times;</button>
        </div>
        <div class="legal-modal__body">
            <p><strong>Última atualização:</strong> 27 de março de 2026</p>
            <h3>1. Aceitação dos Termos</h3>
            <p>Ao acessar e utilizar a plataforma Elo 42, você concorda com estes Termos de Uso. Caso não concorde, não utilize a plataforma.</p>
            <h3>2. Descrição do Serviço</h3>
            <p>A Elo 42 é uma plataforma de gestão, implantação e suporte para igrejas e organizações sem fins lucrativos, oferecendo módulos de gestão de membros, finanças, eventos, comunicação, benefícios e outros serviços relacionados.</p>
            <h3>3. Cadastro e Conta</h3>
            <p>Para utilizar a plataforma, é necessário criar uma conta com informações verdadeiras e atualizadas. Você é responsável pela segurança de suas credenciais de acesso e por todas as atividades realizadas em sua conta.</p>
            <h3>4. Uso Adequado</h3>
            <p>Você se compromete a utilizar a plataforma apenas para fins lícitos e de acordo com sua finalidade. É proibido utilizar a plataforma para atividades ilegais, fraudulentas ou que violem direitos de terceiros.</p>
            <h3>5. Propriedade Intelectual</h3>
            <p>Todo o conteúdo, design, código-fonte, marcas e materiais da plataforma são propriedade da Elo 42 ou de seus licenciadores e estão protegidos por leis de propriedade intelectual.</p>
            <h3>6. Dados e Privacidade</h3>
            <p>O tratamento de dados pessoais é regido pela nossa Política de Privacidade, que é parte integrante destes Termos.</p>
            <h3>7. Limitação de Responsabilidade</h3>
            <p>A Elo 42 não se responsabiliza por danos indiretos, incidentais ou consequenciais decorrentes do uso ou impossibilidade de uso da plataforma.</p>
            <h3>8. Alterações</h3>
            <p>Reservamo-nos o direito de alterar estes Termos a qualquer momento. As alterações entram em vigor a partir da publicação na plataforma.</p>
            <h3>9. Contato</h3>
            <p>Em caso de dúvidas, entre em contato: <strong>suporte@elo42.com.br</strong></p>
        </div>
    </div>
</div>

<!-- Modal: Política de Privacidade -->
<div class="legal-modal" id="modal-privacidade">
    <div class="legal-modal__backdrop" onclick="this.parentElement.classList.remove('active')"></div>
    <div class="legal-modal__content">
        <div class="legal-modal__header">
            <h2>Política de Privacidade</h2>
            <button class="legal-modal__close" onclick="this.closest('.legal-modal').classList.remove('active')" aria-label="Fechar">&times;</button>
        </div>
        <div class="legal-modal__body">
            <p><strong>Última atualização:</strong> 27 de março de 2026</p>
            <h3>1. Dados Coletados</h3>
            <p>Coletamos: nome, e-mail, telefone, dados da organização e informações de uso da plataforma.</p>
            <h3>2. Finalidade do Tratamento</h3>
            <p>Os dados são utilizados para: prestação dos serviços, comunicação com o usuário, melhoria da plataforma e cumprimento de obrigações legais.</p>
            <h3>3. Compartilhamento de Dados</h3>
            <p>Não vendemos ou compartilhamos seus dados com terceiros para fins comerciais. Dados podem ser compartilhados apenas com prestadores essenciais, sob acordo de confidencialidade.</p>
            <h3>4. Armazenamento e Segurança</h3>
            <p>Os dados são armazenados em servidores seguros com criptografia e controle de acesso. Adotamos medidas técnicas e organizacionais para proteção contra acesso não autorizado.</p>
            <h3>5. Seus Direitos (LGPD)</h3>
            <p>Conforme a Lei nº 13.709/2018, você pode: acessar, corrigir, excluir seus dados, revogar consentimento e solicitar portabilidade.</p>
            <h3>6. Cookies</h3>
            <p>Utilizamos cookies essenciais para funcionamento e cookies de análise para melhorar a experiência.</p>
            <h3>7. Retenção de Dados</h3>
            <p>Dados são mantidos pelo período necessário para prestação dos serviços. Após encerramento da conta, serão excluídos em até 90 dias.</p>
            <h3>8. Contato do Encarregado (DPO)</h3>
            <p>Para exercer seus direitos: <strong>suporte@elo42.com.br</strong></p>
        </div>
    </div>
</div>

<?php $__view->endSection(); ?>
