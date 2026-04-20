<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the settings page
     */
    public function index()
    {
        $user = Auth::user();
        return view('settings', compact('user'));
    }

    /**
     * Update user password
     */
    public function update(PasswordUpdateRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        
        // Update password
        $user->password = Hash::make($validated['password']);
        $user->password_set = true;
        $user->save();
        
        return redirect()->route('settings')->with('success', 'Password updated successfully.');
    }
}