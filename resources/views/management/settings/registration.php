<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">Configurações</h1>
        <p class="mgmt-subtitle">Gerencie as informações principais da sua organização</p>
    </div>
    <div class="mgmt-actions">
        <a href="<?= url('/gestao') ?>" class="btn btn--ghost">Voltar</a>
        <button type="submit" form="form-settings" class="btn btn--primary">Salvar Alterações</button>
    </div>
</div>

<div class="mgmt-dashboard-card" style="max-width: 100%;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px;">
        <div>
            <h2 style="margin:0; font-size:18px; font-weight:700; color:var(--color-text-primary);">Cadastro Público de Membros</h2>
            <p style="margin:4px 0 0 0; font-size:13px; color:var(--text-muted);">Ative uma rota pública onde visitantes preenchem o formulário sem login. Os cadastros chegam com status inativo para você aprovar.</p>
        </div>
        <label style="position:relative; display:inline-block; width:48px; height:24px;">
            <input type="checkbox" style="opacity:0; width:0; height:0;" <?= ($settings['public_registration_active'] ?? '0') === '1' ? 'checked' : '' ?>>
            <span style="position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background-color:var(--color-primary); transition:.4s; border-radius:34px;"></span>
            <span style="position:absolute; content:''; height:18px; width:18px; left:26px; bottom:3px; background-color:white; transition:.4s; border-radius:50%;"></span>
        </label>
    </div>

    <form id="form-settings" method="POST" action="<?= url('/gestao/configuracoes') ?>">
        <?= csrf_field() ?>
        
        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" style="font-weight:600;">Slug da rota pública</label>
            <div style="display:flex; align-items:center;">
                <span style="background:var(--color-bg-light); border:1px solid var(--color-border-light); border-right:none; padding:10px 16px; border-radius:6px 0 0 6px; font-size:14px; color:var(--text-muted);">https://elo42.com.br/</span>
                <input type="text" name="public_registration_slug" class="form-input" value="<?= e($settings['public_registration_slug'] ?? 'cadastro-membro') ?>" style="border-radius:0 6px 6px 0; flex:1;">
            </div>
            <div style="font-size:11px; margin-top:8px; display:flex; justify-content:space-between;">
                <span style="color:var(--color-primary);">Link completo: https://elo42.com.br/cadastro-membro/</span>
                <button type="button" style="background:transparent; border:1px solid var(--color-border-light); color:var(--text-muted); border-radius:4px; padding:2px 8px; font-size:10px; cursor:pointer;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg> Copiar</button>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" style="font-weight:600;">Mensagem de boas-vindas</label>
            <textarea name="public_registration_welcome" class="form-input" rows="3" placeholder="Ex: Preencha com atenção. Logo entraremos em contato."><?= e($settings['public_registration_welcome'] ?? '') ?></textarea>
        </div>

        <div style="background:rgba(217, 119, 6, 0.1); border:1px solid rgba(217, 119, 6, 0.3); border-radius:6px; padding:12px 16px; font-size:12px; color:#d97706; display:flex; align-items:center; gap:8px; margin-bottom:32px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            Proteções ativas: token de sessão 30min, honeypot anti-bot, tempo mínimo de 8s, rate limit de 3 cadastros/hora por IP. Após salvar, o link pode demorar alguns segundos para ficar ativo.
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <div>
                <h3 style="margin:0; font-size:16px; font-weight:700;">Campos do formulário</h3>
                <p style="margin:4px 0 0 0; font-size:12px; color:var(--text-muted);">Marque cada campo como Obrigatório ou Opcional. O campo Nome é sempre obrigatório.</p>
            </div>
        </div>

        <?php
        $fields = [
            'Data de nascimento' => 'birth_date',
            'Sexo' => 'gender',
            'Telefone / WhatsApp' => 'phone',
            'Cidade' => 'city',
            'UF' => 'state',
            'Email' => 'email',
            'Estado civil' => 'marital_status',
            'Endereço' => 'address'
        ];
        ?>
        <div style="border: 1px solid var(--color-border-light); border-radius:8px; overflow:hidden;">
            <?php foreach ($fields as $label => $key): ?>
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px; border-bottom:1px solid var(--color-border-light); background:var(--color-bg-light);">
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <span style="font-size:14px; font-weight:600; color:var(--color-text-primary);"><?= $label ?></span>
                    <span style="font-size:11px; color:var(--color-primary);">Obrigatório</span>
                </div>
                <div style="display:flex; gap:8px;">
                    <button type="button" style="background:transparent; border:1px solid var(--color-border-light); padding:4px 12px; border-radius:16px; font-size:11px; color:var(--text-muted); cursor:pointer;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg> Oculto</button>
                    <button type="button" style="background:transparent; border:1px solid var(--color-border-light); padding:4px 12px; border-radius:16px; font-size:11px; color:var(--text-muted); cursor:pointer;">Opcional</button>
                    <button type="button" style="background:var(--color-primary); border:1px solid var(--color-primary); padding:4px 12px; border-radius:16px; font-size:11px; color:white; font-weight:600; cursor:pointer;">* Obrigatório</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </form>
</div>
<?php $__view->endSection(); ?>