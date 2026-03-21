<?php

declare(strict_types=1);

namespace App\Services;

class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(): void
    {
        $routeName = $_GET['route'] ?? 'home';

        if (!isset($this->routes[$routeName])) {
            http_response_code(404);
            echo 'Route non trouvée';
            return;
        }

        $controllerClass = $this->routes[$routeName]['controller'];
        $action = $this->routes[$routeName]['action'];

        $controller = new $controllerClass();
        $controller->$action();
    }
}
