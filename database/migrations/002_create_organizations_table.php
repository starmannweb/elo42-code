<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS organizations (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            type ENUM('church', 'ministry', 'association', 'other') DEFAULT 'church',
            document VARCHAR(20) NULL,
            email VARCHAR(255) NULL,
            phone VARCHAR(20) NULL,
            address TEXT NULL,
            city VARCHAR(100) NULL,
            state VARCHAR(2) NULL,
            zip_code VARCHAR(10) NULL,
            logo VARCHAR(500) NULL,
            website VARCHAR(500) NULL,
            plan ENUM('free', 'starter', 'premium', 'professional', 'enterprise') DEFAULT 'free',
            status ENUM('active', 'inactive', 'trial', 'suspended') DEFAULT 'trial',
            trial_ends_at TIMESTAMP NULL,
            settings JSON NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_organizations_slug (slug),
            INDEX idx_organizations_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "DROP TABLE IF EXISTS organizations",
];
