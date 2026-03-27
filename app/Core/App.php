<?php

declare(strict_types=1);

namespace App\Core;

use Dotenv\Dotenv;

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
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    private function loadEnvironment(): void
    {
        if (file_exists(BASE_PATH . '/.env')) {
            $dotenv = Dotenv::createImmutable(BASE_PATH);
            $dotenv->load();
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

    private function handleException(\Exception $e): void
    {
        http_response_code(500);
        echo '<h1>Error Debug</h1>';
        echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . '</p>';
        echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    }
}
