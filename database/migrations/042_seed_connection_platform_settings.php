<?php

return [
    'up' => "
        INSERT IGNORE INTO platform_settings (setting_key, setting_value, setting_group, description) VALUES
        ('resend_api_key', '', 'email', 'Chave da API Resend usada para disparos transacionais da plataforma'),
        ('resend_from_email', 'suporte@elo42.com.br', 'email', 'Email remetente padrao usado nos disparos'),
        ('resend_from_name', 'Elo 42', 'email', 'Nome do remetente padrao usado nos disparos'),
        ('resend_base_url', 'https://api.resend.com', 'email', 'URL base da API Resend'),
        ('evolution_base_url', '', 'whatsapp', 'URL base da Evolution API'),
        ('evolution_api_key', '', 'whatsapp', 'Chave global de autenticacao da Evolution API'),
        ('evolution_instance', '', 'whatsapp', 'Instancia padrao da Evolution API usada para disparos'),
        ('evolution_webhook_url', '/webhooks/evolution', 'whatsapp', 'Endpoint local para receber eventos da Evolution API'),
        ('evolution_webhook_secret', '', 'whatsapp', 'Segredo para validar eventos recebidos da Evolution API'),
        ('platform_webhook_base_url', '', 'webhooks', 'URL publica base para callbacks e automacoes externas'),
        ('platform_webhook_secret', '', 'webhooks', 'Segredo compartilhado para validar webhooks gerais da plataforma'),
        ('lead_capture_webhook_url', '/webhooks/leads', 'webhooks', 'Endpoint para captura externa de leads e formularios'),
        ('billing_webhook_url', '/webhooks/pagou', 'webhooks', 'Endpoint para eventos financeiros e cobrancas'),
        ('email_events_webhook_url', '/webhooks/resend', 'webhooks', 'Endpoint para eventos de entrega, abertura e falha de emails')
    ",
    'down' => "",
];
