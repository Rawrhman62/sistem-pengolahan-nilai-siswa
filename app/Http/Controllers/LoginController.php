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
     * Handle login request with role-based authentication.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login_id' => 'required|string',
            'password' => 'nullable|string',
            'selected_role' => 'required|string|in:administrator,lectureTeacher,homeroomTeacher,student'
        ]);
        
        $throttleKey = 'login_attempts:' . $request->ip() . ':' . strtolower($credentials['login_id']);
        $maxAttempts = config('security.login_rate_limit.max_attempts', 5);

        // Check rate limiting
        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            throw ValidationException::withMessages([
                'login_id' => ['Too many login attempts. Please try again in ' . $seconds . ' seconds.'],
            ]);
        }

        // Role-based credential lookup
        $user = null;
        $selectedRole = $credentials['selected_role'];
        
        switch ($selectedRole) {
            case 'administrator':
            case 'lectureTeacher':
            case 'homeroomTeacher':
                // Lookup in tb_teachers using nomor_induk
                $teacher = \App\Models\Teacher::findByNomorInduk($credentials['login_id']);
                if ($teacher) {
                    $user = $teacher->user;
                }
                break;
                
            case 'student':
                // Lookup in tb_students using nis
                $student = \App\Models\Student::findByNis($credentials['login_id']);
                if ($student) {
                    $user = $student->user;
                }
                break;
        }
        
        if (!$user) {
            $decayMinutes = config('security.login_rate_limit.decay_minutes', 5);
            RateLimiter::hit($throttleKey, $decayMinutes * 60);
            throw ValidationException::withMessages([
                'login_id' => ['The provided credentials are invalid.'],
            ]);
        }

        // Verify role membership
        if (!$user->hasRole($selectedRole)) {
            $decayMinutes = config('security.login_rate_limit.decay_minutes', 5);
            RateLimiter::hit($throttleKey, $decayMinutes * 60);
            throw ValidationException::withMessages([
                'selected_role' => ['You do not have permission for this role.'],
            ]);
        }

        // If user hasn't set password and password is blank, allow login
        // Check the raw database value to avoid issues with model casting
        $userPasswordFromDb = \DB::table('tb_users')->where('id_user', $user->id_user)->value('password');
        
        if (is_null($userPasswordFromDb) && empty($credentials['password'])) {
            RateLimiter::clear($throttleKey);
            Auth::login($user);
            
            // Store selected role and id_user in session
            $request->session()->put('selected_role', $selectedRole);
            $request->session()->put('id_user', $user->id_user);
            
            return redirect()->intended('/settings/password');
        }

        // Standard authentication for users with passwords
        if (Auth::attempt(['id_user' => $user->id_user, 'password' => $credentials['password']])) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            
            // Store selected role and id_user in session
            $request->session()->put('selected_role', $selectedRole);
            $request->session()->put('id_user', $user->id_user);
            
            return redirect()->intended('/dashboard');
        }

        $decayMinutes = config('security.login_rate_limit.decay_minutes', 5);
        RateLimiter::hit($throttleKey, $decayMinutes * 60);
        throw ValidationException::withMessages([
            'login_id' => ['The provided credentials are invalid.'],
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
