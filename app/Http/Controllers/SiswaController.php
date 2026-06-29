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
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get student's student record
        $student = $user->student;
        
        if (!$student) {
            abort(404, 'Data siswa tidak ditemukan');
        }
        
        // Get current semester from request or use current academic semester
        $defaultSemester = \App\Models\TahunAjaranDate::getCurrentSemesterFormatted();
        $semester = $request->input('semester', $defaultSemester);
        
        // Get student's grades with all relationships
        $nilaiCollection = Nilai::with(['subject', 'nilaiHarian', 'nilaiKeterampilan', 'nilaiUlangan', 'nilaiUjian'])
            ->where('id_user', $user->id_user)
            ->where('semester', $semester)
            ->get();
        
        // Transform data for display
        $nilai = $nilaiCollection->map(function($n) {
            $nilaiAkhir = 0;
            $count = 0;
            
            // Calculate from nilai harian (average of all minggu)
            if ($n->nilaiHarian) {
                $harianTotal = 0;
                $harianCount = 0;
                for ($i = 1; $i <= 12; $i++) {
                    $minggu = 'minggu_' . $i;
                    if ($n->nilaiHarian->$minggu) {
                        $harianTotal += $n->nilaiHarian->$minggu;
                        $harianCount++;
                    }
                }
                if ($harianCount > 0) {
                    $nilaiAkhir += $harianTotal / $harianCount;
                    $count++;
                }
            }
            
            // Add nilai ulangan (average of all ulangan)
            if ($n->nilaiUlangan) {
                $ulanganTotal = 0;
                $ulanganCount = 0;
                for ($i = 1; $i <= 8; $i++) {
                    $ulangan = 'ulangan_' . $i;
                    if ($n->nilaiUlangan->$ulangan) {
                        $ulanganTotal += $n->nilaiUlangan->$ulangan;
                        $ulanganCount++;
                    }
                }
                if ($ulanganCount > 0) {
                    $nilaiAkhir += $ulanganTotal / $ulanganCount;
                    $count++;
                }
            }
            
            // Add nilai ujian (average of all ujian)
            if ($n->nilaiUjian) {
                $ujianValues = array_filter([
                    $n->nilaiUjian->awal_ganjil,
                    $n->nilaiUjian->akhir_ganjil,
                    $n->nilaiUjian->awal_genap,
                    $n->nilaiUjian->akhir_genap,
                ]);
                if (count($ujianValues) > 0) {
                    $nilaiAkhir += array_sum($ujianValues) / count($ujianValues);
                    $count++;
                }
            }
            
            // Calculate final average
            $nilaiPengetahuan = $count > 0 ? round($nilaiAkhir / $count, 1) : 0;
            $nilaiKeterampilan = $n->nilaiKeterampilan?->nilai ?? 0;
            $nilaiAkhirFinal = ($nilaiPengetahuan + $nilaiKeterampilan) / 2;
            
            // Determine predikat
            $predikat = 'D';
            if ($nilaiAkhirFinal >= 90) $predikat = 'A';
            elseif ($nilaiAkhirFinal >= 80) $predikat = 'B';
            elseif ($nilaiAkhirFinal >= 70) $predikat = 'C';
            
            return (object)[
                'mapel' => $n->subject->name ?? 'Unknown',
                'nilai_pengetahuan' => $nilaiPengetahuan,
                'nilai_keterampilan' => $nilaiKeterampilan,
                'nilai_akhir' => round($nilaiAkhirFinal, 1),
                'predikat' => $predikat,
            ];
        });
        
        // Calculate average of all nilai akhir
        $rataRata = $nilai->count() > 0 
            ? round($nilai->avg('nilai_akhir'), 1) 
            : 0;
        
        // Get wali kelas from homeroom relationship
        $waliKelas = '-';
        if ($student->classRoom && $student->classRoom->homeroom) {
            $waliKelas = $student->classRoom->homeroom->user->name ?? '-';
        }
        
        // Get tahun ajaran
        $currentTA = \App\Models\TahunAjaranDate::getCurrentTahunAjaran();
        $tahunAjaran = $currentTA ? $currentTA->tahun_ajaran : date('Y') . '/' . (date('Y') + 1);
        
        // Get student info
        $siswaInfo = [
            'nama' => $user->name,
            'nis' => $student->nis,
            'kelas' => $student->classRoom->name ?? '-',
            'semester' => $semester,
            'tahun_ajaran' => $tahunAjaran,
            'wali_kelas' => $waliKelas,
        ];
        
        return view('siswa.index', compact('nilai', 'rataRata', 'siswaInfo', 'semester'));
    }

    /**
     * Display student profile
     */
    public function profile()
    {
        $user = Auth::user()->load('student');
        return view('siswa.profile', compact('user'));
    }
}
