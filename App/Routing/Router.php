<?php

namespace App\Routing;

use App\Http\Path;
use App\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    //TODO: dependency injection
    private static Router $instance;

    /**
     * Singleton method
     * @return Router
     */
    public static function getInstance(): Router
    {
        if (!isset(self::$instance)) {
            self::$instance = new Router();
        }
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
    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        // Get Path and method from request
        $path = new Path($request->getUri()->getPath());
        $method = $request->getMethod();

        // Check if route exists, if not send 404
        $route = $this->routeContainer->find($method, $path);
        if (is_null($route)) {
            return new Response(404);
        }

        // Call handler from the route and parse right parameters
        $callback = $route->getHandler();
        $params = $path->extractParameters($route->getPath());
        return call_user_func_array($callback, $params);
    }

    /**
     * Creates route with GET method.
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function get(string $path, callable $callback): void
    {
        $this->addRoute("GET", $path, $callback);
    }

    /**
     * Creates route with POST method.
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function post(string $path, callable $callback): void
    {
        $this->addRoute("POST", $path, $callback);
    }


    private function addRoute(string $method, string $path, callable $callback): void
    {
        $this->routeContainer->add(new Route($method, new Path($path), $callback));
    }
}