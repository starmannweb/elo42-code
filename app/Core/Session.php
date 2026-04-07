<?php

declare(strict_types=1);

namespace App\Core;

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $config = config('session', []);
            $sessionPath = $config['path'] ?? null;

            if (is_string($sessionPath) && $sessionPath !== '') {
                if (!is_dir($sessionPath)) {
                    mkdir($sessionPath, 0775, true);
                }

                if (is_dir($sessionPath) && is_writable($sessionPath)) {
                    session_save_path($sessionPath);
                }
            }

            session_name($config['name'] ?? 'elo42_session');

            // Detect HTTPS from proxy headers (Render, Heroku, Cloudflare, etc.)
            $isSecure = self::isHttps();

            session_set_cookie_params([
                'lifetime' => ($config['lifetime'] ?? 120) * 60,
                'path'     => '/',
                'secure'   => $isSecure,
                'httponly'  => $config['httponly'] ?? true,
                'samesite'  => 'Lax',
            ]);

            session_start();

            if (!isset($_SESSION['_initialized'])) {
                session_regenerate_id(true);
                $_SESSION['_initialized'] = true;
            }

            // Garbage collect expired flash data from previous request
            self::ageFlashData();
        }
    }

    /**
     * Detect if the current request is over HTTPS, including behind reverse proxies.
     */
    private static function isHttps(): bool
    {
        // Direct HTTPS
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return true;
        }

        // Behind load balancer / reverse proxy
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        }

        // Cloudflare
        if (isset($_SERVER['HTTP_CF_VISITOR'])) {
            $visitor = json_decode($_SERVER['HTTP_CF_VISITOR'], true);
            if (isset($visitor['scheme']) && $visitor['scheme'] === 'https') {
                return true;
            }
        }

        // Check APP_URL
        $appUrl = $_ENV['APP_URL'] ?? $_SERVER['APP_URL'] ?? '';
        if (is_string($appUrl) && str_starts_with($appUrl, 'https://')) {
            return true;
        }

        return false;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash']['new'][$key] = $value;
    }

    public static function getFlash(string $key, mixed $default = null): mixed
    {
        // Check new flash first, then old
        $value = $_SESSION['_flash']['new'][$key] ?? $_SESSION['_flash']['old'][$key] ?? $default;
        return $value;
    }

    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash']['new'][$key]) || isset($_SESSION['_flash']['old'][$key]);
    }

    /**
     * Age flash data: move 'new' to 'old', discard previous 'old'.
     * This is called once per request at session start.
     */
    private static function ageFlashData(): void
    {
        // Move new -> old, discard previous old
        $_SESSION['_flash']['old'] = $_SESSION['_flash']['new'] ?? [];
        $_SESSION['_flash']['new'] = [];
    }

    public static function destroy(): void
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }

    public static function regenerate(): void
    {
        // Preserve CSRF token across regeneration to avoid token mismatch
        $csrfToken = $_SESSION['_csrf_token'] ?? null;
        $flashData = $_SESSION['_flash'] ?? [];

        session_regenerate_id(true);

        // Restore critical session data
        if ($csrfToken !== null) {
            $_SESSION['_csrf_token'] = $csrfToken;
        }
        if (!empty($flashData)) {
            $_SESSION['_flash'] = $flashData;
        }
    }

    public static function user(): ?array
    {
        return self::get('user');
    }

    public static function isAuthenticated(): bool
    {
        return self::has('user');
    }

    public static function setOld(array $data): void
    {
        self::flash('_old_input', $data);
    }

    public static function getOld(string $key, mixed $default = null): mixed
    {
        $old = self::getFlash('_old_input');
        if (is_array($old)) {
            return $old[$key] ?? $default;
        }
        return $default;
    }
}
