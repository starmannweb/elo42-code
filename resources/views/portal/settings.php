<?php $__view->extends('layouts/portal'); ?>

<?php $__view->section('content'); ?>
<div class="portal-header">
    <h1 class="portal-title">Configurações</h1>
    <p class="portal-subtitle">Gerencie suas preferências de conta</p>
</div>

<div class="portal-grid" style="grid-template-columns: 1fr;">
    <div class="portal-card">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 1.25rem; color: var(--color-text-primary); margin-top: 0; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Perfil do Usuário
        </h2>

        <form action="<?= url('/membro/configuracoes/salvar') ?>" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
            <?= csrf_field() ?>
            
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--color-primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700;">
                    <?= e(substr($user['name'] ?? 'U', 0, 1)) ?>
                </div>
                <div>
                    <button type="button" class="btn btn--outline" style="margin-bottom: 0.5rem;">Alterar Foto</button>
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">JPG, GIF ou PNG. Máximo de 2MB.</p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--color-text-primary);">Nome Completo</label>
                    <input type="text" name="name" value="<?= e($user['name'] ?? '') ?>" class="form-control" style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-border-light); border-radius: 8px;">
                </div>
                
                <div class="form-group">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--color-text-primary);">E-mail</label>
                    <input type="email" name="email" value="<?= e($user['email'] ?? '') ?>" class="form-control" style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-border-light); border-radius: 8px;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--color-text-primary);">Telefone</label>
                    <input type="tel" name="phone" value="<?= e($user['phone'] ?? '') ?>" class="form-control" style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-border-light); border-radius: 8px;">
                </div>
                
                <div class="form-group">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--color-text-primary);">Data de Nascimento</label>
                    <input type="date" name="birth_date" class="form-control" style="width: 100%; padding: 0.75rem; border: 1px solid var(--color-border-light); border-radius: 8px;">
                </div>
            </div>

            <div style="border-top: 1px solid var(--color-border-light); padding-top: 1.5rem; margin-top: 1rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn--primary">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
