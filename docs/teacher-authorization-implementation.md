# Teacher Authorization Implementation Guide

## Overview

This document explains how to implement the teacher authorization system for the E-RAPOR application. The `TeacherAuthorizationHelper` class provides reusable methods for checking teacher permissions, but it requires a database structure to store teacher-class assignments.

## Current Status

The `TeacherAuthorizationHelper` class has been created with placeholder implementations. The following methods return empty/null values until the database structure is implemented:

- `getHomeroomClass()` - Returns null
- `getLectureClasses()` - Returns empty array
- `getAuthorizedSubjects()` - Returns empty array

## Required Database Structure

### Option 1: Single Teacher Assignments Table (Recommended)

Create a `teacher_assignments` table to store all teacher-class and teacher-subject relationships:

```php
// database/migrations/xxxx_create_teacher_assignments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('assignment_type'); // 'homeroom' or 'lecture'
            $table->string('class_name')->nullable(); // e.g., '10A', '11B'
            $table->string('subject_code')->nullable(); // e.g., 'MAT', 'ENG'
            $table->string('academic_year'); // e.g., '2024/2025'
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['teacher_id', 'assignment_type']);
            $table->index(['class_name', 'subject_code']);
            $table->index('academic_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_assignments');
    }
};
```

### Option 2: Separate Tables

Alternatively, create separate tables for different assignment types:

```php
// Homeroom assignments
Schema::create('homeroom_assignments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
    $table->string('class_name');
    $table->string('academic_year');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    // One teacher per class per year
    $table->unique(['class_name', 'academic_year']);
});

// Lecture assignments
Schema::create('lecture_assignments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
    $table->string('class_name');
    $table->string('subject_code');
    $table->string('academic_year');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    // One teacher per subject per class per year
    $table->unique(['class_name', 'subject_code', 'academic_year']);
});
```

## Implementation Steps

### Step 1: Create the Migration

Choose one of the database structure options above and create the migration:

```bash
php artisan make:migration create_teacher_assignments_table
```

### Step 2: Create the Model

```php
// app/Models/TeacherAssignment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAssignment extends Model
{
    protected $fillable = [
        'teacher_id',
        'assignment_type',
        'class_name',
        'subject_code',
        'academic_year',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the teacher for this assignment
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Scope to get only active assignments
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get assignments for current academic year
     */
    public function scopeCurrentYear($query)
    {
        $currentYear = config('app.current_academic_year', date('Y') . '/' . (date('Y') + 1));
        return $query->where('academic_year', $currentYear);
    }

    /**
     * Scope to get homeroom assignments
     */
    public function scopeHomeroom($query)
    {
        return $query->where('assignment_type', 'homeroom');
    }

    /**
     * Scope to get lecture assignments
     */
    public function scopeLecture($query)
    {
        return $query->where('assignment_type', 'lecture');
    }
}
```

### Step 3: Update User Model

Add relationships to the User model:

```php
// app/Models/User.php

/**
 * Get all teacher assignments
 */
public function teacherAssignments()
{
    return $this->hasMany(TeacherAssignment::class, 'teacher_id');
}

/**
 * Get active teacher assignments for current year
 */
public function activeAssignments()
{
    return $this->teacherAssignments()
        ->active()
        ->currentYear();
}

/**
 * Get homeroom assignment
 */
public function homeroomAssignment()
{
    return $this->activeAssignments()
        ->homeroom()
        ->first();
}

/**
 * Get lecture assignments
 */
public function lectureAssignments()
{
    return $this->activeAssignments()
        ->lecture()
        ->get();
}
```

### Step 4: Update TeacherAuthorizationHelper

Replace the placeholder implementations in `app/Helpers/TeacherAuthorizationHelper.php`:

```php
/**
 * Get the homeroom class assigned to a homeroom teacher
 */
public static function getHomeroomClass(User $teacher): ?string
{
    if (!$teacher->hasRole('homeroomTeacher')) {
        return null;
    }

    return TeacherAssignment::where('teacher_id', $teacher->id)
        ->where('assignment_type', 'homeroom')
        ->active()
        ->currentYear()
        ->value('class_name');
}

/**
 * Get all classes a lecture teacher teaches
 */
public static function getLectureClasses(User $teacher): array
{
    if (!$teacher->hasRole('lectureTeacher')) {
        return [];
    }

    return TeacherAssignment::where('teacher_id', $teacher->id)
        ->where('assignment_type', 'lecture')
        ->active()
        ->currentYear()
        ->distinct()
        ->pluck('class_name')
        ->toArray();
}

/**
 * Get all subjects a teacher is authorized to teach
 */
public static function getAuthorizedSubjects(User $teacher): array
{
    if (!$teacher->hasRole('lectureTeacher')) {
        return [];
    }

    return TeacherAssignment::where('teacher_id', $teacher->id)
        ->where('assignment_type', 'lecture')
        ->active()
        ->currentYear()
        ->whereNotNull('subject_code')
        ->distinct()
        ->pluck('subject_code')
        ->toArray();
}
```

### Step 5: Create Seeder for Testing

```php
// database/seeders/TeacherAssignmentSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TeacherAssignment;
use Illuminate\Database\Seeder;

class TeacherAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $currentYear = date('Y') . '/' . (date('Y') + 1);

        // Example: Assign homeroom teacher to class 10A
        $homeroomTeacher = User::where('role', 'like', '%homeroomTeacher%')->first();
        if ($homeroomTeacher) {
            TeacherAssignment::create([
                'teacher_id' => $homeroomTeacher->id,
                'assignment_type' => 'homeroom',
                'class_name' => '10A',
                'academic_year' => $currentYear,
                'is_active' => true,
            ]);
        }

        // Example: Assign lecture teacher to teach Math in multiple classes
        $lectureTeacher = User::where('role', 'like', '%lectureTeacher%')->first();
        if ($lectureTeacher) {
            $classes = ['10A', '10B', '11A'];
            foreach ($classes as $class) {
                TeacherAssignment::create([
                    'teacher_id' => $lectureTeacher->id,
                    'assignment_type' => 'lecture',
                    'class_name' => $class,
                    'subject_code' => 'MAT',
                    'academic_year' => $currentYear,
                    'is_active' => true,
                ]);
            }
        }

        // Example: Dual role teacher
        $dualTeacher = User::where('role', 'lectureTeacher,homeroomTeacher')->first();
        if ($dualTeacher) {
            // Homeroom assignment
            TeacherAssignment::create([
                'teacher_id' => $dualTeacher->id,
                'assignment_type' => 'homeroom',
                'class_name' => '11B',
                'academic_year' => $currentYear,
                'is_active' => true,
            ]);

            // Lecture assignments
            TeacherAssignment::create([
                'teacher_id' => $dualTeacher->id,
                'assignment_type' => 'lecture',
                'class_name' => '11B',
                'subject_code' => 'ENG',
                'academic_year' => $currentYear,
                'is_active' => true,
            ]);
        }
    }
}
```

### Step 6: Create Admin Interface for Managing Assignments

Create a controller to manage teacher assignments:

```php
// app/Http/Controllers/Admin/TeacherAssignmentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherAssignmentController extends Controller
{
    public function index()
    {
        $assignments = TeacherAssignment::with('teacher')
            ->currentYear()
            ->active()
            ->paginate(20);

        return view('admin.teacher-assignments.index', compact('assignments'));
    }

    public function create()
    {
        $teachers = User::where(function ($query) {
            $query->where('role', 'like', '%lectureTeacher%')
                  ->orWhere('role', 'like', '%homeroomTeacher%');
        })->get();

        return view('admin.teacher-assignments.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'assignment_type' => 'required|in:homeroom,lecture',
            'class_name' => 'required|string',
            'subject_code' => 'nullable|string',
            'academic_year' => 'required|string',
        ]);

        TeacherAssignment::create($validated);

        return redirect()->route('admin.teacher-assignments.index')
            ->with('success', 'Teacher assignment created successfully.');
    }

    public function destroy(TeacherAssignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('admin.teacher-assignments.index')
            ->with('success', 'Teacher assignment deleted successfully.');
    }
}
```

## Configuration

### Academic Year Configuration

Add the current academic year to your config:

```php
// config/app.php

'current_academic_year' => env('CURRENT_ACADEMIC_YEAR', '2024/2025'),
```

And in `.env`:

```
CURRENT_ACADEMIC_YEAR=2024/2025
```

## Testing the Implementation

### Unit Tests

```php
// tests/Unit/TeacherAuthorizationHelperTest.php

namespace Tests\Unit;

use App\Helpers\TeacherAuthorizationHelper;
use App\Models\User;
use App\Models\TeacherAssignment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherAuthorizationHelperTest extends TestCase
{
    use RefreshDatabase;

    public function test_homeroom_teacher_gets_correct_class()
    {
        $teacher = User::factory()->create(['role' => 'homeroomTeacher']);
        
        TeacherAssignment::create([
            'teacher_id' => $teacher->id,
            'assignment_type' => 'homeroom',
            'class_name' => '10A',
            'academic_year' => '2024/2025',
            'is_active' => true,
        ]);

        $class = TeacherAuthorizationHelper::getHomeroomClass($teacher);
        
        $this->assertEquals('10A', $class);
    }

    public function test_lecture_teacher_gets_correct_classes()
    {
        $teacher = User::factory()->create(['role' => 'lectureTeacher']);
        
        TeacherAssignment::create([
            'teacher_id' => $teacher->id,
            'assignment_type' => 'lecture',
            'class_name' => '10A',
            'subject_code' => 'MAT',
            'academic_year' => '2024/2025',
            'is_active' => true,
        ]);

        TeacherAssignment::create([
            'teacher_id' => $teacher->id,
            'assignment_type' => 'lecture',
            'class_name' => '10B',
            'subject_code' => 'MAT',
            'academic_year' => '2024/2025',
            'is_active' => true,
        ]);

        $classes = TeacherAuthorizationHelper::getLectureClasses($teacher);
        
        $this->assertCount(2, $classes);
        $this->assertContains('10A', $classes);
        $this->assertContains('10B', $classes);
    }

    public function test_can_access_class_returns_true_for_authorized_class()
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
}
```

### Feature Tests

```php
// tests/Feature/TeacherExcelExportTest.php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\TeacherAssignment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherExcelExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_export_authorized_students()
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
        Siswa::factory()->create(['kelas' => '10B']);

        $response = $this->actingAs($teacher)
            ->get(route('teacher.students.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_teacher_cannot_export_unauthorized_students()
    {
        $teacher = User::factory()->create(['role' => 'homeroomTeacher']);
        
        // No assignments for this teacher
        
        $response = $this->actingAs($teacher)
            ->get(route('teacher.students.export'));

        // Should return empty file or error
        $response->assertStatus(200);
    }
}
```

## Usage Examples

### Check Authorization Before Export

```php
public function exportStudents(Request $request)
{
    $teacher = $request->user();
    
    if (!TeacherAuthorizationHelper::canPerformBulkOperations($teacher)) {
        return redirect()->back()
            ->withErrors(['error' => 'You do not have any class assignments.']);
    }
    
    $students = TeacherAuthorizationHelper::getAuthorizedStudentsQuery($teacher)->get();
    
    return $this->excelExportService->exportSiswa($students);
}
```

### Validate Import Authorization

```php
public function importStudents(Request $request)
{
    $teacher = $request->user();
    $file = $request->file('file');
    
    $result = $this->excelImportService->import($file, 'siswa');
    
    if ($result->hasErrors()) {
        return redirect()->back()->withErrors(['import' => $result->getErrorMessage()]);
    }
    
    // Validate authorization
    $unauthorizedClasses = TeacherAuthorizationHelper::validateStudentAuthorization(
        $teacher,
        $result->data
    );
    
    if (!empty($unauthorizedClasses)) {
        return redirect()->back()->withErrors([
            'import' => 'You are not authorized to import students for classes: ' . 
                       implode(', ', $unauthorizedClasses)
        ]);
    }
    
    // Save data...
}
```

## Maintenance

### Updating Academic Year

At the start of each academic year:

1. Update the `CURRENT_ACADEMIC_YEAR` in `.env`
2. Optionally deactivate old assignments:

```php
TeacherAssignment::where('academic_year', '2023/2024')
    ->update(['is_active' => false]);
```

3. Create new assignments for the new year

### Bulk Assignment Import

Create an Excel template for bulk importing teacher assignments:

```php
public function importAssignments(Request $request)
{
    $file = $request->file('file');
    $result = $this->excelImportService->import($file, 'teacher_assignments');
    
    foreach ($result->data as $assignmentData) {
        TeacherAssignment::create($assignmentData);
    }
    
    return redirect()->back()
        ->with('success', "Imported {$result->successCount} assignments.");
}
```

## Troubleshooting

### Issue: Teacher sees no data

**Check:**
1. Does the teacher have active assignments?
2. Is the academic year correct?
3. Are the assignments marked as active?

**Debug:**
```php
$summary = TeacherAuthorizationHelper::getAuthorizationSummary($teacher);
dd($summary);
```

### Issue: Authorization check fails

**Check:**
1. Is the teacher role correctly set?
2. Are class names consistent (case-sensitive)?
3. Is the TeacherAssignment model imported?

## Next Steps

1. Run migrations: `php artisan migrate`
2. Update TeacherAuthorizationHelper with database queries
3. Create admin interface for managing assignments
4. Seed test data: `php artisan db:seed --class=TeacherAssignmentSeeder`
5. Test authorization in teacher controllers
6. Implement teacher dashboard with Excel functionality
