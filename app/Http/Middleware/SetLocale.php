<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Resolves which locale to render this request in and applies it. Priority:
 * the authenticated user's own stored preference (whichever guard is
 * logged in) > the session value set by a just-performed language switch >
 * the app default. Runs on every request so it stays correct across guards
 * without needing per-route wiring.
 */
class SetLocale
{
    public const SUPPORTED = ['en', 'si', 'ta'];

    public function handle(Request $request, Closure $next)
    {
        $locale = null;

        foreach (['donor', 'hospital', 'admin'] as $guard) {
            if (auth($guard)->check()) {
                $locale = auth($guard)->user()->locale;
                break;
            }
        }

        $locale = $locale ?: $request->session()->get('locale');

        if (!in_array($locale, self::SUPPORTED, true)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
