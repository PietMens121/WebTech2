<?php

namespace App\Routing;

use App\container\Container;
use App\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;

class Route
{

    public static function setRouter(Router $router): void
    {
        self::$router = $router;
    }

    private static Router $router;

    /**
     * Creates route with GET method.
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public static function get(string $path, callable $callback): Route
    {
        return self::$router->get($path, $callback);
    }

    /**
     * Creates route with POST method.
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public static function post(string $path, callable $callback): Route
    {
        return self::$router->post($path, $callback);
    }


    private string $name;
    private string $method;
    private Path $path;
    private $handler;
    private array $middlewareRegistry;
    /**
     * @var Middleware[]
     */
    private array $middleware = [];

    /**
     * Constructor
     * @param $method
     * @param $path
     * @param $handler
     * @param $name
     */
    public function __construct($method, $path, $handler, $middlewareRegistry, $name = "")
    {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
        $this->middlewareRegistry = $middlewareRegistry;
        $this->name = $name;
    }

    // Functions

    /**
     * Getter for name of the route.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Getter for method of the route.
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Getter for {@link Path} of the route.
     * @return string
     */
    public function getPath(): Path
    {
        return $this->path;
    }

    /**
     * Getter for handler of the route.
     * @return callable
     */
    public function getHandler(): callable
    {
        return $this->handler;
    }

    public function handle(array $params): ResponseInterface
    {
        // Call middleware
        foreach ($this->middleware as $middleware) {
            $middleware->handle();
        }

        // Call handler
        return call_user_func_array($this->handler, $params);
    }

    public function middleware(string $name): Route
    {
        $this->middleware[] = new ($this->middlewareRegistry[$name])();
        return $this;
    }
}