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
     * Show the settings page for profile editing
     */
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    /**
     * Update user profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'email' => 'nullable|email|max:255|unique:tb_users,email,' . $user->id_user . ',id_user',
            'phone_number' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'remove_profile_picture' => 'nullable|boolean',
        ]);

        // Handle profile picture upload
        $profilePicturePath = $user->profile_picture;
        
        if ($request->has('remove_profile_picture') && $request->remove_profile_picture) {
            // Delete old profile picture if it exists
            if ($user->profile_picture && $user->profile_picture !== '/public/images/') {
                $relativePath = str_replace('/public/', '', $user->profile_picture);
                $oldPath = public_path($relativePath);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $profilePicturePath = '/public/images/';
        } elseif ($request->hasFile('profile_picture')) {
            // Delete old profile picture with different extensions if exists
            $extensions = ['jpg', 'jpeg', 'png', 'gif'];
            foreach ($extensions as $ext) {
                $oldPath = public_path('images/profiles/' . $user->id_user . '.' . $ext);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            
            // Store new profile picture with format: user_id.extension
            $file = $request->file('profile_picture');
            $extension = $file->getClientOriginalExtension();
            $filename = $user->id_user . '.' . $extension;
            $file->move(public_path('images/profiles'), $filename);
            $profilePicturePath = '/public/images/profiles/' . $filename;
        }

        // Update user information
        $user->update([
            'email' => $validated['email'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'profile_picture' => $profilePicturePath,
        ]);

        return redirect()->route('settings')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Show the password change page
     */
    public function showPasswordForm()
    {
        $user = Auth::user();
        return view('settings.password', compact('user'));
    }

    /**
     * Update user password
     */
    public function updatePassword(PasswordUpdateRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        
        // Update password
        $user->password = Hash::make($validated['password']);
        $user->save();
        
        return redirect()->route('settings.password')->with('success', 'Password berhasil diperbarui.');
    }
}
