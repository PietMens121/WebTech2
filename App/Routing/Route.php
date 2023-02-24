<?php

namespace App\Routing;

use src\controllers\Controller;

class Route
{
    /**
     * @param string $uri
     * @param string $name
     * @param Controller $controller
     * @param string $method
     * @param string $function
     */
    public function __construct(
        private string $uri,
        private string $name,
        private Controller $controller,
        private string $method,
        private string $function,
    ) {
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getFunction()
    {
        return $this->function;
    }

    public function getHandler(){
        return $this->handler;
    }
}