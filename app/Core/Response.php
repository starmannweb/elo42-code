<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    public function status(int $code): self
    {
        http_response_code($code);
        return $this;
    }

    public function header(string $key, string $value): self
    {
        header("{$key}: {$value}");
        return $this;
    }

    public function json(mixed $data, int $status = 200): void
    {
        $this->status($status);
        $this->header('Content-Type', 'application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function redirect(string $url, int $status = 302): void
    {
        $this->status($status);
        header("Location: {$url}");
        exit;
    }

    public function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    public function send(string $content, int $status = 200): void
    {
        $this->status($status);
        echo $content;
        exit;
    }
}
