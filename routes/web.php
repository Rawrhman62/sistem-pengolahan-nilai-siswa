<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('landing');
});

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle.login');
Route::post('/check-user-roles', [LoginController::class, 'checkUserRoles']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/switch-role', [LoginController::class, 'switchRole'])->name('switch.role');
    
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

// Public user profile routes
Route::get('/user/{userId}', [UserController::class, 'profile'])->name('user.profile');

// Admin routes (protected by admin middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/sekolah', [AdminController::class, 'sekolah'])->name('admin.sekolah');
    Route::get('/admin/siswa', [AdminController::class, 'siswa'])->name('admin.siswa');
    Route::get('/admin/guru', [AdminController::class, 'guru'])->name('admin.guru');
    Route::get('/admin/mapel', [AdminController::class, 'mapel'])->name('admin.mapel');
    Route::get('/admin/kelas', [AdminController::class, 'kelas'])->name('admin.kelas');
    Route::get('/admin/register', [AdminController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/admin/register', [AdminController::class, 'register']);
    Route::get('/admin/manage', [AdminController::class, 'manage'])->name('admin.manage');
    Route::get('/admin/tahun-ajaran', [AdminController::class, 'tahunAjaran'])->name('admin.tahun-ajaran');
    
    // Excel Export Routes
    Route::get('/admin/users/export', [AdminController::class, 'exportUsers'])->name('admin.users.export');
    Route::get('/admin/siswa/export', [AdminController::class, 'exportSiswa'])->name('admin.siswa.export');
    Route::get('/admin/guru/export', [AdminController::class, 'exportGuru'])->name('admin.guru.export');
    Route::get('/admin/mapel/export', [AdminController::class, 'exportMapel'])->name('admin.mapel.export');
    Route::get('/admin/kelas/export', [AdminController::class, 'exportKelas'])->name('admin.kelas.export');
    
    // Excel Import Routes
    Route::post('/admin/users/import', [AdminController::class, 'importUsers'])->name('admin.users.import');
    Route::post('/admin/siswa/import', [AdminController::class, 'importSiswa'])->name('admin.siswa.import');
    Route::post('/admin/guru/import', [AdminController::class, 'importGuru'])->name('admin.guru.import');
    Route::post('/admin/mapel/import', [AdminController::class, 'importMapel'])->name('admin.mapel.import');
    Route::post('/admin/kelas/import', [AdminController::class, 'importKelas'])->name('admin.kelas.import');
    
    // Excel Template Download Routes
    Route::get('/admin/users/template', [AdminController::class, 'downloadUsersTemplate'])->name('admin.users.template');
    Route::get('/admin/siswa/template', [AdminController::class, 'downloadSiswaTemplate'])->name('admin.siswa.template');
    Route::get('/admin/guru/template', [AdminController::class, 'downloadGuruTemplate'])->name('admin.guru.template');
    Route::get('/admin/mapel/template', [AdminController::class, 'downloadMapelTemplate'])->name('admin.mapel.template');
    Route::get('/admin/kelas/template', [AdminController::class, 'downloadKelasTemplate'])->name('admin.kelas.template');
});
