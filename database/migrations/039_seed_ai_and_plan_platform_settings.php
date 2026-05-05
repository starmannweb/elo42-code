<?php

return [
    'up' => "
        INSERT IGNORE INTO platform_settings (setting_key, setting_value, setting_group, description) VALUES
        ('openai_api_key', '', 'ai', 'Chave da OpenAI usada pelo Expositor IA'),
        ('openai_model', 'gpt-4o-mini', 'ai', 'Modelo principal do Expositor IA'),
        ('openai_temperature', '0.6', 'ai', 'Temperatura de geracao do Expositor IA'),
        ('openai_timeout', '60', 'ai', 'Tempo limite das chamadas OpenAI em segundos'),
        ('plan_management_monthly_price', '67.00', 'billing', 'Plano de gestao mensal ate 100 usuarios'),
        ('plan_site_monthly_price', '67.00', 'billing', 'Plano avulso do site'),
        ('plan_combo_monthly_price', '99.90', 'billing', 'Combo gestao + site'),
        ('management_included_users', '100', 'billing', 'Usuarios incluidos no plano de gestao')
    ",
    'down' => "",
];
