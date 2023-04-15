<?php

namespace App\Middleware;

use App\Database\Auth;

class LecturerMiddleware implements Middleware
{
    public function handle()
    {
        (new AuthMiddleware())->handle();

        if(Auth::user()->Role()->name == 'lecturer'){
            return;
        }
        if(Auth::user()->Role()->name == 'admin'){
            return;
        }
        abort(401);
    }
}