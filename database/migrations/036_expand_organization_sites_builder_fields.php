<?php

return [
    'up' => "
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS site_description TEXT NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS about_text TEXT NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS contact_email VARCHAR(180) NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS contact_phone VARCHAR(40) NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS whatsapp_url VARCHAR(500) NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS instagram_url VARCHAR(500) NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS facebook_url VARCHAR(500) NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS youtube_url VARCHAR(500) NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS address_line VARCHAR(500) NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS city VARCHAR(120) NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS state VARCHAR(80) NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS cta_label VARCHAR(120) NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS cta_url VARCHAR(500) NULL;
        ALTER TABLE organization_sites ADD COLUMN IF NOT EXISTS published_url VARCHAR(500) NULL;
    ",
    'down' => "
        -- Columns are intentionally kept for SQLite/MySQL compatibility.
    ",
];
