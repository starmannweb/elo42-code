<?php $__view->extends('portal'); ?>

<?php $__view->section('content'); ?>
<div class="portal-page portal-page--wide">
    <div class="portal-page-header">
        <div>
            <h2 class="portal-title">Configurações</h2>
            <p class="portal-subtitle">Atualize dados de contato, preferências e notificações do portal.</p>
        </div>
    </div>

    <div class="portal-split">
        <section class="portal-card">
            <div class="portal-card__header">
                <div>
                    <h3 class="portal-card__title">Perfil</h3>
                    <p class="portal-card__subtitle">Dados usados pela equipe da igreja para contato.</p>
                </div>
            </div>
            <div class="portal-card__body">
                <form class="portal-form" method="POST" action="<?= url('/membro/configuracoes/salvar') ?>">
                    <?= csrf_field() ?>
                    <div style="display:flex;align-items:center;gap:16px;">
                        <span class="portal-avatar" style="width:72px;height:72px;font-size:1.4rem;"><?= e(strtoupper(substr($user['name'] ?? 'U', 0, 1))) ?></span>
                        <div>
                            <strong><?= e($user['name'] ?? 'Usuário') ?></strong>
                            <p class="portal-list-card__text" style="margin:2px 0 0;"><?= e($user['email'] ?? '') ?></p>
                        </div>
                    </div>

                    <div class="portal-form-grid">
                        <div class="portal-field">
                            <label class="portal-label" for="name">Nome completo</label>
                            <input class="portal-input" id="name" name="name" value="<?= e($user['name'] ?? '') ?>">
                        </div>
                        <div class="portal-field">
                            <label class="portal-label" for="email">E-mail</label>
                            <input class="portal-input" id="email" name="email" type="email" value="<?= e($user['email'] ?? '') ?>">
                        </div>
                        <div class="portal-field">
                            <label class="portal-label" for="phone">Telefone</label>
                            <input class="portal-input" id="phone" name="phone" value="<?= e($user['phone'] ?? '') ?>">
                        </div>
                        <div class="portal-field">
                            <label class="portal-label" for="birth_date">Data de nascimento</label>
                            <input class="portal-input" id="birth_date" name="birth_date" type="date" value="<?= e($member['birth_date'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="portal-actions">
                        <button class="portal-btn portal-btn--primary" type="submit">Salvar alterações</button>
                    </div>
                </form>
            </div>
        </section>

        <aside class="portal-grid">
            <div class="portal-card">
                <div class="portal-card__body">
                    <h3 class="portal-card__title">Preferências</h3>
                    <p class="portal-list-card__text">Aparência e alertas da área de membros.</p>
                    <div class="portal-list" style="margin-top:18px;">
                        <label class="portal-list-card" style="cursor:pointer;">
                            <input type="checkbox" checked>
                            <span class="portal-list-card__content">
                                <strong class="portal-list-card__title">Notificar eventos</strong>
                                <span class="portal-list-card__text">Receber lembretes de agenda.</span>
                            </span>
                        </label>
                        <label class="portal-list-card" style="cursor:pointer;">
                            <input type="checkbox" checked>
                            <span class="portal-list-card__content">
                                <strong class="portal-list-card__title">Planos de leitura</strong>
                                <span class="portal-list-card__text">Lembretes para manter constância.</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="portal-card">
                <div class="portal-card__body">
                    <h3 class="portal-card__title">Igreja</h3>
                    <p class="portal-list-card__text"><?= e($organization['name'] ?? 'Organização') ?></p>
                    <div class="portal-meta">
                        <span><?= e($organization['plan'] ?? 'premium') ?></span>
                        <span><?= e($organization['role_name'] ?? 'Membro') ?></span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
<?php $__view->endSection(); ?>
