<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Check if user has multiple roles for role selection.
     */
    public function checkUserRoles(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string'
        ]);

        $user = \App\Models\User::where('user_id', $request->user_id)->first();
        
        if (!$user) {
            return response()->json([
                'has_multiple_roles' => false,
                'roles' => []
            ]);
        }

        $roles = $user->getRoles();
        $hasMultipleRoles = count($roles) > 1;

        return response()->json([
            'has_multiple_roles' => $hasMultipleRoles,
            'roles' => $hasMultipleRoles ? $roles : []
        ]);
    }

    /*** Handle login request with userId-based authentication.*/
    
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $throttleKey = 'login_attempts:' . $request->ip() . ':' . strtolower($credentials['user_id']);
        $maxAttempts = config('security.login_rate_limit.max_attempts', 5);

        // Check rate limiting
        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            throw ValidationException::withMessages([
                'user_id' => ['Too many login attempts. Please try again in ' . $seconds . ' seconds.'],
            ]);
        }

        // For users without password set, allow blank password
        $user = \App\Models\User::where('user_id', $credentials['user_id'])->first();
        
        if (!$user) {
            $decayMinutes = config('security.login_rate_limit.decay_minutes', 5);
            RateLimiter::hit($throttleKey, $decayMinutes * 60);
            throw ValidationException::withMessages([
                'user_id' => ['The provided credentials are invalid.'],
            ]);
        }

        // Check if user has multiple roles and selected_role is provided
        $selectedRole = $credentials['selected_role'] ?? null;
        if (count($user->getRoles()) > 1 && !$selectedRole) {
            throw ValidationException::withMessages([
                'selected_role' => ['Please select a role to login as.'],
            ]);
        }

        // If user hasn't set password and password is blank, allow login
        if (!$user->password_set && empty($credentials['password'])) {
            RateLimiter::clear($throttleKey);
            Auth::login($user);
            
            // Store selected role in session if provided
            if ($selectedRole) {
                $request->session()->put('selected_role', $selectedRole);
            } else {
                // For single role users, store their role
                $request->session()->put('selected_role', $user->getRoles()[0]);
            }
            
            return redirect()->intended('/settings');
        }

        // Standard authentication for users with passwords
        if (Auth::attempt(['user_id' => $credentials['user_id'], 'password' => $credentials['password']])) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            
            // Store selected role in session if provided
            if ($selectedRole) {
                $request->session()->put('selected_role', $selectedRole);
            } else {
                // For single role users, store their role
                $request->session()->put('selected_role', $user->getRoles()[0]);
            }
            
            return redirect()->intended('/dashboard');
        }

        $decayMinutes = config('security.login_rate_limit.decay_minutes', 5);
        RateLimiter::hit($throttleKey, $decayMinutes * 60);
        throw ValidationException::withMessages([
            'user_id' => ['The provided credentials are invalid.'],
        ]);
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    /**
     * Switch user role (for users with multiple roles).
     */
    public function switchRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string|in:administrator,lectureTeacher,homeroomTeacher,student'
        ]);

        $user = Auth::user();
        
        // Check if user has the requested role
        if (!$user->hasRole($request->role)) {
            return redirect()->back()->withErrors(['role' => 'You do not have permission for this role.']);
        }

        // Update session with new role
        $request->session()->put('selected_role', $request->role);

        return redirect()->back()->with('success', 'Role switched successfully.');
    }
}
