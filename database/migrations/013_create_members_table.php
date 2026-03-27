<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS members (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            organization_id BIGINT UNSIGNED NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NULL,
            phone VARCHAR(30) NULL,
            birth_date DATE NULL,
            gender ENUM('M','F','other') NULL,
            marital_status ENUM('single','married','divorced','widowed','other') NULL,
            address VARCHAR(500) NULL,
            city VARCHAR(100) NULL,
            state VARCHAR(2) NULL,
            zip_code VARCHAR(10) NULL,
            photo VARCHAR(500) NULL,
            membership_date DATE NULL,
            baptism_date DATE NULL,
            status ENUM('active','inactive','transferred','visitor') DEFAULT 'active',
            notes TEXT NULL,
            created_by BIGINT UNSIGNED NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_members_org (organization_id),
            INDEX idx_members_status (status),
            INDEX idx_members_name (name),
            FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "DROP TABLE IF EXISTS members",
];
