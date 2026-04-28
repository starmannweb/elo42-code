<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Pregadores</h1>
            <p class="mgmt-subtitle">Cadastre pastores, líderes e convidados para usar nos sermões e ministrações.</p>
        </div>
        <button type="submit" form="form-preacher" class="btn btn--primary">Adicionar pregador</button>
    </div>

    <div class="mgmt-grid" style="grid-template-columns: minmax(320px, .8fr) minmax(0, 1.2fr); gap: 1.25rem;">
        <section class="mgmt-panel">
            <h2 class="mgmt-card__title">Novo pregador</h2>
            <form id="form-preacher" method="POST" action="<?= url('/gestao/pregadores') ?>" style="margin-top:1rem;">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label class="form-label" for="preacher_name">Nome</label>
                    <input id="preacher_name" name="name" class="form-control" placeholder="Ex: Pr. João Silva" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="preacher_unit">Unidade</label>
                    <select id="preacher_unit" name="church_unit_id" class="form-control">
                        <option value="">Geral / todas as unidades</option>
                        <?php foreach (($units ?? []) as $unit): ?>
                            <option value="<?= (int) $unit['id'] ?>"><?= e((string) $unit['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mgmt-grid" style="grid-template-columns: 1fr 1fr; gap: .875rem;">
                    <div class="form-group">
                        <label class="form-label" for="preacher_email">E-mail</label>
                        <input id="preacher_email" name="email" type="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="preacher_phone">Telefone</label>
                        <input id="preacher_phone" name="phone" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="preacher_bio">Observações</label>
                    <textarea id="preacher_bio" name="bio" class="form-control" rows="4" placeholder="Linha ministerial, função, disponibilidade..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="preacher_status">Status</label>
                    <select id="preacher_status" name="status" class="form-control">
                        <option value="active">Ativo</option>
                        <option value="inactive">Inativo</option>
                    </select>
                </div>
            </form>
        </section>

        <section class="mgmt-card">
            <div class="mgmt-card__body">
                <?php if (empty($preachers)): ?>
                    <div class="mgmt-empty">
                        <h3 class="mgmt-empty__title">Nenhum pregador cadastrado</h3>
                        <p class="mgmt-empty__text">Cadastre pregadores para padronizar os sermões e facilitar a publicação para os membros.</p>
                    </div>
                <?php else: ?>
                    <div class="mgmt-table-container">
                        <table class="mgmt-table">
                            <thead>
                                <tr>
                                    <th>Pregador</th>
                                    <th>Unidade</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($preachers as $preacher): ?>
                                    <tr>
                                        <td>
                                            <div class="mgmt-table__name"><?= e((string) ($preacher['name'] ?? 'Pregador')) ?></div>
                                            <small style="color:var(--text-muted);"><?= e((string) (($preacher['email'] ?? '') ?: ($preacher['phone'] ?? ''))) ?></small>
                                        </td>
                                        <td><?= e((string) (($preacher['unit_name'] ?? '') ?: 'Geral')) ?></td>
                                        <td><span class="badge"><?= (string) ($preacher['status'] ?? 'active') === 'active' ? 'Ativo' : 'Inativo' ?></span></td>
                                        <td>
                                            <form method="POST" action="<?= url('/gestao/pregadores/' . (int) $preacher['id'] . '/remover') ?>" onsubmit="return confirm('Remover este pregador?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn--ghost btn--sm">Remover</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
<?php $__view->endSection(); ?>
