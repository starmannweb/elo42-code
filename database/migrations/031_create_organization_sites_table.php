<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS organization_sites (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            organization_id BIGINT UNSIGNED NOT NULL,
            template VARCHAR(120) NOT NULL DEFAULT 'Institucional Clássico',
            status VARCHAR(40) NOT NULL DEFAULT 'draft',
            site_title VARCHAR(255) NULL,
            slug VARCHAR(255) NULL,
            domain VARCHAR(255) NULL,
            theme_color VARCHAR(20) DEFAULT '#0A4DFF',
            hero_image VARCHAR(500) NULL,
            logo_image VARCHAR(500) NULL,
            generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            published_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_organization_sites_org (organization_id),
            UNIQUE KEY uk_organization_sites_org (organization_id),
            FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "DROP TABLE IF EXISTS organization_sites",
];
