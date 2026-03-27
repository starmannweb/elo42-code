<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS action_plans (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            organization_id BIGINT UNSIGNED NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT NULL,
            start_date DATE NULL,
            end_date DATE NULL,
            status ENUM('planning','active','completed','archived') DEFAULT 'planning',
            created_by BIGINT UNSIGNED NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_plans_org (organization_id),
            FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS action_plan_objectives (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            plan_id BIGINT UNSIGNED NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT NULL,
            sort_order INT UNSIGNED DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (plan_id) REFERENCES action_plans(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS action_plan_tasks (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            objective_id BIGINT UNSIGNED NOT NULL,
            title VARCHAR(255) NOT NULL,
            assigned_to BIGINT UNSIGNED NULL,
            due_date DATE NULL,
            status ENUM('todo','doing','done') DEFAULT 'todo',
            sort_order INT UNSIGNED DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (objective_id) REFERENCES action_plan_objectives(id) ON DELETE CASCADE,
            FOREIGN KEY (assigned_to) REFERENCES members(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "
        DROP TABLE IF EXISTS action_plan_tasks;
        DROP TABLE IF EXISTS action_plan_objectives;
        DROP TABLE IF EXISTS action_plans
    ",
];
