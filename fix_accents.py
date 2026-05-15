import os
import re

entities = {
    '&aacute;': 'á', '&eacute;': 'é', '&iacute;': 'í', '&oacute;': 'ó', '&uacute;': 'ú',
    '&atilde;': 'ã', '&otilde;': 'õ', '&ccedil;': 'ç',
    '&Agrave;': 'À', '&Aacute;': 'Á', '&Eacute;': 'É', '&Iacute;': 'Í', '&Oacute;': 'Ó', '&Uacute;': 'Ú',
    '&Atilde;': 'Ã', '&Otilde;': 'Õ', '&Ccedil;': 'Ç',
    '&ecirc;': 'ê', '&Ecirc;': 'Ê', '&ocirc;': 'ô', '&Ocirc;': 'Ô', '&acirc;': 'â', '&Acirc;': 'Â'
}

words = {
    'orientacao': 'orientação',
    'Dizimos': 'Dízimos',
    'dizimos': 'dízimos',
    'Gestao': 'Gestão',
    'gestao': 'gestão',
    'organizacao': 'organização',
    'Organizacao': 'Organização',
    'configuracoes': 'configurações',
    'Configuracoes': 'Configurações',
    'configuracao': 'configuração',
    'Configuracao': 'Configuração',
    'relatorios': 'relatórios',
    'Relatorios': 'Relatórios',
    'relatorio': 'relatório',
    'Relatorio': 'Relatório',
    'sermoes': 'sermões',
    'Sermoes': 'Sermões',
    'aniversarios': 'aniversários',
    'Aniversarios': 'Aniversários',
    'notificacoes': 'notificações',
    'Notificacoes': 'Notificações',
    'notificacao': 'notificação',
    'Notificacao': 'Notificação',
    'integracao': 'integração',
    'Integracao': 'Integração',
    'organizacoes': 'organizações',
    'Organizacoes': 'Organizações',
    'servicos': 'serviços',
    'Servicos': 'Serviços',
    'promocoes': 'promoções',
    'Promocoes': 'Promoções',
    'etaria': 'etária',
    'Etaria': 'Etária',
    'genero': 'gênero',
    'Genero': 'Gênero',
    'usuario': 'usuário',
    'Usuario': 'Usuário',
    'usuarios': 'usuários',
    'Usuarios': 'Usuários',
    'instituicao': 'instituição',
    'Instituicao': 'Instituição',
    'instituicoes': 'instituições',
    'Instituicoes': 'Instituições',
    'descricao': 'descrição',
    'Descricao': 'Descrição',
    'acoes': 'ações',
    'Acoes': 'Ações',
    'situacao': 'situação',
    'Situacao': 'Situação',
    'padrao': 'padrão',
    'Padrao': 'Padrão',
    'edicao': 'edição',
    'Edicao': 'Edição',
    'atencao': 'atenção',
    'Atencao': 'Atenção',
    'confirmacao': 'confirmação',
    'Confirmacao': 'Confirmação',
    'exclusao': 'exclusão',
    'Exclusao': 'Exclusão',
    'publicacao': 'publicação',
    'Publicacao': 'Publicação',
    'reuniao': 'reunião',
    'Reuniao': 'Reunião',
    'sessao': 'sessão',
    'Sessao': 'Sessão',
    'indisponivel': 'indisponível',
    'Indisponivel': 'Indisponível',
    'referencia': 'referência',
    'Referencia': 'Referência',
    'exposicao': 'exposição',
    'Exposicao': 'Exposição',
    'atencao': 'atenção',
    'Atencao': 'Atenção'
}

def fix_content(content):
    # 1. Replace entities
    for entity, utf8 in entities.items():
        content = content.replace(entity, utf8)
    
    # 2. Replace words
    for word, accented in words.items():
        pattern = r'(?<![\$\->/\[])\b' + re.escape(word) + r'\b(?!\s*=>)(?![\[\]])'
        content = re.sub(pattern, accented, content)
    
    return content

updated_files = []
root_dir = os.path.join(os.getcwd(), 'resources', 'views')

for root, dirs, files in os.walk(root_dir):
    for file in files:
        if file.endswith('.php'):
            path = os.path.join(root, file)
            try:
                with open(path, 'r', encoding='utf-8') as f:
                    content = f.read()
                
                new_content = fix_content(content)
                
                if new_content != content:
                    with open(path, 'w', encoding='utf-8', newline='') as f:
                        f.write(new_content)
                    updated_files.append(os.path.relpath(path, os.getcwd()))
            except Exception as e:
                print(f"Error processing {path}: {e}")

print(f"Total files updated in this run: {len(updated_files)}")
for f in updated_files:
    print(f"- {f}")
