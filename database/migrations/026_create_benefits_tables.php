<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS benefits (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            description TEXT NULL,
            requirements TEXT NULL,
            status ENUM('active','inactive','paused') DEFAULT 'active',
            max_usage INT UNSIGNED NULL,
            valid_until DATE NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS benefit_usages (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            benefit_id BIGINT UNSIGNED NOT NULL,
            organization_id BIGINT UNSIGNED NOT NULL,
            user_id BIGINT UNSIGNED NOT NULL,
            status ENUM('pending','approved','denied','used') DEFAULT 'pending',
            used_at TIMESTAMP NULL,
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (benefit_id) REFERENCES benefits(id) ON DELETE CASCADE,
            FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "
        DROP TABLE IF EXISTS benefit_usages;
        DROP TABLE IF EXISTS benefits
    ",
];
