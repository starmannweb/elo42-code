<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS visits (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            organization_id BIGINT UNSIGNED NOT NULL,
            visitor_name VARCHAR(255) NOT NULL,
            phone VARCHAR(30) NULL,
            email VARCHAR(255) NULL,
            visit_date DATE NOT NULL,
            source ENUM('invited','spontaneous','event','online','other') DEFAULT 'spontaneous',
            notes TEXT NULL,
            follow_up ENUM('pending','contacted','scheduled','completed','no_response') DEFAULT 'pending',
            assigned_to BIGINT UNSIGNED NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_visits_org (organization_id),
            FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
            FOREIGN KEY (assigned_to) REFERENCES members(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "DROP TABLE IF EXISTS visits",
];
