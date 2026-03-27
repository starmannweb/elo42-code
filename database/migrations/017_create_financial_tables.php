<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS financial_categories (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            organization_id BIGINT UNSIGNED NOT NULL,
            name VARCHAR(255) NOT NULL,
            type ENUM('income','expense') NOT NULL,
            color VARCHAR(7) DEFAULT '#0A4DFF',
            is_system TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_fincat_org (organization_id),
            FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS financial_transactions (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            organization_id BIGINT UNSIGNED NOT NULL,
            category_id BIGINT UNSIGNED NULL,
            type ENUM('income','expense') NOT NULL,
            description VARCHAR(500) NOT NULL,
            amount DECIMAL(12,2) NOT NULL,
            transaction_date DATE NOT NULL,
            reference VARCHAR(255) NULL,
            member_id BIGINT UNSIGNED NULL,
            status ENUM('confirmed','pending','cancelled') DEFAULT 'confirmed',
            notes TEXT NULL,
            created_by BIGINT UNSIGNED NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_fintx_org (organization_id),
            INDEX idx_fintx_type (type),
            INDEX idx_fintx_date (transaction_date),
            FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES financial_categories(id) ON DELETE SET NULL,
            FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "
        DROP TABLE IF EXISTS financial_transactions;
        DROP TABLE IF EXISTS financial_categories
    ",
];
