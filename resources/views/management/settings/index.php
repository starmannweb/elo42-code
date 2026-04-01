<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>

<!-- Tabs de navegação -->
<div style="display: flex; gap: var(--space-1); margin-bottom: var(--space-6); flex-wrap: wrap;">
    <a href="<?= url('/gestao/configuracoes?tab=church') ?>" style="padding: 8px 16px; font-size: 12px; font-weight: 600; border-radius: 8px; text-decoration:none; display:flex; align-items:center; gap:6px; background: var(--color-primary); color: white;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> Igreja</a>
    <a href="<?= url('/gestao/configuracoes?tab=seo') ?>" style="padding: 8px 16px; font-size: 12px; font-weight: 600; border-radius: 8px; text-decoration:none; display:flex; align-items:center; gap:6px; color: var(--text-muted);"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> SEO e Metatags</a>
    <a href="<?= url('/gestao/configuracoes?tab=pwa') ?>" style="padding: 8px 16px; font-size: 12px; font-weight: 600; border-radius: 8px; text-decoration:none; display:flex; align-items:center; gap:6px; color: var(--text-muted);"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg> App PWA</a>
    <a href="<?= url('/gestao/configuracoes?tab=social') ?>" style="padding: 8px 16px; font-size: 12px; font-weight: 600; border-radius: 8px; text-decoration:none; display:flex; align-items:center; gap:6px; color: var(--text-muted);"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg> Redes</a>
    <a href="<?= url('/gestao/configuracoes?tab=theme') ?>" style="padding: 8px 16px; font-size: 12px; font-weight: 600; border-radius: 8px; text-decoration:none; display:flex; align-items:center; gap:6px; color: var(--text-muted);"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="13.5" cy="6.5" r="0.5"></circle><circle cx="17.5" cy="10.5" r="0.5"></circle><circle cx="8.5" cy="7.5" r="0.5"></circle><circle cx="6.5" cy="12.5" r="0.5"></circle><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"></path></svg> Tema</a>
    <a href="<?= url('/gestao/configuracoes?tab=integrations') ?>" style="padding: 8px 16px; font-size: 12px; font-weight: 600; border-radius: 8px; text-decoration:none; display:flex; align-items:center; gap:6px; color: var(--text-muted);"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg> Integrações</a>
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
