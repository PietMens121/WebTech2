<?php

namespace App\Routing;

use Psr\Http\Server\RequestHandlerInterface;

class Route
{
//    private $method;
//    private $uri;
//    private $handler;
//
//    public function __construct($method, $uri, RequestHandlerInterface $handler)
//    {
//        $this->handler = $handler;
//        $this->uri = $uri;
//        $this->method = $method;
//    }

    /**
     * @param string $method
     * @param string $uri
     * @param RequestHandlerInterface $handler
     */
    public function __construct(
        private string $method,
        private string $uri,
        private RequestHandlerInterface $handler
    ){}
}