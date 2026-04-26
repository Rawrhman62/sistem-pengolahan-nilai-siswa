# Teacher Dashboard Excel Integration Guide

## Overview

This guide explains how to integrate Excel import/export functionality and sortable tables into teacher dashboards (Lecture Teacher and Homeroom Teacher). The implementation follows the same patterns used in the admin pages, with additional authorization checks to ensure teachers can only access data they are permitted to manage.

## Table of Contents

1. [Service Usage](#service-usage)
2. [Authorization Requirements](#authorization-requirements)
3. [Controller Implementation](#controller-implementation)
4. [View Integration](#view-integration)
5. [Route Configuration](#route-configuration)
6. [Example Implementations](#example-implementations)

---

## Service Usage

### ExcelExportService

The `ExcelExportService` handles exporting data to Excel files with proper formatting and styling.

**Location**: `app/Services/ExcelExportService.php`

**Key Methods**:

```php
// Generic export method
public function export(
    Collection $data,
    string $templateType,
    string $filename
): BinaryFileResponse

// Specific export methods
public function exportSiswa(Collection $data): BinaryFileResponse
public function exportGuru(Collection $data): BinaryFileResponse
public function exportMapel(Collection $data): BinaryFileResponse
public function exportKelas(Collection $data): BinaryFileResponse
```

**Usage in Controllers**:

```php
use App\Services\ExcelExportService;

class TeacherController extends Controller
{
    public function __construct(
        private ExcelExportService $excelExportService
    ) {}

    public function exportMyStudents(Request $request): BinaryFileResponse
    {
        // Get only students the teacher is authorized to access
        $students = $this->getAuthorizedStudents($request->user());
        
        // Export using the service
        return $this->excelExportService->exportSiswa($students);
    }
}
```

### ExcelImportService

The `ExcelImportService` handles parsing and validating Excel files for import.

**Location**: `app/Services/ExcelImportService.php`

**Key Method**:

```php
public function import(
    UploadedFile $file,
    string $templateType
): ImportResult
```

**ImportResult Properties**:

- `successCount`: Number of successfully validated rows
- `errorCount`: Number of rows with validation errors
- `errors`: Array of error messages keyed by row number
- `data`: Collection of validated data ready for database insertion

**Usage in Controllers**:

```php
use App\Services\ExcelImportService;

public function importMyStudents(Request $request)
{
    // Validate file upload
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls|max:5120',
    ]);

    try {
        $file = $request->file('file');
        $result = $this->excelImportService->import($file, 'siswa');

        if ($result->hasErrors()) {
            return redirect()->back()
                ->withErrors(['import' => $result->getErrorMessage()]);
        }

        // Validate authorization before saving
        foreach ($result->data as $studentData) {
            if (!$this->canManageClass($request->user(), $studentData['kelas'])) {
                return redirect()->back()
                    ->withErrors(['import' => 'You are not authorized to import students for class: ' . $studentData['kelas']]);
            }
        }

        // Save data to database
        foreach ($result->data as $studentData) {
            Siswa::create($studentData);
        }

        return redirect()->back()
            ->with('success', "Successfully imported {$result->successCount} student(s).");

    } catch (\InvalidArgumentException $e) {
        return redirect()->back()
            ->withErrors(['import' => $e->getMessage()]);
    }
}
```

---

## Authorization Requirements

### Core Authorization Principles

1. **Lecture Teachers** can only access:
   - Students in classes they teach
   - Their assigned subjects (mapel)
   - Grade data for their subjects

2. **Homeroom Teachers** can only access:
   - Students in their assigned homeroom class
   - All subjects for their homeroom class
   - Complete grade data for their class

3. **Dual Role Teachers** (both lecture and homeroom) have combined permissions

### Required Authorization Checks

Before any Excel export or import operation, verify:

1. **User Authentication**: Ensure user is logged in
2. **Role Verification**: Confirm user has teacher role
3. **Data Ownership**: Verify teacher has permission to access the specific data
4. **Class Assignment**: Check teacher is assigned to the relevant class(es)

### Authorization Helper Methods

Use the authorization helper methods (see Task 13.2) to check permissions:

```php
use App\Helpers\TeacherAuthorizationHelper;

// Check if teacher can access a specific class
if (!TeacherAuthorizationHelper::canAccessClass($teacher, $classId)) {
    abort(403, 'Unauthorized access to class data');
}

// Check if teacher can manage student data
if (!TeacherAuthorizationHelper::canManageStudent($teacher, $studentId)) {
    abort(403, 'Unauthorized access to student data');
}

// Get all classes a teacher can access
$authorizedClasses = TeacherAuthorizationHelper::getAuthorizedClasses($teacher);
```

---

## Controller Implementation

### Basic Controller Structure

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Services\ExcelExportService;
use App\Services\ExcelImportService;
use App\Helpers\TeacherAuthorizationHelper;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TeacherController extends Controller
{
    public function __construct(
        private ExcelExportService $excelExportService,
        private ExcelImportService $excelImportService
    ) {
        // Ensure all methods require authentication
        $this->middleware('auth');
    }

    /**
     * Display teacher dashboard with student list
     */
    public function dashboard(Request $request)
    {
        $teacher = $request->user();
        
        // Get authorized classes
        $authorizedClasses = TeacherAuthorizationHelper::getAuthorizedClasses($teacher);
        
        // Build query for students
        $query = Siswa::whereIn('kelas', $authorizedClasses);

        // Apply search filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortColumn = $request->input('sort');
        $sortDirection = $request->input('direction', 'asc');
        
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $query->sortBy($sortColumn, $sortDirection);

        $students = $query->paginate(20)->appends([
            'search' => $request->input('search'),
            'sort' => $sortColumn,
            'direction' => $sortDirection,
        ]);

        return view('teacher.dashboard', compact('students'));
    }

    /**
     * Export students data to Excel (teacher-specific)
     */
    public function exportStudents(Request $request): BinaryFileResponse
    {
        $teacher = $request->user();
        
        // Get authorized classes
        $authorizedClasses = TeacherAuthorizationHelper::getAuthorizedClasses($teacher);
        
        // Build query with authorization filter
        $query = Siswa::whereIn('kelas', $authorizedClasses);

        // Apply current search filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $students = $query->get();

        // Use the export service
        return $this->excelExportService->exportSiswa($students);
    }

    /**
     * Import students data from Excel (teacher-specific)
     */
    public function importStudents(Request $request)
    {
        $teacher = $request->user();
        
        // Validate file upload
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120',
        ], [
            'file.required' => 'Please select a file to upload.',
            'file.mimes' => 'Only Excel files (.xlsx, .xls) are allowed.',
            'file.max' => 'The file size must not exceed 5MB.',
        ]);

        try {
            $file = $request->file('file');
            $result = $this->excelImportService->import($file, 'siswa');

            if ($result->hasErrors()) {
                return redirect()->back()
                    ->withErrors(['import' => $result->getErrorMessage()]);
            }

            // Get authorized classes for this teacher
            $authorizedClasses = TeacherAuthorizationHelper::getAuthorizedClasses($teacher);

            // Validate authorization for each row
            foreach ($result->data as $studentData) {
                if (!in_array($studentData['kelas'], $authorizedClasses)) {
                    return redirect()->back()
                        ->withErrors([
                            'import' => "You are not authorized to import students for class: {$studentData['kelas']}"
                        ]);
                }
            }

            // Save imported data to database
            foreach ($result->data as $studentData) {
                Siswa::create([
                    'nama' => $studentData['nama'],
                    'nis' => $studentData['nis'],
                    'kelas' => $studentData['kelas'],
                ]);
            }

            return redirect()->back()
                ->with('success', "Successfully imported {$result->successCount} student(s).");

        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->withErrors(['import' => $e->getMessage()]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['import' => 'An error occurred during import: ' . $e->getMessage()]);
        }
    }

    /**
     * Download Excel template for students
     */
    public function downloadStudentTemplate(): BinaryFileResponse
    {
        $emptyData = collect();
        return $this->excelExportService->exportSiswa($emptyData);
    }
}
```

---

## View Integration

### Using Blade Components

The same Blade components used in admin pages can be reused in teacher dashboards.

#### Sortable Headers

```blade
<x-sortable-header 
    column="nama" 
    label="Nama Siswa" 
    :current-sort="request('sort')" 
    :current-direction="request('direction')" 
/>
```

#### Excel Buttons

```blade
<x-excel-buttons 
    :export-route="route('teacher.students.export')" 
    :import-route="route('teacher.students.import')" 
    :template-route="route('teacher.students.template')" 
/>
```

### Complete View Example

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Students</h2>
        
        <!-- Excel Import/Export Buttons -->
        <x-excel-buttons 
            :export-route="route('teacher.students.export')" 
            :import-route="route('teacher.students.import')" 
            :template-route="route('teacher.students.template')" 
        />
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('teacher.dashboard') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" 
                   placeholder="Search students..." 
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Students Table -->
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
                <tr>
                    <td colspan="3" class="text-center">No students found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $students->links() }}
</div>
@endsection
```

---

## Route Configuration

### Teacher Routes Example

```php
// routes/web.php

use App\Http\Controllers\TeacherController;

Route::middleware(['auth'])->group(function () {
    
    // Teacher Dashboard
    Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])
        ->name('teacher.dashboard');
    
    // Excel Export
    Route::get('/teacher/students/export', [TeacherController::class, 'exportStudents'])
        ->name('teacher.students.export');
    
    // Excel Import
    Route::post('/teacher/students/import', [TeacherController::class, 'importStudents'])
        ->name('teacher.students.import');
    
    // Template Download
    Route::get('/teacher/students/template', [TeacherController::class, 'downloadStudentTemplate'])
        ->name('teacher.students.template');
});
```

---

## Example Implementations

### Example 1: Lecture Teacher - Export Subject Grades

```php
public function exportSubjectGrades(Request $request, string $subjectCode): BinaryFileResponse
{
    $teacher = $request->user();
    
    // Verify teacher teaches this subject
    if (!TeacherAuthorizationHelper::canAccessSubject($teacher, $subjectCode)) {
        abort(403, 'You are not authorized to access this subject');
    }
    
    // Get students and grades for this subject
    $grades = Grade::where('mapel_code', $subjectCode)
        ->whereIn('kelas', TeacherAuthorizationHelper::getAuthorizedClasses($teacher))
        ->with('siswa')
        ->get();
    
    // Transform data for export
    $exportData = $grades->map(function ($grade) {
        return [
            'nama' => $grade->siswa->nama,
            'nis' => $grade->siswa->nis,
            'kelas' => $grade->kelas,
            'nilai' => $grade->nilai,
        ];
    });
    
    // Export with custom filename
    $filename = "Grades_{$subjectCode}_" . date('Y-m-d') . '.xlsx';
    return $this->excelExportService->export($exportData, 'grades', $filename);
}
```

### Example 2: Homeroom Teacher - Export Class Report

```php
public function exportClassReport(Request $request): BinaryFileResponse
{
    $teacher = $request->user();
    
    // Get homeroom class
    $homeroomClass = TeacherAuthorizationHelper::getHomeroomClass($teacher);
    
    if (!$homeroomClass) {
        abort(403, 'You are not assigned as a homeroom teacher');
    }
    
    // Get all students in homeroom class with their grades
    $students = Siswa::where('kelas', $homeroomClass)
        ->with('grades')
        ->get();
    
    // Transform data for comprehensive report
    $reportData = $students->map(function ($student) {
        return [
            'nama' => $student->nama,
            'nis' => $student->nis,
            'kelas' => $student->kelas,
            'rata_rata' => $student->grades->avg('nilai'),
            'jumlah_mapel' => $student->grades->count(),
        ];
    });
    
    $filename = "Class_Report_{$homeroomClass}_" . date('Y-m-d') . '.xlsx';
    return $this->excelExportService->export($reportData, 'class_report', $filename);
}
```

### Example 3: Import with Validation and Authorization

```php
public function importGrades(Request $request, string $subjectCode)
{
    $teacher = $request->user();
    
    // Verify authorization
    if (!TeacherAuthorizationHelper::canAccessSubject($teacher, $subjectCode)) {
        abort(403, 'You are not authorized to import grades for this subject');
    }
    
    // Validate file
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls|max:5120',
    ]);

    try {
        $file = $request->file('file');
        $result = $this->excelImportService->import($file, 'grades');

        if ($result->hasErrors()) {
            return redirect()->back()
                ->withErrors(['import' => $result->getErrorMessage()]);
        }

        $authorizedClasses = TeacherAuthorizationHelper::getAuthorizedClasses($teacher);

        // Validate and save grades
        foreach ($result->data as $gradeData) {
            // Check class authorization
            if (!in_array($gradeData['kelas'], $authorizedClasses)) {
                return redirect()->back()
                    ->withErrors(['import' => "Unauthorized class: {$gradeData['kelas']}"]);
            }
            
            // Verify student exists
            $student = Siswa::where('nis', $gradeData['nis'])->first();
            if (!$student) {
                return redirect()->back()
                    ->withErrors(['import' => "Student not found: {$gradeData['nis']}"]);
            }
            
            // Save or update grade
            Grade::updateOrCreate(
                [
                    'siswa_id' => $student->id,
                    'mapel_code' => $subjectCode,
                ],
                [
                    'nilai' => $gradeData['nilai'],
                    'teacher_id' => $teacher->id,
                ]
            );
        }

        return redirect()->back()
            ->with('success', "Successfully imported {$result->successCount} grade(s).");

    } catch (\Exception $e) {
        return redirect()->back()
            ->withErrors(['import' => 'Import failed: ' . $e->getMessage()]);
    }
}
```

---

## Best Practices

1. **Always Validate Authorization**: Check permissions before any data access
2. **Filter Data Early**: Apply authorization filters at the query level
3. **Provide Clear Error Messages**: Help teachers understand why operations fail
4. **Log Authorization Failures**: Track unauthorized access attempts
5. **Use Transactions**: Wrap import operations in database transactions
6. **Validate Data Ownership**: Ensure imported data belongs to authorized classes
7. **Maintain Audit Trail**: Log who imported/exported what data and when

---

## Security Considerations

1. **Never Trust Client Input**: Always validate and authorize on the server
2. **Prevent Data Leakage**: Ensure queries filter by authorized classes
3. **Validate File Contents**: Check that imported data matches authorization
4. **Rate Limiting**: Consider limiting export/import frequency
5. **File Size Limits**: Enforce maximum file sizes (5MB recommended)
6. **Sanitize Filenames**: Prevent directory traversal attacks

---

## Troubleshooting

### Common Issues

**Issue**: Teacher can see all students instead of only their classes
- **Solution**: Ensure `whereIn('kelas', $authorizedClasses)` is applied to all queries

**Issue**: Import succeeds but saves unauthorized data
- **Solution**: Add authorization validation loop before saving any data

**Issue**: Export includes data from other teachers' classes
- **Solution**: Apply authorization filter before calling export service

**Issue**: Template download fails
- **Solution**: Ensure empty collection is passed to export service

---

## Additional Resources

- [Laravel Query Builder Documentation](https://laravel.com/docs/queries)
- [PhpSpreadsheet Documentation](https://phpspreadsheet.readthedocs.io/)
- [Laravel Authorization Documentation](https://laravel.com/docs/authorization)
