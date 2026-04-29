# Deploy no Napoleon

Este projeto roda em PHP 8.2+ com MySQL. O plano Business do Napoleon atende o uso esperado porque oferece PHP, bases MySQL, SSL e recursos de hospedagem compartilhada.

## Caminho recomendado

1. No painel Napoleon/DirectAdmin, crie o dominio ou subdominio.
2. Ative SSL para o dominio.
3. Selecione PHP 8.2 ou superior.
4. Crie uma base MySQL e um usuario com permissao total nessa base.
5. Via SSH/Terminal ou Git Manager, clone o repositorio:

```bash
git clone https://github.com/starmannweb/elo42-code.git elo42-code
cd elo42-code
```

6. Configure o document root do dominio para a pasta `public` do projeto, por exemplo:

```text
/home/USUARIO/elo42-code/public
```

7. Crie o arquivo `.env` a partir do modelo:

```bash
cp .env.napoleon.example .env
```

8. Edite o `.env` com o dominio e os dados do MySQL:

```env
APP_URL=https://SEU_DOMINIO.com.br
DB_HOST=localhost
DB_DATABASE=USUARIO_nome_do_banco
DB_USERNAME=USUARIO_usuario_do_banco
DB_PASSWORD=SENHA_FORTE_AQUI
SESSION_SECURE=true
APP_DEBUG=false
```

9. Rode as migracoes:

```bash
php migrate.php
```

10. Garanta permissao de escrita:

```bash
chmod -R 775 storage
chmod -R 775 public/storage
```

## Se o painel nao deixar apontar para /public

Tambem e possivel colocar o repositorio dentro de `public_html`. O arquivo `.htaccess` da raiz faz o fallback para `public/index.php`, libera `/assets`, `/sw.js`, `/manifest.json` e bloqueia acesso direto a `app`, `config`, `database`, `modules`, `.env` e outros arquivos privados.

Nesse modo, o caminho fica assim:

```text
/home/USUARIO/domains/SEU_DOMINIO/public_html
```

Depois rode os mesmos passos de `.env`, migracoes e permissoes.

## Atualizar depois do primeiro deploy

Quando houver novos commits na branch `master`:

```bash
cd /home/USUARIO/elo42-code
git pull origin master
php migrate.php
```

Se o projeto estiver dentro de `public_html`, rode os comandos dentro dessa pasta.

## Observacoes

- Nunca envie o arquivo `.env` para o Git.
- Se a tela ficar branca, confirme `APP_DEBUG=false`, permissao de `storage/logs` e dados do MySQL.
- Se assets nao carregarem, confirme que o dominio esta apontando para `public` ou que o `.htaccess` da raiz esta no `public_html`.
