<?php

return [
    'up' => "
        ALTER TABLE blog_articles
            ADD COLUMN meta_title VARCHAR(255) NULL AFTER published_at,
            ADD COLUMN meta_description VARCHAR(320) NULL AFTER meta_title,
            ADD COLUMN focus_keyword VARCHAR(120) NULL AFTER meta_description,
            ADD COLUMN noindex TINYINT(1) NOT NULL DEFAULT 0 AFTER focus_keyword
    ",
    'down' => "
        ALTER TABLE blog_articles
            DROP COLUMN meta_title,
            DROP COLUMN meta_description,
            DROP COLUMN focus_keyword,
            DROP COLUMN noindex
    ",
];
