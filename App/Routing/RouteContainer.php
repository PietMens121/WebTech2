<?php

namespace App\Routing;

class RouteContainer
{
    /**
     * @var array<string, Route[]>
     */
    private array $routes = [];

    /**
     * Adds route to container
     * @param Route $route
     * @return void
     */
    function add(Route $route): void
    {
        $this->routes[$route->getMethod()][] = $route;
    }

    /**
     * Find corresponding route to URI in container.
     * Returns null if no route was found.
     * @param string $method
     * @param Path $uri
     * @return Route|null
     */
    function find(string $method, Path $uri): Route|null
    {
        foreach ($this->routes[$method] as $route) {
            if ($route->getPath()->matches($uri)) {
                return $route;
            }
        }
        return null;
    }
}