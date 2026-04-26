# Teacher Excel & Authorization Quick Reference

## TeacherAuthorizationHelper Methods

### Get Authorized Data

```php
use App\Helpers\TeacherAuthorizationHelper;

// Get all classes teacher can access
$classes = TeacherAuthorizationHelper::getAuthorizedClasses($teacher);

// Get homeroom class (returns string or null)
$class = TeacherAuthorizationHelper::getHomeroomClass($teacher);

// Get lecture classes (returns array)
$classes = TeacherAuthorizationHelper::getLectureClasses($teacher);

// Get authorized subjects (returns array)
$subjects = TeacherAuthorizationHelper::getAuthorizedSubjects($teacher);
```

### Check Permissions

```php
// Check if teacher can access a class
if (TeacherAuthorizationHelper::canAccessClass($teacher, '10A')) {
    // Authorized
}

// Check if teacher can access a subject
if (TeacherAuthorizationHelper::canAccessSubject($teacher, 'MAT')) {
    // Authorized
}

// Check if teacher can manage a student
if (TeacherAuthorizationHelper::canManageStudent($teacher, $studentId)) {
    // Authorized
}

// Check if teacher can perform bulk operations
if (TeacherAuthorizationHelper::canPerformBulkOperations($teacher)) {
    // Has at least one class assignment
}
```

### Query Builders

```php
// Get query builder for authorized students
$query = TeacherAuthorizationHelper::getAuthorizedStudentsQuery($teacher);
$students = $query->where('nama', 'like', '%John%')->get();

// Filter collection of students
$authorizedStudents = TeacherAuthorizationHelper::filterAuthorizedStudents(
    $teacher,
    $allStudents
);
```

### Validation

```php
// Validate student authorization (for imports)
$unauthorizedClasses = TeacherAuthorizationHelper::validateStudentAuthorization(
    $teacher,
    $studentsCollection
);

if (!empty($unauthorizedClasses)) {
    // Handle unauthorized classes
}
```

### Debugging

```php
// Get complete authorization summary
$summary = TeacherAuthorizationHelper::getAuthorizationSummary($teacher);
dd($summary);
```

---

## ExcelExportService Usage

```php
use App\Services\ExcelExportService;

public function __construct(
    private ExcelExportService $excelExportService
) {}

// Export with specific template
public function exportData(Request $request)
{
    $data = $this->getAuthorizedData($request->user());
    
    return $this->excelExportService->exportSiswa($data);
    // or: exportGuru(), exportMapel(), exportKelas(), exportUsers()
}

// Generic export with custom template
public function exportCustom(Request $request)
{
    $data = collect([...]);
    $filename = 'Custom_' . date('Y-m-d') . '.xlsx';
    
    return $this->excelExportService->export($data, 'template_type', $filename);
}
```

---

## ExcelImportService Usage

```php
use App\Services\ExcelImportService;

public function __construct(
    private ExcelImportService $excelImportService
) {}

public function importData(Request $request)
{
    // Validate file
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls|max:5120',
    ]);

    try {
        $file = $request->file('file');
        $result = $this->excelImportService->import($file, 'siswa');

        // Check for errors
        if ($result->hasErrors()) {
            return redirect()->back()
                ->withErrors(['import' => $result->getErrorMessage()]);
        }

        // Validate authorization
        $teacher = $request->user();
        $unauthorizedClasses = TeacherAuthorizationHelper::validateStudentAuthorization(
            $teacher,
            $result->data
        );

        if (!empty($unauthorizedClasses)) {
            return redirect()->back()
                ->withErrors(['import' => 'Unauthorized classes: ' . implode(', ', $unauthorizedClasses)]);
        }

        // Save data
        foreach ($result->data as $row) {
            Siswa::create($row);
        }

        return redirect()->back()
            ->with('success', "Imported {$result->successCount} records.");

    } catch (\InvalidArgumentException $e) {
        return redirect()->back()
            ->withErrors(['import' => $e->getMessage()]);
    }
}
```

---

## Controller Pattern

```php
namespace App\Http\Controllers;

use App\Services\ExcelExportService;
use App\Services\ExcelImportService;
use App\Helpers\TeacherAuthorizationHelper;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function __construct(
        private ExcelExportService $excelExportService,
        private ExcelImportService $excelImportService
    ) {
        $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
        $teacher = $request->user();
        $authorizedClasses = TeacherAuthorizationHelper::getAuthorizedClasses($teacher);
        
        $query = Siswa::whereIn('kelas', $authorizedClasses);
        
        // Apply search
        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%");
        }
        
        // Apply sorting
        $query->sortBy($request->sort, $request->direction ?? 'asc');
        
        $students = $query->paginate(20);
        
        return view('teacher.dashboard', compact('students'));
    }

    public function export(Request $request)
    {
        $teacher = $request->user();
        $students = TeacherAuthorizationHelper::getAuthorizedStudentsQuery($teacher)->get();
        
        return $this->excelExportService->exportSiswa($students);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls|max:5120']);
        
        $teacher = $request->user();
        $result = $this->excelImportService->import($request->file('file'), 'siswa');
        
        if ($result->hasErrors()) {
            return back()->withErrors(['import' => $result->getErrorMessage()]);
        }
        
        $unauthorized = TeacherAuthorizationHelper::validateStudentAuthorization(
            $teacher,
            $result->data
        );
        
        if (!empty($unauthorized)) {
            return back()->withErrors(['import' => 'Unauthorized: ' . implode(', ', $unauthorized)]);
        }
        
        foreach ($result->data as $data) {
            Siswa::create($data);
        }
        
        return back()->with('success', "Imported {$result->successCount} students.");
    }

    public function template()
    {
        return $this->excelExportService->exportSiswa(collect());
    }
}
```

---

## View Pattern

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header with Excel Buttons -->
    <div class="d-flex justify-content-between mb-4">
        <h2>My Students</h2>
        <x-excel-buttons 
            :export-route="route('teacher.students.export')" 
            :import-route="route('teacher.students.import')" 
            :template-route="route('teacher.students.template')" 
        />
    </div>

    <!-- Search Form -->
    <form method="GET" class="mb-4">
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <!-- Table with Sortable Headers -->
    <table class="table">
        <thead>
            <tr>
                <x-sortable-header 
                    column="nama" 
                    label="Nama" 
                    :current-sort="request('sort')" 
                    :current-direction="request('direction')" 
                />
                <x-sortable-header 
                    column="nis" 
                    label="NIS" 
                    :current-sort="request('sort')" 
                    :current-direction="request('direction')" 
                />
                <x-sortable-header 
                    column="kelas" 
                    label="Kelas" 
                    :current-sort="request('sort')" 
                    :current-direction="request('direction')" 
                />
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>{{ $student->nama }}</td>
                    <td>{{ $student->nis }}</td>
                    <td>{{ $student->kelas }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No students found</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $students->links() }}
</div>
@endsection
```

---

## Routes Pattern

```php
// routes/web.php

Route::middleware(['auth'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/students/export', [TeacherController::class, 'export'])->name('students.export');
    Route::post('/students/import', [TeacherController::class, 'import'])->name('students.import');
    Route::get('/students/template', [TeacherController::class, 'template'])->name('students.template');
});
```

---

## Common Patterns

### Export with Filters

```php
public function export(Request $request)
{
    $teacher = $request->user();
    $query = TeacherAuthorizationHelper::getAuthorizedStudentsQuery($teacher);
    
    // Apply filters
    if ($request->filled('search')) {
        $query->where('nama', 'like', "%{$request->search}%");
    }
    
    if ($request->filled('kelas')) {
        $query->where('kelas', $request->kelas);
    }
    
    $students = $query->get();
    
    return $this->excelExportService->exportSiswa($students);
}
```

### Import with Transaction

```php
use Illuminate\Support\Facades\DB;

public function import(Request $request)
{
    $result = $this->excelImportService->import($request->file('file'), 'siswa');
    
    if ($result->hasErrors()) {
        return back()->withErrors(['import' => $result->getErrorMessage()]);
    }
    
    DB::beginTransaction();
    try {
        foreach ($result->data as $data) {
            Siswa::create($data);
        }
        DB::commit();
        
        return back()->with('success', "Imported {$result->successCount} records.");
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['import' => 'Import failed: ' . $e->getMessage()]);
    }
}
```

### Authorization Middleware

```php
// app/Http/Middleware/CheckTeacherClassAccess.php

public function handle($request, Closure $next, $classParam = 'class')
{
    $teacher = $request->user();
    $className = $request->route($classParam);
    
    if (!TeacherAuthorizationHelper::canAccessClass($teacher, $className)) {
        abort(403, 'You do not have access to this class.');
    }
    
    return $next($request);
}

// Usage in routes
Route::get('/teacher/class/{class}', [TeacherController::class, 'show'])
    ->middleware('check.teacher.class:class');
```

---

## Error Handling

```php
// File validation errors
try {
    $result = $this->excelImportService->import($file, 'siswa');
} catch (\InvalidArgumentException $e) {
    // Invalid file type or format
    return back()->withErrors(['import' => $e->getMessage()]);
}

// Import validation errors
if ($result->hasErrors()) {
    // Row-level validation errors
    return back()->withErrors(['import' => $result->getErrorMessage()]);
}

// Authorization errors
$unauthorized = TeacherAuthorizationHelper::validateStudentAuthorization($teacher, $result->data);
if (!empty($unauthorized)) {
    return back()->withErrors(['import' => 'Unauthorized classes: ' . implode(', ', $unauthorized)]);
}
```

---

## Testing Snippets

```php
// Unit test
public function test_teacher_can_access_assigned_class()
{
    $teacher = User::factory()->create(['role' => 'homeroomTeacher']);
    
    TeacherAssignment::create([
        'teacher_id' => $teacher->id,
        'assignment_type' => 'homeroom',
        'class_name' => '10A',
        'academic_year' => '2024/2025',
        'is_active' => true,
    ]);
    
    $this->assertTrue(TeacherAuthorizationHelper::canAccessClass($teacher, '10A'));
    $this->assertFalse(TeacherAuthorizationHelper::canAccessClass($teacher, '10B'));
}

// Feature test
public function test_teacher_can_export_students()
{
    $teacher = User::factory()->create(['role' => 'homeroomTeacher']);
    
    TeacherAssignment::create([
        'teacher_id' => $teacher->id,
        'assignment_type' => 'homeroom',
        'class_name' => '10A',
        'academic_year' => '2024/2025',
        'is_active' => true,
    ]);
    
    Siswa::factory()->create(['kelas' => '10A']);
    
    $response = $this->actingAs($teacher)->get(route('teacher.students.export'));
    
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
}
```

---

## Debugging Commands

```php
// Check teacher authorization
$summary = TeacherAuthorizationHelper::getAuthorizationSummary($teacher);
dd($summary);

// Check import result
$result = $this->excelImportService->import($file, 'siswa');
dd([
    'success_count' => $result->successCount,
    'error_count' => $result->errorCount,
    'errors' => $result->errors,
    'data' => $result->data,
]);

// Check authorized classes
$classes = TeacherAuthorizationHelper::getAuthorizedClasses($teacher);
dd($classes);
```
