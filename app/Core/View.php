<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    private array $sections = [];
    private array $sectionStack = [];
    private string $currentLayout = '';
    private array $layoutData = [];

    public function render(string $view, array $data = []): string
    {
        $this->sections = [];
        $this->currentLayout = '';

        $content = $this->renderFile($view, $data);

        if ($this->currentLayout) {
            $this->sections['content'] = $content;
            return $this->renderFile(
                'layouts/' . $this->currentLayout,
                array_merge($this->layoutData, $data)
            );
        }

        return $content;
    }

    public function renderFile(string $view, array $data = []): string
    {
        $path = BASE_PATH . '/resources/views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($path)) {
            throw new \Exception("View [{$view}] not found at {$path}");
        }

        extract($data, EXTR_SKIP);
        $__view = $this;

        ob_start();
        require $path;
        return ob_get_clean();
    }

    public function extends(string $layout, array $data = []): void
    {
        $this->currentLayout = $layout;
        $this->layoutData = $data;
    }

    public function section(string $name): void
    {
        $this->sectionStack[] = $name;
        ob_start();
    }

    public function endSection(): void
    {
        $name = array_pop($this->sectionStack);
        $this->sections[$name] = ob_get_clean();
    }

    public function yield(string $name, string $default = ''): string
    {
        return $this->sections[$name] ?? $default;
    }

    public function partial(string $view, array $data = []): string
    {
        return $this->renderFile('partials/' . $view, $data);
    }

    public function component(string $view, array $data = []): string
    {
        return $this->renderFile('components/' . $view, $data);
    }

    public static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', true);
    }
}
