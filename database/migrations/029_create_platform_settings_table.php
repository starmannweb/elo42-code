<?php

return [
    'up' => "
        CREATE TABLE IF NOT EXISTS platform_settings (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(255) UNIQUE NOT NULL,
            setting_value TEXT NULL,
            setting_group VARCHAR(100) DEFAULT 'general',
            description VARCHAR(500) NULL,
            updated_by BIGINT UNSIGNED NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        INSERT IGNORE INTO platform_settings (setting_key, setting_value, setting_group, description) VALUES
        ('platform_name', 'Elo 42', 'general', 'Nome da plataforma'),
        ('platform_email', 'suporte@elo42.com.br', 'general', 'E-mail principal'),
        ('trial_days', '14', 'subscriptions', 'Dias de trial para novas organizações'),
        ('max_members_basic', '50', 'limits', 'Limite de membros no plano básico'),
        ('max_members_pro', '500', 'limits', 'Limite de membros no plano profissional'),
        ('maintenance_mode', '0', 'system', 'Modo de manutenção (0=off, 1=on)'),
        ('registration_enabled', '1', 'system', 'Cadastro de novos usuários habilitado')
    ",
    'down' => "DROP TABLE IF EXISTS platform_settings",
];
