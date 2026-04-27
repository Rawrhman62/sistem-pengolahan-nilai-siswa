<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the student dashboard with grades
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get student's siswa record to get kelas and tahun_ajaran
        $siswa = $user->siswa;
        
        if (!$siswa) {
            abort(404, 'Data siswa tidak ditemukan');
        }
        
        // Get current semester (you can make this dynamic)
        $semester = 'Ganjil (1)';
        
        // Get student's grades
        $nilai = Nilai::where('user_id', $user->user_id)
            ->where('semester', $semester)
            ->get();
        
        // Calculate average
        $rataRata = $nilai->avg('nilai_akhir');
        
        // Get class rank (mock data for now - you can implement actual ranking)
        $peringkatKelas = 5;
        $totalSiswa = 32;
        
        // Get student info
        $siswaInfo = [
            'nama' => $user->name,
            'nis' => $user->user_id,
            'kelas' => $siswa->kelas ?? 'X-A',
            'semester' => $semester,
            'tahun_ajaran' => $siswa->tahun_ajaran ?? '2025/2026',
            'wali_kelas' => 'Pak Budi', // Mock data - you can get from kelas table
        ];
        
        return view('siswa.index', compact('nilai', 'rataRata', 'peringkatKelas', 'totalSiswa', 'siswaInfo'));
    }

    /**
     * Display student profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('siswa.profile', compact('user'));
    }
}
