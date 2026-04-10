<?php $__view->extends('layouts/management'); ?>

<?php $__view->section('content'); ?>
<div class="mgmt-header">
    <div>
        <h1 class="mgmt-title">Inteligência Artificial</h1>
        <p class="mgmt-subtitle">Configure a integração com OpenAI para transcrição e geração de ministrações</p>
    </div>
    <div class="mgmt-actions">
        <a href="<?= url('/gestao') ?>" class="btn btn--ghost">Cancelar</a>
        <button type="submit" form="form-ia" class="btn btn--primary">Salvar Configurações</button>
    </div>
</div>

<div class="mgmt-panel" style="margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--color-text-primary); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            Status da IA
        </h3>
        <span style="font-size: 0.75rem; background: var(--color-bg-light); color: var(--text-muted); padding: 4px 8px; border-radius: 12px; font-weight: 600; display: flex; align-items: center; gap: 0.25rem;">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            Inativa
        </span>
    </div>
    <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">Configure sua chave API da OpenAI para habilitar as funcionalidades de IA.</p>
</div>

<div class="mgmt-panel" style="margin-bottom: 1.5rem;">
    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--color-text-primary); margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
        Chave API OpenAI
    </h3>
    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Sua chave API é armazenada de forma segura e utilizada apenas para processar ministrações.</p>
    
    <form id="form-ia" action="#" method="POST">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="openai_key">Chave API</label>
            <div style="display: flex; gap: 0.5rem;">
                <div style="position: relative; flex: 1;">
                    <input type="password" id="openai_key" name="openai_key" class="form-control" placeholder="sk-..." value="">
                    <button type="button" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                </div>
                <button type="button" class="btn btn--outline" style="padding: 0.5rem 1.5rem;">Testar</button>
            </div>
        </div>
        
        <div style="margin-top: 1.5rem; padding: 1.5rem; background: rgba(0,0,0,0.02); border: 1px solid var(--color-border-light); border-radius: 8px;">
            <h4 style="font-size: 0.95rem; margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--color-text-primary);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
                Como obter sua chave API
            </h4>
            <ol style="margin: 0; padding-left: 1.5rem; color: var(--text-muted); font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.5rem;">
                <li>Acesse <a href="https://platform.openai.com" target="_blank" style="color: var(--color-primary); text-decoration: none;">platform.openai.com</a></li>
                <li>Faça login ou crie uma conta</li>
                <li>Vá em "API Keys" e clique em "Create new secret key"</li>
                <li>Copie a chave e cole aqui</li>
            </ol>
            <div style="margin-top: 1rem; font-size: 0.85rem; color: #f59e0b; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                Dica: Você precisa ter créditos na sua conta OpenAI para usar a API.
            </div>
        </div>
    </form>
</div>

<div class="mgmt-panel">
    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--color-text-primary); margin: 0 0 1rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
        Modelos de IA
    </h3>
    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Escolha os modelos para cada tipo de tarefa</p>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem; color: #f59e0b;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                Modelo para Análise
            </label>
            <select class="form-control" name="model_analysis">
                <option value="gpt-4o-mini">GPT-4o Mini</option>
                <option value="gpt-4o">GPT-4o</option>
            </select>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Usado para transcrição de áudio e análise de ministrações</div>
        </div>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem; color: #8b5cf6;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"></path></svg>
                Modelo para Geração
            </label>
            <select class="form-control" name="model_generation">
                <option value="gpt-4o">GPT-4o</option>
                <option value="gpt-4o-mini">GPT-4o Mini</option>
            </select>
            <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Usado para criar ministrações com IA</div>
        </div>
    </div>
</div>
<?php $__view->endSection(); ?>
