<?php

namespace App\Routing;

use App\container\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    private RouteContainer $routeContainer;
    private array $middlewareRegistry;

    /**
     * Creates a router
     */
    public function __construct(array $middlewareRegistry)
    {
        $this->middlewareRegistry = $middlewareRegistry;
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
            abort(404);
        }

        // Call handler from the route and parse right parameters
        $params = $path->extractParameters($route->getPath());
        return $route->handle($params);
    }

    /**
     * Creates route with GET method.
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function get(string $path, callable $callback): Route
    {
        return $this->addRoute("GET", $path, $callback);
    }

    /**
     * Creates route with POST method.
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public function post(string $path, callable $callback): Route
    {
        return $this->addRoute("POST", $path, $callback);
    }


    private function addRoute(string $method, string $path, callable $callback): Route
    {
        $route = new Route($method, new Path($path), $callback, $this->middlewareRegistry);
        $this->routeContainer->add($route);
        return $route;
    }
}