<?php

namespace App\Routing;

use App\Factories\HttpFactory;
use App\Routing\RouteArray;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use App\Http\Auth\AuthUser;

class Router implements MiddlewareInterface, RequestHandlerInterface
{
    private $methods = ['GET'];

    private static $routerArray;
    private static $container;

    public function __construct()
    {
        self::$routerArray = new RouteArray();
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

    public static function newRoute(string $uri, string $name, string $controller, string $method, string $function): Route
    {
        if (in_array($method, self::$methods)) {
            $controller = self::$container->get($controller);
            $route = new Route($method, $uri, $controller, $function);
            self::add($name, $route);
        }
        return $route;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $controller): ResponseInterface
    {
        $route = self::$routeArray->getURI(parse_url($request->getUri(), PHP_URL_PATH));
        if($route !== null) {
            if($route->getMethod() === $request->getMethod()) {

                if(!empty($route->getPermissions())) {
                    if( empty($_SESSION['user_permissions']) or
                        !AuthUser::hasPermissions($_SESSION['user_permissions'], $route->getPermissions()) )
                    {
                        return HttpFactory::createResponse(401)->withAddedHeader('Location', '/');
                    }
                }

                $routeHandler = $route->getHandler();
                return $routeHandler->handle($request);

            } else {
                // wrong method.
                return HttpFactory::createResponse(405);
            }
        } else {
            return $controller->handle($request);
        }
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