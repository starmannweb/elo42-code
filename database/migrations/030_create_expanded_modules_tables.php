<?php

return [
    'up' => "
        -- Adicionar role 'member' para membros da igreja
        INSERT IGNORE INTO roles (name, slug, description, is_system) VALUES
        ('Membro da Igreja', 'member', 'Membro da congregação com acesso à área de membros', 1);

        -- Permissões para membros
        INSERT IGNORE INTO permissions (name, slug, module, description) VALUES
        ('Acessar Área do Membro', 'member.access', 'member', 'Acesso à área do membro da igreja'),
        ('Ver Meus Eventos', 'member.events', 'member', 'Visualizar eventos da igreja'),
        ('Ver Minhas Doações', 'member.donations', 'member', 'Visualizar doações pessoais'),
        ('Fazer Pedidos', 'member.requests', 'member', 'Criar pedidos para a igreja');

        INSERT IGNORE INTO role_permissions (role_id, permission_id)
        SELECT r.id, p.id FROM roles r CROSS JOIN permissions p
        WHERE r.slug = 'member' AND p.module = 'member';

        -- Grupos Pequenos / Células
        CREATE TABLE IF NOT EXISTS small_groups (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            leader_member_id INT,
            co_leader_member_id INT,
            meeting_day VARCHAR(20),
            meeting_time TIME,
            location VARCHAR(255),
            max_members INT DEFAULT 15,
            status ENUM('active','inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_org (organization_id),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS small_group_members (
            id INT AUTO_INCREMENT PRIMARY KEY,
            small_group_id INT NOT NULL,
            member_id INT NOT NULL,
            role ENUM('leader','co-leader','member') DEFAULT 'member',
            joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_group (small_group_id),
            INDEX idx_member (member_id),
            UNIQUE KEY uk_group_member (small_group_id, member_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        -- Novos Convertidos
        CREATE TABLE IF NOT EXISTS converts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            phone VARCHAR(30),
            email VARCHAR(255),
            conversion_date DATE,
            sponsor_member_id INT,
            status ENUM('acompanhando','batismo_agendado','batizado','desistiu') DEFAULT 'acompanhando',
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_org (organization_id),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        -- Contas Financeiras / Caixa
        CREATE TABLE IF NOT EXISTS financial_accounts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            type ENUM('bank','cash','pix','digital','other') DEFAULT 'bank',
            bank_name VARCHAR(100),
            account_number VARCHAR(50),
            balance DECIMAL(12,2) DEFAULT 0.00,
            status ENUM('active','inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_org (organization_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        -- Aprovação de Despesas
        CREATE TABLE IF NOT EXISTS expense_approvals (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            transaction_id INT,
            description TEXT NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            category VARCHAR(100),
            account_id INT,
            requested_by INT NOT NULL,
            approved_by INT,
            status ENUM('pending','approved','rejected') DEFAULT 'pending',
            notes TEXT,
            approved_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_org (organization_id),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        -- Auditoria Financeira
        CREATE TABLE IF NOT EXISTS financial_audit_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            entity_type VARCHAR(50) NOT NULL,
            entity_id INT NOT NULL,
            action VARCHAR(30) NOT NULL,
            old_values JSON,
            new_values JSON,
            performed_by INT NOT NULL,
            ip_address VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_org (organization_id),
            INDEX idx_entity (entity_type, entity_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        -- Banners
        CREATE TABLE IF NOT EXISTS banners (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            image_url VARCHAR(500),
            link_url VARCHAR(500),
            position ENUM('home_top','home_bottom','sidebar','popup') DEFAULT 'home_top',
            start_date DATE,
            end_date DATE,
            sort_order INT DEFAULT 0,
            status ENUM('active','inactive','scheduled') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_org (organization_id),
            INDEX idx_status_dates (status, start_date, end_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        -- Cursos (expandido)
        CREATE TABLE IF NOT EXISTS courses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            instructor VARCHAR(255),
            category VARCHAR(100),
            duration_hours INT,
            max_students INT,
            start_date DATE,
            end_date DATE,
            status ENUM('draft','published','ongoing','completed','cancelled') DEFAULT 'draft',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_org (organization_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS course_modules (
            id INT AUTO_INCREMENT PRIMARY KEY,
            course_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            sort_order INT DEFAULT 0,
            INDEX idx_course (course_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS course_enrollments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            course_id INT NOT NULL,
            member_id INT NOT NULL,
            progress INT DEFAULT 0,
            status ENUM('enrolled','in_progress','completed','dropped') DEFAULT 'enrolled',
            enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            completed_at TIMESTAMP NULL,
            INDEX idx_course (course_id),
            INDEX idx_member (member_id),
            UNIQUE KEY uk_course_member (course_id, member_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        -- Conquistas / Gamification
        CREATE TABLE IF NOT EXISTS achievements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            icon VARCHAR(50) DEFAULT 'star',
            points INT DEFAULT 10,
            criteria_type VARCHAR(50),
            criteria_value INT,
            status ENUM('active','inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_org (organization_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS member_achievements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            member_id INT NOT NULL,
            achievement_id INT NOT NULL,
            earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_member (member_id),
            UNIQUE KEY uk_member_achievement (member_id, achievement_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        -- Jornadas / Trilhas
        CREATE TABLE IF NOT EXISTS member_journeys (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            member_id INT NOT NULL,
            stage ENUM('visitor','convert','baptism','member','leader','pastor') DEFAULT 'visitor',
            previous_stage VARCHAR(30),
            changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            notes TEXT,
            changed_by INT,
            INDEX idx_org (organization_id),
            INDEX idx_member (member_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        -- Adicionar campo birthday no members se não existir
        ALTER TABLE members ADD COLUMN IF NOT EXISTS birth_date DATE AFTER phone;
        ALTER TABLE members ADD COLUMN IF NOT EXISTS journey_stage VARCHAR(30) DEFAULT 'member' AFTER status;
    ",
    'down' => "
        DROP TABLE IF EXISTS member_journeys;
        DROP TABLE IF EXISTS member_achievements;
        DROP TABLE IF EXISTS achievements;
        DROP TABLE IF EXISTS course_enrollments;
        DROP TABLE IF EXISTS course_modules;
        DROP TABLE IF EXISTS courses;
        DROP TABLE IF EXISTS banners;
        DROP TABLE IF EXISTS financial_audit_log;
        DROP TABLE IF EXISTS expense_approvals;
        DROP TABLE IF EXISTS financial_accounts;
        DROP TABLE IF EXISTS converts;
        DROP TABLE IF EXISTS small_group_members;
        DROP TABLE IF EXISTS small_groups;
    ",
];
