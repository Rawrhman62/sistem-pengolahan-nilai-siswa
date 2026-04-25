<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard with role-based content.
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('dashboard', [
            'user' => $user,
            'role' => $user->getCurrentRole(),
            'allRoles' => $user->getRoles(),
            'isAdmin' => $user->isCurrentlyAdmin(),
            'isTeacher' => $user->isCurrentlyTeacher(),
        ]);
    }
}