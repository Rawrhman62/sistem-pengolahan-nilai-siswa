<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the teacher dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get classes taught by this teacher (mock data for now)
        $kelasList = [
            ['nama' => 'X-A', 'mapel' => 'Matematika', 'jumlah_siswa' => 32],
            ['nama' => 'X-B', 'mapel' => 'Matematika', 'jumlah_siswa' => 30],
            ['nama' => 'XI-A', 'mapel' => 'Matematika', 'jumlah_siswa' => 28],
        ];
        
        // Statistics
        $totalKelas = count($kelasList);
        $totalSiswa = array_sum(array_column($kelasList, 'jumlah_siswa'));
        $nilaiDiinput = 45; // Mock data
        $nilaiBelumDiinput = $totalSiswa - $nilaiDiinput;
        
        return view('guru.index', compact('kelasList', 'totalKelas', 'totalSiswa', 'nilaiDiinput', 'nilaiBelumDiinput'));
    }

    /**
     * Display class details for input grades
     */
    public function kelas($kelasNama)
    {
        $user = Auth::user();
        
        // Get students in this class from siswas table
        $siswaList = User::where('role', 'student')
            ->whereHas('siswa', function($query) use ($kelasNama) {
                $query->where('kelas', $kelasNama);
            })
            ->take(10)
            ->get();
        
        // Get existing grades for these students
        $nilaiList = Nilai::whereIn('user_id', $siswaList->pluck('user_id'))
            ->where('semester', 'Ganjil (1)')
            ->get()
            ->keyBy('user_id');
        
        return view('guru.kelas', compact('kelasNama', 'siswaList', 'nilaiList'));
    }

    /**
     * Save or update grades
     */
    public function simpanNilai(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string',
            'mapel' => 'required|string',
            'nilai_pengetahuan' => 'required|integer|min:0|max:100',
            'nilai_keterampilan' => 'required|integer|min:0|max:100',
            'semester' => 'required|string',
        ]);
        
        // Calculate final grade and predicate
        $nilaiAkhir = ($validated['nilai_pengetahuan'] + $validated['nilai_keterampilan']) / 2;
        
        $predikat = 'D';
        if ($nilaiAkhir >= 90) $predikat = 'A';
        elseif ($nilaiAkhir >= 80) $predikat = 'B';
        elseif ($nilaiAkhir >= 70) $predikat = 'C';
        
        Nilai::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'mapel' => $validated['mapel'],
                'semester' => $validated['semester'],
            ],
            [
                'nilai_pengetahuan' => $validated['nilai_pengetahuan'],
                'nilai_keterampilan' => $validated['nilai_keterampilan'],
                'nilai_akhir' => $nilaiAkhir,
                'predikat' => $predikat,
            ]
        );
        
        return redirect()->back()->with('success', 'Nilai berhasil disimpan!');
    }
}
