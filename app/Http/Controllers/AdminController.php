<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\UserSearchRequest;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Kelas;
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
        $query = Siswa::with('user'); // Eager load user relationship

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

        // Sorting functionality
        $sortColumn = $request->input('sort');
        $sortDirection = $request->input('direction', 'asc');
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // Apply sorting (handle user.name separately)
        if ($sortColumn === 'nama') {
            $query->join('users', 'siswas.user_id', '=', 'users.id')
                  ->orderBy('users.name', $sortDirection)
                  ->select('siswas.*');
        } else {
            $query->sortBy($sortColumn, $sortDirection);
        }

        $siswa = $query->paginate(20)->appends([
            'search' => $request->input('search'),
            'sort' => $sortColumn,
            'direction' => $sortDirection,
        ]);

        return view('admin.siswa', compact('siswa'));
    }

    /**
     * Display the kelola data guru view.
     */
    public function guru(Request $request)
    {
        $query = Guru::with('user'); // Eager load user relationship

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

        // Sorting functionality
        $sortColumn = $request->input('sort');
        $sortDirection = $request->input('direction', 'asc');
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // Apply sorting (handle user.name separately)
        if ($sortColumn === 'nama') {
            $query->join('users', 'gurus.user_id', '=', 'users.id')
                  ->orderBy('users.name', $sortDirection)
                  ->select('gurus.*');
        } else {
            $query->sortBy($sortColumn, $sortDirection);
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
        $query = Mapel::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('kelompok', 'like', "%{$search}%");
            });
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
            'sort' => $sortColumn,
            'direction' => $sortDirection,
        ]);

        return view('admin.mapel', compact('mapel'));
    }

    /**
     * Display the kelola data kelas view.
     */
    public function kelas(Request $request)
    {
        $query = Kelas::with('waliKelas'); // Eager load wali kelas relationship

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

        // Sorting functionality
        $sortColumn = $request->input('sort');
        $sortDirection = $request->input('direction', 'asc');
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // Apply sorting (handle wali_kelas separately)
        if ($sortColumn === 'wali_kelas') {
            $query->join('users', 'kelas.wali_kelas_id', '=', 'users.id')
                  ->orderBy('users.name', $sortDirection)
                  ->select('kelas.*');
        } else {
            $query->sortBy($sortColumn, $sortDirection);
        }

        $kelas = $query->paginate(20)->appends([
            'search' => $request->input('search'),
            'sort' => $sortColumn,
            'direction' => $sortDirection,
        ]);

        return view('admin.kelas', compact('kelas'));
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

        User::create([
            'name' => $validated['name'],
            'user_name' => $validated['user_name'],
            'user_id' => $validated['user_id'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'role' => $role,
            'password_set' => false,
            'password' => null,
        ]);

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
                $q->where('user_id', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
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
                $q->where('user_id', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
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
        $query = Siswa::with('user');

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
        $query = Guru::with('user');

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
        $query = Mapel::query();

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
        $query = Kelas::query();

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
                // Create user account for student
                $user = User::create([
                    'name' => $siswaData['nama'],
                    'user_name' => 'student_' . $siswaData['nis'],
                    'user_id' => $siswaData['nis'],
                    'email' => 'student' . $siswaData['nis'] . '@school.id',
                    'phone_number' => null,
                    'role' => 'student',
                    'password_set' => false,
                    'password' => null,
                ]);

                // Create siswa record linked to user
                Siswa::create([
                    'user_id' => $user->id,
                    'nis' => $siswaData['nis'],
                    'kelas' => $siswaData['kelas'],
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
                // Create user account for teacher
                $user = User::create([
                    'name' => $guruData['nama'],
                    'user_name' => 'teacher_' . $guruData['nip'],
                    'user_id' => $guruData['nip'],
                    'email' => 'teacher' . str_replace(' ', '', $guruData['nip']) . '@school.id',
                    'phone_number' => null,
                    'role' => 'lectureTeacher',
                    'password_set' => false,
                    'password' => null,
                ]);

                // Create guru record linked to user
                Guru::create([
                    'user_id' => $user->id,
                    'nip' => $guruData['nip'],
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
                Mapel::create([
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
                Kelas::create([
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
