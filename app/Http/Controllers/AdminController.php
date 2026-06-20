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
        return redirect()->route('admin.siswa');
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
        $query = ClassRoom::with('homeroom.user'); // Eager load homeroom and wali kelas

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

        // Auto-generate unique id_user
        $lastUser = User::orderBy('id_user', 'desc')->first();
        $nextId = $lastUser ? ((int)$lastUser->id_user) + 1 : 1001;

        // Handle dual teacher role
        $role = $validated['role'];
        if ($request->has('dual_teacher') && $request->dual_teacher) {
            $role = 'lectureTeacher,homeroomTeacher';
        }

        $user = User::create([
            'name' => $validated['name'],
            'id_user' => $nextId,
            'email' => $validated['email'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'gender' => $validated['gender'],
            'password' => null,
        ]);

        // Create role records
        if (str_contains($role, 'administrator')) {
            \App\Models\Admin::create(['id_user' => $user->id_user]);
            // Administrators are also teachers
            \App\Models\Teacher::create([
                'id_user' => $user->id_user,
                'nomor_induk' => $validated['nomor_induk'],
                'date_of_employment' => now()->year,
                'teacher_status' => 'employed',
                'type' => $validated['type']
            ]);
        }
        if (str_contains($role, 'lectureTeacher')) {
            // Create teacher record if not exists
            if (!$user->teacher()->exists()) {
                \App\Models\Teacher::create([
                    'id_user' => $user->id_user,
                    'nomor_induk' => $validated['nomor_induk'],
                    'date_of_employment' => now()->year,
                    'teacher_status' => 'employed',
                    'type' => $validated['type']
                ]);
            }
            \App\Models\Lecturer::create(['id_user' => $user->id_user]);
        }
        if (str_contains($role, 'homeroomTeacher')) {
            // Create teacher record if not exists
            if (!$user->teacher()->exists()) {
                \App\Models\Teacher::create([
                    'id_user' => $user->id_user,
                    'nomor_induk' => $validated['nomor_induk'],
                    'date_of_employment' => now()->year,
                    'teacher_status' => 'employed',
                    'type' => $validated['type']
                ]);
            }
            \App\Models\Homeroom::create([
                'id_user' => $user->id_user,
                'id_class' => null,
            ]);
        }
        if (str_contains($role, 'student')) {
            // Auto-generate NIS if not provided
            $nis = $validated['nis'] ?? null;
            if (empty($nis)) {
                // Get current year (last 2 digits)
                $currentYear = now()->format('y');
                
                // Find the highest NIS for this year
                $lastStudent = \App\Models\Student::where('nis', 'like', $currentYear . '%')
                    ->orderBy('nis', 'desc')
                    ->first();
                
                if ($lastStudent) {
                    // Extract the sequential number and increment
                    $lastSequence = (int)substr($lastStudent->nis, 2);
                    $newSequence = $lastSequence + 1;
                } else {
                    // First student for this year
                    $newSequence = 1;
                }
                
                // Format: YYXXXX (e.g., 240001)
                $nis = $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
            }
            
            \App\Models\Student::create([
                'id_user' => $user->id_user,
                'nis' => $nis,
                'nisn' => $validated['nisn'] ?? null,
                'entry_year' => now()->year,
                'id_class' => null,
            ]);
        }

        // Determine login credential
        $loginCredential = '';
        if (str_contains($role, 'administrator') || str_contains($role, 'lectureTeacher') || str_contains($role, 'homeroomTeacher')) {
            $loginCredential = 'nomor induk: ' . $validated['nomor_induk'];
        } elseif (str_contains($role, 'student')) {
            $loginCredential = 'NIS: ' . $nis;
        }

        return redirect()->route('admin.register')
            ->with('success', 'Pengguna berhasil didaftarkan. Mereka dapat login menggunakan ' . $loginCredential . '.');
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
     * Show the user edit form.
     */
    public function editUser($id_user)
    {
        $user = User::with(['admin', 'student', 'teacher', 'lecturer', 'homerooms'])
            ->where('id_user', $id_user)
            ->firstOrFail();

        $classList = ClassRoom::orderBy('grade')->orderBy('name')->get();

        return view('admin.edit-user', compact('user', 'classList'));
    }

    /**
     * Update user information.
     */
    public function updateUser(Request $request, $id_user)
    {
        $user = User::where('id_user', $id_user)->firstOrFail();

        // Basic validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'email' => 'nullable|email|max:255|unique:tb_users,email,' . $user->id_user . ',id_user',
            'phone_number' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'remove_profile_picture' => 'nullable|boolean',
            
            // Password fields
            'new_password' => 'nullable|string|min:8|confirmed',
            'reset_password' => 'nullable|boolean',
            
            // Student fields
            'nis' => 'nullable|string|max:50|unique:tb_students,nis,' . $user->id_user . ',id_user',
            'nisn' => 'nullable|string|max:50',
            'entry_year' => 'nullable|integer|min:2000|max:2100',
            'id_class' => 'nullable|exists:tb_classes,id_class',
            
            // Teacher fields
            'nomor_induk' => 'nullable|string|max:50|unique:tb_teachers,nomor_induk,' . $user->id_user . ',id_user',
            'type' => 'nullable|in:pns,honorer',
            'date_of_employment' => 'nullable|integer|min:1950|max:2100',
            'teacher_status' => 'nullable|in:employed,resigned',
            
            // Teacher role fields
            'is_lecturer' => 'nullable|boolean',
            'is_homeroom' => 'nullable|boolean',
            
            // Homeroom fields
            'homeroom_class' => 'nullable|exists:tb_classes,id_class',
        ]);

        // Handle profile picture upload
        $profilePicturePath = $user->profile_picture;
        
        if ($request->has('remove_profile_picture') && $request->remove_profile_picture) {
            // Delete old profile picture if it exists
            if ($user->profile_picture && $user->profile_picture !== '/public/images/') {
                // Convert database path to actual file path
                $relativePath = str_replace('/public/', '', $user->profile_picture);
                $oldPath = public_path($relativePath);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $profilePicturePath = '/public/images/';
        } elseif ($request->hasFile('profile_picture')) {
            // Delete old profile picture with different extensions if exists
            $extensions = ['jpg', 'jpeg', 'png', 'gif'];
            foreach ($extensions as $ext) {
                $oldPath = public_path('images/profiles/' . $user->id_user . '.' . $ext);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            
            // Store new profile picture with format: user_id.extension
            $file = $request->file('profile_picture');
            $extension = $file->getClientOriginalExtension();
            $filename = $user->id_user . '.' . $extension;
            $file->move(public_path('images/profiles'), $filename);
            $profilePicturePath = '/public/images/profiles/' . $filename;
        }

        // Update basic user information
        $updateData = [
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'email' => $validated['email'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'profile_picture' => $profilePicturePath,
        ];

        // Handle password changes
        if ($request->has('reset_password') && $request->reset_password) {
            // Reset password to NULL
            $updateData['password'] = null;
        } elseif ($request->filled('new_password')) {
            // Set new password
            $updateData['password'] = \Hash::make($validated['new_password']);
        }

        $user->update($updateData);

        // Update student information
        if ($user->isStudent()) {
            $user->student->update([
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'] ?? null,
                'entry_year' => $validated['entry_year'] ?? null,
                'id_class' => $validated['id_class'] ?? null,
            ]);
        }

        // Update teacher information and roles
        if ($user->isTeacher()) {
            $user->teacher->update([
                'nomor_induk' => $validated['nomor_induk'],
                'type' => $validated['type'],
                'date_of_employment' => $validated['date_of_employment'] ?? null,
                'teacher_status' => $validated['teacher_status'],
            ]);

            // Handle Lecturer role
            $isLecturer = $request->has('is_lecturer') && $request->is_lecturer;
            if ($isLecturer && !$user->isLecturer()) {
                // Add lecturer role
                \App\Models\Lecturer::create(['id_user' => $user->id_user]);
            } elseif (!$isLecturer && $user->isLecturer()) {
                // Remove lecturer role
                \App\Models\Lecturer::where('id_user', $user->id_user)->delete();
            }

            // Handle Homeroom role
            $isHomeroom = $request->has('is_homeroom') && $request->is_homeroom;
            if ($isHomeroom && !$user->isHomeroomTeacher()) {
                // Add homeroom role
                \App\Models\Homeroom::create([
                    'id_user' => $user->id_user,
                    'id_class' => $validated['homeroom_class'] ?? null,
                ]);
            } elseif (!$isHomeroom && $user->isHomeroomTeacher()) {
                // Remove homeroom role
                \App\Models\Homeroom::where('id_user', $user->id_user)->delete();
            } elseif ($isHomeroom && $user->isHomeroomTeacher()) {
                // Update existing homeroom class
                $homeroom = $user->homerooms->first();
                if ($homeroom) {
                    $homeroom->update([
                        'id_class' => $validated['homeroom_class'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.user.edit', $user->id_user)
            ->with('success', 'Data pengguna berhasil diperbarui.');
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
            $successCount = 0;
            $errorMessages = [];

            foreach ($result->data as $index => $siswaData) {
                try {
                    \DB::beginTransaction();

                    // Check if NIS already exists
                    $existingStudent = Student::where('nis', $siswaData['nis'])->first();
                    
                    if ($existingStudent) {
                        $errorMessages[] = "Row " . ($index + 2) . ": Siswa dengan NIS {$siswaData['nis']} sudah ada";
                        continue;
                    }

                    // Auto-generate unique id_user
                    $lastUser = User::orderBy('id_user', 'desc')->first();
                    $nextId = $lastUser ? ((int)$lastUser->id_user) + 1 : 1001;

                    // Create user account
                    $user = User::create([
                        'id_user' => $nextId,
                        'name' => $siswaData['name'],
                        'phone_number' => null,
                        'gender' => match(strtoupper($siswaData['gender'] ?? '')) {
                            'L', 'M' => 'M',
                            'P', 'F' => 'F',
                            default   => 'M',
                        },
                        'password' => null,
                    ]);

                    // Find or skip class assignment
                    $idClass = null;
                    if (!empty($siswaData['class_name'])) {
                        $class = ClassRoom::where('name', $siswaData['class_name'])->first();
                        $idClass = $class ? $class->id_class : null;
                    }

                    // Create student record
                    Student::create([
                        'id_user' => $user->id_user,
                        'nis' => $siswaData['nis'],
                        'nisn' => $siswaData['nisn'] ?? null,
                        'entry_year' => $siswaData['entry_year'] ?? now()->year,
                        'id_class' => $idClass,
                    ]);

                    \DB::commit();
                    $successCount++;

                } catch (\Exception $e) {
                    \DB::rollBack();
                    $errorMessages[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            if ($successCount > 0) {
                $message = "Successfully imported {$successCount} siswa record(s).";
                if (!empty($errorMessages)) {
                    $message .= "\n\nErrors:\n" . implode("\n", $errorMessages);
                }
                return redirect()->back()->with('success', $message);
            } else {
                return redirect()->back()
                    ->withErrors(['import' => "Import failed.\n" . implode("\n", $errorMessages)]);
            }

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
            $successCount = 0;
            $errorMessages = [];

            foreach ($result->data as $index => $guruData) {
                try {
                    \DB::beginTransaction();

                    // Check if teacher with this nomor_induk already exists
                    $existingTeacher = Teacher::where('nomor_induk', $guruData['nomor_induk'])->first();
                    
                    if ($existingTeacher) {
                        $errorMessages[] = "Row " . ($index + 2) . ": Guru dengan nomor induk {$guruData['nomor_induk']} sudah ada";
                        continue;
                    }

                    // Auto-generate unique id_user
                    $lastUser = User::orderBy('id_user', 'desc')->first();
                    $nextId = $lastUser ? ((int)$lastUser->id_user) + 1 : 1001;

                    // Create user account
                    $user = User::create([
                        'id_user' => $nextId,
                        'name' => $guruData['name'],
                        'phone_number' => null,
                        'gender' => match(strtoupper($guruData['gender'] ?? '')) {
                            'L', 'M' => 'M',
                            'P', 'F' => 'F',
                            default   => 'M',
                        },
                        'password' => null,
                    ]);

                    // Create teacher record
                    Teacher::create([
                        'id_user' => $user->id_user,
                        'nomor_induk' => $guruData['nomor_induk'],
                        'type' => $guruData['type'] ?? 'honorer',
                        'date_of_employment' => $guruData['date_of_employment'] ?? now()->year,
                        'teacher_status' => $guruData['teacher_status'] ?? 'employed',
                    ]);

                    // Create lecturer role by default
                    \App\Models\Lecturer::create(['id_user' => $user->id_user]);

                    \DB::commit();
                    $successCount++;

                } catch (\Exception $e) {
                    \DB::rollBack();
                    $errorMessages[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            if ($successCount > 0) {
                $message = "Successfully imported {$successCount} guru record(s).";
                if (!empty($errorMessages)) {
                    $message .= "\n\nErrors:\n" . implode("\n", $errorMessages);
                }
                return redirect()->back()->with('success', $message);
            } else {
                return redirect()->back()
                    ->withErrors(['import' => "Import failed.\n" . implode("\n", $errorMessages)]);
            }

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
            $successCount = 0;
            $errorMessages = [];

            foreach ($result->data as $index => $mapelData) {
                try {
                    Subject::create([
                        'code' => $mapelData['code'] ?? null,
                        'name' => $mapelData['name'],
                        'grade' => $mapelData['grade'] ?? null,
                        'curriculum' => $mapelData['curriculum'] ?? null,
                        'group' => $mapelData['group'] ?? null,
                    ]);

                    $successCount++;

                } catch (\Exception $e) {
                    $errorMessages[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            if ($successCount > 0) {
                $message = "Successfully imported {$successCount} mapel record(s).";
                if (!empty($errorMessages)) {
                    $message .= "\n\nErrors:\n" . implode("\n", $errorMessages);
                }
                return redirect()->back()->with('success', $message);
            } else {
                return redirect()->back()
                    ->withErrors(['import' => "Import failed.\n" . implode("\n", $errorMessages)]);
            }

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
            $successCount = 0;
            $errorMessages = [];

            foreach ($result->data as $index => $kelasData) {
                try {
                    ClassRoom::create([
                        'name' => $kelasData['name'],
                        'grade' => $kelasData['grade'] ?? null,
                    ]);

                    $successCount++;

                } catch (\Exception $e) {
                    $errorMessages[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            if ($successCount > 0) {
                $message = "Successfully imported {$successCount} kelas record(s).";
                if (!empty($errorMessages)) {
                    $message .= "\n\nErrors:\n" . implode("\n", $errorMessages);
                }
                return redirect()->back()->with('success', $message);
            } else {
                return redirect()->back()
                    ->withErrors(['import' => "Import failed.\n" . implode("\n", $errorMessages)]);
            }

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
     * Inline update for siswa (Ajax)
     */
    public function updateSiswa(Request $request, $id)
    {
        $student = Student::with('user')->where('id_user', $id)->firstOrFail();
        $validated = $request->validate([
            'nis'        => 'sometimes|string|max:20|unique:tb_students,nis,' . $student->id_user . ',id_user',
            'nisn'       => 'nullable|string|max:20',
            'entry_year' => 'nullable|integer|min:2000|max:2100',
            'class_name' => 'nullable|string|max:50',
            'name'       => 'sometimes|string|max:255',
            'gender'     => 'nullable|in:M,F',
        ]);

        if (isset($validated['name']) || isset($validated['gender'])) {
            $student->user->update(array_filter([
                'name'   => $validated['name'] ?? null,
                'gender' => $validated['gender'] ?? null,
            ], fn($v) => $v !== null));
        }

        $idClass = $student->id_class;
        if (array_key_exists('class_name', $validated)) {
            $class = ClassRoom::where('name', $validated['class_name'])->first();
            $idClass = $class ? $class->id_class : null;
        }

        $student->update(array_filter([
            'nis'        => $validated['nis'] ?? null,
            'nisn'       => array_key_exists('nisn', $validated) ? $validated['nisn'] : $student->nisn,
            'entry_year' => $validated['entry_year'] ?? null,
            'id_class'   => $idClass,
        ], fn($v) => $v !== null));

        return response()->json(['success' => true]);
    }

    /**
     * Delete siswa (Ajax)
     */
    public function deleteSiswa($id)
    {
        $student = Student::where('id_user', $id)->firstOrFail();
        $userId = $student->id_user;
        $student->delete();
        User::where('id_user', $userId)->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Inline update for guru (Ajax)
     */
    public function updateGuru(Request $request, $id)
    {
        $teacher = Teacher::with('user')->where('id_user', $id)->firstOrFail();
        $validated = $request->validate([
            'nomor_induk'        => 'sometimes|string|max:50|unique:tb_teachers,nomor_induk,' . $teacher->id_user . ',id_user',
            'name'               => 'sometimes|string|max:255',
            'gender'             => 'nullable|in:M,F',
            'type'               => 'nullable|in:pns,honorer',
            'date_of_employment' => 'nullable|integer|min:1950|max:2100',
            'teacher_status'     => 'nullable|in:employed,resigned',
        ]);

        if (isset($validated['name']) || isset($validated['gender'])) {
            $teacher->user->update(array_filter([
                'name'   => $validated['name'] ?? null,
                'gender' => $validated['gender'] ?? null,
            ], fn($v) => $v !== null));
        }

        $teacher->update(array_filter([
            'nomor_induk'        => $validated['nomor_induk'] ?? null,
            'type'               => $validated['type'] ?? null,
            'date_of_employment' => $validated['date_of_employment'] ?? null,
            'teacher_status'     => $validated['teacher_status'] ?? null,
        ], fn($v) => $v !== null));

        return response()->json(['success' => true]);
    }

    /**
     * Delete guru (Ajax)
     */
    public function deleteGuru($id)
    {
        $teacher = Teacher::where('id_user', $id)->firstOrFail();
        $userId = $teacher->id_user;
        $teacher->delete();
        User::where('id_user', $userId)->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Inline update for mapel (Ajax)
     */
    public function updateMapel(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $validated = $request->validate([
            'code'       => 'nullable|string|max:20',
            'name'       => 'sometimes|string|max:255',
            'grade'      => 'nullable|integer|min:1|max:12',
            'curriculum' => 'nullable|string|max:100',
            'group'      => 'nullable|string|max:100',
        ]);
        $subject->update($validated);
        return response()->json(['success' => true]);
    }

    /**
     * Delete mapel (Ajax)
     */
    public function deleteMapel($id)
    {
        Subject::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Inline update for kelas (Ajax)
     */
    public function updateKelas(Request $request, $id)
    {
        $kelas = ClassRoom::findOrFail($id);
        $validated = $request->validate([
            'name'  => 'sometimes|string|max:50',
            'grade' => 'nullable|integer|min:1|max:12',
        ]);
        $kelas->update($validated);
        return response()->json(['success' => true]);
    }

    /**
     * Delete kelas (Ajax)
     */
    public function deleteKelas($id)
    {
        ClassRoom::findOrFail($id)->delete();
        return response()->json(['success' => true]);
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
