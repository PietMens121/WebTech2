<?php

namespace App\Middleware;

use App\Database\Auth;

class LecturerMiddleware implements Middleware
{
    public function handle()
    {
        if(Auth::user()->Role()->name == 'lecturer'){
            return;
        }
        if(Auth::user()->Role()->name == 'admin'){
            return;
        }
        redirect('/');
    }
}