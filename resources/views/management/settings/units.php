<?php $__view->extends('management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-title">Unidades da igreja</h1>
            <p class="mgmt-subtitle">Cadastre sedes, congregações e campus para segmentar membros, eventos, cursos e financeiro.</p>
        </div>
        <button type="submit" form="form-unit" class="btn btn--primary">Adicionar unidade</button>
    </div>

    <div class="mgmt-grid" style="grid-template-columns: minmax(320px, .8fr) minmax(0, 1.2fr); gap: 1.25rem;">
        <section class="mgmt-panel">
            <h2 class="mgmt-card__title">Nova unidade</h2>
            <form id="form-unit" method="POST" action="<?= url('/gestao/configuracoes/unidades') ?>" class="portal-form" style="margin-top:1rem;">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label class="form-label" for="unit_name">Nome</label>
                    <input id="unit_name" name="name" class="form-control" placeholder="Ex: Sede Centro" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="unit_code">Código interno</label>
                    <input id="unit_code" name="code" class="form-control" placeholder="Ex: SEDE">
                </div>
                <div class="form-group">
                    <label class="form-label" for="unit_address">Endereço</label>
                    <input id="unit_address" name="address" class="form-control" placeholder="Rua, número e bairro">
                </div>
                <div class="mgmt-grid" style="grid-template-columns: 1fr 90px; gap: .875rem;">
                    <div class="form-group">
                        <label class="form-label" for="unit_city">Cidade</label>
                        <input id="unit_city" name="city" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="unit_state">UF</label>
                        <input id="unit_state" name="state" class="form-control" maxlength="2">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="unit_phone">Telefone</label>
                    <input id="unit_phone" name="phone" class="form-control" placeholder="(00) 00000-0000">
                </div>
                <div class="form-group">
                    <label class="form-label" for="unit_status">Status</label>
                    <select id="unit_status" name="status" class="form-control">
                        <option value="active">Ativa</option>
                        <option value="inactive">Inativa</option>
                    </select>
                </div>
            </form>
        </section>

        <section class="mgmt-card">
            <div class="mgmt-card__body">
                <?php if (empty($units)): ?>
                    <div class="mgmt-empty">
                        <h3 class="mgmt-empty__title">Nenhuma unidade cadastrada</h3>
                        <p class="mgmt-empty__text">Comece pela sede principal. Depois você poderá vincular membros, eventos, sermões e cursos a cada unidade.</p>
                    </div>
                <?php else: ?>
                    <div class="mgmt-table-container">
                        <table class="mgmt-table">
                            <thead>
                                <tr>
                                    <th>Unidade</th>
                                    <th>Cidade</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($units as $unit): ?>
                                    <tr>
                                        <td>
                                            <div class="mgmt-table__name"><?= e((string) ($unit['name'] ?? 'Unidade')) ?></div>
                                            <small style="color:var(--text-muted);"><?= e((string) ($unit['address'] ?? '')) ?></small>
                                        </td>
                                        <td><?= e(trim((string) ($unit['city'] ?? '') . ' ' . (string) ($unit['state'] ?? '')) ?: '-') ?></td>
                                        <td><span class="badge"><?= (string) ($unit['status'] ?? 'active') === 'active' ? 'Ativa' : 'Inativa' ?></span></td>
                                        <td>
                                            <form method="POST" action="<?= url('/gestao/configuracoes/unidades/' . (int) $unit['id'] . '/remover') ?>" onsubmit="return confirm('Remover esta unidade?');">
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
