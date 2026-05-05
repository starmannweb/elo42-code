<?php

return [
    'up' => "
        ALTER TABLE benefits ADD COLUMN IF NOT EXISTS target_type ENUM('organization','user') NULL AFTER service_id;
        ALTER TABLE benefits ADD COLUMN IF NOT EXISTS target_id BIGINT UNSIGNED NULL AFTER target_type;
        ALTER TABLE benefits ADD COLUMN IF NOT EXISTS target_label VARCHAR(255) NULL AFTER target_id
    ",
    'down' => "
        ALTER TABLE benefits DROP COLUMN target_label;
        ALTER TABLE benefits DROP COLUMN target_id;
        ALTER TABLE benefits DROP COLUMN target_type
    ",
];
