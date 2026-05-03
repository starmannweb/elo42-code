<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<?php
    $form = is_array($expositorForm ?? null) ? $expositorForm : [];
    $creditCost = (int) ($iaCreditCost ?? 1);
    $credits = (int) ($iaCredits ?? 0);
    $canGenerate = !empty($canGenerateIa);
    $lastResult = $expositorLastResult ?? null;
    $generatedDraft = is_array($expositorGeneratedDraft ?? null) ? $expositorGeneratedDraft : null;
    $contentType = (string) ($form['content_type'] ?? 'sermon');
    $activeExpositorTab = match ($contentType) {
        'study', 'reading_plan' => 'estudos',
        'resource' => 'treinamento',
        default => !empty($lastResult) ? 'pregacao' : 'planejamento',
    };
    $resourceIcons = [
        'ebd' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z"></path></svg>',
        'discipulado' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="m16 11 2 2 4-4"></path></svg>',
        'casais' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 12 5a5.5 5.5 0 0 0-10 3.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path></svg>',
        'lideranca' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l7 4v5c0 5-3 8-7 9-4-1-7-4-7-9V7l7-4z"></path><path d="M9 12l2 2 4-4"></path></svg>',
        'pg' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
        'anual' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="m12 6 2 6 4 2-6 2-2 4-2-4-6-2 6-2 2-6z"></path></svg>',
    ];
    $resources = [
        ['key' => 'ebd', 'title' => 'Currículo Escola Dominical', 'text' => 'Séries bíblicas com progressão pedagógica para classes e faixas etárias.', 'accent' => 'green', 'action' => 'Gerar visão da série'],
        ['key' => 'discipulado', 'title' => 'Roteiro de Discipulado', 'text' => 'Trilhas de encontros adaptadas ao perfil espiritual do discípulo.', 'accent' => 'green', 'action' => 'Gerar trilha'],
        ['key' => 'casais', 'title' => 'Discipulado de Casais', 'text' => 'Curso de preparação bíblica para aliança matrimonial e vida familiar.', 'accent' => 'rose', 'action' => 'Gerar curso'],
        ['key' => 'lideranca', 'title' => 'Treinamento de Liderança', 'text' => 'Formação para presbíteros, diáconos, líderes de jovens, EBD e PG.', 'accent' => 'green', 'action' => 'Gerar treinamento'],
        ['key' => 'pg', 'title' => 'Planejamento de Pequenos Grupos', 'text' => 'Ciclos fechados com comunhão, Palavra, aplicação e oração.', 'accent' => 'violet', 'action' => 'Gerar ciclo'],
        ['key' => 'anual', 'title' => 'Plano Anual da Igreja', 'text' => 'Discernimento pastoral assistido para tema, pilares e diretrizes do ano.', 'accent' => 'amber', 'action' => 'Gerar esboço macro'],
    ];
?>

<section class="hub-page" data-expositor-workbench>
    <header class="hub-page__header expositor-page-header">
        <div>
            <h1 class="hub-page__title">Expositor IA</h1>
            <p class="hub-page__subtitle">Um ambiente ministerial separado para planejar, estudar o texto, gerar sermões, refinar rascunhos e preparar materiais para EBD, pequenos grupos e discipulado.</p>
        </div>
    </header>

    <nav class="expositor-workbench-tabs" aria-label="Fluxos do Expositor IA">
        <button type="button" class="expositor-workbench-tabs__item <?= $activeExpositorTab === 'planejamento' ? 'active' : '' ?>" data-expositor-tab="planejamento" aria-controls="expositor-panel-planejamento">Planejamento</button>
        <button type="button" class="expositor-workbench-tabs__item <?= $activeExpositorTab === 'pregacao' ? 'active' : '' ?>" data-expositor-tab="pregacao" aria-controls="expositor-panel-pregacao">Pregação</button>
        <button type="button" class="expositor-workbench-tabs__item <?= $activeExpositorTab === 'estudos' ? 'active' : '' ?>" data-expositor-tab="estudos" aria-controls="expositor-panel-estudos">Estudos</button>
        <button type="button" class="expositor-workbench-tabs__item <?= $activeExpositorTab === 'treinamento' ? 'active' : '' ?>" data-expositor-tab="treinamento" aria-controls="expositor-panel-treinamento">Treinamento</button>
    </nav>

    <div class="expositor-usage">
        <div>
            <strong>Plano gratuito</strong>
            <span>3 gerações liberadas por mês. Depois disso, cada geração consome <?= e((string) $creditCost) ?> crédito.</span>
        </div>
        <div class="expositor-usage__meter" aria-label="Saldo de créditos">
            <span><?= e((string) $credits) ?> crédito(s) disponíveis</span>
            <div><i style="width:<?= $credits > 0 ? '100' : '0' ?>%;"></i></div>
            <a href="<?= url('/hub/creditos') ?>" class="btn btn--outline btn--sm" style="margin-top: 0.5rem;">Ver créditos</a>
        </div>
    </div>

    <?php if (!empty($monthlyAllowanceGranted)): ?>
        <div class="expositor-usage expositor-usage--notice" role="status">
            <div>
                <strong>3 gerações gratuitas liberadas</strong>
                <span>Esse benefício mensal pertence ao Expositor IA e já foi adicionado ao saldo deste workspace.</span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($lastResult)): ?>
        <article class="hub-panel expositor-result-panel">
            <div class="hub-panel__row">
                <div>
                    <h2 class="hub-panel__title">Material gerado</h2>
                    <p class="hub-panel__text">Revise o conteúdo, publique para a gestão e libere para a área de membros quando estiver pronto.</p>
                </div>
                <?php if (!empty($generatedDraft)): ?>
                    <span class="hub-badge hub-badge--warning">Rascunho salvo</span>
                <?php endif; ?>
            </div>
            <pre class="expositor-result"><?= e((string) $lastResult) ?></pre>
            <?php if (!empty($generatedDraft)): ?>
                <div class="expositor-publish-card">
                    <div>
                        <strong><?= e((string) ($generatedDraft['title'] ?? 'Material gerado')) ?></strong>
                        <span><?= e((string) ($generatedDraft['label'] ?? 'Conteúdo')) ?> pronto para publicação.</span>
                    </div>
                    <form method="POST" action="<?= url('/hub/expositor-ia/publicar') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="draft_type" value="<?= e((string) ($generatedDraft['type'] ?? 'sermon')) ?>">
                        <input type="hidden" name="draft_id" value="<?= e((string) ($generatedDraft['id'] ?? 0)) ?>">
                        <button type="submit" class="btn btn--primary">Publicar para membros</button>
                    </form>
                    <a href="<?= url((string) ($generatedDraft['destination'] ?? '/gestao/sermoes')) ?>" class="btn btn--outline">Ver na gestão</a>
                </div>
            <?php endif; ?>
        </article>
    <?php endif; ?>

    <div id="expositor-panel-planejamento" class="expositor-panel" data-expositor-panel="planejamento" <?= $activeExpositorTab === 'planejamento' ? '' : 'hidden' ?>>
    <div class="expositor-choice-grid">
        <article class="expositor-choice-card">
            <span class="expositor-choice-card__icon">✎</span>
            <small>Caminho livre</small>
            <h2>Organizar um sermão avulso</h2>
            <p>Gere sermão, estudo bíblico, aula EBD ou roteiro para culto especial de forma rápida.</p>
            <button type="button" class="expositor-link-button" data-expositor-target="pregacao">Começar</button>
        </article>
        <article class="expositor-choice-card">
            <span class="expositor-choice-card__icon">▦</span>
            <small>Caminho ministerial</small>
            <h2>Planejar uma série</h2>
            <p>Crie séries com PG e EBD alinhados, mantendo direção teológica em todos os encontros.</p>
            <button type="button" class="expositor-link-button" data-expositor-target="treinamento">Ver recursos</button>
        </article>
        <article class="expositor-choice-card">
            <span class="expositor-choice-card__icon">⌕</span>
            <small>Caminho exegético</small>
            <h2>Partir do texto bíblico</h2>
            <p>Aprofunde contexto, estrutura, palavras-chave e aplicação antes de desenvolver o material.</p>
            <button type="button" class="expositor-link-button" data-expositor-target="estudos">Estudar texto</button>
        </article>
    </div>

    <article class="hub-panel" style="margin-top:var(--space-5);">
        <div class="hub-panel__row">
            <div>
                <h2 class="hub-panel__title">Planejamento rápido</h2>
                <p class="hub-panel__text">Informe a passagem ou tema e gere um esboço inicial de planejamento ministerial em segundos.</p>
            </div>
            <div class="hub-badge <?= $canGenerate ? 'hub-badge--success' : 'hub-badge--warning' ?>">
                <?= $canGenerate ? 'Geração liberada' : 'Sem créditos suficientes' ?>
            </div>
        </div>

        <?php if (!$canGenerate): ?>
            <div class="alert alert--warning" role="alert" style="margin-top:var(--space-3);">
                Use as 3 gerações gratuitas do mês ou compre créditos para continuar.
                <a href="<?= url('/hub/creditos') ?>" class="text-primary font-bold">Comprar créditos</a>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('/hub/expositor-ia/gerar') ?>" data-loading style="margin-top:var(--space-4);">
            <?= csrf_field() ?>
            <input type="hidden" name="content_type" value="sermon">
            <input type="hidden" name="resource_title" value="Planejamento rápido">

            <div class="form-grid form-grid--2">
                <div class="form-group">
                    <label class="form-label" for="ia-plan-passage">Passagem ou tema</label>
                    <input id="ia-plan-passage" type="text" name="passage" class="form-input" value="<?= e((string) ($form['passage'] ?? '')) ?>" placeholder="Ex.: Romanos 12 ou A graça transformadora" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="ia-plan-theme">Ênfase pastoral</label>
                    <input id="ia-plan-theme" type="text" name="theme" class="form-input" value="<?= e((string) ($form['theme'] ?? '')) ?>" placeholder="Ex.: Vida na Igreja">
                </div>
            </div>

            <div class="form-grid form-grid--2">
                <div class="form-group">
                    <label class="form-label" for="ia-plan-confessional">Linha confessional</label>
                    <select id="ia-plan-confessional" name="confessional" class="form-select">
                        <?php foreach (($confessionalOptions ?? []) as $option): ?>
                            <option value="<?= e((string) ($option['value'] ?? '')) ?>" <?= (($form['confessional'] ?? '') === ($option['value'] ?? '')) ? 'selected' : '' ?>>
                                <?= e((string) ($option['label'] ?? '')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="ia-plan-depth">Profundidade</label>
                    <select id="ia-plan-depth" name="depth" class="form-select">
                        <?php foreach (($depthOptions ?? []) as $option): ?>
                            <option value="<?= e((string) ($option['value'] ?? '')) ?>" <?= (($form['depth'] ?? 'pastoral') === ($option['value'] ?? '')) ? 'selected' : '' ?>>
                                <?= e((string) ($option['label'] ?? '')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="hub-page__actions" style="margin-top:var(--space-3);">
                <button type="submit" class="btn btn--primary" <?= !$canGenerate ? 'disabled aria-disabled="true"' : '' ?>>Gerar planejamento</button>
                <a href="<?= url('/hub/creditos') ?>" class="btn btn--ghost">Saldo de créditos</a>
            </div>
        </form>
    </article>

    <div class="expositor-flow-grid">
        <article class="expositor-flow-card">
            <span>1</span>
            <strong>Caminho exegético</strong>
            <p>Contexto, estrutura literária, palavras-chave, teologia do texto e eixo cristológico antes da aplicação.</p>
        </article>
        <article class="expositor-flow-card">
            <span>2</span>
            <strong>Revisão pastoral</strong>
            <p>O pastor valida tema central, pontos e ênfase confessional antes de transformar o estudo em material.</p>
        </article>
        <article class="expositor-flow-card">
            <span>3</span>
            <strong>Desenvolvimento</strong>
            <p>Gere esboço, roteiro de pequeno grupo, aula de EBD, discipulado ou série a partir da mesma base.</p>
        </article>
    </div>
    </div>

    <div id="expositor-panel-pregacao" class="hub-panel expositor-panel" data-expositor-panel="pregacao" <?= $activeExpositorTab === 'pregacao' ? '' : 'hidden' ?>>
        <div class="hub-panel__row">
            <div>
                <h2 class="hub-panel__title">Workspace de pregação</h2>
                <p class="hub-panel__text">Preencha a base pastoral e gere um esboço pronto para revisão.</p>
            </div>
            <div class="hub-badge <?= $canGenerate ? 'hub-badge--success' : 'hub-badge--warning' ?>">
                <?= $canGenerate ? 'Geração liberada' : 'Sem créditos suficientes' ?>
            </div>
        </div>

        <?php if (!$canGenerate): ?>
            <div class="alert alert--warning" role="alert">
                Você pode usar as 3 gerações gratuitas do mês ou comprar créditos para continuar.
                <a href="<?= url('/hub/creditos') ?>" class="text-primary font-bold">Comprar créditos</a>
            </div>
        <?php endif; ?>

        <div class="expositor-layout">
            <form method="POST" action="<?= url('/hub/expositor-ia/gerar') ?>" class="hub-mini-card" data-loading>
                <?= csrf_field() ?>
                <input type="hidden" name="content_type" value="sermon">
                <input type="hidden" name="resource_title" value="">

                <div class="form-group">
                    <label class="form-label" for="ia-passage">Passagem bíblica</label>
                    <input id="ia-passage" type="text" name="passage" class="form-input" value="<?= e((string) ($form['passage'] ?? '')) ?>" placeholder="Ex.: Efésios 2:1-10" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="ia-theme">Tema / Ênfase</label>
                    <input id="ia-theme" type="text" name="theme" class="form-input" value="<?= e((string) ($form['theme'] ?? '')) ?>" placeholder="Ex.: Salvos pela graça">
                </div>

                <div class="form-group">
                    <label class="form-label" for="ia-confessional">Linha teológica / confessional</label>
                    <select id="ia-confessional" name="confessional" class="form-select">
                        <?php foreach (($confessionalOptions ?? []) as $option): ?>
                            <option value="<?= e((string) ($option['value'] ?? '')) ?>" <?= (($form['confessional'] ?? '') === ($option['value'] ?? '')) ? 'selected' : '' ?>>
                                <?= e((string) ($option['label'] ?? '')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="ia-depth">Nível de profundidade</label>
                    <select id="ia-depth" name="depth" class="form-select">
                        <?php foreach (($depthOptions ?? []) as $option): ?>
                            <option value="<?= e((string) ($option['value'] ?? '')) ?>" <?= (($form['depth'] ?? '') === ($option['value'] ?? '')) ? 'selected' : '' ?>>
                                <?= e((string) ($option['label'] ?? '')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="expositor-form-grid">
                    <div class="form-group">
                        <label class="form-label" for="ia-duration">Duração</label>
                        <select id="ia-duration" class="form-select" name="duration">
                            <option>35 minutos</option>
                            <option>45 minutos</option>
                            <option>60 minutos</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="ia-audience">Público-alvo</label>
                        <select id="ia-audience" class="form-select" name="audience">
                            <option>Geral</option>
                            <option>Jovens</option>
                            <option>Casais</option>
                            <option>Novos convertidos</option>
                        </select>
                    </div>
                </div>

                <p class="hub-panel__text">
                    <strong>Custo:</strong> <?= e((string) $creditCost) ?> crédito por geração.
                </p>
                <button type="submit" class="btn btn--gold" style="width:100%;" <?= !$canGenerate ? 'disabled aria-disabled="true"' : '' ?>>
                    Gerar sermão
                </button>
            </form>

            <article class="hub-mini-card">
                <h2 class="hub-mini-card__title">Resultado</h2>
                <?php if (!empty($lastResult)): ?>
                    <div class="expositor-empty-result">
                        <strong>Material pronto para revisão</strong>
                        <p>Use o bloco "Material gerado" acima para publicar ou abrir o registro salvo na gestão.</p>
                    </div>
                <?php else: ?>
                    <div class="expositor-empty-result">
                        <strong>Aguardando geração</strong>
                        <p>O esboço aparecerá aqui para revisão pastoral e próximos passos.</p>
                    </div>
                    <ul class="expositor-next-list">
                        <li>Caminho exegético com 6 ênfases</li>
                        <li>Roteiro para PG, EBD e discipulado</li>
                        <li>Base para exportação em PDF/DOCX</li>
                    </ul>
                <?php endif; ?>
                <div class="hub-page__actions" style="margin-top:auto;">
                    <a href="<?= url('/hub/creditos') ?>" class="btn btn--outline">Comprar créditos</a>
                    <a href="https://wa.me/5513978008047" target="_blank" rel="noopener noreferrer" class="btn btn--ghost">Suporte no WhatsApp</a>
                </div>
            </article>
        </div>
    </div>

    <section id="expositor-panel-estudos" class="hub-panel expositor-panel" data-expositor-panel="estudos" <?= $activeExpositorTab === 'estudos' ? '' : 'hidden' ?>>
        <div class="hub-panel__row">
            <div>
                <h2 class="hub-panel__title">Estudos bíblicos</h2>
                <p class="hub-panel__text">Escolha um caminho de estudo e prepare base bíblica antes da aplicação ministerial.</p>
            </div>
            <div class="hub-badge <?= $canGenerate ? 'hub-badge--success' : 'hub-badge--warning' ?>">
                <?= $canGenerate ? 'Geração liberada' : 'Sem créditos suficientes' ?>
            </div>
        </div>
        <div class="expositor-study-grid">
            <form method="POST" action="<?= url('/hub/expositor-ia/gerar') ?>" class="hub-mini-card" data-loading>
                <?= csrf_field() ?>
                <input type="hidden" name="content_type" value="study">
                <input type="hidden" name="resource_title" value="">
                <input type="hidden" name="depth" value="teologico">
                <input type="hidden" name="confessional" value="biblico-evangelico">
                <h3 class="hub-mini-card__title">Estudo do texto</h3>
                <div class="form-group">
                    <label class="form-label" for="ia-study-passage">Passagem bíblica</label>
                    <input id="ia-study-passage" type="text" name="passage" class="form-input" placeholder="Ex.: Romanos 8:28-39" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="ia-study-theme">Ênfase do estudo</label>
                    <input id="ia-study-theme" type="text" name="theme" class="form-input" placeholder="Ex.: Segurança do crente">
                </div>
                <button type="submit" class="btn btn--primary" <?= !$canGenerate ? 'disabled aria-disabled="true"' : '' ?>>Gerar estudo</button>
            </form>

            <form method="POST" action="<?= url('/hub/expositor-ia/gerar') ?>" class="hub-mini-card" data-loading>
                <?= csrf_field() ?>
                <input type="hidden" name="content_type" value="study">
                <input type="hidden" name="resource_title" value="Aula EBD avulsa">
                <input type="hidden" name="depth" value="pastoral">
                <input type="hidden" name="confessional" value="biblico-evangelico">
                <h3 class="hub-mini-card__title">Aula EBD avulsa</h3>
                <div class="form-group">
                    <label class="form-label" for="ia-ebd-passage">Passagem ou tema</label>
                    <input id="ia-ebd-passage" type="text" name="passage" class="form-input" placeholder="Ex.: Efésios 2:1-10" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="ia-ebd-theme">Público-alvo</label>
                    <input id="ia-ebd-theme" type="text" name="theme" class="form-input" placeholder="Ex.: Classe de adultos">
                </div>
                <button type="submit" class="btn btn--primary" <?= !$canGenerate ? 'disabled aria-disabled="true"' : '' ?>>Gerar aula</button>
            </form>

            <form method="POST" action="<?= url('/hub/expositor-ia/gerar') ?>" class="hub-mini-card" data-loading>
                <?= csrf_field() ?>
                <input type="hidden" name="content_type" value="reading_plan">
                <input type="hidden" name="resource_title" value="Plano de leitura">
                <input type="hidden" name="depth" value="pastoral">
                <input type="hidden" name="confessional" value="biblico-evangelico">
                <h3 class="hub-mini-card__title">Plano de leitura</h3>
                <div class="form-group">
                    <label class="form-label" for="ia-reading-passage">Livro ou percurso</label>
                    <input id="ia-reading-passage" type="text" name="passage" class="form-input" placeholder="Ex.: Salmos em 30 dias" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="ia-reading-theme">Objetivo pastoral</label>
                    <input id="ia-reading-theme" type="text" name="theme" class="form-input" placeholder="Ex.: oração e confiança diária">
                </div>
                <button type="submit" class="btn btn--primary" <?= !$canGenerate ? 'disabled aria-disabled="true"' : '' ?>>Gerar plano</button>
            </form>
        </div>
    </section>

    <section id="expositor-panel-treinamento" class="hub-panel expositor-panel" data-expositor-panel="treinamento" <?= $activeExpositorTab === 'treinamento' ? '' : 'hidden' ?>>
        <div class="hub-panel__row">
            <div>
                <h2 class="hub-panel__title">Recursos do ministério</h2>
                <p class="hub-panel__text">Caminhos independentes para gerar material pastoral com a mesma identidade visual do painel.</p>
            </div>
        </div>
        <div class="expositor-resource-grid">
            <?php foreach ($resources as $resource): ?>
                <article class="expositor-resource-card expositor-resource-card--<?= e($resource['accent']) ?>">
                    <span class="expositor-resource-card__icon" aria-hidden="true"><?= $resourceIcons[$resource['key']] ?? $resourceIcons['ebd'] ?></span>
                    <h3><?= e($resource['title']) ?></h3>
                    <p><?= e($resource['text']) ?></p>
                    <button
                        type="button"
                        class="expositor-link-button"
                        data-expositor-resource
                        data-resource-title="<?= e($resource['title']) ?>"
                        data-resource-text="<?= e($resource['text']) ?>"
                        data-resource-action="<?= e($resource['action']) ?>"
                    >Acessar</button>
                </article>
            <?php endforeach; ?>
        </div>
        <article class="expositor-resource-detail" id="expositor-resource-detail" hidden>
            <div>
                <span class="hub-badge hub-badge--success">Recurso selecionado</span>
                <h3 data-resource-detail-title>Escolha um recurso</h3>
                <p data-resource-detail-text>Selecione uma opção acima para preparar o fluxo ministerial.</p>
            </div>
            <form method="POST" action="<?= url('/hub/expositor-ia/gerar') ?>" data-loading>
                <?= csrf_field() ?>
                <input type="hidden" name="content_type" value="resource">
                <input type="hidden" name="resource_title" value="" data-resource-detail-input>
                <input type="hidden" name="confessional" value="biblico-evangelico">
                <input type="hidden" name="depth" value="pastoral">
                <div class="form-group">
                    <label class="form-label" for="ia-resource-context">Contexto ministerial</label>
                    <textarea id="ia-resource-context" name="passage" class="form-input" rows="4" placeholder="Descreva público, objetivo, etapa atual e necessidade pastoral." required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="ia-resource-theme">Tema ou foco</label>
                    <input id="ia-resource-theme" name="theme" class="form-input" placeholder="Ex.: Família e aliança">
                </div>
                <button type="submit" class="btn btn--primary" data-resource-detail-action <?= !$canGenerate ? 'disabled aria-disabled="true"' : '' ?>>Gerar recurso</button>
            </form>
        </article>
    </section>
</section>

<?php $__view->endSection(); ?>
