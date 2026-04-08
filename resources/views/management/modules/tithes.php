<?php $__view->extends('management', ['pageTitle' => $pageTitle ?? 'Dizimos & Ofertas', 'breadcrumb' => $breadcrumb ?? 'Dizimos & Ofertas', 'activeMenu' => 'dizimos-ofertas']); ?>

<?php $__view->section('content'); ?>
<div style="max-width: 960px; margin: 0 auto;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700; margin: 0; color: var(--text-primary);">Dizimos & Ofertas</h1>
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin: 0.25rem 0 0;">Gerencie contribuicoes via PIX com QR Code</p>
        </div>
        <a href="<?= url('/gestao/doacoes/nova') ?>" class="btn btn--primary" style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.5rem 1rem;background:var(--color-primary,#1e3a8a);color:#fff;border-radius:8px;text-decoration:none;font-size:0.875rem;font-weight:500;">
            + Registrar contribuicao
        </a>
    </div>

    <?php if (!empty($pixWarning)): ?>
        <div style="display:flex;align-items:flex-start;gap:0.75rem;padding:1rem 1.25rem;background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:10px;margin-bottom:1.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" style="flex-shrink:0;margin-top:2px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <div style="font-size:0.875rem;color:var(--text-primary);line-height:1.5;">
                <strong>Configure sua chave PIX</strong> — Acesse <a href="<?= url('/gestao/configuracoes') ?>" style="color:#d97706;font-weight:600;">Configuracoes</a> para cadastrar a chave PIX da igreja e gerar o QR Code automaticamente.
            </div>
        </div>
    <?php endif; ?>

    <?php
        $pixKey = $pixKey ?? '';
        $orgName = $orgName ?? 'Igreja';
    ?>

    <?php if (!empty($pixKey)): ?>
    <div style="background:var(--card-bg,#fff);border:1px solid var(--border-color,#e5e7eb);border-radius:12px;padding:2rem;margin-bottom:1.5rem;text-align:center;">
        <h2 style="font-size:1.1rem;font-weight:600;margin:0 0 0.5rem;color:var(--text-primary);">QR Code PIX</h2>
        <p style="font-size:0.8rem;color:var(--text-secondary);margin:0 0 1.25rem;">Compartilhe este QR Code para receber dizimos e ofertas</p>
        <div style="display:inline-flex;align-items:center;justify-content:center;width:200px;height:200px;background:#fff;border:3px solid #f59e0b;border-radius:12px;margin-bottom:1rem;">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=<?= urlencode($pixKey) ?>" alt="QR Code PIX" width="180" height="180" style="border-radius:8px;" onerror="this.parentElement.innerHTML='<svg width=60 height=60 viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#d97706\' stroke-width=\'1.5\'><rect x=\'3\' y=\'3\' width=\'7\' height=\'7\'></rect><rect x=\'14\' y=\'3\' width=\'7\' height=\'7\'></rect><rect x=\'3\' y=\'14\' width=\'7\' height=\'7\'></rect><rect x=\'14\' y=\'14\' width=\'3\' height=\'3\'></rect></svg>'">
        </div>
        <div style="display:flex;align-items:center;justify-content:center;gap:0.5rem;margin-top:0.5rem;">
            <code style="background:var(--card-bg-secondary,#f9fafb);padding:0.375rem 0.75rem;border-radius:6px;font-size:0.8rem;color:var(--text-primary);border:1px solid var(--border-color,#e5e7eb);"><?= htmlspecialchars($pixKey) ?></code>
            <button onclick="navigator.clipboard.writeText('<?= htmlspecialchars($pixKey) ?>');this.textContent='Copiado!';setTimeout(()=>this.textContent='Copiar',2000)" style="padding:0.375rem 0.75rem;background:#f59e0b;color:#fff;border:none;border-radius:6px;font-size:0.75rem;font-weight:600;cursor:pointer;">Copiar</button>
        </div>
    </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
        <div style="background:var(--card-bg,#fff);border:1px solid var(--border-color,#e5e7eb);border-radius:10px;padding:1.25rem;">
            <div style="font-size:0.75rem;color:var(--text-secondary);font-weight:500;margin-bottom:0.25rem;">Total do mes</div>
            <div style="font-size:1.5rem;font-weight:700;color:var(--text-primary);">R$ <?= number_format(($summary['total'] ?? 0), 2, ',', '.') ?></div>
        </div>
        <div style="background:var(--card-bg,#fff);border:1px solid var(--border-color,#e5e7eb);border-radius:10px;padding:1.25rem;">
            <div style="font-size:0.75rem;color:var(--text-secondary);font-weight:500;margin-bottom:0.25rem;">Dizimos</div>
            <div style="font-size:1.5rem;font-weight:700;color:#059669;">R$ <?= number_format(($summary['tithe'] ?? 0), 2, ',', '.') ?></div>
        </div>
        <div style="background:var(--card-bg,#fff);border:1px solid var(--border-color,#e5e7eb);border-radius:10px;padding:1.25rem;">
            <div style="font-size:0.75rem;color:var(--text-secondary);font-weight:500;margin-bottom:0.25rem;">Ofertas</div>
            <div style="font-size:1.5rem;font-weight:700;color:#d97706;">R$ <?= number_format(($summary['offering'] ?? 0), 2, ',', '.') ?></div>
        </div>
        <div style="background:var(--card-bg,#fff);border:1px solid var(--border-color,#e5e7eb);border-radius:10px;padding:1.25rem;">
            <div style="font-size:0.75rem;color:var(--text-secondary);font-weight:500;margin-bottom:0.25rem;">Contribuintes</div>
            <div style="font-size:1.5rem;font-weight:700;color:var(--text-primary);"><?= (int) ($summary['donors'] ?? 0) ?></div>
        </div>
    </div>

    <div style="background:var(--card-bg,#fff);border:1px solid var(--border-color,#e5e7eb);border-radius:12px;overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border-color,#e5e7eb);display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:1rem;font-weight:600;margin:0;color:var(--text-primary);">Ultimas contribuicoes</h2>
        </div>

        <?php if (empty($donations)): ?>
            <div style="padding:2.5rem;text-align:center;color:var(--text-secondary);font-size:0.875rem;">
                Nenhuma contribuicao registrada ainda.
            </div>
        <?php else: ?>
            <div style="overflow-x:auto;">
                <?php foreach ($donations as $d): ?>
                    <?php
                        $parts = explode(' ', trim($d['member_name'] ?? $d['donor_name'] ?? 'Anonimo'));
                        $init = strtoupper(substr($parts[0] ?? '', 0, 1) . substr(end($parts) ?: '', 0, 1));
                        $typeLabel = match($d['type'] ?? '') {
                            'tithe' => 'Dizimo',
                            'offering' => 'Oferta',
                            'campaign' => 'Campanha',
                            default => 'Contribuicao'
                        };
                        $typeColor = match($d['type'] ?? '') {
                            'tithe' => '#059669',
                            'offering' => '#d97706',
                            'campaign' => '#7c3aed',
                            default => '#6b7280'
                        };
                    ?>
                    <div style="display:flex;align-items:center;gap:1rem;padding:0.875rem 1.25rem;border-bottom:1px solid var(--border-color,#e5e7eb);">
                        <div style="flex-shrink:0;width:40px;height:40px;border-radius:50%;background:<?= $typeColor ?>15;color:<?= $typeColor ?>;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;"><?= htmlspecialchars($init) ?></div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:600;font-size:0.875rem;color:var(--text-primary);"><?= htmlspecialchars($d['member_name'] ?? $d['donor_name'] ?? 'Anonimo') ?></div>
                            <div style="font-size:0.75rem;color:var(--text-secondary);">
                                <span style="display:inline-flex;align-items:center;gap:0.25rem;padding:0.0625rem 0.375rem;background:<?= $typeColor ?>15;color:<?= $typeColor ?>;border-radius:3px;font-weight:600;font-size:0.65rem;"><?= $typeLabel ?></span>
                                <?= !empty($d['donation_date']) ? date('d/m/Y', strtotime($d['donation_date'])) : '' ?>
                            </div>
                        </div>
                        <div style="flex-shrink:0;font-weight:700;font-size:0.95rem;color:var(--text-primary);">R$ <?= number_format((float)($d['amount'] ?? 0), 2, ',', '.') ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__view->endSection(); ?>
