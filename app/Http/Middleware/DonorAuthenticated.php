<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DonorAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth('donor')->check()) {
            return redirect()->route('donor.login')
                             ->with('error', 'Please log in to access your dashboard.');
        }
        return $next($request);
    }
}