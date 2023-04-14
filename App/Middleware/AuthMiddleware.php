<?php

namespace App\Middleware;

use App\Database\Auth;

class AuthMiddleware implements Middleware
{

    public function handle()
    {
        if (!Auth::user()) {
             abort(404);
        }
    }
}