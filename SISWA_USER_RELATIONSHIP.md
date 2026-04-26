# Siswa-User Relationship Implementation ✓

## Overview

The `siswas` table has been restructured to establish a proper relationship with the `users` table. This eliminates data duplication and creates a clean separation between authentication/user data and student-specific data.

## Database Structure

### Before (Duplicate Data)
```
siswas table:
- id
- nama (duplicate of users.name)
- nis
- kelas
- timestamps
```

### After (Normalized with Relationship)
```
siswas table:
- id
- user_id (foreign key to users.id)
- nis (unique student identifier)
- nisn (national student ID)
- kelas (class assignment)
- tanggal_lahir (birth date)
- tempat_lahir (birth place)
- jenis_kelamin (gender: L/P)
- alamat (address)
- nama_wali (guardian name)
- telepon_wali (guardian phone)
- timestamps

users table:
- id
- name (student's name)
- user_name (login username)
- user_id (NIS for students)
- email
- phone_number
- role ('student' for students)
- password_set
- password
- timestamps
```

## Key Changes

### 1. Removed Duplicate Fields
- ❌ Removed `nama` from `siswas` table
- ✓ Now uses `users.name` through relationship

### 2. Added Foreign Key
- ✓ Added `user_id` column in `siswas` table
- ✓ Foreign key constraint to `users.id`
- ✓ Cascade delete (if user is deleted, siswa record is also deleted)

### 3. Added Student-Specific Fields
- `nisn` - National Student ID Number
- `tanggal_lahir` - Birth date
- `tempat_lahir` - Birth place
- `jenis_kelamin` - Gender (L = Laki-laki, P = Perempuan)
- `alamat` - Address
- `nama_wali` - Guardian name
- `telepon_wali` - Guardian phone number

## Model Relationships

### Siswa Model

```php
// Get the user that owns the siswa record
public function user()
{
    return $this->belongsTo(User::class);
}

// Get student's name from related user (accessor)
public function getNamaAttribute()
{
    return $this->user ? $this->user->name : null;
}
```

### User Model

```php
// Get the siswa record for this user (if they are a student)
public function siswa()
{
    return $this->hasOne(Siswa::class);
}

// Check if user is a student
public function isStudent(): bool
{
    return $this->hasRole('student');
}
```

## Usage Examples

### Get Student with User Data

```php
// Eager load user relationship
$siswa = Siswa::with('user')->find(1);

// Access user data
echo $siswa->user->name;        // Student name
echo $siswa->user->email;       // Student email
echo $siswa->nama;              // Also works (accessor)

// Access siswa data
echo $siswa->nis;               // Student ID
echo $siswa->kelas;             // Class
```

### Get User with Siswa Data

```php
// Get a student user
$user = User::where('role', 'student')->with('siswa')->first();

// Access siswa data
echo $user->siswa->nis;         // Student ID
echo $user->siswa->kelas;       // Class
```

### Query with Relationships

```php
// Search by student name (in users table)
$students = Siswa::whereHas('user', function ($query) {
    $query->where('name', 'like', '%Ahmad%');
})->get();

// Get all students in a class with their user data
$students = Siswa::with('user')
    ->where('kelas', '10A')
    ->get();
```

## Controller Updates

### AdminController - siswa() Method

```php
public function siswa(Request $request)
{
    $query = Siswa::with('user'); // Eager load user relationship

    // Search in both siswa and user fields
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

    // Sort by user name
    if ($sortColumn === 'nama') {
        $query->join('users', 'siswas.user_id', '=', 'users.id')
              ->orderBy('users.name', $sortDirection)
              ->select('siswas.*');
    }

    $siswa = $query->paginate(20);
    return view('admin.siswa', compact('siswa'));
}
```

### AdminController - importSiswa() Method

```php
public function importSiswa(Request $request)
{
    // ... validation ...

    foreach ($result->data as $siswaData) {
        // Create user account for student
        $user = User::create([
            'name' => $siswaData['nama'],
            'user_name' => 'student_' . $siswaData['nis'],
            'user_id' => $siswaData['nis'],
            'email' => 'student' . $siswaData['nis'] . '@school.id',
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
}
```

## Data Migration

All existing student data has been migrated:

1. ✓ Created user accounts for all 20 students
2. ✓ Linked siswa records to user accounts via `user_id`
3. ✓ Removed duplicate `nama` field from siswas table
4. ✓ All relationships working correctly

### Migration Details

- **Migration File**: `2026_04_26_044906_modify_siswas_table_add_user_relationship.php`
- **Data Seeder**: `MigrateStudentDataSeeder.php`
- **Records Migrated**: 20 students

## Benefits

### 1. No Data Duplication
- Student name stored once in `users` table
- Changes to name automatically reflected everywhere

### 2. Consistent Authentication
- All students have user accounts
- Can login with NIS as user_id
- Unified authentication system

### 3. Better Data Organization
- User data (name, email, phone) in `users` table
- Student-specific data (NIS, class, birth info) in `siswas` table
- Clear separation of concerns

### 4. Easier Queries
- Can query across both tables easily
- Eager loading prevents N+1 queries
- Relationships make code cleaner

### 5. Data Integrity
- Foreign key constraints ensure consistency
- Cascade delete prevents orphaned records
- Database-level validation

## View Updates Needed

The views need to be updated to use the relationship:

### Before
```blade
<td>{{ $siswa->nama }}</td>
```

### After
```blade
<td>{{ $siswa->nama }}</td>  <!-- Still works (accessor) -->
<!-- OR -->
<td>{{ $siswa->user->name }}</td>  <!-- Direct access -->
```

## Excel Template Updates

The Excel import template still uses `nama` column, which is handled in the import process:

1. Excel has `nama` column
2. Import creates User with that name
3. Siswa record links to User via `user_id`
4. Export shows `nama` via accessor

## Testing

All relationships have been tested and verified:

```
✓ Siswa → User relationship working
✓ User → Siswa relationship working
✓ All 20 siswas have user accounts
✓ No orphaned records
✓ Accessor for nama working
✓ Search across relationships working
✓ Sorting by user name working
```

## Future Enhancements

With this structure, you can now easily:

1. **Student Login**: Students can login with their NIS
2. **Student Dashboard**: Show personalized data
3. **Grade Management**: Link grades to user accounts
4. **Attendance**: Track attendance by user
5. **Parent Portal**: Link parents to student users
6. **Notifications**: Send emails to students
7. **Profile Management**: Students can update their info

## Rollback

If needed, you can rollback the migration:

```bash
php artisan migrate:rollback --step=1
```

This will:
- Remove new fields from siswas table
- Add back nama field
- Remove user_id foreign key

Note: User accounts created for students will remain in the users table.

## Summary

✓ **Relationship Established**: Siswa ↔ User
✓ **Data Migrated**: All 20 students
✓ **No Duplication**: Name stored once
✓ **Foreign Key**: Enforced at database level
✓ **Accessor Added**: `$siswa->nama` still works
✓ **Controllers Updated**: Search and sort working
✓ **Import Updated**: Creates both user and siswa
✓ **Export Updated**: Uses relationship

The database structure is now properly normalized and ready for production use!
