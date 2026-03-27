<?php

namespace App\Services;

/**
 * WhatsAppService — Integration stub for WhatsApp messaging.
 *
 * Prepared for integration with WhatsApp Business API or providers
 * like Twilio, Z-API, Evolution API, etc.
 *
 * @package App\Services
 */
class WhatsAppService
{
    protected array $config;

    public function __construct()
    {
        $this->config = [
            'provider'   => env('WHATSAPP_PROVIDER', 'twilio'),
            'api_key'    => env('WHATSAPP_API_KEY', ''),
            'api_secret' => env('WHATSAPP_API_SECRET', ''),
            'from_number' => env('WHATSAPP_FROM_NUMBER', ''),
            'webhook_url' => env('WHATSAPP_WEBHOOK_URL', ''),
        ];
    }

    /**
     * Send a text message to a phone number.
     */
    public function sendMessage(string $to, string $message): array
    {
        // TODO: Implement when WhatsApp provider is selected
        return [
            'success' => false,
            'message' => 'WhatsApp integration not yet configured.',
            'message_id' => null,
        ];
    }

    /**
     * Send a template message (pre-approved by WhatsApp).
     */
    public function sendTemplate(string $to, string $templateName, array $params = []): array
    {
        // TODO: Implement
        return [
            'success' => false,
            'message' => 'WhatsApp integration not yet configured.',
        ];
    }

    /**
     * Handle incoming webhook from WhatsApp.
     */
    public function handleWebhook(array $payload): array
    {
        // TODO: Implement message reception and status updates
        return ['handled' => false];
    }

    /**
     * Format a Brazilian phone number to international format.
     */
    public function formatPhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (strlen($digits) === 11) {
            return '+55' . $digits;
        }

        if (strlen($digits) === 13 && str_starts_with($digits, '55')) {
            return '+' . $digits;
        }

        return '+' . $digits;
    }
}
