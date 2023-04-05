<?php

namespace App\Routing;

class Route
{
    /**
     * Creates route with GET method.
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public static function get(string $path, callable $callback): void
    {
        Router::getInstance()->get($path, $callback);
    }

    /**
     * Creates route with POST method.
     * @param string $path
     * @param callable $callback
     * @return void
     */
    public static function post(string $path, callable $callback): void
    {
        Router::getInstance()->post($path, $callback);
    }


    private string $name;
    private string $method;
    private Path $path;
    private $handler;
    private array $pathSegments;

    /**
     * Constructor
     * @param $method
     * @param $path
     * @param $handler
     * @param $name
     */
    public function __construct($method, $path, $handler, $name = "")
    {
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
}