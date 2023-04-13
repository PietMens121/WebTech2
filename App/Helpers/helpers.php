<?php

use App\container\Container;
use App\Http\ServerRequest;

function request(): ServerRequest {
    return Container::getInstance()->get(ServerRequest::class);
}