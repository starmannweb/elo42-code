<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS campaigns (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            goal_amount DECIMAL(12,2) DEFAULT 0.00,
            raised_amount DECIMAL(12,2) DEFAULT 0.00,
            designation VARCHAR(160),
            end_date DATE NULL,
            status ENUM('draft','active','published','completed','archived') DEFAULT 'draft',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_campaigns_org (organization_id),
            INDEX idx_campaigns_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS reading_plans (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            duration_days INT DEFAULT 30,
            book_range VARCHAR(255),
            participants_count INT DEFAULT 0,
            status ENUM('draft','active','archived') DEFAULT 'draft',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_reading_plans_org (organization_id),
            INDEX idx_reading_plans_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "
        DROP TABLE IF EXISTS reading_plans;
        DROP TABLE IF EXISTS campaigns
    ",
];
