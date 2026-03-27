<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS settings (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `group` VARCHAR(100) NOT NULL DEFAULT 'general',
            `key` VARCHAR(255) NOT NULL,
            value TEXT NULL,
            type ENUM('string', 'integer', 'boolean', 'json') DEFAULT 'string',
            organization_id BIGINT UNSIGNED NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY uk_settings_key (`group`, `key`, organization_id),
            INDEX idx_settings_group (`group`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "DROP TABLE IF EXISTS settings",
];
