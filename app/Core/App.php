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
            'portal',
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
        // Clean any partial output first
        while (ob_get_level() > 0) { @ob_end_clean(); }

        // Log error
        try {
            (new Logger())->error('app.unhandled_exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
                'uri'     => $_SERVER['REQUEST_URI'] ?? null,
            ]);
        } catch (\Throwable $logError) {}

        $uri = $_SERVER['REQUEST_URI'] ?? '';

        // For management sub-pages, redirect to dashboard with error message
        if (str_contains($uri, '/gestao') && $uri !== '/gestao' && $uri !== '/gestao/') {
            try {
                Session::flash('error', 'Erro ao carregar a pagina. Tente novamente.');
            } catch (\Throwable $ignored) {}
            if (!headers_sent()) {
                header('Location: /gestao', true, 302);
            }
            exit;
        }

        // For Hub sub-pages, redirect to hub home with friendly message
        if (str_starts_with($uri, '/hub') && $uri !== '/hub' && $uri !== '/hub/') {
            try {
                Session::flash('error', 'Não foi possível carregar a página agora. Tente novamente em instantes.');
            } catch (\Throwable $ignored) {}
            if (!headers_sent()) {
                header('Location: /hub', true, 302);
            }
            exit;
        }

        // For Admin sub-pages: render a contained error page (no redirect loop back to /admin).
        // Os controllers do admin tratam falhas de DB internamente com modo de contingência;
        // se mesmo assim algo falhar, mostramos a página de erro contida para o usuário poder
        // navegar pelo menu lateral sem perder o contexto.
        if (str_starts_with($uri, '/admin') && $uri !== '/admin' && $uri !== '/admin/') {
            $debug = (bool) (config('app')['debug'] ?? false);
            if ($debug) {
                http_response_code(500);
                echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Admin — Erro</title>';
                echo '<style>body{margin:0;font-family:ui-monospace,Menlo,Consolas,monospace;background:#0b1730;color:#e7edf7;padding:32px;line-height:1.5}';
                echo '.box{max-width:980px;margin:0 auto;background:rgba(255,255,255,.05);border:1px solid rgba(255,80,80,.4);border-radius:12px;padding:24px}';
                echo 'h1{margin:0 0 8px;color:#ff8c8c;font-size:18px}h2{margin:18px 0 6px;font-size:14px;color:#9ec0ff}';
                echo 'pre{white-space:pre-wrap;word-break:break-word;background:rgba(0,0,0,.3);padding:14px;border-radius:8px;font-size:12px;margin:0}';
                echo 'a{color:#8ec1ff}</style></head><body><div class="box">';
                echo '<h1>Erro no Admin (APP_DEBUG=true)</h1>';
                echo '<p>' . htmlspecialchars(get_class($e) . ': ' . $e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<h2>' . htmlspecialchars($e->getFile() . ':' . $e->getLine(), ENT_QUOTES, 'UTF-8') . '</h2>';
                echo '<h2>Stack trace</h2><pre>' . htmlspecialchars($e->getTraceAsString(), ENT_QUOTES, 'UTF-8') . '</pre>';
                echo '<p style="margin-top:16px;"><a href="/admin">← Voltar ao painel</a></p>';
                echo '</div></body></html>';
                exit;
            }

            // Production: render contained error page that keeps admin navigation context.
            http_response_code(500);
            echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Admin — Erro temporário</title>';
            echo '<style>body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#061a3a;color:#e7edf7;display:grid;place-items:center;min-height:100vh;padding:24px}';
            echo '.card{max-width:640px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.14);border-radius:16px;padding:28px;box-shadow:0 14px 44px rgba(0,0,0,.35)}';
            echo 'h1{margin:0 0 12px;font-size:24px;color:#ffc56b}p{margin:0 0 12px;color:#b7c6df;line-height:1.6}';
            echo 'a{color:#8ec1ff;text-decoration:none;font-weight:600;display:inline-block;margin-top:8px}</style></head><body>';
            echo '<div class="card"><h1>Página administrativa indisponível</h1>';
            echo '<p>Não foi possível carregar essa seção do painel administrativo neste momento. O serviço de dados pode estar temporariamente fora do ar — tente novamente em alguns instantes.</p>';
            echo '<p>Se o problema persistir, verifique a conexão com o banco de dados ou abra um chamado de suporte.</p>';
            echo '<a href="/admin">← Voltar ao painel</a></div></body></html>';
            exit;
        }

        http_response_code(500);

        echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Erro interno</title>';
        echo '<style>body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#061a3a;color:#e7edf7;display:grid;place-items:center;min-height:100vh;padding:24px}';
        echo '.card{max-width:680px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.14);border-radius:16px;padding:28px;box-shadow:0 14px 44px rgba(0,0,0,.35)}';
        echo 'h1{margin:0 0 12px;font-size:28px}p{margin:0;color:#b7c6df;line-height:1.6}a{color:#8ec1ff;text-decoration:none;font-weight:600}</style></head><body>';
        echo '<div class="card"><h1>Ops, tivemos um problema temporario.</h1><p>Nossa equipe ja foi notificada e estamos trabalhando para normalizar o sistema. Tente novamente em alguns instantes.</p>';
        echo '<p style="margin-top:14px;"><a href="/">Voltar para a pagina inicial</a></p></div></body></html>';
    }
}
