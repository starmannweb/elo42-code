<?php $__view->extends('management'); ?>
<?php $__view->section('content'); ?>
<?php $members = is_array($members ?? null) ? $members : []; ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-header__title">Visitas</h1>
        <p class="mgmt-header__subtitle">Registre visitantes e acompanhe o retorno pastoral.</p>
    </div>
    <div class="mgmt-header__actions">
        <button type="button" class="btn btn--primary" onclick="document.getElementById('modal-new-visit').style.display='flex'">+ Nova visita</button>
    </div>
</div>

<div class="mgmt-filter-card mgmt-card">
    <div class="mgmt-card__body">
        <form method="GET" action="<?= url('/gestao/visitantes') ?>" class="mgmt-filter-grid">
            <div class="mgmt-filter-field">
                <label for="visit_search" class="form-label">Buscar</label>
                <input type="text" id="visit_search" name="search" class="form-control" placeholder="Nome, e-mail ou telefone" value="<?= e($filters['search'] ?? '') ?>">
            </div>
            <div class="mgmt-filter-field">
                <label for="visit_status" class="form-label">Status</label>
                <select id="visit_status" name="status" class="form-control">
                    <option value="">Todos</option>
                    <?php foreach (['pending'=>'Pendente','contacted'=>'Contatado','scheduled'=>'Agendado','completed'=>'Concluído','no_response'=>'Sem resposta'] as $k=>$lbl): ?>
                        <option value="<?= $k ?>" <?= ($filters['status'] ?? '') === $k ? 'selected' : '' ?>><?= $lbl ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mgmt-filter-field">
                <label for="visit_month" class="form-label">Período</label>
                <input type="month" id="visit_month" name="month" class="form-control" value="<?= e($filters['month'] ?? date('Y-m')) ?>">
            </div>
            <div class="mgmt-filter-actions">
                <button type="submit" class="btn btn--outline">Filtrar</button>
                <a href="<?= url('/gestao/visitantes') ?>" class="btn btn--outline">Limpar</a>
            </div>
        </form>
    </div>
</div>

<?php if (empty($visits)): ?><div class="mgmt-empty"><div class="mgmt-empty__icon"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"></path><path d="M5 21V7l8-4v18"></path><path d="M19 21V11l-6-4"></path><circle cx="10" cy="13" r="0.6" fill="currentColor"></circle></svg></div><h3 class="mgmt-empty__title">Nenhuma visita</h3><p class="mgmt-empty__text">Registre visitas recebidas.</p></div>
<?php else: ?>
    <div class="mgmt-table-container"><table class="mgmt-table"><thead><tr><th>Visitante</th><th>Contato</th><th>Data</th><th>Origem</th><th>Acompanhamento</th><th>Responsável</th><th>Ações</th></tr></thead><tbody>
        <?php foreach ($visits as $v): ?><tr>
            <td class="mgmt-table__name"><?= e($v['visitor_name']) ?></td>
            <td><?= e($v['phone'] ?? $v['email'] ?? '—') ?></td>
            <td><?= date('d/m/Y', strtotime($v['visit_date'])) ?></td>
            <td><?= e(match($v['source']) { 'invited'=>'Convidado','spontaneous'=>'Espontâneo','event'=>'Evento','online'=>'Online', default=>$v['source'] }) ?></td>
            <td><form method="POST" action="<?= url('/gestao/visitantes/' . $v['id'] . '/status') ?>" style="display:inline;"><?= csrf_field() ?><select name="follow_up" class="form-select" style="font-size:0.7rem;padding:2px 4px;" onchange="this.form.submit()">
                <?php foreach (['pending'=>'Pendente','contacted'=>'Contatado','scheduled'=>'Agendado','completed'=>'Concluído','no_response'=>'Sem resposta'] as $k=>$lbl): ?><option value="<?= $k ?>" <?= $v['follow_up'] === $k ? 'selected' : '' ?>><?= $lbl ?></option><?php endforeach; ?>
            </select></form></td>
            <td><?= e($v['assigned_name'] ?? '—') ?></td>
            <td class="mgmt-table__actions"><?php if ($v['notes']): ?><span title="<?= e($v['notes']) ?>">📋</span><?php endif; ?></td>
        </tr><?php endforeach; ?>
    </tbody></table></div>
<?php endif; ?>

<div class="modal" id="modal-new-visit" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="modal-new-visit-title">
    <div class="modal__content modal__content--wide">
        <div class="modal__header">
            <h2 class="modal__title" id="modal-new-visit-title">Nova visita</h2>
            <button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'" aria-label="Fechar">&times;</button>
        </div>
        <form method="POST" action="<?= url('/gestao/visitantes') ?>" data-loading>
            <?= csrf_field() ?>
            <div class="modal__body">
                <div class="modal-grid">
                    <div class="form-group modal-grid__full">
                        <label class="form-label">Nome do visitante *</label>
                        <input type="text" name="visitor_name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Telefone</label>
                        <input type="tel" name="phone" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Data da visita *</label>
                        <input type="date" name="visit_date" class="form-input" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Origem</label>
                        <select name="source" class="form-select">
                            <option value="spontaneous">Espontâneo</option>
                            <option value="invited">Convidado</option>
                            <option value="event">Evento</option>
                            <option value="online">Online</option>
                            <option value="other">Outro</option>
                        </select>
                    </div>
                    <div class="form-group modal-grid__full">
                        <label class="form-label">Responsável pelo acompanhamento</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">Nenhum</option>
                            <?php foreach ($members as $m): ?>
                                <option value="<?= (int) $m['id'] ?>"><?= e((string) $m['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group modal-grid__full">
                        <label class="form-label">Observações</label>
                        <textarea name="notes" class="form-input" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button>
                <button type="submit" class="btn btn--primary">Registrar</button>
            </div>
        </form>
    </div>
</div>
<?php $__view->endSection(); ?>
