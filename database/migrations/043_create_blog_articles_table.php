<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS blog_articles (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            summary TEXT NULL,
            content LONGTEXT NOT NULL DEFAULT '',
            cover_image VARCHAR(500) NULL,
            author VARCHAR(255) NOT NULL DEFAULT 'Equipe Elo 42',
            status ENUM('draft','published') DEFAULT 'draft',
            published_at DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "DROP TABLE IF EXISTS blog_articles",
];
