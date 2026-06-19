<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\UserSearchRequest;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Services\ExcelExportService;
use App\Services\ExcelImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminController extends Controller
{
    public function __construct(
        private ExcelExportService $excelExportService,
        private ExcelImportService $excelImportService
    ) {}

    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        return redirect()->route('admin.sekolah');
    }

    /**
     * Display the kelola data sekolah view.
     */
    public function sekolah()
    {
        return view('admin.sekolah');
    }

    /**
     * Display the kelola data siswa view.
     */
    public function siswa(Request $request)
    {
        $query = Student::with('user'); // Eager load user relationship

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by id_class
        if ($request->filled('id_class')) {
            $query->where('id_class', $request->input('id_class'));
        }

        // Filter by entry_year
        if ($request->filled('entry_year')) {
            $query->where('entry_year', $request->input('entry_year'));
        }

        // Sorting functionality
        $sortColumn = $request->input('sort');
        $sortDirection = $request->input('direction', 'asc');
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // Apply sorting (handle user.name separately)
        if ($sortColumn === 'nama') {
            $query->join('tb_users', 'tb_students.id_user', '=', 'tb_users.id_user')
                  ->orderBy('tb_users.name', $sortDirection)
                  ->select('tb_students.*');
        } else {
            $query->orderBy($sortColumn ?? 'id_user', $sortDirection);
        }

        $siswa = $query->paginate(20)->appends([
            'search' => $request->input('search'),
            'id_class' => $request->input('id_class'),
            'entry_year' => $request->input('entry_year'),
            'sort' => $sortColumn,
            'direction' => $sortDirection,
        ]);

        // Get filter options
        $kelasList = \App\Models\ClassRoom::orderBy('name')->pluck('name', 'id_class');
        $entryYearList = Student::select('entry_year')->distinct()->whereNotNull('entry_year')->orderBy('entry_year', 'desc')->pluck('entry_year');

        return view('admin.siswa', compact('siswa', 'kelasList', 'entryYearList'));
    }

    /**
     * Display the kelola data guru view.
     */
    public function guru(Request $request)
    {
        $query = Teacher::with('user'); // Eager load user relationship

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nomor_induk', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting functionality
        $sortColumn = $request->input('sort');
        $sortDirection = $request->input('direction', 'asc');
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // Apply sorting (handle user.name separately)
        if ($sortColumn === 'nama') {
            $query->join('tb_users', 'tb_teachers.id_user', '=', 'tb_users.id_user')
                  ->orderBy('tb_users.name', $sortDirection)
                  ->select('tb_teachers.*');
        } else {
            $query->orderBy($sortColumn ?? 'id_user', $sortDirection);
        }

        $guru = $query->paginate(20)->appends([
            'search' => $request->input('search'),
            'sort' => $sortColumn,
            'direction' => $sortDirection,
        ]);

        return view('admin.guru', compact('guru'));
    }

    /**
     * Display the kelola data mapel view.
     */
    public function mapel(Request $request)
    {
        $query = Subject::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('group', 'like', "%{$search}%");
            });
        }

        // Filter by group
        if ($request->filled('group')) {
            $query->where('group', $request->input('group'));
        }

        // Sorting functionality
        $sortColumn = $request->input('sort');
        $sortDirection = $request->input('direction', 'asc');
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // Apply sorting
        $query->orderBy($sortColumn ?? 'id_subjects', $sortDirection);

        $mapel = $query->paginate(20)->appends([
            'search' => $request->input('search'),
            'group' => $request->input('group'),
            'sort' => $sortColumn,
            'direction' => $sortDirection,
        ]);

        // Get filter options
        $groupList = Subject::select('group')->distinct()->whereNotNull('group')->orderBy('group')->pluck('group');

        return view('admin.mapel', compact('mapel', 'groupList'));
    }

    /**
     * Display the kelola data kelas view.
     */
    public function kelas(Request $request)
    {
        $query = ClassRoom::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('grade', 'like', "%{$search}%");
            });
        }

        // Filter by grade
        if ($request->filled('grade')) {
            $query->where('grade', $request->input('grade'));
        }

        // Sorting functionality
        $sortColumn = $request->input('sort');
        $sortDirection = $request->input('direction', 'asc');
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // Apply sorting
        $query->orderBy($sortColumn ?? 'id_class', $sortDirection);

        $kelas = $query->paginate(20)->appends([
            'search' => $request->input('search'),
            'grade' => $request->input('grade'),
            'sort' => $sortColumn,
            'direction' => $sortDirection,
        ]);

        // Get filter options
        $gradeList = ClassRoom::select('grade')->distinct()->whereNotNull('grade')->orderBy('grade')->pluck('grade');

        return view('admin.kelas', compact('kelas', 'gradeList'));
    }

    /**
     * Show the user registration form.
     */
    public function showRegisterForm()
    {
        return view('admin.register');
    }

    /**
     * Register a new user.
     */
    public function register(UserRegistrationRequest $request)
    {
        $validated = $request->validated();

        // Handle dual teacher role
        $role = $validated['role'];
        if ($request->has('dual_teacher') && $request->dual_teacher) {
            $role = 'lectureTeacher,homeroomTeacher';
        }

        $user = User::create([
            'name' => $validated['name'],
            'id_user' => $validated['user_id'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'password' => null,
        ]);

        // Create role records
        if (str_contains($role, 'administrator')) {
            \App\Models\Admin::create(['id_user' => $user->id_user]);
        }
        if (str_contains($role, 'lectureTeacher')) {
            // Create teacher record if not exists
            if (!$user->teacher()->exists()) {
                \App\Models\Teacher::create([
                    'id_user' => $user->id_user,
                    'nomor_induk' => $validated['nomor_induk'] ?? $user->id_user,
                    'date_of_employment' => now()->year,
                    'teacher_status' => 'employed',
                    'type' => 'honorer'
                ]);
            }
            \App\Models\Lecturer::create(['id_user' => $user->id_user]);
        }
        if (str_contains($role, 'homeroomTeacher')) {
            // Create teacher record if not exists
            if (!$user->teacher()->exists()) {
                \App\Models\Teacher::create([
                    'id_user' => $user->id_user,
                    'nomor_induk' => $validated['nomor_induk'] ?? $user->id_user,
                    'date_of_employment' => now()->year,
                    'teacher_status' => 'employed',
                    'type' => 'honorer'
                ]);
            }
            \App\Models\Homeroom::create([
                'id_user' => $user->id_user,
                'id_class' => $validated['id_class'] ?? null,
                'school_year' => now()->year . '/' . (now()->year + 1)
            ]);
        }
        if (str_contains($role, 'student')) {
            \App\Models\Student::create([
                'id_user' => $user->id_user,
                'nis' => $validated['nis'] ?? $user->id_user,
                'nisn' => $validated['nisn'] ?? null,
                'entry_year' => now()->year,
                'id_class' => $validated['id_class'] ?? null,
            ]);
        }

        return redirect()->route('admin.register')
            ->with('success', 'User registered successfully. They can login with their credentials and will be prompted to set a password.');
    }

    /**
     * Display user management interface.
     */
    public function manage(UserSearchRequest $request)
    {
        $query = User::with(['admin', 'student', 'teacher', 'lecturer', 'homerooms']);

        // Role filter
        $roleFilter = $request->input('role_filter');
        if ($roleFilter && $roleFilter !== 'all') {
            $query->where(function($q) use ($roleFilter) {
                switch ($roleFilter) {
                    case 'administrator':
                        $q->whereHas('admin');
                        break;
                    case 'student':
                        $q->whereHas('student');
                        break;
                    case 'lectureTeacher':
                        $q->whereHas('lecturer');
                        break;
                    case 'homeroomTeacher':
                        $q->whereHas('homerooms');
                        break;
                    case 'teacher':
                        $q->whereHas('teacher');
                        break;
                }
            });
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->validated()['search'];
            $query->where(function ($q) use ($search) {
                $q->where('id_user', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting functionality
        $sortColumn = $request->input('sort');
        $sortDirection = $request->input('direction', 'asc');
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // Apply sorting or default order
        if ($sortColumn) {
            $query->sortBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $users = $query->paginate(20)->appends([
            'search' => $request->input('search'),
            'sort' => $sortColumn,
            'direction' => $sortDirection,
            'role_filter' => $roleFilter,
        ]);

        return view('admin.manage', compact('users'));
    }

    /**
     * Display kelola tahun ajaran view.
     */
    public function tahunAjaran()
    {
        // Auto-create next year if needed
        \App\Models\TahunAjaranDate::autoCreateNextYear();
        
        $tahunAjaranList = \App\Models\TahunAjaranDate::orderBy('tanggal_mulai', 'desc')->get();
        $currentTahunAjaran = \App\Models\TahunAjaranDate::getCurrentTahunAjaran();
        $currentSemester = \App\Models\TahunAjaranDate::getCurrentSemester();
        
        return view('admin.tahun-ajaran', compact('tahunAjaranList', 'currentTahunAjaran', 'currentSemester'));
    }

    /**
     * Update tahun ajaran end date
     */
    public function updateTahunAjaran(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tb_tanggal_tahun_ajaran,id',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai'
        ]);

        $tahunAjaran = \App\Models\TahunAjaranDate::findOrFail($request->id);
        $tahunAjaran->tanggal_selesai = $request->tanggal_selesai;
        $tahunAjaran->save();

        return redirect()->back()->with('success', 'Tanggal selesai tahun ajaran berhasil diperbarui!');
    }

    /**
     * Create new tahun ajaran
     */
    public function storeTahunAjaran(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|unique:tb_tanggal_tahun_ajaran,tahun_ajaran',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai'
        ]);

        \App\Models\TahunAjaranDate::create([
            'tahun_ajaran' => $request->tahun_ajaran,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->back()->with('success', 'Tahun ajaran baru berhasil ditambahkan!');
    }

    /**
     * Export users data to Excel
     */
    public function exportUsers(UserSearchRequest $request): BinaryFileResponse
    {
        $query = User::query();

        // Apply current search filters
        if ($request->filled('search')) {
            $search = $request->validated()['search'];
            $query->where(function ($q) use ($search) {
                $q->where('id_user', 'like', "%{$search}%")
                  ->orWhere('id_user', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        return $this->excelExportService->exportUsers($users);
    }

    /**
     * Export siswa data to Excel
     */
    public function exportSiswa(Request $request): BinaryFileResponse
    {
        $query = Student::with('user');

        // Apply current search filters if any
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $siswa = $query->get();

        return $this->excelExportService->exportSiswa($siswa);
    }

    /**
     * Export guru data to Excel
     */
    public function exportGuru(Request $request): BinaryFileResponse
    {
        $query = Teacher::with('user');

        // Apply current search filters if any
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nomor_induk', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $guru = $query->get();

        return $this->excelExportService->exportGuru($guru);
    }

    /**
     * Export mapel data to Excel
     */
    public function exportMapel(Request $request): BinaryFileResponse
    {
        $query = Subject::query();

        // Apply current search filters if any
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('group', 'like', "%{$search}%");
            });
        }

        $mapel = $query->get();

        return $this->excelExportService->exportMapel($mapel);
    }

    /**
     * Export kelas data to Excel
     */
    public function exportKelas(Request $request): BinaryFileResponse
    {
        $query = ClassRoom::query();

        // Apply current search filters if any
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('grade', 'like', "%{$search}%");
            });
        }

        $kelas = $query->get();

        return $this->excelExportService->exportKelas($kelas);
    }

    /**
     * Import users data from Excel
     */
    public function importUsers(Request $request)
    {
        // Validate uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120', // 5MB max
        ], [
            'file.required' => 'Please select a file to upload.',
            'file.file' => 'The uploaded file is invalid.',
            'file.mimes' => 'Only Excel files (.xlsx, .xls) are allowed.',
            'file.max' => 'The file size must not exceed 5MB.',
        ]);

        try {
            $file = $request->file('file');
            $result = $this->excelImportService->import($file, 'users');

            if ($result->hasErrors()) {
                return redirect()->back()
                    ->withErrors(['import' => $result->getErrorMessage()])
                    ->withInput();
            }

            // Save imported data to database
            foreach ($result->data as $userData) {
                User::create([
                    'name' => $userData['name'],
                    'id_user' => $userData['id_user'],
                    'email' => $userData['email'],
                    'phone_number' => $userData['phone_number'] ?? null,
                    'password' => null,
                ]);
            }

            return redirect()->back()
                ->with('success', "Successfully imported {$result->successCount} user(s).");

        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withErrors(['import' => $e->getMessage()])
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['import' => 'An error occurred during import: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Import siswa data from Excel
     */
    public function importSiswa(Request $request)
    {
        // Validate uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120', // 5MB max
        ], [
            'file.required' => 'Please select a file to upload.',
            'file.file' => 'The uploaded file is invalid.',
            'file.mimes' => 'Only Excel files (.xlsx, .xls) are allowed.',
            'file.max' => 'The file size must not exceed 5MB.',
        ]);

        try {
            $file = $request->file('file');
            $result = $this->excelImportService->import($file, 'siswa');

            if ($result->hasErrors()) {
                return redirect()->back()
                    ->withErrors(['import' => $result->getErrorMessage()])
                    ->withInput();
            }

            // Save imported data to database
            foreach ($result->data as $siswaData) {
                $user = User::create([
                    'name' => $siswaData['nama'],
                    'id_user' => $siswaData['nis'],
                    'email' => 'student' . $siswaData['nis'] . '@school.id',
                    'phone_number' => null,
                    'password' => null,
                ]);

                \App\Models\Student::create([
                    'id_user' => $user->id_user,
                    'nis' => $siswaData['nis'],
                ]);
            }

            return redirect()->back()
                ->with('success', "Successfully imported {$result->successCount} siswa record(s).");

        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withErrors(['import' => $e->getMessage()])
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['import' => 'An error occurred during import: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Import guru data from Excel
     */
    public function importGuru(Request $request)
    {
        // Validate uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120', // 5MB max
        ], [
            'file.required' => 'Please select a file to upload.',
            'file.file' => 'The uploaded file is invalid.',
            'file.mimes' => 'Only Excel files (.xlsx, .xls) are allowed.',
            'file.max' => 'The file size must not exceed 5MB.',
        ]);

        try {
            $file = $request->file('file');
            $result = $this->excelImportService->import($file, 'guru');

            if ($result->hasErrors()) {
                return redirect()->back()
                    ->withErrors(['import' => $result->getErrorMessage()])
                    ->withInput();
            }

            // Save imported data to database
            foreach ($result->data as $guruData) {
                $user = User::create([
                    'name' => $guruData['nama'],
                    'id_user' => $guruData['nip'],
                    'email' => 'teacher' . str_replace(' ', '', $guruData['nip']) . '@school.id',
                    'phone_number' => null,
                    'password' => null,
                ]);

                \App\Models\Lecturer::create(['id_user' => $user->id_user]);
                Teacher::create([
                    'id_user' => $user->id_user,
                    'nomor_induk' => $guruData['nip'],
                ]);
            }

            return redirect()->back()
                ->with('success', "Successfully imported {$result->successCount} guru record(s).");

        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withErrors(['import' => $e->getMessage()])
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['import' => 'An error occurred during import: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Import mapel data from Excel
     */
    public function importMapel(Request $request)
    {
        // Validate uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120', // 5MB max
        ], [
            'file.required' => 'Please select a file to upload.',
            'file.file' => 'The uploaded file is invalid.',
            'file.mimes' => 'Only Excel files (.xlsx, .xls) are allowed.',
            'file.max' => 'The file size must not exceed 5MB.',
        ]);

        try {
            $file = $request->file('file');
            $result = $this->excelImportService->import($file, 'mapel');

            if ($result->hasErrors()) {
                return redirect()->back()
                    ->withErrors(['import' => $result->getErrorMessage()])
                    ->withInput();
            }

            // Save imported data to database
            foreach ($result->data as $mapelData) {
                Subject::create([
                    'kode' => $mapelData['kode'],
                    'nama' => $mapelData['nama'],
                    'kelompok' => $mapelData['kelompok'],
                ]);
            }

            return redirect()->back()
                ->with('success', "Successfully imported {$result->successCount} mapel record(s).");

        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withErrors(['import' => $e->getMessage()])
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['import' => 'An error occurred during import: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Import kelas data from Excel
     */
    public function importKelas(Request $request)
    {
        // Validate uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120', // 5MB max
        ], [
            'file.required' => 'Please select a file to upload.',
            'file.file' => 'The uploaded file is invalid.',
            'file.mimes' => 'Only Excel files (.xlsx, .xls) are allowed.',
            'file.max' => 'The file size must not exceed 5MB.',
        ]);

        try {
            $file = $request->file('file');
            $result = $this->excelImportService->import($file, 'kelas');

            if ($result->hasErrors()) {
                return redirect()->back()
                    ->withErrors(['import' => $result->getErrorMessage()])
                    ->withInput();
            }

            // Save imported data to database
            foreach ($result->data as $kelasData) {
                ClassRoom::create([
                    'name' => $kelasData['name'],
                    'grade' => $kelasData['grade'],
                    'id_class' => $kelasData['id_class'] ?? null,
                ]);
            }

            return redirect()->back()
                ->with('success', "Successfully imported {$result->successCount} kelas record(s).");

        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withErrors(['import' => $e->getMessage()])
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['import' => 'An error occurred during import: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Download Excel template for users
     */
    public function downloadUsersTemplate(): BinaryFileResponse
    {
        // Create empty collection to generate template with headers only
        $emptyData = collect();
        
        return $this->excelExportService->exportUsers($emptyData);
    }

    /**
     * Download Excel template for siswa
     */
    public function downloadSiswaTemplate(): BinaryFileResponse
    {
        // Create empty collection to generate template with headers only
        $emptyData = collect();
        
        return $this->excelExportService->exportSiswa($emptyData);
    }

    /**
     * Download Excel template for guru
     */
    public function downloadGuruTemplate(): BinaryFileResponse
    {
        // Create empty collection to generate template with headers only
        $emptyData = collect();
        
        return $this->excelExportService->exportGuru($emptyData);
    }

    /**
     * Download Excel template for mapel
     */
    public function downloadMapelTemplate(): BinaryFileResponse
    {
        // Create empty collection to generate template with headers only
        $emptyData = collect();
        
        return $this->excelExportService->exportMapel($emptyData);
    }

    /**
     * Download Excel template for kelas
     */
    public function downloadKelasTemplate(): BinaryFileResponse
    {
        // Create empty collection to generate template with headers only
        $emptyData = collect();
        
        return $this->excelExportService->exportKelas($emptyData);
    }

    /**
     * Display Excel configuration page
     */
    public function excelConfig()
    {
        $templates = config('excel-templates');
        return view('admin.excelconfig', compact('templates'));
    }

    /**
     * Update Excel configuration
     */
    public function updateExcelConfig(Request $request)
    {
        $validated = $request->validate([
            'template_type' => 'required|in:users,siswa,guru,mapel,kelas',
            'config' => 'required|json',
        ]);

        try {
            // Parse the JSON config
            $config = json_decode($validated['config'], true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()
                    ->withErrors(['config' => 'Invalid JSON format'])
                    ->withInput();
            }

            // Read current config file
            $configPath = config_path('excel-templates.php');
            $currentConfig = include $configPath;
            
            // Update the specific template
            $currentConfig[$validated['template_type']] = $config;
            
            // Write back to file
            $configContent = "<?php\n\nreturn " . var_export($currentConfig, true) . ";\n";
            file_put_contents($configPath, $configContent);
            
            // Clear config cache
            \Artisan::call('config:clear');
            
            return redirect()->back()
                ->with('success', 'Excel template configuration updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['config' => 'Error updating configuration: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
