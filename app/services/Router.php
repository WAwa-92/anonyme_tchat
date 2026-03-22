<?php

namespace App\Services;

class Router
{
    private $routes;

    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    public function run()
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
