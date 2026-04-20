<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLogins
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->throttleKey($request);
        $maxAttempts = config('security.login_rate_limit.max_attempts', 5);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            return response()->json([
                'message' => 'Too many login attempts. Please try again in ' . $seconds . ' seconds.',
                'retry_after' => $seconds
            ], 429);
        }

        $response = $next($request);

        // If login failed (validation exception or redirect back), increment attempts
        if ($response->getStatusCode() === 422 || 
            ($response->getStatusCode() === 302 && $response->headers->get('Location') === $request->url())) {
            $decayMinutes = config('security.login_rate_limit.decay_minutes', 5);
            RateLimiter::hit($key, $decayMinutes * 60); // Convert to seconds
        } else {
            // Clear attempts on successful login
            RateLimiter::clear($key);
        }

        return $response;
    }

    /**
     * Get the throttle key for the given request.
     */
    protected function throttleKey(Request $request): string
    {
        return 'login_attempts:' . $request->ip() . ':' . strtolower($request->input('user_id', ''));
    }
}