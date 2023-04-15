<?php

namespace App\Middleware;

use App\Database\Auth;

class AdminMiddleware implements Middleware
{
    public function handle()
    {
        (new AuthMiddleware())->handle();

        if(Auth::user()->Role()->name !== 'admin'){
            abort(401);
        }
    }
}