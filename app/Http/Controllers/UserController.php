<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show public user profile
     */
    public function profile($userId)
    {
        $user = User::where('id_user', $userId)->firstOrFail();
        
        return view('user.profile', compact('user'));
    }
}