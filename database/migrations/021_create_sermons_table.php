<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS sermons (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            organization_id BIGINT UNSIGNED NOT NULL,
            title VARCHAR(255) NOT NULL,
            preacher VARCHAR(255) NULL,
            sermon_date DATE NULL,
            bible_reference VARCHAR(255) NULL,
            summary TEXT NULL,
            series_name VARCHAR(255) NULL,
            tags VARCHAR(500) NULL,
            status ENUM('draft','published') DEFAULT 'draft',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_sermons_org (organization_id),
            INDEX idx_sermons_date (sermon_date),
            FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "DROP TABLE IF EXISTS sermons",
];
