<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePasswordSet
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->password_set) {
            // Allow access to settings page and logout
            if (!$request->routeIs('settings', 'settings.update', 'logout')) {
                return redirect()->route('settings');
            }
        }

        return $next($request);
    }
}