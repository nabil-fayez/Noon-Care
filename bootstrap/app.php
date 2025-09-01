<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckUserTypeMiddleware;
use App\Http\Middleware\RedirectIfAuthenticatedMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'user.type'=> CheckUserTypeMiddleware::class,
            'guest'=> RedirectIfAuthenticatedMiddleware::class
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();