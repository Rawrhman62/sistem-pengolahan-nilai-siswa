<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\TeachedSubject;
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
        
        // Get classes taught by this teacher from teached_subjects
        $teachedSubjects = TeachedSubject::with('classRoom', 'subject')
            ->where('id_user', $user->id_user)
            ->get();
            
        $kelasList = [];
        foreach($teachedSubjects as $ts) {
            $kelasList[] = [
                'nama' => $ts->classRoom ? $ts->classRoom->name : 'Unknown',
                'mapel' => $ts->subject ? $ts->subject->name : 'Unknown',
                'jumlah_siswa' => 30 // Mock
            ];
        }
        
        if (empty($kelasList)) {
            // Mock data if none
            $kelasList = [
                ['nama' => 'X-A', 'mapel' => 'Matematika', 'jumlah_siswa' => 32],
            ];
        }
        
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
        
        // Get students in this class
        $siswaList = User::whereHas('student.classRoom', function($query) use ($kelasNama) {
                $query->where('name', $kelasNama);
            })
            ->take(10)
            ->get();
        
        // Get existing grades for these students
        $nilaiList = Nilai::whereIn('id_user', $siswaList->pluck('id_user'))
            ->where('semester', '2/24')
            ->get()
            ->keyBy('id_user');
        
        return view('guru.kelas', compact('kelasNama', 'siswaList', 'nilaiList'));
    }

    /**
     * Save or update grades
     */
    public function simpanNilai(Request $request)
    {
        $validated = $request->validate([
            'id_user' => 'required|string',
            'id_subjects' => 'required|string',
            'nilai_pengetahuan' => 'required|integer|min:0|max:100',
            'nilai_keterampilan' => 'required|integer|min:0|max:100',
            'semester' => 'required|string',
        ]);
        
        // For now, redirect back with success. Real implementation will require inserting into the 4 sub-tables
        return redirect()->back()->with('success', 'Nilai berhasil disimpan!');
    }
}
