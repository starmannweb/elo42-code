<?php

return [
    'up' => "
        ALTER TABLE benefits ADD COLUMN IF NOT EXISTS service_id BIGINT UNSIGNED NULL AFTER slug;
        ALTER TABLE benefits ADD COLUMN IF NOT EXISTS duration_days INT UNSIGNED NULL AFTER max_usage;
        ALTER TABLE benefit_usages ADD COLUMN IF NOT EXISTS starts_at TIMESTAMP NULL AFTER status;
        ALTER TABLE benefit_usages ADD COLUMN IF NOT EXISTS expires_at TIMESTAMP NULL AFTER starts_at
    ",
    'down' => "",
];
