<?php

spl_autoload_register(function ($class) {
    // Mapeamento de namespaces para diretórios
    $prefixes = [
        'App\\' => __DIR__ . '/',
        'Modules\\' => dirname(__DIR__) . '/modules/',
    ];
    
    foreach ($prefixes as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relative_class = substr($class, $len);
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
            
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

require __DIR__ . '/Helpers/helpers.php';
