<?php

namespace App\Routing;

class Router
{
    private static Router $instance;

    /**
     * @return Router
     */
    public static function getInstance(): Router
    {
        if (!isset(self::$instance))
            self::$instance = new Router();
        return self::$instance;
    }

    private RouteArray $routeArray;

    public function __construct()
    {
        $this->routeArray = new RouteArray();
    }

    public function handleRequest() : void {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        $route = $this->routeArray->find($method, $uri);
        $route->getHandler()();
    }

    public function get(string $uri, callable $callback) : void {
        $this->addRoute("GET", $uri, $callback);
    }

    public function post(string $uri, callable $callback) : void {
        $this->addRoute("POST", $uri, $callback);
    }

    private function addRoute(string $method, string $uri, callable $callback) : void {
        $this->routeArray->add(new Route($method, $uri, $callback));
    }
}