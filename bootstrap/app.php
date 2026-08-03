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
    ->withMiddleware(function (Middleware $middleware) {
        // ── ADD THIS BLOCK ──
        $middleware->alias([
            'donor.auth' => \App\Http\Middleware\DonorAuthenticated::class,
            'admin.auth' => \App\Http\Middleware\AdminAuthenticated::class,
            'hospital.auth' => \App\Http\Middleware\HospitalAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();