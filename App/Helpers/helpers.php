<?php

use App\container\Container;
use App\Http\Response;
use App\Http\ServerRequest;
use App\Templating\Render;

function request(): ServerRequest {
    return Container::getInstance()->get(ServerRequest::class);
}

function abort($status): void {
    /**
     * @var $response Response
     */
    $response = Render::view('errors/error.html', [], $status);
    $response->send();
}