<?php

use Services\Router;

session_start();

spl_autoload_register(function ($class) {
    $prefixes = [
        'Controllers\\' => __DIR__ . '/controllers/',
        'Models\\'      => __DIR__ . '/models/',
        'Managers\\'    => __DIR__ . '/managers/',
        'Services\\'    => __DIR__ . '/services/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (strpos($class, $prefix) === 0) {
            $relativeClass = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

            if (file_exists($file)) {
                require_once $file;
            }
        }
    }
});

require_once __DIR__ . '/configs/settings.php';

$router = new Router(AVAILABLE_ROUTES);
$router->run();
