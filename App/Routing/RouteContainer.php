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
     * @param Uri $uri
     * @return Route|null
     */
    function find(string $method, Uri $uri): Route|null
    {
        foreach ($this->routes[$method] as $route) {
            if ($route->getUri()->matches($uri)) {
                return $route;
            }
        }
        return null;
    }
}