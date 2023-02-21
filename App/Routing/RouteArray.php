<?php

use App\Routing\Route;

class RouteArray
{

    private static array $routes = [];

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

    public function getUri(string $uri)
    {

    }
}