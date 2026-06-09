<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Homeroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WaliKelasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the homeroom teacher dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get homeroom class
        $homeroom = Homeroom::with('classRoom')->where('id_user', $user->id_user)->first();
        $kelasWali = $homeroom && $homeroom->classRoom ? $homeroom->classRoom->name : 'X-A';
        
        // Get students in homeroom class
        $siswaList = User::whereHas('student.classRoom', function($query) use ($kelasWali) {
                $query->where('name', $kelasWali);
            })
            ->take(32)
            ->get();
        
        // Get all grades for students in this class
        $nilaiData = Nilai::whereIn('id_user', $siswaList->pluck('id_user'))
            ->where('semester', '2/24')
            ->get()
            ->groupBy('id_user');
        
        // Calculate statistics
        $siswaWithGrades = $siswaList->map(function($siswa) use ($nilaiData) {
            $grades = $nilaiData->get($siswa->id_user, collect());
            // Mock average for new schema
            $average = 85; 
            
            return [
                'siswa' => $siswa,
                'rata_rata' => round($average, 1),
                'jumlah_mapel' => $grades->count(),
            ];
        })->sortByDesc('rata_rata')->values();
        
        // Class statistics
        $rataRataKelas = count($siswaWithGrades) > 0 ? $siswaWithGrades->avg('rata_rata') : 0;
        $totalSiswa = $siswaList->count();
        $siswaLengkap = $siswaWithGrades->where('jumlah_mapel', '>=', 8)->count();
        $siswaBelumLengkap = $totalSiswa - $siswaLengkap;
        
        return view('wali_kelas.index', compact(
            'kelasWali',
            'siswaWithGrades',
            'rataRataKelas',
            'totalSiswa',
            'siswaLengkap',
            'siswaBelumLengkap'
        ));
    }

    /**
     * Display detailed grades for a specific student
     */
    public function detailSiswa($userId)
    {
        $siswa = User::where('id_user', $userId)->firstOrFail();
        
        $nilai = Nilai::where('id_user', $userId)
            ->where('semester', '2/24')
            ->get();
        
        $rataRata = 85; // Mock for new schema
        
        return view('wali_kelas.detail', compact('siswa', 'nilai', 'rataRata'));
    }
}
