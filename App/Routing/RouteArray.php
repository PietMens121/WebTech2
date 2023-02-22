<?php

namespace App\Routing;

use App\Routing\Route;

class RouteArray
{

    private static array $routes = [];

    /**
     * @param string $name
     * @param \App\Routing\Route $route
     * @return void
     */
    public function add(string $name, Route $route): void
    {
        self::$routes[$name] = $route;
    }

    /**
     * @param string $name
     * @return array|false
     */
    public function get(string $name)
    {
        if (array_key_exists($name, self::$routes)) {
            return self::$routes;
        } else {
            return false;
        }
    }

    /**
     * @param string $uri
     * @return mixed|null
     */
    public function getUri(string $uri)
    {
        foreach (self::$routes as $key => $route) {
            if($route->getUri() === $uri ){
                return $route;
            }
        }
        return null;
    }
}