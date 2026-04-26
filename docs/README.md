# E-RAPOR Documentation

## Overview

This directory contains documentation for the E-RAPOR system, specifically for the Excel import/export and sorting functionality.

## Available Documentation

### 1. Teacher Excel Integration Guide
**File:** `teacher-excel-integration.md`

**Purpose:** Comprehensive guide for implementing Excel import/export functionality in teacher dashboards.

**Contents:**
- Service usage examples (ExcelExportService, ExcelImportService)
- Authorization requirements for teachers
- Controller implementation patterns
- View integration with Blade components
- Route configuration
- Complete working examples for lecture and homeroom teachers
- Best practices and security considerations

**Target Audience:** Developers implementing teacher dashboard features

---

### 2. Teacher Authorization Implementation Guide
**File:** `teacher-authorization-implementation.md`

**Purpose:** Step-by-step guide for implementing the teacher authorization system with database structure.

**Contents:**
- Database schema design for teacher assignments
- Migration examples
- Model creation and relationships
- Updating TeacherAuthorizationHelper with real queries
- Seeder examples for testing
- Admin interface for managing assignments
- Unit and feature test examples
- Troubleshooting guide

**Target Audience:** Developers setting up the teacher assignment system

---

## Quick Start

### For Teacher Dashboard Implementation

1. **Read:** `teacher-excel-integration.md`
2. **Ensure:** Teacher authorization system is implemented (see step 3)
3. **Implement:** Authorization database structure from `teacher-authorization-implementation.md`
4. **Use:** The provided controller examples to add Excel functionality
5. **Test:** Using the provided test examples

### For Authorization System Setup

1. **Read:** `teacher-authorization-implementation.md`
2. **Create:** Database migrations for teacher assignments
3. **Update:** `TeacherAuthorizationHelper` with database queries
4. **Seed:** Test data using the provided seeder
5. **Test:** Authorization logic with unit tests

---

## Key Components

### Services

- **ExcelExportService** (`app/Services/ExcelExportService.php`)
  - Handles exporting data to Excel files
  - Supports template-based formatting
  - Methods: `export()`, `exportSiswa()`, `exportGuru()`, etc.

- **ExcelImportService** (`app/Services/ExcelImportService.php`)
  - Handles parsing and validating Excel imports
  - Returns `ImportResult` with validation details
  - Method: `import()`

- **ExcelTemplateService** (`app/Services/ExcelTemplateService.php`)
  - Manages Excel template configurations
  - Provides column mappings and validation rules

### Helpers

- **TeacherAuthorizationHelper** (`app/Helpers/TeacherAuthorizationHelper.php`)
  - Centralized authorization logic for teachers
  - Methods for checking class/subject access
  - Query builders for authorized data
  - Validation helpers for import/export operations

### Blade Components

- **sortable-header** (`resources/views/components/sortable-header.blade.php`)
  - Renders sortable table headers with indicators
  - Maintains sort state in URL parameters

- **excel-buttons** (`resources/views/components/excel-buttons.blade.php`)
  - Renders import/export/template buttons
  - Includes file upload form

---

## Authorization Flow

```
User Request
    ↓
Controller (Check Authentication)
    ↓
TeacherAuthorizationHelper (Check Permissions)
    ↓
Query with Authorization Filter
    ↓
ExcelExportService / ExcelImportService
    ↓
Response
```

---

## Common Use Cases

### Export Student Data (Teacher)

```php
$teacher = $request->user();
$authorizedClasses = TeacherAuthorizationHelper::getAuthorizedClasses($teacher);
$students = Siswa::whereIn('kelas', $authorizedClasses)->get();
return $this->excelExportService->exportSiswa($students);
```

### Import with Authorization Check

```php
$result = $this->excelImportService->import($file, 'siswa');
$unauthorizedClasses = TeacherAuthorizationHelper::validateStudentAuthorization(
    $teacher,
    $result->data
);
if (!empty($unauthorizedClasses)) {
    return redirect()->back()->withErrors(['import' => 'Unauthorized classes']);
}
```

### Check Class Access

```php
if (!TeacherAuthorizationHelper::canAccessClass($teacher, $className)) {
    abort(403, 'Unauthorized access');
}
```

---

## Configuration

### Excel Templates

Templates are configured in `config/excel-templates.php`:

```php
'siswa' => [
    'columns' => ['nama' => 'Nama Siswa', 'nis' => 'NIS', ...],
    'required' => ['nama', 'nis'],
    'validation' => ['nis' => 'numeric|digits:10'],
    'header_style' => [...]
]
```

### Academic Year

Set in `.env`:

```
CURRENT_ACADEMIC_YEAR=2024/2025
```

---

## Testing

### Run All Tests

```bash
php artisan test
```

### Run Specific Test Suite

```bash
php artisan test --filter TeacherAuthorizationHelperTest
```

### Manual Testing Checklist

- [ ] Teacher can export only authorized classes
- [ ] Teacher cannot export unauthorized classes
- [ ] Import validates class authorization
- [ ] Sortable headers work correctly
- [ ] Excel buttons render properly
- [ ] Template download works
- [ ] Error messages are clear and helpful

---

## Troubleshooting

### Teacher sees no data

**Solution:** Check if teacher has active assignments for current academic year

```php
$summary = TeacherAuthorizationHelper::getAuthorizationSummary($teacher);
dd($summary);
```

### Import fails with authorization error

**Solution:** Verify class names in Excel match database exactly (case-sensitive)

### Export returns empty file

**Solution:** Ensure teacher has at least one class assignment

---

## Future Enhancements

1. **Bulk Assignment Management**
   - Excel import for teacher assignments
   - Bulk update/delete operations

2. **Advanced Filtering**
   - Filter by subject, grade level, semester
   - Custom export templates per teacher

3. **Audit Logging**
   - Track all import/export operations
   - Monitor authorization failures

4. **Performance Optimization**
   - Cache teacher assignments
   - Optimize queries for large datasets

---

## Support

For questions or issues:

1. Check the relevant documentation file
2. Review the code examples
3. Run the diagnostic commands
4. Check the troubleshooting section

---

## Version History

- **v1.0** - Initial documentation
  - Teacher Excel integration guide
  - Authorization implementation guide
  - TeacherAuthorizationHelper class
