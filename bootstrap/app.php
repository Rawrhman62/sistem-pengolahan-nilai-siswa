<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'password.set' => \App\Http\Middleware\EnsurePasswordSet::class,
            'throttle.login' => \App\Http\Middleware\ThrottleLogins::class,
        ]);
        
        // Apply password check to all authenticated routes
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\EnsurePasswordSet::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        // Replace default CSRF middleware with our custom one
        $middleware->web(replace: [
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class => \App\Http\Middleware\VerifyCsrfToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
