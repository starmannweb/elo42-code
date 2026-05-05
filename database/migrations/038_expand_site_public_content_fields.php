<?php

return [
    'up' => "
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS service_times TEXT NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS gallery_images TEXT NULL
    ",
    'down' => "
        -- Columns are intentionally kept for compatibility.
    ",
];
