<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\NilaiHarian;
use App\Models\NilaiKeterampilan;
use App\Models\NilaiUlangan;
use App\Models\NilaiUjian;
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
     * Display the teacher dashboard with search, sort, and pagination (max 5 items)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ambil parameter input filter dari request
        $search = $request->input('search');
        $sort = $request->input('sort', 'nama');
        $direction = $request->input('direction', 'asc');

        // Base Query: Mengambil data pengajaran guru saat ini
        $query = TeachedSubject::with(['classRoom', 'subject'])
            ->where('id_user', $user->id_user);
            
        // 1. Fitur Pencarian di Dashboard (Bisa mencari berdasarkan Nama Kelas atau Nama Mapel)
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->whereHas('classRoom', function($cq) use ($search) {
                    $cq->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('subject', function($sq) use ($search) {
                    $sq->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        $teachedSubjects = $query->get();
        $allKelasData = [];
        $arrayIdKelas = [];

        foreach($teachedSubjects as $ts) {
            if ($ts->classRoom) {
                $arrayIdKelas[] = $ts->teached_class;
                
                // Hitung jumlah siswa secara dinamis di kelas ini berdasarkan tb_students
                $jumlahSiswa = User::whereHas('student', function($q) use ($ts) {
                    $q->where('id_class', $ts->teached_class);
                })->count();

                $allKelasData[] = [
                    'id_class' => $ts->teached_class,
                    'nama' => $ts->classRoom->name,
                    'mapel' => $ts->subject ? $ts->subject->name : 'Unknown',
                    'jumlah_siswa' => $jumlahSiswa > 0 ? $jumlahSiswa : 30
                ];
            }
        }
        
        // Jika data relasi di database kosong, gunakan Mock Data agar dashboard tidak blank
        if (empty($allKelasData)) {
            $allKelasData = [
                ['id_class' => 'CLS001', 'nama' => 'X-A', 'mapel' => 'Matematika', 'jumlah_siswa' => 32],
                ['id_class' => 'CLS002', 'nama' => 'X-B', 'mapel' => 'Matematika', 'jumlah_siswa' => 30],
                ['id_class' => 'CLS003', 'nama' => 'XI-A', 'mapel' => 'Matematika', 'jumlah_siswa' => 28],
            ];
            $arrayIdKelas = ['CLS001', 'CLS002', 'CLS003'];
        }

        // 2. Fitur Pengurutan Array Data
        usort($allKelasData, function($a, $b) use ($sort, $direction) {
            $valA = $a[$sort] ?? '';
            $valB = $b[$sort] ?? '';
            if ($direction === 'asc') {
                return strnatcasecmp($valA, $valB);
            } else {
                return strnatcasecmp($valB, $valA);
            }
        });

        // 3. Manual Pagination untuk Array (Maksimal 5 Data per Halaman)
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 5;
        $currentItems = array_slice($allKelasData, ($currentPage - 1) * $perPage, $perPage);
        
        $kelasList = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems, 
            count($allKelasData), 
            $perPage, 
            $currentPage, 
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Statistik Ringkas Dashboard
        $totalKelas = count($allKelasData);
        $totalSiswa = array_sum(array_column($allKelasData, 'jumlah_siswa'));
        
        // Hitung total siswa yang sudah diberi nilai di kelas-kelas pengajaran guru tersebut
        $nilaiDiinput = Nilai::whereIn('id_user', function($query) use ($arrayIdKelas) {
                $query->select('id_user')
                      ->from('tb_students')
                      ->whereIn('id_class', $arrayIdKelas);
            })
            ->where('semester', '2/24')
            ->distinct('id_user')
            ->count();

        $nilaiBelumDiinput = max(0, $totalSiswa - $nilaiDiinput);
        
        return view('guru.index', compact('kelasList', 'totalKelas', 'totalSiswa', 'nilaiDiinput', 'nilaiBelumDiinput'));
    }

    /**
     * Display class details for input grades
     */
    public function kelas(Request $request, $id_class)
    {
        $user = Auth::user();
        $kelas = ClassRoom::where('id_class', $id_class)->firstOrFail();
        $kelasNama = $kelas->name;

        // Get current semester automatically
        $defaultSemester = \App\Models\TahunAjaranDate::getCurrentSemesterFormatted();
        $semester = $request->input('semester', $defaultSemester);
        $gradeType = $request->input('grade_type', 'harian'); // harian, keterampilan, ulangan, ujian
        
        $search = $request->input('search');
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');

        // Get students in this class
        $query = User::with('student')->whereHas('student', function($q) use ($id_class) {
            $q->where('id_class', $id_class);
        });

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('tb_users.name', 'like', '%' . $search . '%')
                  ->orWhereHas('student', function($sq) use ($search) {
                      $sq->where('nis', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($sort === 'nis') {
            $query->join('tb_students', 'tb_users.id_user', '=', 'tb_students.id_user')
                  ->orderBy('tb_students.nis', $direction)
                  ->select('tb_users.*');
        } else {
            $query->orderBy('tb_users.name', $direction);
        }

        $siswaList = $query->paginate(40);

        // Get nilai records with all relationships
        $nilaiList = Nilai::with(['nilaiHarian', 'nilaiKeterampilan', 'nilaiUlangan', 'nilaiUjian'])
            ->whereIn('id_user', $siswaList->pluck('id_user'))
            ->where('semester', $semester)
            ->get()
            ->keyBy('id_user');

        // Get disabled columns for nilai harian (stored in session or default)
        $disabledColumns = session('disabled_columns_' . $id_class, []);

        return view('guru.kelas', compact(
            'kelasNama',
            'id_class',
            'siswaList',
            'nilaiList',
            'semester',
            'gradeType',
            'disabledColumns'
        ));
    }

    /**
     * Save or update batch grades safely
     */
    public function simpanNilai(Request $request)
    {
        $request->validate([
            'nilai' => 'required|array',
            'id_class' => 'required|string',
            'semester' => 'required|string',
            'grade_type' => 'required|string|in:harian,keterampilan,ulangan,ujian'
        ]);

        $idSubjects = 'MAPEL001';
        $semester = $request->input('semester');
        $gradeType = $request->input('grade_type');

        foreach ($request->input('nilai') as $idUser => $scores) {
            // Get or create main nilai record
            $nilai = Nilai::where('id_user', $idUser)
                ->where('semester', $semester)
                ->where('id_subjects', $idSubjects)
                ->first();

            if (!$nilai) {
                $nilai = new Nilai();
                $nilai->id_nilai = 'NILAI_' . uniqid();
                $nilai->id_user = $idUser;
                $nilai->id_subjects = $idSubjects;
                $nilai->semester = $semester;
            }

            // Handle different grade types
            switch ($gradeType) {
                case 'harian':
                    // Find existing by primary key, or create new
                    $nilaiHarian = $nilai->id_nilai_harian 
                        ? NilaiHarian::find($nilai->id_nilai_harian)
                        : null;
                    
                    if (!$nilaiHarian) {
                        $nilaiHarian = new NilaiHarian();
                        // Don't manually set id_nilai_harian - let auto-increment handle it
                    }
                    
                    for ($i = 1; $i <= 12; $i++) {
                        $minggu = 'minggu_' . $i;
                        if (isset($scores[$minggu])) {
                            $nilaiHarian->$minggu = $scores[$minggu] === '' ? null : $scores[$minggu];
                        }
                    }
                    $nilaiHarian->save();
                    $nilai->id_nilai_harian = $nilaiHarian->id_nilai_harian;
                    break;

                case 'keterampilan':
                    $nilaiKeterampilan = $nilai->id_nilai_keterampilan
                        ? NilaiKeterampilan::find($nilai->id_nilai_keterampilan)
                        : null;
                    
                    if (!$nilaiKeterampilan) {
                        $nilaiKeterampilan = new NilaiKeterampilan();
                        $nilaiKeterampilan->id_nilai_keterampilan = 'NK_' . uniqid();
                    }
                    $nilaiKeterampilan->nilai = $scores['nilai'] ?? null;
                    $nilaiKeterampilan->save();
                    $nilai->id_nilai_keterampilan = $nilaiKeterampilan->id_nilai_keterampilan;
                    break;

                case 'ulangan':
                    $nilaiUlangan = $nilai->id_nilai_ulangan
                        ? NilaiUlangan::find($nilai->id_nilai_ulangan)
                        : null;
                    
                    if (!$nilaiUlangan) {
                        $nilaiUlangan = new NilaiUlangan();
                        $nilaiUlangan->id_nilai_ulangan = 'NU_' . uniqid();
                    }
                    
                    for ($i = 1; $i <= 8; $i++) {
                        $ulangan = 'ulangan_' . $i;
                        if (isset($scores[$ulangan])) {
                            $nilaiUlangan->$ulangan = $scores[$ulangan] === '' ? null : $scores[$ulangan];
                        }
                    }
                    $nilaiUlangan->save();
                    $nilai->id_nilai_ulangan = $nilaiUlangan->id_nilai_ulangan;
                    break;

                case 'ujian':
                    $nilaiUjian = $nilai->id_nilai_ujian
                        ? NilaiUjian::find($nilai->id_nilai_ujian)
                        : null;
                    
                    if (!$nilaiUjian) {
                        $nilaiUjian = new NilaiUjian();
                        $nilaiUjian->id_nilai_ujian = 'NUJ_' . uniqid();
                    }
                    $nilaiUjian->awal_ganjil = $scores['awal_ganjil'] ?? null;
                    $nilaiUjian->akhir_ganjil = $scores['akhir_ganjil'] ?? null;
                    $nilaiUjian->awal_genap = $scores['awal_genap'] ?? null;
                    $nilaiUjian->akhir_genap = $scores['akhir_genap'] ?? null;
                    $nilaiUjian->year = date('Y');
                    $nilaiUjian->save();
                    $nilai->id_nilai_ujian = $nilaiUjian->id_nilai_ujian;
                    break;
            }

            $nilai->save();
        }

        return redirect()->back()->with('success', 'Semua data nilai berhasil disimpan ke database!');
    }

    /**
     * Toggle column visibility for nilai harian
     */
    public function toggleColumn(Request $request)
    {
        $request->validate([
            'id_class' => 'required|string',
            'column' => 'required|string',
            'enabled' => 'required|boolean'
        ]);

        $idClass = $request->input('id_class');
        $column = $request->input('column');
        $enabled = $request->input('enabled');

        $disabledColumns = session('disabled_columns_' . $idClass, []);

        if (!$enabled && !in_array($column, $disabledColumns)) {
            $disabledColumns[] = $column;
        } elseif ($enabled) {
            $disabledColumns = array_diff($disabledColumns, [$column]);
        }

        session(['disabled_columns_' . $idClass => $disabledColumns]);

        return response()->json(['success' => true]);
    }
}