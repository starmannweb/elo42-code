<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS services (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            description TEXT NULL,
            rules TEXT NULL,
            price DECIMAL(12,2) DEFAULT 0.00,
            recurrence ENUM('one_time','monthly','quarterly','yearly') DEFAULT 'one_time',
            status ENUM('active','inactive') DEFAULT 'active',
            sort_order INT UNSIGNED DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "DROP TABLE IF EXISTS services",
];
