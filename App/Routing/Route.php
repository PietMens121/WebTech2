<?php

namespace App\Routing;

class Route
{
    private string $name;
    private string $method;
    private string $uri;
    private $handler;

    public function __construct($method, $uri, $handler, $name = "")
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->handler = $handler;
        $this->name = $name;
    }

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
    public function getUri(): string
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