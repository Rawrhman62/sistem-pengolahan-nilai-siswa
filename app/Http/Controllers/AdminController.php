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
        return view('admin.index');
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
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by kelas
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->input('kelas'));
        }

        // Filter by tahun ajaran
        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->input('tahun_ajaran'));
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
            $query->join('users', 'tb_students.id_user', '=', 'tb_users.id_user')
                  ->orderBy('tb_users.name', $sortDirection)
                  ->select('siswas.*');
        } else {
            $query->sortBy($sortColumn, $sortDirection);
        }

        $siswa = $query->paginate(20)->appends([
            'search' => $request->input('search'),
            'kelas' => $request->input('kelas'),
            'tahun_ajaran' => $request->input('tahun_ajaran'),
            'sort' => $sortColumn,
            'direction' => $sortDirection,
        ]);

        // Get filter options
        $kelasList = Student::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $tahunAjaranList = Student::select('tahun_ajaran')->distinct()->whereNotNull('tahun_ajaran')->orderBy('tahun_ajaran', 'desc')->pluck('tahun_ajaran');

        return view('admin.siswa', compact('siswa', 'kelasList', 'tahunAjaranList'));
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
                $q->where('nip', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by mapel
        if ($request->filled('mapel')) {
            $query->where('mapel_diampu', 'like', '%' . $request->input('mapel') . '%');
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
            $query->join('users', 'tb_teachers.id_user', '=', 'tb_users.id_user')
                  ->orderBy('tb_users.name', $sortDirection)
                  ->select('gurus.*');
        } else {
            $query->sortBy($sortColumn, $sortDirection);
        }

        $guru = $query->paginate(20)->appends([
            'search' => $request->input('search'),
            'mapel' => $request->input('mapel'),
            'sort' => $sortColumn,
            'direction' => $sortDirection,
        ]);

        // Get filter options
        $mapelList = Subject::orderBy('nama')->pluck('nama', 'nama');

        return view('admin.guru', compact('guru', 'mapelList'));
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
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('kelompok', 'like', "%{$search}%");
            });
        }

        // Filter by kelompok
        if ($request->filled('kelompok')) {
            $query->where('kelompok', $request->input('kelompok'));
        }

        // Sorting functionality
        $sortColumn = $request->input('sort');
        $sortDirection = $request->input('direction', 'asc');
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // Apply sorting
        $query->sortBy($sortColumn, $sortDirection);

        $mapel = $query->paginate(20)->appends([
            'search' => $request->input('search'),
            'kelompok' => $request->input('kelompok'),
            'sort' => $sortColumn,
            'direction' => $sortDirection,
        ]);

        // Get filter options
        $kelompokList = Subject::select('kelompok')->distinct()->whereNotNull('kelompok')->orderBy('kelompok')->pluck('kelompok');

        return view('admin.mapel', compact('mapel', 'kelompokList'));
    }

    /**
     * Display the kelola data kelas view.
     */
    public function kelas(Request $request)
    {
        $query = ClassRoom::with('waliKelas'); // Eager load wali kelas relationship

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('tingkat', 'like', "%{$search}%")
                  ->orWhere('wali_kelas', 'like', "%{$search}%")
                  ->orWhereHas('waliKelas', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by tingkat
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->input('tingkat'));
        }

        // Sorting functionality
        $sortColumn = $request->input('sort');
        $sortDirection = $request->input('direction', 'asc');
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // Apply sorting (handle wali_kelas separately)
        if ($sortColumn === 'wali_kelas') {
            $query->join('users', 'tb_classes.wali_kelas_id', '=', 'tb_users.id_user')
                  ->orderBy('tb_users.name', $sortDirection)
                  ->select('kelas.*');
        } else {
            $query->sortBy($sortColumn, $sortDirection);
        }

        $kelas = $query->paginate(20)->appends([
            'search' => $request->input('search'),
            'tingkat' => $request->input('tingkat'),
            'sort' => $sortColumn,
            'direction' => $sortDirection,
        ]);

        // Get filter options
        $tingkatList = ClassRoom::select('tingkat')->distinct()->whereNotNull('tingkat')->orderBy('tingkat')->pluck('tingkat');

        return view('admin.kelas', compact('kelas', 'tingkatList'));
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
            'phone_number' => $validated['phone_number'],
            'password' => null,
        ]);

        if (str_contains($role, 'administrator')) {
            \App\Models\Admin::create(['id_user' => $user->id_user]);
        }
        if (str_contains($role, 'lectureTeacher')) {
            \App\Models\Lecturer::create(['id_user' => $user->id_user]);
            \App\Models\Teacher::create(['id_user' => $user->id_user]);
        }
        if (str_contains($role, 'homeroomTeacher')) {
            \App\Models\Homeroom::create(['id_user' => $user->id_user]);
            if (!str_contains($role, 'lectureTeacher')) {
                \App\Models\Teacher::create(['id_user' => $user->id_user]);
            }
        }
        if (str_contains($role, 'student')) {
            \App\Models\Student::create(['id_user' => $user->id_user]);
        }

        return redirect()->route('admin.register')
            ->with('success', 'User registered successfully. They can login with their userId and will be prompted to set a password.');
    }

    /**
     * Display user management interface.
     */
    public function manage(UserSearchRequest $request)
    {
        $query = User::query();

        // Search functionality
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
        ]);

        return view('admin.manage', compact('users'));
    }

    /**
     * Display kelola tahun ajaran view.
     */
    public function tahunAjaran()
    {
        return view('admin.tahun-ajaran');
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
                  ->orWhere('kelas', 'like', "%{$search}%")
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
                $q->where('nip', 'like', "%{$search}%")
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
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('kelompok', 'like', "%{$search}%");
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
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('tingkat', 'like', "%{$search}%")
                  ->orWhere('wali_kelas', 'like', "%{$search}%");
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
                    'user_name' => $userData['user_name'],
                    'user_id' => $userData['user_id'],
                    'email' => $userData['email'],
                    'phone_number' => $userData['phone_number'] ?? null,
                    'role' => $userData['role'],
                    'password_set' => false,
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
                    'nama' => $kelasData['nama'],
                    'tingkat' => $kelasData['tingkat'],
                    'wali_kelas' => $kelasData['wali_kelas'],
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
