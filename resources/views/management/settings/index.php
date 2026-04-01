<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>

<!-- Tabs de navegação -->
<div style="display: flex; gap: var(--space-4); margin-bottom: var(--space-6); border-bottom: 1px solid var(--color-border); padding-bottom: 0;">
    <button class="btn btn--ghost" style="border-bottom: 2px solid var(--color-gold); border-radius: 0; padding-bottom: var(--space-3); color: var(--color-gold); display: flex; align-items: center; gap: 6px;">⛪ Igreja</button>
    <button class="btn btn--ghost" style="border-radius: 0; padding-bottom: var(--space-3); color: var(--text-muted); display: flex; align-items: center; gap: 6px;">🔍 SEO e Metatags</button>
    <button class="btn btn--ghost" style="border-radius: 0; padding-bottom: var(--space-3); color: var(--text-muted); display: flex; align-items: center; gap: 6px;">📱 App PWA</button>
    <button class="btn btn--ghost" style="border-radius: 0; padding-bottom: var(--space-3); color: var(--text-muted); display: flex; align-items: center; gap: 6px;">🔗 Redes</button>
    <button class="btn btn--ghost" style="border-radius: 0; padding-bottom: var(--space-3); color: var(--text-muted); display: flex; align-items: center; gap: 6px;">🎨 Tema</button>
    <button class="btn btn--ghost" style="border-radius: 0; padding-bottom: var(--space-3); color: var(--text-muted); display: flex; align-items: center; gap: 6px;">⚡ Integrações</button>
</div>

<!-- Formulário de configurações da Igreja -->
<div class="mgmt-dashboard-card" style="max-width: 100%;">
    <form method="POST" action="<?= url('/gestao/configuracoes') ?>">
        <?= csrf_field() ?>
        
        <div style="margin-bottom: var(--space-5);">
            <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-2);">Nome da Igreja</label>
            <input type="text" name="church_name" class="form-input" value="<?= e($settings['church_name'] ?? 'Minha Igreja') ?>" style="width: 100%;">
        </div>

        <div style="margin-bottom: var(--space-5);">
            <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-2);">Slogan</label>
            <input type="text" name="slogan" class="form-input" value="<?= e($settings['slogan'] ?? '') ?>" placeholder="Ex: Conectando vidas ao propósito de Deus" style="width: 100%;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4); margin-bottom: var(--space-5);">
            <div>
                <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-2);">E-mail</label>
                <input type="email" name="email" class="form-input" value="<?= e($settings['email'] ?? '') ?>" placeholder="contato@suaigreja.com" style="width: 100%;">
            </div>
            <div>
                <label style="font-size: var(--text-sm); font-weight: 600; display: block; margin-bottom: var(--space-2);">Telefone</label>
                <input type="tel" name="phone" class="form-input" value="<?= e($settings['phone'] ?? '') ?>" placeholder="(00) 00000-0000" style="width: 100%;">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn--primary">✓ Salvar</button>
        </div>
    </form>
</div>

<!-- Categorias Financeiras (seção adicional) -->
<div class="mgmt-dashboard-card" style="margin-top: var(--space-6);">
    <header class="mgmt-dashboard-card__header">
        <h2>Categorias Financeiras</h2>
    </header>
    
    <?php if (empty($categories)): ?>
        <p style="font-size:var(--text-sm);color:var(--color-text-muted);text-align:center;padding:var(--space-4);">Nenhuma categoria cadastrada.</p>
    <?php else: ?>
        <table class="mgmt-table"><thead><tr><th>Cor</th><th>Nome</th><th>Tipo</th></tr></thead><tbody>
            <?php foreach ($categories as $c): ?><tr>
                <td><span style="width:14px;height:14px;border-radius:50%;background:<?= e($c['color']) ?>;display:inline-block;"></span></td>
                <td><?= e($c['name']) ?></td>
                <td><span class="badge badge--<?= $c['type'] ?>"><?= $c['type'] === 'income' ? 'Entrada' : 'Saída' ?></span></td>
            </tr><?php endforeach; ?>
        </tbody></table>
    <?php endif; ?>

    <form method="POST" action="<?= url('/gestao/financeiro/categoria') ?>" style="margin-top:var(--space-5);padding-top:var(--space-4);border-top:1px solid var(--color-border-light);">
        <?= csrf_field() ?>
        <h4 style="font-size:var(--text-sm);font-weight:700;margin-bottom:var(--space-3);">Adicionar categoria</h4>
        <div style="display:flex;gap:var(--space-3);flex-wrap:wrap;">
            <input type="text" name="name" class="form-input" placeholder="Nome da categoria" required style="flex:1;min-width:150px;">
            <select name="type" class="form-select" required style="max-width:120px;"><option value="income">Entrada</option><option value="expense">Saída</option></select>
            <input type="color" name="color" class="form-input" value="#0A4DFF" style="width:50px;padding:4px;">
            <button type="submit" class="btn btn--primary">Adicionar</button>
        </div>
    </form>
</div>
<?php $__view->endSection(); ?>
