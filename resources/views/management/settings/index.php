<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<div class="mgmt-header"><div><h1 class="mgmt-header__title">Configurações</h1><p class="mgmt-header__subtitle">Configurações do módulo de gestão</p></div></div>

<div class="mgmt-info-card" style="max-width:720px;">
    <h3 class="mgmt-info-card__title">Categorias financeiras</h3>
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

<div class="mgmt-info-card" style="max-width:720px;margin-top:var(--space-5);">
    <h3 class="mgmt-info-card__title">Sobre o módulo</h3>
    <div class="mgmt-info-row"><span class="mgmt-info-row__label">Versão</span><span class="mgmt-info-row__value">1.0.0</span></div>
    <div class="mgmt-info-row"><span class="mgmt-info-row__label">Módulos ativos</span><span class="mgmt-info-row__value">Membros, Ministérios, Eventos, Financeiro, Solicitações, Visitas, Aconselhamento, Sermões, Planos, Doações, Relatórios</span></div>
</div>
<?php $__view->endSection(); ?>
