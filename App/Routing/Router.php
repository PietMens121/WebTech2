<?php

namespace App\Routing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


class Router implements MiddlewareInterface, RequestHandlerInterface
{
    private $methods = ['GET'];

    private static $routerArray;
    private static $container;

    public function __construct()
    {
        self::$routerArray = new RouteArray();
    }

    public static function add(string $name, Route $route): void
    {
        self::$routeArray->add($name, $route);
    }

    public static function newRoute(string $uri, string $name, string $controller, string $method): Route
    {
        if (in_array($method, self::$methods)) {
            $controller = self::$container->get($controller);
            $route = new Route($method, $uri, $controller);
            self::add($name, $route);
        }
        return $route;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // TODO: Implement process() method.
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement handle() method.
    }
}