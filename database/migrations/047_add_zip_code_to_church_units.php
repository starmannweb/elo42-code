<?php

return [
    'up' => "
        ALTER TABLE church_units ADD COLUMN IF NOT EXISTS zip_code VARCHAR(10) NULL AFTER state
    ",
    'down' => "
        ALTER TABLE church_units DROP COLUMN zip_code
    ",
];
