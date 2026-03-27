<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS donations (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            organization_id BIGINT UNSIGNED NOT NULL,
            member_id BIGINT UNSIGNED NULL,
            donor_name VARCHAR(255) NULL,
            type ENUM('tithe','offering','special','campaign','other') DEFAULT 'offering',
            amount DECIMAL(12,2) NOT NULL,
            donation_date DATE NOT NULL,
            payment_method ENUM('cash','pix','card','transfer','other') DEFAULT 'cash',
            reference VARCHAR(255) NULL,
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_donations_org (organization_id),
            INDEX idx_donations_date (donation_date),
            INDEX idx_donations_type (type),
            FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
            FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "DROP TABLE IF EXISTS donations",
];
