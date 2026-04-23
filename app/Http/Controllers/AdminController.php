<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\UserSearchRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * Display the kelola data sekolah view.
     */
    public function sekolah()
    {
        return view('admin.sekolah');
    }

    /**
     * Display the kelola data siswa view.
     */
    public function siswa()
    {
        return view('admin.siswa');
    }

    /**
     * Display the kelola data guru view.
     */
    public function guru()
    {
        return view('admin.guru');
    }

    /**
     * Display the kelola data mapel view.
     */
    public function mapel()
    {
        return view('admin.mapel');
    }

    /**
     * Display the kelola data kelas view.
     */
    public function kelas()
    {
        return view('admin.kelas');
    }

    /**
     * Show the user registration form.
     */
    public function showRegisterForm()
    {
        return view('admin.register');
    }

    /**
     * Register a new user.
     */
    public function register(UserRegistrationRequest $request)
    {
        $validated = $request->validated();

        // Handle dual teacher role
        $role = $validated['role'];
        if ($request->has('dual_teacher') && $request->dual_teacher) {
            $role = 'lectureTeacher,homeroomTeacher';
        }

        User::create([
            'name' => $validated['name'],
            'user_name' => $validated['user_name'],
            'user_id' => $validated['user_id'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'role' => $role,
            'password_set' => false,
            'password' => null,
        ]);

        return redirect()->route('admin.register')
            ->with('success', 'User registered successfully. They can login with their userId and will be prompted to set a password.');
    }

    /**
     * Display user management interface.
     */
    public function manage(UserSearchRequest $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->validated()['search'];
            $query->where(function ($q) use ($search) {
                $q->where('user_id', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.manage', compact('users'));
    }

    /**
     * Display kelola tahun ajaran view.
     */
    public function tahunAjaran()
    {
        return view('admin.tahun-ajaran');
    }
}