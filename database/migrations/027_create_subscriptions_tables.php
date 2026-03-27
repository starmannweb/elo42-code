<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS subscriptions (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            organization_id BIGINT UNSIGNED NOT NULL,
            plan_name VARCHAR(100) NOT NULL DEFAULT 'basic',
            plan_slug VARCHAR(100) NOT NULL DEFAULT 'basic',
            price DECIMAL(12,2) DEFAULT 0.00,
            billing_cycle ENUM('monthly','quarterly','yearly') DEFAULT 'monthly',
            status ENUM('trial','active','past_due','cancelled','expired') DEFAULT 'trial',
            trial_ends_at TIMESTAMP NULL,
            starts_at TIMESTAMP NULL,
            expires_at TIMESTAMP NULL,
            cancelled_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_subs_org (organization_id),
            INDEX idx_subs_status (status),
            FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS subscription_history (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            subscription_id BIGINT UNSIGNED NOT NULL,
            action ENUM('created','activated','renewed','upgraded','downgraded','cancelled','expired','reactivated') NOT NULL,
            old_plan VARCHAR(100) NULL,
            new_plan VARCHAR(100) NULL,
            notes TEXT NULL,
            created_by BIGINT UNSIGNED NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (subscription_id) REFERENCES subscriptions(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "
        DROP TABLE IF EXISTS subscription_history;
        DROP TABLE IF EXISTS subscriptions
    ",
];
