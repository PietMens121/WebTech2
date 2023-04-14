<?php

return [

    // All middleware names and corresponding classes

    'auth' => \App\Middleware\AuthMiddleware::class,
    'lecturer' => \App\Middleware\LecturerMiddleware::class,
    'admin' => \App\Middleware\AdminMiddleware::class,
];