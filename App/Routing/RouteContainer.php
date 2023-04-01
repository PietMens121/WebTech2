<?php

namespace App\Routing;

class RouteContainer
{
    private array $routes = [];

    function add(Route $route) : void {
        $this->routes[$route->getMethod()][$route->getUri()] = $route;
    }

    function find(string $method, string $uri) {
        return $this->routes[$method][$uri];
    }
}