<?php

use App\container\DIContainer;
use App\Http\Response;
use App\Http\ServerRequest;
use App\Templating\Render;

function request(): ServerRequest {
    return DIContainer::getInstance()->get(ServerRequest::class);
}

function abort($status): void {
    /**
     * @var $response Response
     */
    $response = Render::view('errors/error.html', [], $status);
    $response->send();
}

function user(): \App\Database\Model|null {
    return \App\Database\Auth::user();
}