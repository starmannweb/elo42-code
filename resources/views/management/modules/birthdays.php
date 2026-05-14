<?php $__view->extends('management', ['pageTitle' => $pageTitle ?? 'Aniversários', 'breadcrumb' => $breadcrumb ?? 'Aniversários', 'activeMenu' => $activeMenu ?? 'aniversarios']); ?>

<?php $__view->section('content'); ?>
<?php
    $birthdayCount = count($members ?? []);
    $months = [1 => 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
    $monthAbbr = [1 => 'jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
    $monthLabel = ucfirst($months[(int) date('n')]) . ' de ' . date('Y');
?>

<div class="mgmt-container">
    <div class="mgmt-header">
        <div>
            <h1 class="mgmt-header__title">Aniversariantes do Mês</h1>
            <p class="mgmt-header__subtitle"><?= e($monthLabel) ?></p>
        </div>
        <?php if ($birthdayCount > 0): ?>
            <div class="mgmt-header__actions">
                <button type="button" class="btn btn--primary" onclick="openCardsModal()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    Gerar Cartões
                </button>
            </div>
        <?php endif; ?>
    </div>

    <div class="mgmt-card mgmt-filter-card">
        <div class="mgmt-card__body">
            <form method="GET" action="<?= url('/gestao/aniversarios') ?>" class="mgmt-filter-grid mgmt-filter-grid--compact">
                <div class="mgmt-filter-field">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Nome ou telefone" value="<?= e($search ?? '') ?>">
                </div>
                <div class="mgmt-filter-field">
                    <label for="month" class="form-label">Período</label>
                    <input type="month" id="month" name="month" class="form-control" value="<?= e($month ?? date('Y-m')) ?>">
                </div>
                <div class="mgmt-filter-actions">
                    <button type="submit" class="btn btn--outline">Filtrar</button>
                    <a href="<?= url('/gestao/aniversarios') ?>" class="btn btn--outline">Limpar</a>
                </div>
            </form>
        </div>
    </div>

    <?php if (empty($members)): ?>
        <div class="mgmt-card">
            <div class="mgmt-empty">
                <div class="mgmt-empty__icon" aria-hidden="true">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 21h16"></path><path d="M5 12h14v9H5z"></path><path d="M7 12V9a5 5 0 0 1 10 0v3"></path><path d="M9 7c0-1 .7-2 1.5-3C11.3 5 12 6 12 7"></path><path d="M14 7c0-1 .7-2 1.5-3C16.3 5 17 6 17 7"></path><path d="M5 16c1.5 1 3 1 4.5 0s3-1 4.5 0 3 1 5 0"></path>
                    </svg>
                </div>
                <h2 class="mgmt-empty__title">Nenhum aniversariante este mês</h2>
                <p class="mgmt-empty__text">Cadastre membros com data de nascimento para visualizar os aniversariantes.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert--warning" role="status">
            <strong>Lembrete pastoral:</strong> não se esqueça de parabenizar os aniversariantes do mês. Um pequeno gesto de carinho e uma oração podem fazer diferença.
        </div>

        <div class="mgmt-card">
            <div class="mgmt-card__body">
                <div class="mgmt-list">
                    <?php foreach ($members as $m): ?>
                        <?php
                            $day = date('d', strtotime($m['birth_date']));
                            $monthName = $monthAbbr[(int) date('n', strtotime($m['birth_date']))] ?? date('m', strtotime($m['birth_date']));
                            $isToday = date('m-d') === date('m-d', strtotime($m['birth_date']));
                            $parts = explode(' ', trim($m['name'] ?? 'Membro'));
                            $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . substr(end($parts) ?: '', 0, 1));
                            
                            $today = new \DateTime();
                            $bday = new \DateTime($m['birth_date']);
                            $age = $today->diff($bday)->y;
                        ?>
                        <div class="mgmt-list__item <?= $isToday ? 'mgmt-list__item--featured' : '' ?>" data-birthday-id="<?= $m['id'] ?>" data-name="<?= e($m['name']) ?>" data-day="<?= e($day) ?>" data-month="<?= strtoupper($monthName) ?>" data-initials="<?= e($initials) ?>" data-age="<?= $age ?>">
                            <div class="avatar"><?= e($initials) ?></div>
                            <div class="mgmt-list__content">
                                <strong><?= e($m['name'] ?? 'Membro') ?></strong>
                                <span><?= e($m['email'] ?? $m['phone'] ?? '') ?></span>
                            </div>
                            <?php if ($isToday): ?>
                                <span class="badge badge-warning">Hoje</span>
                            <?php endif; ?>
                            <div class="mgmt-list__date">
                                <strong><?= e($day) ?></strong>
                                <span><?= e($monthName) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
/* Estilos Específicos do Modal de Cartões */
.cards-modal-option {
    border: 1px solid var(--color-border-light);
    background: transparent;
    padding: 16px;
    border-radius: 8px;
    cursor: pointer;
    text-align: center;
    color: var(--text-muted);
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}
.cards-modal-option:hover {
    border-color: rgba(236, 72, 153, 0.4);
    background: rgba(236, 72, 153, 0.05);
}
.cards-modal-option.active {
    border-color: #ec4899;
    color: #ec4899;
    background: rgba(236, 72, 153, 0.1);
}
.cards-modal-option svg {
    stroke: currentColor;
    margin-bottom: 4px;
}
.cards-modal-option span {
    font-size: 13px;
    font-weight: 600;
    color: var(--color-text-primary);
}
.cards-modal-option small {
    font-size: 11px;
    opacity: 0.8;
}
</style>

<!-- Modal Gerar Cartões -->
<div class="modal" id="modal-cards" style="display:none;" role="dialog" aria-modal="true">
    <div class="modal__content" style="max-width: 500px;">
        <div class="modal__header">
            <div style="display:flex; align-items:center; gap:12px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ec4899" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                <div>
                    <h2 class="modal__title" style="margin-bottom:2px;">Gerar Cartões</h2>
                    <span style="font-size:12px; color:var(--text-muted);"><?= $birthdayCount ?> aniversariantes selecionados</span>
                </div>
            </div>
            <button type="button" class="modal__close" onclick="this.closest('.modal').style.display='none'" aria-label="Fechar">&times;</button>
        </div>
        
        <div class="modal__body" style="padding-top: 24px;">
            <div class="form-group">
                <label class="form-label" style="font-weight:700;">Formato de impressão</label>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                    <div class="cards-modal-option active" data-format="a5" onclick="selectFormat(this)">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke-width="1.5"><rect x="6" y="4" width="12" height="16" rx="1"></rect></svg>
                        <span>A5</span>
                        <small>1 por folha</small>
                    </div>
                    <div class="cards-modal-option" data-format="a4-2" onclick="selectFormat(this)">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke-width="1.5"><rect x="4" y="6" width="16" height="12" rx="1"></rect><line x1="12" y1="6" x2="12" y2="18"></line></svg>
                        <span>A4</span>
                        <small>2 por folha</small>
                    </div>
                    <div class="cards-modal-option" data-format="a4-4" onclick="selectFormat(this)">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke-width="1.5"><rect x="5" y="4" width="14" height="16" rx="1"></rect><line x1="12" y1="4" x2="12" y2="20"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>A4</span>
                        <small>4 por folha</small>
                    </div>
                </div>
                <input type="hidden" id="card-format" value="a5">
            </div>

            <div class="form-group" style="margin-top:24px;">
                <label class="form-label" style="font-weight:700;">Título da página</label>
                <input type="text" id="card-title" class="form-input" value="Nossos Aniversariantes">
                <div class="form-text" style="font-size:11px; margin-top:4px;">Deixe em branco para não mostrar título.</div>
            </div>

            <div class="form-group" style="margin-top:20px;">
                <label class="form-label" style="font-weight:700;">Mensagem no rodapé do cartão</label>
                <textarea id="card-message" class="form-input" rows="2">Feliz aniversário! Que Deus abençoe seu novo ciclo.</textarea>
            </div>

            <div style="display:flex; gap: 24px; margin-top:20px; margin-bottom: 24px;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px; font-weight:600; color:var(--color-text-primary);">
                    <input type="checkbox" id="card-show-photo" checked>
                    Mostrar foto / iniciais
                </label>
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px; font-weight:600; color:var(--color-text-primary);">
                    <input type="checkbox" id="card-show-age" checked>
                    Mostrar idade
                </label>
            </div>

            <div class="form-group">
                <label class="form-label" style="font-weight:700;">Estilo do fundo</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="cards-modal-option active" data-style="color" onclick="selectStyle(this)">
                        <span>Faixa colorida</span>
                    </div>
                    <div class="cards-modal-option" data-style="minimal" onclick="selectStyle(this)">
                        <span>Minimalista</span>
                    </div>
                </div>
                <input type="hidden" id="card-style" value="color">
            </div>
        </div>
        
        <div class="modal__footer" style="margin-top:16px;">
            <button type="button" class="btn btn--ghost" onclick="this.closest('.modal').style.display='none'">Cancelar</button>
            <button type="button" class="btn btn--primary" style="background:#ec4899; border-color:#ec4899; color:white;" onclick="generatePrintWindow()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Gerar PDF
            </button>
        </div>
    </div>
</div>

<script>
function openCardsModal() {
    document.getElementById('modal-cards').style.display = 'flex';
}

function selectFormat(el) {
    document.querySelectorAll('[data-format]').forEach(e => e.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('card-format').value = el.getAttribute('data-format');
}

function selectStyle(el) {
    document.querySelectorAll('[data-style]').forEach(e => e.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('card-style').value = el.getAttribute('data-style');
}

function generatePrintWindow() {
    const format = document.getElementById('card-format').value;
    const style = document.getElementById('card-style').value;
    const title = document.getElementById('card-title').value;
    const message = document.getElementById('card-message').value;
    const showPhoto = document.getElementById('card-show-photo').checked;
    const showAge = document.getElementById('card-show-age').checked;

    const items = document.querySelectorAll('.mgmt-list__item');
    if (items.length === 0) return;

    let cardsHtml = '';
    
    // CSS Grid configuration based on format
    let gridCols = '1fr';
    let cardHeight = 'calc(100vh - 100px)'; // A5 approx
    let pageSize = 'A5 portrait';
    let margin = '15mm';

    if (format === 'a4-2') {
        gridCols = '1fr 1fr';
        cardHeight = 'calc(50vh - 40px)';
        pageSize = 'A4 landscape';
        margin = '10mm';
    } else if (format === 'a4-4') {
        gridCols = '1fr 1fr';
        cardHeight = 'calc(50vh - 40px)';
        pageSize = 'A4 portrait';
        margin = '10mm';
    }

    items.forEach(item => {
        const name = item.getAttribute('data-name');
        const day = item.getAttribute('data-day');
        const month = item.getAttribute('data-month');
        const initials = item.getAttribute('data-initials');
        const age = item.getAttribute('data-age');

        const bgStyle = style === 'color' 
            ? 'background: linear-gradient(135deg, #111827 0%, #1f2937 100%); color: white; border:none;' 
            : 'background: white; border: 1px solid #e2e8f0; color: #1e293b;';
        
        const photoHtml = showPhoto ? `
            <div style="width: 140px; height: 140px; border-radius: 50%; background: ${style === 'color' ? 'rgba(255,255,255,0.1)' : '#0f172a'}; color: ${style === 'color' ? 'white' : 'white'}; display: flex; align-items: center; justify-content: center; font-size: 64px; font-weight: 800; margin: 0 auto 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                ${initials}
            </div>
        ` : '';

        const ageHtml = showAge ? `
            <div style="font-size: 16px; opacity: 0.8; margin-top: 8px;">
                ${age} anos
            </div>
        ` : '';

        const messageHtml = message ? `
            <div style="margin-top: auto; padding-top: 30px; font-size: 13px; font-style: italic; opacity: 0.8; text-align: center;">
                ${message}
            </div>
        ` : '';

        cardsHtml += `
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 30px; border-radius: 16px; text-align: center; height: ${cardHeight}; box-sizing: border-box; ${bgStyle} page-break-inside: avoid;">
                ${photoHtml}
                
                <div style="background: ${style === 'color' ? '#ec4899' : '#0f172a'}; color: white; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 800; letter-spacing: 1px; margin-bottom: 16px; display: inline-block;">
                    ${day} / ${month}
                </div>
                
                <h3 style="font-family: 'Inter', sans-serif; font-size: 28px; font-weight: 800; margin: 0; line-height: 1.2;">
                    ${name}
                </h3>
                
                ${ageHtml}
                ${messageHtml}
            </div>
        `;
    });

    const titleHtml = title ? `<h1 style="text-align: center; font-family: 'Inter', sans-serif; font-size: 32px; font-weight: 800; color: #0f172a; margin: 0 0 30px 0;">${title}</h1>` : '';

    const htmlContent = `
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <title>Cartões de Aniversário</title>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
                @page { size: ${pageSize}; margin: ${margin}; }
                body { 
                    margin: 0; 
                    padding: 0; 
                    font-family: 'Inter', sans-serif; 
                    background: #ffffff;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                .grid {
                    display: grid;
                    grid-template-columns: ${gridCols};
                    gap: 20px;
                }
            </style>
        </head>
        <body>
            ${titleHtml}
            <div class="grid">
                ${cardsHtml}
            </div>
            <script>
                window.onload = () => {
                    setTimeout(() => {
                        window.print();
                    }, 500);
                };
            <\/script>
        </body>
        </html>
    `;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(htmlContent);
    printWindow.document.close();
    
    document.getElementById('modal-cards').style.display = 'none';
}
</script>

<?php $__view->endSection(); ?>
