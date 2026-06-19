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

        $search = $request->input('search');
        $filterStatus = $request->input('filter_status', 'all');
        $sort = $request->input('sort', 'name'); 
        $direction = $request->input('direction', 'asc');

        $query = User::whereHas('student', function($q) use ($id_class) {
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

        $nilaiList = Nilai::whereIn('id_user', $siswaList->pluck('id_user'))
            ->where('semester', '2/24')
            ->get()
            ->keyBy('id_user');

        if ($filterStatus === 'sudah') {
            $filteredItems = $siswaList->getCollection()->filter(function($siswa) use ($nilaiList) {
                $nilai = $nilaiList->get($siswa->id_user);
                return $nilai && (!is_null($nilai->nilai_pengetahuan) || !is_null($nilai->nilai_keterampilan));
            });
            $siswaList->setCollection($filteredItems);
        } elseif ($filterStatus === 'belum') {
            $filteredItems = $siswaList->getCollection()->filter(function($siswa) use ($nilaiList) {
                $nilai = $nilaiList->get($siswa->id_user);
                return !$nilai || (is_null($nilai->nilai_pengetahuan) && is_null($nilai->nilai_keterampilan));
            });
            $siswaList->setCollection($filteredItems);
        }

        return view('guru.kelas', compact('kelasNama', 'id_class', 'siswaList', 'nilaiList'));
    }

    /**
     * Save or update batch grades safely
     */
    public function simpanNilai(Request $request)
    {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*.pengetahuan' => 'nullable|integer|min:0|max:100',
            'nilai.*.keterampilan' => 'nullable|integer|min:0|max:100',
            'id_class' => 'required|string'
        ]);

        $idSubjects = 'MAPEL001'; 
        $semester = '2/24';

        foreach ($request->input('nilai') as $idUser => $scores) {
            $nilai = Nilai::where('id_user', $idUser)
                ->where('semester', $semester)
                ->first();

            if (!$nilai) {
                $nilai = new Nilai();
                $nilai->id_nilai = 'NILAI_' . uniqid();
                $nilai->id_user = $idUser;
                $nilai->id_subjects = $idSubjects;
                $nilai->semester = $semester;
            }

            $nilai->nilai_pengetahuan = $scores['pengetahuan'];
            $nilai->nilai_keterampilan = $scores['keterampilan'];
            $nilai->save();
        }

        return redirect()->back()->with('success', 'Semua data nilai berhasil disimpan ke database!');
    }
}