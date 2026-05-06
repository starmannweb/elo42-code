<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

<style>
    .ministry-ai-shortcuts {
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    }
    @media (max-width: 1200px) {
        .ministry-ai-shortcuts {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }
    @media (max-width: 600px) {
        .ministry-ai-shortcuts {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<?php
    $modules = is_array($ministryAiModules ?? null) ? $ministryAiModules : [];
    $workflowsByModule = is_array($ministryAiWorkflows ?? null) ? $ministryAiWorkflows : [];
    $allWorkflows = is_array($ministryAiAllWorkflows ?? null) ? $ministryAiAllWorkflows : [];
    $generateUrl = (string) ($ministryAiGenerateUrl ?? url('/hub/ministry-ai/generate'));
    $token = (string) ($csrfToken ?? csrf_token());
    $credits = (int) ($iaCredits ?? 0);
    $creditCost = (int) ($iaCreditCost ?? 1);
?>

<section class="hub-page ministry-ai-page" data-ministry-ai>
    <header class="hub-page__header">
        <div>
            <h1 class="hub-page__title">Central Pastoral IA</h1>
            <p class="hub-page__subtitle">Prepare sermões, estudos, aulas e planejamentos ministeriais com apoio inteligente, sem perder o cuidado pastoral.</p>
        </div>
        <div class="hub-page__actions">
            <a href="<?= url('/hub/créditos') ?>" class="btn btn--outline">Ver créditos</a>
        </div>
    </header>

    <div class="ministry-ai-usage">
        <div>
            <strong>Plano gratuito</strong>
            <span>3 gerações mensais liberadas. Depois disso, cada geração consome <?= e((string) $creditCost) ?> crédito.</span>
        </div>
        <div class="ministry-ai-usage__meter">
            <strong data-credit-count><?= e((string) $credits) ?> crédito(s) disponíveis</strong>
            <span><i style="width:<?= $credits > 0 ? min(100, $credits * 34) : 0 ?>%;"></i></span>
        </div>
    </div>

    <?php if (!empty($monthlyAllowanceGranted)): ?>
        <div class="alert alert--success" style="margin-top:var(--space-4);">
            3 gerações gratuitas foram adicionadas ao saldo deste workspace.
        </div>
    <?php endif; ?>

    <section class="ministry-ai-shortcuts" aria-label="Caminhos de trabalho">
        <div class="ministry-ai-shortcut" role="button" tabindex="0" data-shortcut-module="pregacao" data-shortcut-workflow="gerar_sermao">
            <span class="ministry-ai-card-icon">✎</span>
            <small>Caminho livre</small>
            <strong>Organizar um sermão avulso</strong>
            <p>Gere sermão, estudo bíblico, aula EBD ou roteiro para culto especial de forma rápida.</p>
            <b>Começar</b>
        </div>
        <div class="ministry-ai-shortcut" role="button" tabindex="0" data-shortcut-module="planejamento" data-shortcut-workflow="series_sermoes">
            <span class="ministry-ai-card-icon">▦</span>
            <small>Caminho ministerial</small>
            <strong>Estou planejando o trimestre</strong>
            <p>Crie séries com PG e EBD alinhados, mantendo direção teológica em todos os encontros.</p>
            <b>Começar</b>
        </div>
        <div class="ministry-ai-shortcut" role="button" tabindex="0" data-shortcut-module="estudos" data-shortcut-workflow="estudo_exegetico">
            <span class="ministry-ai-card-icon">⌕</span>
            <small>Caminho exegético</small>
            <strong>Quero partir do texto</strong>
            <p>Aprofunde contexto, estrutura e aplicação antes de desenvolver o material.</p>
            <b>Começar</b>
        </div>
        <div class="ministry-ai-shortcut" role="button" tabindex="0" data-shortcut-module="planejamento" data-shortcut-workflow="plano_anual_igreja">
            <span class="ministry-ai-card-icon">◎</span>
            <small>Caminho estratégico</small>
            <strong>Plano Anual da Igreja</strong>
            <p>Estruture discernimento pastoral, pilares e ações para o ano de forma inteligente.</p>
            <b>Começar</b>
        </div>
    </section>

    <section class="hub-panel ministry-ai-vision">
        <div class="hub-panel__head">
            <div>
                <h2 class="hub-panel__title">Impressões e dados de dashboard</h2>
                <p class="hub-panel__text">Use este quadro como ponto de partida para alinhar séries, pequenos grupos e escola dominical.</p>
            </div>
            <button type="button" class="btn btn--outline" data-shortcut-module="planejamento" data-shortcut-workflow="plano_anual_igreja">Definir foco</button>
        </div>
        <div class="ministry-ai-vision__grid" style="display:grid; grid-template-columns: repeat(6, 1fr); gap: 1rem; margin-top: 1.5rem;">
            <article class="mgmt-card" style="padding: 1.25rem; background: var(--color-bg-light, #f8faff); border: 1px solid var(--color-border-light, #dfe7f4);">
                <span style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Séries</span>
                <strong style="display: block; font-size: 1.75rem; margin-top: 0.25rem; color: var(--color-primary);">0</strong>
            </article>
            <article class="mgmt-card" style="padding: 1.25rem; background: var(--color-bg-light, #f8faff); border: 1px solid var(--color-border-light, #dfe7f4);">
                <span style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Sermões gerados</span>
                <strong style="display: block; font-size: 1.75rem; margin-top: 0.25rem; color: var(--color-primary);">0</strong>
            </article>
            <article class="mgmt-card" style="padding: 1.25rem; background: var(--color-bg-light, #f8faff); border: 1px solid var(--color-border-light, #dfe7f4);">
                <span style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Estudos criados</span>
                <strong style="display: block; font-size: 1.75rem; margin-top: 0.25rem; color: var(--color-primary);">0</strong>
            </article>
            <article class="mgmt-card" style="padding: 1.25rem; background: var(--color-bg-light, #f8faff); border: 1px solid var(--color-border-light, #dfe7f4);">
                <span style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Culto / Série</span>
                <strong style="display: block; font-size: 1rem; margin-top: 0.25rem; color: var(--text-muted);">Não iniciado</strong>
            </article>
            <article class="mgmt-card" style="padding: 1.25rem; background: var(--color-bg-light, #f8faff); border: 1px solid var(--color-border-light, #dfe7f4);">
                <span style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Pequenos Grupos</span>
                <strong style="display: block; font-size: 1rem; margin-top: 0.25rem; color: var(--text-muted);">Não iniciado</strong>
            </article>
            <article class="mgmt-card" style="padding: 1.25rem; background: var(--color-bg-light, #f8faff); border: 1px solid var(--color-border-light, #dfe7f4);">
                <span style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em;">Escola Dominical</span>
                <strong style="display: block; font-size: 1rem; margin-top: 0.25rem; color: var(--text-muted);">Não iniciado</strong>
            </article>
        </div>
        <div class="ministry-ai-vision__notice">
            <strong>Próxima ação</strong>
            <span>Defina um foco para começar a medir coerência ministerial.</span>
        </div>
    </section>

    <div class="ministry-ai-layout">
        <nav class="ministry-ai-module-list ministry-ai-tabs" data-module-list aria-label="Módulos da Central Pastoral IA">
            <?php foreach ($modules as $index => $module): ?>
                <button type="button" class="ministry-ai-module <?= $index === 0 ? 'is-active' : '' ?>" data-module-id="<?= e((string) $module['id']) ?>">
                    <strong><?= e((string) $module['title']) ?></strong>
                    <span><?= e((string) $module['description']) ?></span>
                </button>
            <?php endforeach; ?>
        </nav>

        <div class="ministry-ai-main">
            <section class="hub-panel">
                <div class="hub-panel__head">
                    <div>
                        <h2 class="hub-panel__title" data-workflow-section-title>Recursos do ministério</h2>
                        <p class="hub-panel__text">Cada recurso monta um prompt especializado a partir do contexto informado.</p>
                    </div>
                </div>
                <div class="ministry-ai-workflows" data-workflow-list></div>
            </section>

            <section class="hub-panel ministry-ai-form-panel">
                <div class="hub-panel__head">
                    <div>
                        <h2 class="hub-panel__title" data-workflow-title>Formulário inteligente</h2>
                        <p class="hub-panel__text" data-workflow-description>Preencha os campos para revisar antes da geração.</p>
                    </div>
                </div>
                <div class="ministry-ai-insights" data-workflow-insights></div>
                <form class="ministry-ai-form" data-workflow-form novalidate></form>
            </section>

            <section class="hub-panel">
                <div class="hub-panel__head">
                    <div>
                        <h2 class="hub-panel__title">Revisão</h2>
                        <p class="hub-panel__text">Confira o resumo antes de enviar para a IA.</p>
                    </div>
                </div>
                <div class="ministry-ai-review" data-review-box>
                    <p class="hub-panel__text">Escolha um recurso para começar.</p>
                </div>
                <div class="hub-page__actions" style="margin-top:var(--space-4);justify-content:flex-end;">
                    <button type="button" class="btn btn--primary btn--lg" data-generate-button <?= $credits >= $creditCost ? '' : 'disabled' ?>>
                        Gerar material
                    </button>
                </div>
                <p class="form-hint" data-form-message></p>
            </section>

            <section class="hub-panel ministry-ai-result" data-result-section hidden>
                <div class="hub-panel__head">
                    <div>
                        <h2 class="hub-panel__title" data-result-title>Resultado</h2>
                        <p class="hub-panel__text">Markdown gerado para revisão pastoral.</p>
                    </div>
                    <span class="hub-badge hub-badge--success" data-result-model></span>
                </div>
                <pre data-result-markdown></pre>
                <div class="hub-page__actions" style="margin-top:var(--space-4);">
                    <button type="button" class="btn btn--outline" data-copy-result>Copiar</button>
                    <button type="button" class="btn btn--outline" data-download-result>Baixar Markdown</button>
                    <button type="button" class="btn btn--ghost" data-new-generation>Nova geração</button>
                    <button type="button" class="btn btn--ghost" disabled>Salvar em breve</button>
                </div>
            </section>
        </div>
    </div>
</section>

<script>
(function () {
    var modules = <?= json_encode($modules, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?> || [];
    var workflowsByModule = <?= json_encode($workflowsByModule, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?> || {};
    var allWorkflows = <?= json_encode($allWorkflows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?> || {};
    var generateUrl = <?= json_encode($generateUrl, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    var csrfToken = <?= json_encode($token, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    var credits = <?= (int) $credits ?>;
    var creditCost = <?= (int) $creditCost ?>;

    var activeModule = <?= json_encode($selectedModule ?? ($modules[0] ? $modules[0]['id'] : '')) ?>;
    var activeWorkflow = <?= json_encode($selectedWorkflow ?? '') ?>;
    var currentMarkdown = '';
    var isGenerating = false;

    var moduleButtons = document.querySelectorAll('[data-module-id]');
    var workflowList = document.querySelector('[data-workflow-list]');
    var form = document.querySelector('[data-workflow-form]');
    var reviewBox = document.querySelector('[data-review-box]');
    var title = document.querySelector('[data-workflow-title]');
    var description = document.querySelector('[data-workflow-description]');
    var insightsBox = document.querySelector('[data-workflow-insights]');
    var message = document.querySelector('[data-form-message]');
    var generateButton = document.querySelector('[data-generate-button]');
    var resultSection = document.querySelector('[data-result-section]');
    var resultMarkdown = document.querySelector('[data-result-markdown]');
    var resultTitle = document.querySelector('[data-result-title]');
    var resultModel = document.querySelector('[data-result-model]');
    var creditCount = document.querySelector('[data-credit-count]');

    function workflowWithId(id) {
        var workflow = allWorkflows[id] || null;
        if (workflow) workflow.id = id;
        return workflow;
    }

    function selectModule(moduleId, workflowId) {
        activeModule = moduleId || activeModule;
        activeWorkflow = workflowId || '';
        moduleButtons.forEach(function (item) {
            item.classList.toggle('is-active', item.getAttribute('data-module-id') === activeModule);
        });
        renderWorkflows();
        renderForm();
    }

    function renderWorkflows() {
        var workflows = workflowsByModule[activeModule] || [];
        var filteredWorkflows = workflows.filter(function (w) { return w.id !== 'plano_anual_igreja'; });
        
        if (!activeWorkflow && filteredWorkflows[0]) activeWorkflow = filteredWorkflows[0].id;
        if (!workflows.some(function (workflow) { return workflow.id === activeWorkflow; }) && filteredWorkflows[0]) {
            activeWorkflow = filteredWorkflows[0].id;
        }

        workflowList.innerHTML = filteredWorkflows.map(function (workflow) {
            var accent = workflow.accent || '#0A4DFF';
            return '<button type="button" class="ministry-ai-workflow ' + (workflow.id === activeWorkflow ? 'is-active' : '') + '" data-workflow-id="' + escapeHtml(workflow.id) + '" style="--workflow-accent:' + escapeHtml(accent) + '">' +
                '<span class="ministry-ai-workflow__icon">' + workflowIcon(workflow.icon || 'spark') + '</span>' +
                '<strong>' + escapeHtml(workflow.title) + '</strong>' +
                '<span>' + escapeHtml(workflow.description || '') + '</span>' +
                '</button>';
        }).join('');

        workflowList.querySelectorAll('[data-workflow-id]').forEach(function (button) {
            button.addEventListener('click', function () {
                activeWorkflow = button.getAttribute('data-workflow-id') || '';
                renderWorkflows();
                renderForm();
            });
        });
    }

    function renderInsights(workflow) {
        var insights = workflow.insights || [];
        insightsBox.innerHTML = insights.map(function (insight) {
            return '<article class="ministry-ai-insight" style="--workflow-accent:' + escapeHtml(workflow.accent || '#0A4DFF') + '">' +
                '<strong>' + escapeHtml(insight.title || '') + '</strong>' +
                '<ul>' + (insight.items || []).map(function (item) { return '<li>' + escapeHtml(item) + '</li>'; }).join('') + '</ul>' +
                '</article>';
        }).join('');
    }

    function renderForm() {
        var workflow = workflowWithId(activeWorkflow);
        if (!workflow) return;

        title.textContent = workflow.title || 'Formulario inteligente';
        description.textContent = workflow.description || '';
        renderInsights(workflow);

        form.innerHTML = (workflow.fields || []).map(function (field) {
            var required = field.required ? ' required' : '';
            var condition = field.condition || {};
            var conditionAttrs = condition.field ? ' data-condition-field="' + escapeHtml(condition.field) + '" data-condition-equals="' + escapeHtml(condition.equals || '') + '"' : '';
            var wide = field.type === 'textarea' ? ' ministry-ai-field--wide' : '';
            var label = '<label class="form-label" for="ministry_' + escapeHtml(field.name) + '">' + escapeHtml(field.label) + (field.required ? ' *' : '') + '</label>';
            if (field.type === 'textarea') {
                return '<div class="form-group ministry-ai-field' + wide + '"' + conditionAttrs + '>' + label +
                    '<textarea id="ministry_' + escapeHtml(field.name) + '" name="' + escapeHtml(field.name) + '" class="form-textarea" maxlength="8000" rows="5" placeholder="' + escapeHtml(field.placeholder || '') + '"' + required + '></textarea></div>';
            }
            if (field.type === 'select') {
                var options = (field.options || []).map(function (option) {
                    return '<option value="' + escapeHtml(option[0]) + '">' + escapeHtml(option[1]) + '</option>';
                }).join('');
                return '<div class="form-group ministry-ai-field"' + conditionAttrs + '>' + label +
                    '<select id="ministry_' + escapeHtml(field.name) + '" name="' + escapeHtml(field.name) + '" class="form-select"' + required + '>' + options + '</select></div>';
            }
            return '<div class="form-group ministry-ai-field"' + conditionAttrs + '>' + label +
                '<input id="ministry_' + escapeHtml(field.name) + '" name="' + escapeHtml(field.name) + '" class="form-input" maxlength="500" placeholder="' + escapeHtml(field.placeholder || '') + '"' + required + '></div>';
        }).join('');

        form.querySelectorAll('input, select, textarea').forEach(function (input) {
            input.addEventListener('input', function () { updateConditionalFields(); renderReview(); });
            input.addEventListener('change', function () { updateConditionalFields(); renderReview(); });
        });

        updateConditionalFields();
        message.textContent = '';
        renderReview();
    }

    function updateConditionalFields() {
        var payload = collectPayload(true);
        form.querySelectorAll('[data-condition-field]').forEach(function (group) {
            var field = group.getAttribute('data-condition-field') || '';
            var expected = group.getAttribute('data-condition-equals') || '';
            var visible = (payload[field] || '') === expected;
            group.hidden = !visible;
            group.querySelectorAll('input, select, textarea').forEach(function (input) {
                input.disabled = !visible;
            });
        });
    }

    function collectPayload(includeHidden) {
        var payload = {};
        form.querySelectorAll('input, select, textarea').forEach(function (input) {
            if (!includeHidden && input.disabled) return;
            payload[input.name] = input.value.trim();
        });
        return payload;
    }

    function renderReview() {
        var workflow = workflowWithId(activeWorkflow);
        if (!workflow) return;
        var payload = collectPayload(false);
        var items = (workflow.fields || []).map(function (field) {
            if (field.condition && field.condition.field && payload[field.condition.field] !== field.condition.equals) return '';
            var value = payload[field.name] || '';
            if (!value) return '';
            return '<li><strong>' + escapeHtml(field.label) + '</strong><span>' + escapeHtml(optionLabel(field, value)) + '</span></li>';
        }).filter(Boolean).join('');

        reviewBox.innerHTML = '<div class="ministry-ai-review__head"><strong>' + escapeHtml(workflow.title) + '</strong><span>' + escapeHtml(workflow.description || '') + '</span></div>' +
            (items ? '<ul>' + items + '</ul>' : '<p class="hub-panel__text">Preencha os campos para montar o resumo.</p>');
    }

    function optionLabel(field, value) {
        var found = (field.options || []).find(function (option) { return option[0] === value; });
        return found ? found[1] : value;
    }

    function validateRequired() {
        var workflow = workflowWithId(activeWorkflow);
        var payload = collectPayload(false);
        var missing = (workflow.fields || []).filter(function (field) {
            if (field.condition && field.condition.field && payload[field.condition.field] !== field.condition.equals) return false;
            return field.required && !(payload[field.name] || '').trim();
        });
        if (missing.length) {
            return 'Preencha: ' + missing.map(function (field) { return field.label; }).join(', ') + '.';
        }
        return '';
    }

    function setLoading(state) {
        isGenerating = state;
        generateButton.disabled = state || credits < creditCost;
        generateButton.textContent = state ? 'Gerando...' : 'Gerar material';
    }

    function generate() {
        if (isGenerating) return;
        var error = validateRequired();
        if (error) {
            message.textContent = error;
            message.style.color = '#dc2626';
            return;
        }
        if (credits < creditCost) {
            message.textContent = 'Voce nao possui creditos suficientes.';
            message.style.color = '#dc2626';
            return;
        }

        setLoading(true);
        message.textContent = 'Preparando material...';
        message.style.color = '';

        fetch(generateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify({
                module: activeModule,
                workflowId: activeWorkflow,
                inputPayload: collectPayload(false)
            })
        }).then(function (response) {
            return response.json().then(function (json) {
                if (!response.ok || !json.success) {
                    throw new Error(json.error || 'Nao foi possivel gerar agora.');
                }
                return json.data;
            });
        }).then(function (data) {
            currentMarkdown = data.outputMarkdown || '';
            resultTitle.textContent = data.title || 'Resultado';
            resultModel.textContent = data.modelUsed || '';
            resultMarkdown.textContent = currentMarkdown;
            resultSection.hidden = false;
            credits = Math.max(0, credits - creditCost);
            if (creditCount) creditCount.textContent = credits + ' credito(s) disponiveis';
            message.textContent = 'Material gerado. Revise antes de usar.';
            resultSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }).catch(function (err) {
            message.textContent = err.message || 'Nao foi possivel gerar agora.';
            message.style.color = '#dc2626';
        }).finally(function () {
            setLoading(false);
        });
    }

    function workflowIcon(name) {
        var icons = {
            pen: '✎',
            spark: '✦',
            heart: '♡',
            book: '▣',
            'book-open': '▤',
            search: '⌕',
            users: '♙',
            award: '⌾',
            network: '⌘',
            target: '◎',
            mic: '◉'
        };
        return icons[name] || '✦';
    }

    function escapeHtml(value) {
        return String(value || '').replace(/[&<>"']/g, function (char) {
            return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char]);
        });
    }

    moduleButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            selectModule(button.getAttribute('data-module-id') || activeModule, '');
        });
    });

    document.querySelectorAll('[data-shortcut-module][data-shortcut-workflow]').forEach(function (item) {
        item.addEventListener('click', function () {
            selectModule(item.getAttribute('data-shortcut-module') || '', item.getAttribute('data-shortcut-workflow') || '');
            document.querySelector('.ministry-ai-layout').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
        item.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                item.click();
            }
        });
    });

    generateButton.addEventListener('click', generate);
    document.querySelector('[data-copy-result]').addEventListener('click', function () {
        if (currentMarkdown && navigator.clipboard) navigator.clipboard.writeText(currentMarkdown);
    });
    document.querySelector('[data-download-result]').addEventListener('click', function () {
        if (!currentMarkdown) return;
        var blob = new Blob([currentMarkdown], { type: 'text/markdown;charset=utf-8' });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'central-pastoral-ia.md';
        link.click();
        URL.revokeObjectURL(link.href);
    });
    document.querySelector('[data-new-generation]').addEventListener('click', function () {
        currentMarkdown = '';
        resultSection.hidden = true;
        form.reset();
        updateConditionalFields();
        renderReview();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    renderWorkflows();
    renderForm();
})();
</script>

<?php $__view->endSection(); ?>
