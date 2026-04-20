<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Get security headers configuration
        $headers = config('security.headers', []);

        // Add security headers
        $response->headers->set('X-Content-Type-Options', $headers['x_content_type_options'] ?? 'nosniff');
        $response->headers->set('X-Frame-Options', $headers['x_frame_options'] ?? 'DENY');
        $response->headers->set('X-XSS-Protection', $headers['x_xss_protection'] ?? '1; mode=block');
        $response->headers->set('Referrer-Policy', $headers['referrer_policy'] ?? 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', $headers['permissions_policy'] ?? 'geolocation=(), microphone=(), camera=()');
        
        // Only add HSTS in production
        if (app()->environment('production')) {
            $maxAge = $headers['hsts_max_age'] ?? 31536000;
            $response->headers->set('Strict-Transport-Security', "max-age={$maxAge}; includeSubDomains");
        }

        return $response;
    }
}