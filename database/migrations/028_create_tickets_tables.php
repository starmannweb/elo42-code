<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS tickets (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NOT NULL,
            organization_id BIGINT UNSIGNED NULL,
            subject VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            category ENUM('support','bug','feature','billing','other') DEFAULT 'support',
            priority ENUM('low','normal','high','urgent') DEFAULT 'normal',
            status ENUM('open','in_progress','waiting','resolved','closed') DEFAULT 'open',
            assigned_to BIGINT UNSIGNED NULL,
            resolved_at TIMESTAMP NULL,
            closed_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_tickets_status (status),
            INDEX idx_tickets_priority (priority),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS ticket_replies (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ticket_id BIGINT UNSIGNED NOT NULL,
            user_id BIGINT UNSIGNED NOT NULL,
            message TEXT NOT NULL,
            is_admin TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "
        DROP TABLE IF EXISTS ticket_replies;
        DROP TABLE IF EXISTS tickets
    ",
];
