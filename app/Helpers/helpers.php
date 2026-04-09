<?php

declare(strict_types=1);

use App\Core\Session;

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false) {
            return $default;
        }

        return match (strtolower((string) $value)) {
            'true', '(true)'   => true,
            'false', '(false)' => false,
            'null', '(null)'   => null,
            'empty', '(empty)' => '',
            default            => $value,
        };
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        $parts = explode('.', $key);
        $file = array_shift($parts);

        $config = $GLOBALS['__config'][$file] ?? null;

        if ($config === null) {
            return $default;
        }

        foreach ($parts as $part) {
            if (!is_array($config) || !array_key_exists($part, $config)) {
                return $default;
            }
            $config = $config[$part];
        }

        return $config;
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8', true);
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $appUrl = env('APP_URL', '');
        if (empty($appUrl)) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $appUrl = $scheme . '://' . $host;
        }
        $base = rtrim($appUrl, '/');
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $relativePath = ltrim($path, '/');
        $assetUrl = url('assets/' . $relativePath);

        $version = env('ASSET_VERSION');
        if (!$version && defined('BASE_PATH')) {
            $fullPath = BASE_PATH . '/public/assets/' . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
            if (is_file($fullPath)) {
                $version = (string) filemtime($fullPath);
            }
        }

        if ($version) {
            $separator = str_contains($assetUrl, '?') ? '&' : '?';
            $assetUrl .= $separator . 'v=' . rawurlencode((string) $version);
        }

        return $assetUrl;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        if (str_starts_with($url, '/')) {
            $url = url($url);
        }
        header("Location: {$url}");
        exit;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (!Session::has('_csrf_token')) {
            Session::set('_csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('_csrf_token');
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed
    {
        return Session::getOld($key, $default);
    }
}

if (!function_exists('dd')) {
    function dd(mixed ...$vars): never
    {
        echo '<pre style="background:#1a1a2e;color:#e0e0e0;padding:16px;border-radius:8px;font-size:13px;">';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
        exit;
    }
}

if (!function_exists('flash')) {
    function flash(string $key, mixed $default = null): mixed
    {
        return Session::getFlash($key, $default);
    }
}

if (!function_exists('auth')) {
    function auth(): ?array
    {
        return Session::user();
    }
}

if (!function_exists('is_authenticated')) {
    function is_authenticated(): bool
    {
        return Session::isAuthenticated();
    }
}

if (!function_exists('method_field')) {
    function method_field(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
    }
}

if (!function_exists('active_class')) {
    function active_class(string $path, string $class = 'active'): string
    {
        $currentUri = '/' . trim($_GET['url'] ?? '', '/');
        return ($currentUri === $path || str_starts_with($currentUri, $path . '/')) ? $class : '';
    }
}
