# Elo 42 Platform

## Executar localmente (Windows)

1. Copie o arquivo de ambiente:
   - `Copy-Item .env.example .env`
2. Suba o servidor:
   - `powershell -ExecutionPolicy Bypass -File .\serve.ps1`
3. Abra no navegador:
   - `http://127.0.0.1:8080`

O script `serve.ps1` detecta automaticamente o PHP no `PATH` e tambem em instalacoes comuns (XAMPP).

## Executar migracoes

Use o mesmo executavel PHP para rodar:

```powershell
C:\xampp\php\php.exe migrate.php
```

## Deploy no GitHub

```powershell
git add .
git commit -m "chore: atualizar deploy"
git push origin master
```

## Deploy publico com Render

Este repositorio ja inclui `Dockerfile` e `render.yaml`.

1. Conecte o repositorio no Render.
2. Selecione **Blueprint** e use o arquivo `render.yaml`.
3. O Render vai buildar e publicar automaticamente.

## CI e deploy automatico no GitHub Actions

- Workflow de validacao: `.github/workflows/ci.yml`
- Workflow de deploy Render: `.github/workflows/deploy-render.yml`

Para ativar deploy automatico para o Render via hook:

1. Crie um Deploy Hook no Render.
2. No GitHub, adicione o secret `RENDER_DEPLOY_HOOK_URL` com a URL do hook.
3. Cada push na branch `master` vai disparar o deploy.
