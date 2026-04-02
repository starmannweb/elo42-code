<?php

return [
    'up' => "
        INSERT IGNORE INTO roles (name, slug, description, is_system) VALUES
        ('Super Admin', 'super-admin', 'Acesso total à plataforma Elo 42', 1),
        ('Admin Elo 42', 'admin-elo42', 'Administrador interno da plataforma', 1),
        ('Gestor da Organização', 'org-manager', 'Gestor principal de uma organização parceira', 1),
        ('Operador', 'org-operator', 'Operador da organização com acesso aos módulos', 1),
        ('Usuário', 'user', 'Usuário comum com acesso básico', 1);

        INSERT IGNORE INTO permissions (name, slug, module, description) VALUES
        ('Acessar Admin', 'admin.access', 'admin', 'Acesso ao painel administrativo central'),
        ('Gerenciar Usuários', 'users.manage', 'admin', 'Criar, editar e remover usuários'),
        ('Gerenciar Organizações', 'organizations.manage', 'admin', 'Gerenciar organizações da plataforma'),
        ('Acessar Hub', 'hub.access', 'hub', 'Acesso ao hub autenticado'),
        ('Gerenciar Membros', 'members.manage', 'church', 'Cadastrar e gerenciar membros'),
        ('Gerenciar Finanças', 'finance.manage', 'church', 'Controle financeiro da organização'),
        ('Gerenciar Eventos', 'events.manage', 'church', 'Criar e gerenciar eventos'),
        ('Gerenciar Grupos', 'groups.manage', 'church', 'Gerenciar grupos e células'),
        ('Gerenciar Comunicação', 'communication.manage', 'church', 'Enviar comunicados e avisos'),
        ('Visualizar Relatórios', 'reports.view', 'church', 'Acessar dashboards e relatórios'),
        ('Gerenciar Configurações', 'settings.manage', 'church', 'Configurar organização');

        INSERT IGNORE INTO role_permissions (role_id, permission_id)
        SELECT r.id, p.id FROM roles r CROSS JOIN permissions p
        WHERE r.slug = 'super-admin';

        INSERT IGNORE INTO role_permissions (role_id, permission_id)
        SELECT r.id, p.id FROM roles r CROSS JOIN permissions p
        WHERE r.slug = 'admin-elo42' AND p.slug IN ('admin.access', 'users.manage', 'organizations.manage', 'hub.access');

        INSERT IGNORE INTO role_permissions (role_id, permission_id)
        SELECT r.id, p.id FROM roles r CROSS JOIN permissions p
        WHERE r.slug = 'org-manager' AND p.module IN ('hub', 'church');

        INSERT IGNORE INTO role_permissions (role_id, permission_id)
        SELECT r.id, p.id FROM roles r CROSS JOIN permissions p
        WHERE r.slug = 'org-operator' AND p.slug IN ('hub.access', 'members.manage', 'events.manage', 'groups.manage', 'communication.manage', 'reports.view');

        INSERT IGNORE INTO role_permissions (role_id, permission_id)
        SELECT r.id, p.id FROM roles r CROSS JOIN permissions p
        WHERE r.slug = 'user' AND p.slug = 'hub.access'
    ",
    'down' => "
        DELETE FROM role_permissions;
        DELETE FROM permissions;
        DELETE FROM roles WHERE is_system = 1
    ",
];
