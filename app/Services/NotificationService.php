<?php

namespace App\Services;

/**
 * NotificationService — Integration stub for multi-channel notifications.
 *
 * Prepared for: email (SMTP/Mailgun/SES), push notifications,
 * in-app notifications, and SMS.
 *
 * @package App\Services
 */
class NotificationService
{
    protected array $config;

    public function __construct()
    {
        $this->config = [
            'mail_driver'  => env('MAIL_DRIVER', 'smtp'),
            'mail_host'    => env('MAIL_HOST', ''),
            'mail_port'    => env('MAIL_PORT', 587),
            'mail_user'    => env('MAIL_USERNAME', ''),
            'mail_pass'    => env('MAIL_PASSWORD', ''),
            'mail_from'    => env('MAIL_FROM_ADDRESS', 'suporte@elo42.com.br'),
            'mail_from_name' => env('MAIL_FROM_NAME', 'Elo 42'),
        ];
    }

    /**
     * Send an email notification.
     *
     * Driver "resend" usa a API REST do Resend (RESEND_API_KEY).
     * Os demais drivers caem em stub aguardando integração.
     */
    public function sendEmail(string $to, string $subject, string $htmlBody, array $options = []): array
    {
        $driver = strtolower((string) ($this->config['mail_driver'] ?? 'smtp'));

        if ($driver === 'resend' || (env('RESEND_API_KEY', '') !== '' && in_array($driver, ['smtp', 'sendmail'], true))) {
            return (new ResendService())->sendEmail($to, $subject, $htmlBody, $options);
        }

        return [
            'success' => false,
            'message' => 'Email notification not yet configured.',
        ];
    }

    /**
     * Create an in-app notification for a user.
     */
    public function createInApp(int $userId, string $title, string $message, string $type = 'info', ?string $actionUrl = null): array
    {
        // TODO: Implement with notifications database table
        return [
            'success' => false,
            'message' => 'In-app notifications not yet configured.',
        ];
    }

    /**
     * Get unread notifications for a user.
     */
    public function getUnread(int $userId, int $limit = 20): array
    {
        // TODO: Implement
        return [];
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(int $notificationId, int $userId): bool
    {
        // TODO: Implement
        return false;
    }

    /**
     * Send a notification through the preferred channel for a user.
     */
    public function notify(int $userId, string $channel, string $subject, string $message, array $data = []): array
    {
        return match ($channel) {
            'email'    => $this->sendEmail($data['email'] ?? '', $subject, $message),
            'in_app'   => $this->createInApp($userId, $subject, $message),
            'whatsapp' => (new WhatsAppService())->sendMessage($data['phone'] ?? '', $message),
            default    => ['success' => false, 'message' => 'Unknown notification channel.'],
        };
    }
}
