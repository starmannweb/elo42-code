<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS spiritual_journeys (
            id INT AUTO_INCREMENT PRIMARY KEY,
            organization_id INT NOT NULL,
            title VARCHAR(180) NOT NULL,
            description TEXT NULL,
            duration_days INT NULL,
            status ENUM('draft','active','archived') DEFAULT 'draft',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_spiritual_journeys_org (organization_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "DROP TABLE IF EXISTS spiritual_journeys",
];
