<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\User;
use App\Models\Student;
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
        
        // Get student's student record
        $student = $user->student;
        
        if (!$student) {
            abort(404, 'Data siswa tidak ditemukan');
        }
        
        // Get current semester (you can make this dynamic)
        $semester = '2/24';
        
        // Get student's grades
        $nilai = Nilai::where('id_user', $user->id_user)
            ->where('semester', $semester)
            ->get();
        
        // Calculate average
        // In the new schema, average is calculated across joined tables, but for now mock it:
        $rataRata = 85; 
        
        // Get class rank (mock data for now - you can implement actual ranking)
        $peringkatKelas = 5;
        $totalSiswa = 32;
        
        // Get student info
        $siswaInfo = [
            'nama' => $user->name,
            'nis' => $student->nis,
            'kelas' => $student->classRoom->name ?? 'X-A',
            'semester' => $semester,
            'tahun_ajaran' => $student->entry_year ?? '2024',
            'wali_kelas' => 'Pak Budi', // Mock data - you can get from class
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
