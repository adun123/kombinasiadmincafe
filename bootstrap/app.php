<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Tymon\JWTAuth\Http\Middleware\Authenticate as JWTMiddleware;
use Illuminate\Foundation\Configuration\Routing;
use App\Http\Middleware\Authenticate;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
            api: __DIR__.'/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })

   

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
      ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'jwt' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        ]);
    })
    //   ->withRoutes(function (Routing $router) {
    //     $router->web();   // untuk routes/web.php
    //     $router->api();   // 🟢 WAJIB kalau mau routes/api.php terbaca
    // })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
