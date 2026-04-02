<?php

declare(strict_types=1);

namespace App\Core;

use App\Support\Logger;

class App
{
    private Router $router;
    private Request $request;

    public function __construct()
    {
        $this->loadEnvironment();
        $this->loadConfig();
        $this->initSession();
        $this->request = new Request();
        $this->router = new Router();
        $this->loadRoutes();
    }

    public function run(): void
    {
        $method = $this->request->method();
        $uri = $this->request->uri();

        try {
            $this->router->dispatch($method, $uri, $this->request);
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    private function loadEnvironment(): void
    {
        $envFile = BASE_PATH . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#')) {
                    continue;
                }
                if (str_contains($line, '=')) {
                    [$key, $value] = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value, " \t\n\r\0\x0B\"\'" );
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                    putenv("{$key}={$value}");
                }
            }
        }
    }

    private function loadConfig(): void
    {
        $configPath = BASE_PATH . '/config';
        foreach (glob($configPath . '/*.php') as $file) {
            $key = basename($file, '.php');
            $GLOBALS['__config'][$key] = require $file;
        }
    }

    private function initSession(): void
    {
        Session::start();
    }

    private function loadRoutes(): void
    {
        $routeFiles = [
            'web',
            'auth',
            'hub',
            'management',
            'admin',
            'api',
        ];

        foreach ($routeFiles as $file) {
            $path = BASE_PATH . '/routes/' . $file . '.php';
            if (file_exists($path)) {
                $router = $this->router;
                require $path;
            }
        }
    }

    private function handleException(\Throwable $e): void
    {
        try {
            (new Logger())->error('app.unhandled_exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
                'uri'     => $_SERVER['REQUEST_URI'] ?? null,
                'method'  => $_SERVER['REQUEST_METHOD'] ?? null,
            ]);
        } catch (\Throwable $logError) {
            // Ignore logger failures.
        }

        $debug = (bool) config('app.debug', false);
        // Temporary: enable debug for management routes to diagnose 500 errors
        if (str_contains($_SERVER['REQUEST_URI'] ?? '', '/gestao/')) {
            $debug = true;
        }

        http_response_code(500);

        if ($debug) {
            echo '<h1>Error Debug</h1>';
            echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . '</p>';
            echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
            echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            return;
        }

        echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Erro interno</title>';
        echo '<style>body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#061a3a;color:#e7edf7;display:grid;place-items:center;min-height:100vh;padding:24px}';
        echo '.card{max-width:680px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.14);border-radius:16px;padding:28px;box-shadow:0 14px 44px rgba(0,0,0,.35)}';
        echo 'h1{margin:0 0 12px;font-size:28px}p{margin:0;color:#b7c6df;line-height:1.6}a{color:#8ec1ff;text-decoration:none;font-weight:600}</style></head><body>';
        echo '<div class="card"><h1>Ops, tivemos um problema temporario.</h1><p>Nossa equipe ja foi notificada e estamos trabalhando para normalizar o sistema. Tente novamente em alguns instantes.</p>';
        echo '<p style="margin-top:14px;"><a href="' . htmlspecialchars(url('/')) . '">Voltar para a pagina inicial</a></p></div></body></html>';
    }
}
