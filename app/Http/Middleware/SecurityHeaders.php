<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Adds baseline security headers to every response. The CSP intentionally
 * keeps 'unsafe-inline' for script-src/style-src -- every existing view in
 * this app embeds its CSS in a page-level <style> block and its JS in plain
 * <script> tags (no nonces/build pipeline), so a strict CSP would break
 * every page. This still meaningfully restricts framing, base-uri/form
 * hijacking, and loading of executable content from untrusted origins.
 * Permissions-Policy explicitly allows geolocation for 'self' since the
 * donor/hospital location picker (resources/views/components/location_picker.blade.php)
 * depends on it.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(self), camera=(), microphone=(), payment=(), usb=()');
        // Ignored by browsers on plain HTTP, so safe to always send.
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        $response->headers->set('Content-Security-Policy', implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src 'self' data: https:",
            "connect-src 'self'",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'",
        ]));

        return $response;
    }
}
