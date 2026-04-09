<?php $__view->extends('layouts/portal'); ?>

<?php $__view->section('content'); ?>
<div style="max-width: 600px;">
    <div style="background: #fff; border: 1px solid #f3f4f6; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.25rem; margin: 0 0 1.5rem; color: #111827;">Meu Perfil</h3>
        
        <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: #e5e7eb; color: #4b5563; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 600;">
                <?php
                    $parts = explode(' ', (string) ($user['name'] ?? ''));
                    $initials = strtoupper(substr((string) ($parts[0] ?? 'U'), 0, 1) . substr((string) (end($parts) ?: 'U'), 0, 1));
                    echo e($initials);
                ?>
            </div>
            <div>
                <button style="background: #fff; border: 1px solid #d1d5db; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 500; font-size: 0.85rem; cursor: pointer; color: #374151;">Alterar foto</button>
            </div>
        </div>

        <form>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Nome completo</label>
                <input type="text" value="<?= e($user['name'] ?? '') ?>" style="width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid #d1d5db; font-size: 0.95rem; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">E-mail</label>
                <input type="email" value="<?= e($user['email'] ?? '') ?>" disabled style="width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid #e5e7eb; background: #f9fafb; font-size: 0.95rem; color: #6b7280; box-sizing: border-box;">
                <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.5rem;">O e-mail não pode ser alterado.</div>
            </div>
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Telefone / WhatsApp</label>
                <input type="text" value="<?= e($user['phone'] ?? '') ?>" style="width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid #d1d5db; font-size: 0.95rem; box-sizing: border-box;">
            </div>

            <button type="button" style="background: #1e3a8a; color: white; border: none; padding: 0.875rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer;">Salvar Alterações</button>
        </form>
    </div>

    <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
        <form action="<?= url('/logout') ?>" method="POST">
            <?= csrf_field() ?>
            <button type="submit" style="background: none; border: none; color: #ef4444; font-weight: 600; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Sair da minha conta
            </button>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
