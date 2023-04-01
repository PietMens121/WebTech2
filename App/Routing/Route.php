<?php

namespace App\Routing;

class Route
{
    // Static functions
    public static function get(string $uri, callable $callback) : void {
        Router::getInstance()->get($uri, $callback);
    }

    public static function post(string $uri, callable $callback) : void {
        Router::getInstance()->post($uri, $callback);
    }

    // Fields
    private string $name;
    private string $method;
    private Uri $uri;
    private $handler;
    private array $uriSegments;

    // Constructor
    public function __construct($method, $uri, $handler, $name = "")
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->handler = $handler;
        $this->name = $name;
    }

    // Functions
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri(): Uri
    {
        return $this->uri;
    }

    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }
}