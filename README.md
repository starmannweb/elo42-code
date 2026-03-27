# Elo 42 Platform

## Executar localmente (Windows)

1. Copie o arquivo de ambiente:
   - `Copy-Item .env.example .env`
2. Suba o servidor:
   - `./serve.ps1`
3. Abra no navegador:
   - `http://127.0.0.1:8080`

O script `serve.ps1` detecta automaticamente o PHP no `PATH` e tambem em instalacoes comuns (XAMPP).

## Executar migracoes

Use o mesmo executavel PHP para rodar:

```powershell
C:\xampp\php\php.exe migrate.php
```

## Deploy no GitHub (push)

```powershell
git add .
git commit -m "fix: ajustar execucao local e sessao"
git push origin master
```
