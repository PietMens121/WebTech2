<?php

namespace App\Routing;

use App\Http\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use src\controllers\Controller;

class Router implements MiddlewareInterface, RequestHandlerInterface
{
    /**
     * @var string[]
     */
    private static array $methods = ['GET', 'POST'];


    private static $container;
    private static $routeArray;

    public function __construct()
    {
        self::$routeArray = new RouteArray();
    }

    /**
     * @param string $name
     * @param Route $route
     * @return void
     */
    public static function add(string $name, Route $route): void
    {
        self::$routeArray->add($name, $route);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param string $name
     * @param Controller $controller
     * @param string $function
     * @return Route
     */

    public static function newRoute(
        string $method,
        string $uri,
        string $name,
        string $controller,
        string $function
    ): Route {
//        Check if the method is available
        if (in_array(strtoupper($method), self::$methods)) {
//            Get the controller that is in the container
            $controller = self::$container->get($controller);

//            Set the new Route
            $route = new Route($uri, $name, $controller, $method, $function);
            self::add($name, $route);
        }
        return $route;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
//        Get the route from RouteArray
        $route = self::$routeArray->getUri(parse_url($request->getUri(), PHP_URL_PATH));

//        Check if the route exists
        if ($route == null) {
            return new Response('404');
        }

//        Check if the method in $methods exists
        if (!in_array($route->getMethod(), $this::$methods)) {
            return new Response('405');
        }

        if (!class_exists($route->getController())) {
            return new Response('404');
        }

        if (!method_exists($route->getContorller(), $route->getFunction())) {
            return new Response('404');
        }

        return new Response('200');
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $notFound = new RouteNotFoundHandler();
        return $notFound->handle($request);
    }

    public function setContainer(ContainerInterface $container)
    {
        self::$container = $container;
    }
}