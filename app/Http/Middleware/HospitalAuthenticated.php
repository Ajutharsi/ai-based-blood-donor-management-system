<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HospitalAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (!auth('hospital')->check()) {
            return redirect()->route('hospital.login')
                             ->with('error', 'Please log in as a hospital.');
        }
        return $next($request);
    }
}
