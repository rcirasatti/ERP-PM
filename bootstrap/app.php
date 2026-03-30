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
            'check.role' => \App\Http\Middleware\CheckRole::class,
            'check.any.role' => \App\Http\Middleware\CheckAnyRole::class,
        ]);
        
        // Throttle middleware configuration for BoQ upload endpoints
        $middleware->throttleWithRedis('boq_upload', '60,5');    // 5 requests per 60 seconds
        $middleware->throttleWithRedis('boq_store', '60,3');     // 3 requests per 60 seconds
        $middleware->throttleWithRedis('boq_analyze', '60,2');   // 2 requests per 60 seconds
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
