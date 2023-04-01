<?php

namespace App\Routing;

use App\Http\Response;

class Router
{
    // Singleton
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

    // Fields
    private RouteContainer $routeContainer;

    // Constructor
    public function __construct()
    {
        $this->routeContainer = new RouteContainer();
    }

    // Functions
    public function handleRequest() : void {
        $uri = new Uri($_SERVER['REQUEST_URI']);
        $method = $_SERVER['REQUEST_METHOD'];

        $route = $this->routeContainer->find($method, $uri);
        if (is_null($route)) new Response(404);

        $callback = $route->getHandler();
        call_user_func_array($callback, $uri->extractParameters($route->getUri()));
    }

    public function get(string $uri, callable $callback) : void {
        $this->addRoute("GET", $uri, $callback);
    }

    public function post(string $uri, callable $callback) : void {
        $this->addRoute("POST", $uri, $callback);
    }

    private function addRoute(string $method, string $uri, callable $callback) : void {
        $this->routeContainer->add(new Route($method, new Uri($uri), $callback));
    }
}