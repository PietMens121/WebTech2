<?php

namespace App\Routing;

use Psr\Http\Server\RequestHandlerInterface;

class Route
{
    /**
     * @param string $method
     * @param string $uri
     * @param RequestHandlerInterface $handler
     */
    public function __construct(
        private string $method,
        private string $uri,
        private RequestHandlerInterface $handler,
        private string $function
    ){}
}