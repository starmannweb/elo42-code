<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS church_units (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            name VARCHAR(180) NOT NULL,
            code VARCHAR(40) NULL,
            address VARCHAR(500) NULL,
            city VARCHAR(120) NULL,
            state VARCHAR(2) NULL,
            phone VARCHAR(30) NULL,
            status ENUM('active','inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_church_units_org (organization_id),
            INDEX idx_church_units_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS preachers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            church_unit_id INT NULL,
            name VARCHAR(180) NOT NULL,
            email VARCHAR(180) NULL,
            phone VARCHAR(30) NULL,
            bio TEXT NULL,
            status ENUM('active','inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_preachers_org (organization_id),
            INDEX idx_preachers_unit (church_unit_id),
            INDEX idx_preachers_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        ALTER TABLE members ADD COLUMN IF NOT EXISTS church_unit_id INT NULL AFTER organization_id;
        ALTER TABLE ministries ADD COLUMN IF NOT EXISTS church_unit_id INT NULL AFTER organization_id;
        ALTER TABLE events ADD COLUMN IF NOT EXISTS church_unit_id INT NULL AFTER organization_id;
        ALTER TABLE sermons ADD COLUMN IF NOT EXISTS church_unit_id INT NULL AFTER organization_id;
        ALTER TABLE courses ADD COLUMN IF NOT EXISTS church_unit_id INT NULL AFTER organization_id;
        ALTER TABLE campaigns ADD COLUMN IF NOT EXISTS church_unit_id INT NULL AFTER organization_id;
        ALTER TABLE donations ADD COLUMN IF NOT EXISTS church_unit_id INT NULL AFTER organization_id;
        ALTER TABLE financial_transactions ADD COLUMN IF NOT EXISTS church_unit_id INT NULL AFTER organization_id;
    ",
    'down' => "
        DROP TABLE IF EXISTS preachers;
        DROP TABLE IF EXISTS church_units
    ",
];
