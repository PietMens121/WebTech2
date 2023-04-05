<?php

namespace App\Routing;

class Route
{
    /**
     * Creates route with GET method.
     * @param string $uri
     * @param callable $callback
     * @return void
     */
    public static function get(string $uri, callable $callback): void
    {
        Router::getInstance()->get($uri, $callback);
    }

    /**
     * Creates route with POST method.
     * @param string $uri
     * @param callable $callback
     * @return void
     */
    public static function post(string $uri, callable $callback): void
    {
        Router::getInstance()->post($uri, $callback);
    }


    private string $name;
    private string $method;
    private Uri $uri;
    private $handler;
    private array $uriSegments;

    /**
     * Constructor
     * @param $method
     * @param $uri
     * @param $handler
     * @param $name
     */
    public function __construct($method, $uri, $handler, $name = "")
    {
        $this->method = $method;
        $this->uri = $uri;
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
     * Getter for {@link Uri} of the route.
     * @return string
     */
    public function getUri(): Uri
    {
        return $this->uri;
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