<?php

namespace App\Routing;

class Router
{
    public function get(string $uri, callable $callback) {
        $this->addRoute("GET", $uri, $callback);
    }

    public function post(string $uri, callable $callback) {
        $this->addRoute("POST", $uri, $callback);
    }

    private function addRoute(string $method, string $uri, callable $callback) {

    }
}