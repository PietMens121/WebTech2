<?php

use App\container\Container;
use App\container\ServiceLocator;
use App\Helpers\dd;
use App\Http\Response;
use App\Http\ServerRequest;
use App\Templating\Render;

function request(): ServerRequest
{
    return ServiceLocator::getInstance()->get(ServerRequest::class);

}

function abort($status): void
{
    $reasonPhrase = Response::PHRASES[$status];
    $response = Render::view('errors/error.html', ["statusCode" => $status, "reasonPhrase" => $reasonPhrase], $status);
    Response::send($response);
}

function redirect($url): void
{
    $response = new Response(null, 302);
    Response::send($response->withHeader('Location', $url));
}

function user(): \App\Database\Model|null
{
    return \App\Database\Auth::user();
}

function dd($data): void
{
    $dd = new dd();
    echo $dd->dnl($data);
    exit;
}