<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PlatformSetting;

/**
 * ResendService — Integração com a API REST do Resend (https://resend.com).
 *
 * Lê RESEND_API_KEY, RESEND_FROM_EMAIL e RESEND_FROM_NAME do env.
 * Quando a chave não está configurada, sendEmail retorna um array
 * com success=false e mensagem explicativa para o caller decidir o que fazer.
 */
class ResendService
{
    private string $apiKey;
    private string $endpoint;
    private string $fromEmail;
    private string $fromName;
    private int $timeout;

    public function __construct()
    {
        $this->apiKey    = $this->setting('resend_api_key', (string) env('RESEND_API_KEY', ''));
        $this->endpoint  = rtrim($this->setting('resend_base_url', (string) env('RESEND_BASE_URL', 'https://api.resend.com')), '/') . '/emails';
        $this->fromEmail = $this->setting('resend_from_email', (string) env('RESEND_FROM_EMAIL', env('MAIL_FROM_ADDRESS', 'suporte@elo42.com.br')));
        $this->fromName  = $this->setting('resend_from_name', (string) env('RESEND_FROM_NAME', env('MAIL_FROM_NAME', 'Elo 42')));
        $this->timeout   = (int) env('RESEND_TIMEOUT', 20);
    }

    private function setting(string $key, string $fallback = ''): string
    {
        try {
            $value = PlatformSetting::get($key);
            return trim((string) $value) !== '' ? (string) $value : $fallback;
        } catch (\Throwable $e) {
            return $fallback;
        }
    }

    public function isEnabled(): bool
    {
        return $this->apiKey !== '';
    }

    /**
     * Envia um e-mail via Resend.
     *
     * @param string|array $to       Destinatário ou lista de destinatários
     * @param string       $subject  Assunto
     * @param string       $html     Conteúdo HTML
     * @param array        $options  Opções extras: text, reply_to, cc, bcc, tags, attachments
     */
    public function sendEmail($to, string $subject, string $html, array $options = []): array
    {
        if (!$this->isEnabled()) {
            return [
                'success' => false,
                'message' => 'Resend não configurado (RESEND_API_KEY ausente).',
                'id'      => null,
            ];
        }

        $payload = [
            'from'    => $this->buildFromHeader(),
            'to'      => is_array($to) ? array_values(array_filter($to)) : [$to],
            'subject' => $subject,
            'html'    => $html,
        ];

        if (!empty($options['text']))     { $payload['text'] = (string) $options['text']; }
        if (!empty($options['reply_to'])) { $payload['reply_to'] = is_array($options['reply_to']) ? $options['reply_to'] : [$options['reply_to']]; }
        if (!empty($options['cc']))       { $payload['cc']  = is_array($options['cc'])  ? $options['cc']  : [$options['cc']]; }
        if (!empty($options['bcc']))      { $payload['bcc'] = is_array($options['bcc']) ? $options['bcc'] : [$options['bcc']]; }
        if (!empty($options['tags']) && is_array($options['tags'])) { $payload['tags'] = $options['tags']; }
        if (!empty($options['attachments']) && is_array($options['attachments'])) { $payload['attachments'] = $options['attachments']; }

        $response = $this->postJson($payload);
        if ($response === null) {
            return [
                'success' => false,
                'message' => 'Falha ao enviar e-mail via Resend.',
                'id'      => null,
            ];
        }

        return [
            'success' => true,
            'message' => 'E-mail enviado.',
            'id'      => (string) ($response['id'] ?? ''),
        ];
    }

    /**
     * Envia um e-mail transacional simples (texto puro convertido em HTML básico).
     */
    public function sendSimple(string $to, string $subject, string $message): array
    {
        $html = '<div style="font-family:Inter,sans-serif;color:#0f172a;line-height:1.6;">'
              . nl2br(htmlspecialchars($message, ENT_QUOTES | ENT_HTML5, 'UTF-8'))
              . '</div>';
        return $this->sendEmail($to, $subject, $html, ['text' => $message]);
    }

    private function buildFromHeader(): string
    {
        if ($this->fromName !== '') {
            return $this->fromName . ' <' . $this->fromEmail . '>';
        }
        return $this->fromEmail;
    }

    private function postJson(array $payload): ?array
    {
        try {
            $ch = curl_init($this->endpoint);
            if ($ch === false) {
                return null;
            }

            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $this->apiKey,
                    'Content-Type: application/json',
                ],
                CURLOPT_TIMEOUT        => $this->timeout,
                CURLOPT_CONNECTTIMEOUT => 8,
            ]);

            $response = curl_exec($ch);
            $status   = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error    = curl_error($ch);
            curl_close($ch);

            if ($response === false || $status >= 400) {
                error_log('[ResendService] HTTP ' . $status . ' err=' . $error . ' body=' . (is_string($response) ? substr($response, 0, 200) : '-'));
                return null;
            }

            $decoded = json_decode((string) $response, true);
            return is_array($decoded) ? $decoded : null;
        } catch (\Throwable $e) {
            error_log('[ResendService] ' . $e->getMessage());
            return null;
        }
    }
}
