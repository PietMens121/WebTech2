<?php

namespace App\Http;

use App\container\DIContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Routing\Router;

class RequestHandler implements RequestHandlerInterface
{
    private Router $router;

    public function __construct(DIContainer $container)
    {
        $this->router = $container->get(Router::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->router->handleRequest($request);
    }
}