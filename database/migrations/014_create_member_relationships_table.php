<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS member_relationships (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            member_id BIGINT UNSIGNED NOT NULL,
            related_member_id BIGINT UNSIGNED NOT NULL,
            relationship_type ENUM('spouse','child','parent','sibling','other') NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
            FOREIGN KEY (related_member_id) REFERENCES members(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ",
    'down' => "DROP TABLE IF EXISTS member_relationships",
];
