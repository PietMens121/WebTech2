<?php

namespace App\Routing;

use App\container\Container;
use App\Http\Path;
use App\Middleware\Middleware;
use Psr\Http\Message\ResponseInterface;

class Route
{
    /**
     * Creates route with GET method.
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public static function get(string $path, callable $callback): Route
    {
        /**
         * @var $router Router
         */
        $router = Container::getInstance()->get(Router::class);
        return $router->get($path, $callback);
    }

    /**
     * Creates route with POST method.
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public static function post(string $path, callable $callback): Route
    {
        /**
         * @var $router Router
         */
        $router = Container::getInstance()->get(Router::class);
        return $router->post($path, $callback);
    }


    private Container $diContainer;
    private string $name;
    private string $method;
    private Path $path;
    private $handler;
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
    public function __construct(Container $diContainer, $method, $path, $handler, $name = "")
    {
        $this->diContainer = $diContainer;
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
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

    public function middleware(string $middleware): Route
    {
        $this->middleware[] = new ($this->diContainer->get("MiddlewareRegistry")[$middleware])();
        return $this;
    }
}