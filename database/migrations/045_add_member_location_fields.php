<?php

return [
    'up' => "
        ALTER TABLE members ADD COLUMN IF NOT EXISTS latitude DECIMAL(10,7) NULL AFTER zip_code;
        ALTER TABLE members ADD COLUMN IF NOT EXISTS longitude DECIMAL(10,7) NULL AFTER latitude
    ",
    'down' => "
        ALTER TABLE members DROP COLUMN latitude;
        ALTER TABLE members DROP COLUMN longitude
    ",
];
