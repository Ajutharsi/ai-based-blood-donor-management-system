<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Central log point for every unauthorized/forbidden response,
        // regardless of which controller/middleware raised it (abort(403),
        // a failed guard middleware, etc.) -- avoids needing a log call at
        // every individual authorization check across the app. Deliberately
        // a render() hook, not report(): Laravel's default handler puts
        // HttpException/AuthorizationException in its internal "don't
        // report" list (they're expected, not exceptional), so a report()
        // callback here would silently never run. render() callbacks are
        // not filtered that way, and returning null (nothing) here lets
        // Laravel's normal rendering (our resources/views/errors/* pages)
        // proceed untouched -- this hook only observes, never replaces
        // the response.
        $exceptions->render(function (HttpExceptionInterface $e, $request) {
            if (in_array($e->getStatusCode(), [401, 403], true)) {
                Log::warning('Unauthorized access attempt', [
                    'status'   => $e->getStatusCode(),
                    'path'     => $request->path(),
                    'method'   => $request->method(),
                    'ip'       => $request->ip(),
                    'donor_id' => auth('donor')->id(),
                    'hospital_id' => auth('hospital')->id(),
                    'admin_id' => auth('admin')->id(),
                ]);
            }
        });
    })->create();