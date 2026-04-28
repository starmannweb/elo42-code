<?php

return [
    'up' => "
        ALTER TABLE courses ADD COLUMN IF NOT EXISTS pdf_file_url VARCHAR(500) NULL AFTER status;
        ALTER TABLE courses ADD COLUMN IF NOT EXISTS video_url VARCHAR(500) NULL AFTER pdf_file_url;
    ",
    'down' => "
        ALTER TABLE courses DROP COLUMN IF EXISTS video_url;
        ALTER TABLE courses DROP COLUMN IF EXISTS pdf_file_url;
    ",
];
