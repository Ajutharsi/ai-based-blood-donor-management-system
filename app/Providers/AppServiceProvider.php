<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Keyed by IP + submitted email so one attacker can't lock out a
        // real user by hammering their email, while still capping brute
        // force from a single source.
        RateLimiter::for('login', function ($request) {
            return Limit::perMinute(5)->by($request->ip() . '|' . strtolower((string) $request->input('email')));
        });

        RateLimiter::for('register', function ($request) {
            return Limit::perHour(10)->by($request->ip());
        });

        RateLimiter::for('chat', function ($request) {
            return Limit::perMinute(15)->by($request->ip());
        });

        RateLimiter::for('blood-request', function ($request) {
            return Limit::perMinute(10)->by(auth('hospital')->id() ?? $request->ip());
        });

        RateLimiter::for('donor-notify', function ($request) {
            return Limit::perMinute(20)->by(auth('hospital')->id() ?? $request->ip());
        });
    }
}
