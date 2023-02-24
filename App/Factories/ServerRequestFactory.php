<?php

namespace App\Factories;

use App\Http\ServerRequest;

class ServerRequestFactory
{
    public static function createServerRequest()
    {
        return new ServerRequest(
            getallheaders(),
            "php://input",
            $_SERVER,
            $_COOKIE,
            $_GET,
            $_POST,
            $_FILES
        );
    }
}