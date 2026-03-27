<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middlewareGroups = [];
    private string $currentPrefix = '';
    private array $currentMiddleware = [];

    public function get(string $path, array|string $action, string $name = ''): self
    {
        return $this->addRoute('GET', $path, $action, $name);
    }

    public function post(string $path, array|string $action, string $name = ''): self
    {
        return $this->addRoute('POST', $path, $action, $name);
    }

    public function put(string $path, array|string $action, string $name = ''): self
    {
        return $this->addRoute('PUT', $path, $action, $name);
    }

    public function delete(string $path, array|string $action, string $name = ''): self
    {
        return $this->addRoute('DELETE', $path, $action, $name);
    }

    public function group(array $options, callable $callback): void
    {
        $previousPrefix = $this->currentPrefix;
        $previousMiddleware = $this->currentMiddleware;

        if (isset($options['prefix'])) {
            $this->currentPrefix .= '/' . trim($options['prefix'], '/');
        }

        if (isset($options['middleware'])) {
            $middleware = is_array($options['middleware']) ? $options['middleware'] : [$options['middleware']];
            $this->currentMiddleware = array_merge($this->currentMiddleware, $middleware);
        }

        $callback($this);

        $this->currentPrefix = $previousPrefix;
        $this->currentMiddleware = $previousMiddleware;
    }

    public function dispatch(string $method, string $uri, Request $request): void
    {
        $uri = '/' . trim($uri, '/');

        if ($method === 'POST') {
            $override = $request->input('_method', '');
            if (in_array(strtoupper($override), ['PUT', 'DELETE'])) {
                $method = strtoupper($override);
            }
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->buildPattern($route['path']);

            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $request->setRouteParams($params);

                $this->executeMiddleware($route['middleware'], $request, function() use ($route, $request) {
                    $this->callAction($route['action'], $request);
                });
                return;
            }
        }

        http_response_code(404);
        $view = new View();
        if (file_exists(BASE_PATH . '/resources/views/errors/404.php')) {
            echo $view->render('errors/404');
        } else {
            echo '<h1>404 - Página não encontrada</h1>';
        }
    }

    private function addRoute(string $method, string $path, array|string $action, string $name): self
    {
        $fullPath = $this->currentPrefix . '/' . trim($path, '/');
        $fullPath = '/' . trim($fullPath, '/');

        $this->routes[] = [
            'method'     => $method,
            'path'       => $fullPath,
            'action'     => $action,
            'middleware'  => $this->currentMiddleware,
            'name'       => $name,
        ];

        return $this;
    }

    private function buildPattern(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function executeMiddleware(array $middleware, Request $request, callable $final): void
    {
        $middlewareConfig = config('middleware', []);
        $aliases = $middlewareConfig['aliases'] ?? [];

        $stack = $final;

        foreach (array_reverse($middleware) as $mw) {
            $params = [];
            if (str_contains($mw, ':')) {
                [$mw, $paramStr] = explode(':', $mw, 2);
                $params = explode(',', $paramStr);
            }

            $class = $aliases[$mw] ?? $mw;

            if (!class_exists($class)) {
                continue;
            }

            $previousStack = $stack;
            $stack = function () use ($class, $request, $previousStack, $params) {
                $instance = new $class();
                $instance->handle($request, $previousStack, ...$params);
            };
        }

        $stack();
    }

    private function callAction(array|string $action, Request $request): void
    {
        if (is_string($action)) {
            echo $action;
            return;
        }

        [$controllerClass, $method] = $action;

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerClass} not found");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            throw new \Exception("Method {$method} not found in {$controllerClass}");
        }

        $controller->$method($request);
    }
}
