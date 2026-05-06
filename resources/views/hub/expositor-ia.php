<?php $__view->extends('hub'); ?>

<?php $__view->section('content'); ?>

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
            <span class="hub-badge hub-badge--primary">ministry-ai</span>
            <h1 class="hub-page__title">Central Pastoral IA</h1>
            <p class="hub-page__subtitle">Prepare sermões, estudos, aulas e planejamentos ministeriais com apoio inteligente, sem perder o cuidado pastoral.</p>
        </div>
        <div class="hub-page__actions">
            <a href="<?= url('/hub/creditos') ?>" class="btn btn--outline">Ver créditos</a>
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

    <div class="ministry-ai-layout">
        <aside class="ministry-ai-sidebar hub-panel">
            <h2 class="hub-panel__title">Módulos</h2>
            <p class="hub-panel__text">Escolha a área pastoral e depois o fluxo de trabalho.</p>
            <div class="ministry-ai-module-list" data-module-list>
                <?php foreach ($modules as $index => $module): ?>
                    <button type="button" class="ministry-ai-module <?= $index === 0 ? 'is-active' : '' ?>" data-module-id="<?= e((string) $module['id']) ?>">
                        <strong><?= e((string) $module['title']) ?></strong>
                        <span><?= e((string) $module['description']) ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </aside>

        <div class="ministry-ai-main">
            <section class="hub-panel">
                <div class="hub-panel__head">
                    <div>
                        <h2 class="hub-panel__title">Fluxos disponíveis</h2>
                        <p class="hub-panel__text">Cada fluxo monta um prompt especializado a partir do contexto informado.</p>
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
                    <span class="hub-badge hub-badge--neutral" data-workflow-step-count>Revisão</span>
                </div>

                <div class="ministry-ai-steps" data-workflow-steps></div>
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
                    <p class="hub-panel__text">Escolha um fluxo para começar.</p>
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

    var activeModule = modules[0] ? modules[0].id : '';
    var activeWorkflow = '';
    var currentMarkdown = '';
    var isGenerating = false;

    var moduleButtons = document.querySelectorAll('[data-module-id]');
    var workflowList = document.querySelector('[data-workflow-list]');
    var form = document.querySelector('[data-workflow-form]');
    var reviewBox = document.querySelector('[data-review-box]');
    var title = document.querySelector('[data-workflow-title]');
    var description = document.querySelector('[data-workflow-description]');
    var stepsBox = document.querySelector('[data-workflow-steps]');
    var stepCount = document.querySelector('[data-workflow-step-count]');
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

    function renderWorkflows() {
        var workflows = workflowsByModule[activeModule] || [];
        if (!activeWorkflow && workflows[0]) activeWorkflow = workflows[0].id;
        if (!workflows.some(function (workflow) { return workflow.id === activeWorkflow; }) && workflows[0]) {
            activeWorkflow = workflows[0].id;
        }

        workflowList.innerHTML = workflows.map(function (workflow) {
            return '<button type="button" class="ministry-ai-workflow ' + (workflow.id === activeWorkflow ? 'is-active' : '') + '" data-workflow-id="' + escapeHtml(workflow.id) + '">' +
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

    function renderForm() {
        var workflow = workflowWithId(activeWorkflow);
        if (!workflow) return;

        title.textContent = workflow.title || 'Formulário inteligente';
        description.textContent = workflow.description || '';
        stepCount.textContent = (workflow.steps || []).length + ' etapas';
        stepsBox.innerHTML = (workflow.steps || []).map(function (step, index) {
            return '<span class="ministry-ai-step"><b>' + (index + 1) + '</b>' + escapeHtml(step) + '</span>';
        }).join('');

        form.innerHTML = (workflow.fields || []).map(function (field) {
            var required = field.required ? ' required' : '';
            var label = '<label class="form-label" for="ministry_' + escapeHtml(field.name) + '">' + escapeHtml(field.label) + (field.required ? ' *' : '') + '</label>';
            if (field.type === 'textarea') {
                return '<div class="form-group ministry-ai-field ministry-ai-field--wide">' + label +
                    '<textarea id="ministry_' + escapeHtml(field.name) + '" name="' + escapeHtml(field.name) + '" class="form-textarea" maxlength="8000" rows="5" placeholder="' + escapeHtml(field.placeholder || '') + '"' + required + '></textarea></div>';
            }
            if (field.type === 'select') {
                var options = (field.options || []).map(function (option) {
                    return '<option value="' + escapeHtml(option[0]) + '">' + escapeHtml(option[1]) + '</option>';
                }).join('');
                return '<div class="form-group ministry-ai-field">' + label +
                    '<select id="ministry_' + escapeHtml(field.name) + '" name="' + escapeHtml(field.name) + '" class="form-select"' + required + '>' + options + '</select></div>';
            }
            return '<div class="form-group ministry-ai-field">' + label +
                '<input id="ministry_' + escapeHtml(field.name) + '" name="' + escapeHtml(field.name) + '" class="form-input" maxlength="500" placeholder="' + escapeHtml(field.placeholder || '') + '"' + required + '></div>';
        }).join('');

        form.querySelectorAll('input, select, textarea').forEach(function (input) {
            input.addEventListener('input', renderReview);
            input.addEventListener('change', renderReview);
        });

        message.textContent = '';
        renderReview();
    }

    function collectPayload() {
        var payload = {};
        form.querySelectorAll('input, select, textarea').forEach(function (input) {
            payload[input.name] = input.value.trim();
        });
        return payload;
    }

    function renderReview() {
        var workflow = workflowWithId(activeWorkflow);
        if (!workflow) return;
        var payload = collectPayload();
        var items = (workflow.fields || []).map(function (field) {
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
        var payload = collectPayload();
        var missing = (workflow.fields || []).filter(function (field) {
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
            message.textContent = 'Você não possui créditos suficientes.';
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
                inputPayload: collectPayload()
            })
        }).then(function (response) {
            return response.json().then(function (json) {
                if (!response.ok || !json.success) {
                    throw new Error(json.error || 'Não foi possível gerar agora.');
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
            if (creditCount) creditCount.textContent = credits + ' crédito(s) disponíveis';
            message.textContent = 'Material gerado. Revise antes de usar.';
            resultSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }).catch(function (err) {
            message.textContent = err.message || 'Não foi possível gerar agora.';
            message.style.color = '#dc2626';
        }).finally(function () {
            setLoading(false);
        });
    }

    function escapeHtml(value) {
        return String(value || '').replace(/[&<>"']/g, function (char) {
            return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char]);
        });
    }

    moduleButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            activeModule = button.getAttribute('data-module-id') || activeModule;
            activeWorkflow = '';
            moduleButtons.forEach(function (item) { item.classList.toggle('is-active', item === button); });
            renderWorkflows();
            renderForm();
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
        renderReview();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    renderWorkflows();
    renderForm();
})();
</script>

<?php $__view->endSection(); ?>
