<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatórios - Elo 42</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; margin: 32px; }
        header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #1e3a8a; padding-bottom: 16px; margin-bottom: 24px; }
        h1 { margin: 0; font-size: 24px; }
        .muted { color: #6b7280; font-size: 13px; }
        .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 24px; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px; }
        .label { font-size: 11px; color: #6b7280; text-transform: uppercase; }
        .value { font-size: 20px; font-weight: 700; margin-top: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border-bottom: 1px solid #e5e7eb; text-align: left; padding: 10px; }
        th { background: #f9fafb; font-size: 12px; text-transform: uppercase; color: #4b5563; }
        .actions { margin-bottom: 18px; }
        button { border: 0; border-radius: 6px; background: #1e3a8a; color: #fff; padding: 10px 14px; cursor: pointer; }
        @media print { .actions { display: none; } body { margin: 18mm; } }
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">Imprimir / salvar em PDF</button></div>
    <header>
        <div>
            <h1>Relatórios Elo 42</h1>
            <div class="muted">Período: <?= e((string) ($filters['start_date'] ?? '')) ?> até <?= e((string) ($filters['end_date'] ?? '')) ?></div>
        </div>
        <div class="muted">Gerado em <?= date('d/m/Y H:i') ?></div>
    </header>

    <section class="grid">
        <div class="card"><div class="label">Membros</div><div class="value"><?= (int) $totalMembers ?></div></div>
        <div class="card"><div class="label">Membros ativos</div><div class="value"><?= (int) $activeMembers ?></div></div>
        <div class="card"><div class="label">Receitas</div><div class="value">R$ <?= number_format((float) ($financial['income'] ?? 0), 2, ',', '.') ?></div></div>
        <div class="card"><div class="label">Despesas</div><div class="value">R$ <?= number_format((float) ($financial['expense'] ?? 0), 2, ',', '.') ?></div></div>
    </section>

    <table>
        <thead><tr><th>Indicador</th><th>Valor</th></tr></thead>
        <tbody>
            <tr><td>Novos membros</td><td><?= (int) $newMembers ?></td></tr>
            <tr><td>Eventos ativos</td><td><?= (int) $activeEvents ?></td></tr>
            <tr><td>Solicitações abertas</td><td><?= (int) $openRequests ?></td></tr>
            <tr><td>Tarefas pendentes</td><td><?= (int) $pendingTasks ?></td></tr>
            <tr><td>Saldo financeiro</td><td>R$ <?= number_format((float) ($financial['balance'] ?? 0), 2, ',', '.') ?></td></tr>
        </tbody>
    </table>
</body>
</html>
