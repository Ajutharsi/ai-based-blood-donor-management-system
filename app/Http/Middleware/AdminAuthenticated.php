<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth('admin')->check()) {
            return redirect()->route('admin.login')
                             ->with('error', 'Please log in as admin.');
        }
        return $next($request);
    }
}