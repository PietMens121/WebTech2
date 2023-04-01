<?php

namespace App\Routing;

class RouteContainer
{
    /**
     * @var array<string, Route[]>
     */
    private array $routes = [];

    function add(Route $route) : void {
        $this->routes[$route->getMethod()][] = $route;
    }

    function find(string $method, Uri $uri) : Route|null {
        foreach ($this->routes[$method] as $route) {
            if ($route->getUri()->matches($uri)) return $route;
        }
        return null;
    }
}