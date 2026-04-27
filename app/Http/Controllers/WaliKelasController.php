<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\User;
use App\Models\Kelas;
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
        
        // Get homeroom class (mock data - you can get from kelas table)
        $kelasWali = 'X-A';
        
        // Get students in homeroom class from siswas table
        $siswaList = User::where('role', 'student')
            ->whereHas('siswa', function($query) use ($kelasWali) {
                $query->where('kelas', $kelasWali);
            })
            ->take(32)
            ->get();
        
        // Get all grades for students in this class
        $nilaiData = Nilai::whereIn('user_id', $siswaList->pluck('user_id'))
            ->where('semester', 'Ganjil (1)')
            ->get()
            ->groupBy('user_id');
        
        // Calculate statistics
        $siswaWithGrades = $siswaList->map(function($siswa) use ($nilaiData) {
            $grades = $nilaiData->get($siswa->user_id, collect());
            $average = $grades->avg('nilai_akhir') ?? 0;
            
            return [
                'siswa' => $siswa,
                'rata_rata' => round($average, 1),
                'jumlah_mapel' => $grades->count(),
            ];
        })->sortByDesc('rata_rata')->values();
        
        // Class statistics
        $rataRataKelas = $siswaWithGrades->avg('rata_rata');
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
        $siswa = User::where('user_id', $userId)->firstOrFail();
        
        $nilai = Nilai::where('user_id', $userId)
            ->where('semester', 'Ganjil (1)')
            ->get();
        
        $rataRata = $nilai->avg('nilai_akhir');
        
        return view('wali_kelas.detail', compact('siswa', 'nilai', 'rataRata'));
    }
}
