<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS requests (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            organization_id BIGINT UNSIGNED NOT NULL,
            member_id BIGINT UNSIGNED NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT NULL,
            type ENUM('prayer','support','general','material','other') DEFAULT 'general',
            priority ENUM('low','normal','high','urgent') DEFAULT 'normal',
            status ENUM('open','in_progress','resolved','closed') DEFAULT 'open',
            resolved_at TIMESTAMP NULL,
            created_by BIGINT UNSIGNED NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_requests_org (organization_id),
            INDEX idx_requests_status (status),
            FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
            FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "DROP TABLE IF EXISTS requests",
];
