<?php

return [
    'up' => "
        ALTER TABLE organizations
        MODIFY COLUMN plan ENUM('free', 'starter', 'premium', 'professional', 'enterprise') DEFAULT 'free'
    ",
    'down' => "
        UPDATE organizations SET plan = 'professional' WHERE plan = 'premium';
        ALTER TABLE organizations
        MODIFY COLUMN plan ENUM('free', 'starter', 'professional', 'enterprise') DEFAULT 'free'
    ",
];
