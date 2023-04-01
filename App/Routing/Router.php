<?php

namespace App\Routing;

use App\Http\Response;

class Router
{
    private static Router $instance;

    /**
     * Singleton method
     * @return Router
     */
    public static function getInstance(): Router
    {
        if (!isset(self::$instance))
            self::$instance = new Router();
        return self::$instance;
    }


    private RouteContainer $routeContainer;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->routeContainer = new RouteContainer();
    }

    /**
     * Handles incoming request.
     * @return void
     */
    public function handleRequest() : void {
        // Get URI and method from request
        $uri = new Uri($_SERVER['REQUEST_URI']);
        $method = $_SERVER['REQUEST_METHOD'];

        // Check if route exists, if not send 404
        $route = $this->routeContainer->find($method, $uri);
        if (is_null($route)) new Response(404);

        // Call handler from the route and parse right parameters
        $callback = $route->getHandler();
        call_user_func_array($callback, $uri->extractParameters($route->getUri()));
    }

    /**
     * Creates route with GET method.
     * @param string $uri
     * @param callable $callback
     * @return void
     */
    public function get(string $uri, callable $callback) : void {
        $this->addRoute("GET", $uri, $callback);
    }

    /**
     * Creates route with POST method.
     * @param string $uri
     * @param callable $callback
     * @return void
     */
    public function post(string $uri, callable $callback) : void {
        $this->addRoute("POST", $uri, $callback);
    }


    private function addRoute(string $method, string $uri, callable $callback) : void {
        $this->routeContainer->add(new Route($method, new Uri($uri), $callback));
    }
}