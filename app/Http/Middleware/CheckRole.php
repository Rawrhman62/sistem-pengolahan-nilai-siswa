<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Get the current role from session or default to first role
        $currentRole = $request->user()->getCurrentRole();

        // Check if user has the required role
        if (!$request->user()->hasRole($role)) {
            abort(403, 'Unauthorized access. You do not have the required role.');
        }

        // Check if the current active role matches the required role
        if ($currentRole !== $role) {
            abort(403, 'Please switch to the appropriate role to access this page.');
        }

        return $next($request);
    }
}
