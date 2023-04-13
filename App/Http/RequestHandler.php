<?php

namespace App\Http;

use App\container\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Routing\Router;

class RequestHandler implements RequestHandlerInterface
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->container->set(ServerRequest::class, $request);

        /**
         * @var $router Router
         */
        $router = $this->container->get(Router::class);
        return $router->handleRequest($request);
    }
}