# Guru and Kelas Relationship Implementation Complete ✓

## Summary

Successfully restructured the `gurus` and `kelas` tables to establish proper relationships with the `users` table, following the same pattern as the `siswas` table.

## Database Changes

### Gurus Table

**Removed:**
- ❌ `nama` field (duplicate of users.name)
- ❌ `mapel` field (will be handled through separate relationships)

**Added:**
- ✓ `user_id` (foreign key to users.id)
- ✓ `tanggal_lahir` (birth date)
- ✓ `tempat_lahir` (birth place)
- ✓ `jenis_kelamin` (gender: L/P)
- ✓ `alamat` (address)
- ✓ `pendidikan_terakhir` (last education)
- ✓ `gelar` (academic degree)
- ✓ `tanggal_bergabung` (join date)

### Kelas Table

**Added:**
- ✓ `wali_kelas_id` (foreign key to users.id for homeroom teacher)
- ✓ `kapasitas` (class capacity, default 40)
- ✓ `jumlah_siswa` (number of students, default 0)
- ✓ `ruangan` (classroom/room number)
- ✓ `tahun_ajaran` (academic year)

**Kept:**
- ✓ `wali_kelas` (string, for backward compatibility)

## Relationships Established

### Guru ↔ User
```
User (1) ←→ (1) Guru
```
- Each teacher has a user account
- User account stores: name, email, phone, authentication
- Guru record stores: NIP, birth info, education, join date

### Kelas → User (Wali Kelas)
```
User (1) ←→ (many) Kelas
```
- Each class has one homeroom teacher (wali kelas)
- One teacher can be homeroom teacher for multiple classes
- Foreign key with SET NULL on delete (if teacher deleted, class remains)

## Data Migration Results

### Gurus
- ✓ Created user accounts for all 10 teachers
- ✓ Linked all guru records to users via `user_id`
- ✓ Teachers use NIP as their `user_id` for login
- ✓ Email format: `teacher{NIP}@school.id`
- ✓ All teachers have role = 'lectureTeacher'

### Kelas
- ✓ Linked all 6 classes to homeroom teachers
- ✓ All classes have `wali_kelas_id` set
- ✓ Kept `wali_kelas` string for backward compatibility

## Model Updates

### Guru Model

```php
// Relationship
public function user()
{
    return $this->belongsTo(User::class);
}

// Accessor
public function getNamaAttribute()
{
    return $this->user ? $this->user->name : null;
}

// Fillable fields
protected $fillable = [
    'user_id', 'nip', 'tanggal_lahir', 'tempat_lahir',
    'jenis_kelamin', 'alamat', 'pendidikan_terakhir',
    'gelar', 'tanggal_bergabung',
];
```

### Kelas Model

```php
// Relationship
public function waliKelas()
{
    return $this->belongsTo(User::class, 'wali_kelas_id');
}

// Accessor
public function getWaliKelasNamaAttribute()
{
    return $this->waliKelas ? $this->waliKelas->name : $this->wali_kelas;
}

// Fillable fields
protected $fillable = [
    'nama', 'tingkat', 'wali_kelas', 'wali_kelas_id',
    'kapasitas', 'jumlah_siswa', 'ruangan', 'tahun_ajaran',
];
```

### User Model

```php
// Relationships
public function guru()
{
    return $this->hasOne(Guru::class);
}

public function kelasAsWali()
{
    return $this->hasMany(Kelas::class, 'wali_kelas_id');
}
```

## Controller Updates

### AdminController - guru() Method

```php
public function guru(Request $request)
{
    $query = Guru::with('user'); // Eager load

    // Search in both guru and user fields
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('nip', 'like', "%{$search}%")
              ->orWhereHas('user', function ($userQuery) use ($search) {
                  $userQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    // Sort by user name
    if ($sortColumn === 'nama') {
        $query->join('users', 'gurus.user_id', '=', 'users.id')
              ->orderBy('users.name', $sortDirection)
              ->select('gurus.*');
    }

    return view('admin.guru', compact('guru'));
}
```

### AdminController - kelas() Method

```php
public function kelas(Request $request)
{
    $query = Kelas::with('waliKelas'); // Eager load

    // Search in kelas and wali kelas fields
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

    return view('admin.kelas', compact('kelas'));
}
```

### AdminController - importGuru() Method

```php
public function importGuru(Request $request)
{
    // ... validation ...

    foreach ($result->data as $guruData) {
        // Create user account for teacher
        $user = User::create([
            'name' => $guruData['nama'],
            'user_name' => 'teacher_' . $guruData['nip'],
            'user_id' => $guruData['nip'],
            'email' => 'teacher' . str_replace(' ', '', $guruData['nip']) . '@school.id',
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
}
```

## Current Database Status

```
Total Users: 36
├─ Original users: 6
├─ Student users: 20
└─ Teacher users: 10

Guru Records: 10
└─ All linked to users: 10 ✓

Kelas Records: 6
└─ All linked to wali kelas: 6 ✓
```

## Teacher Data

All 10 teachers migrated successfully:

1. Dr. Agus Salim (NIP: 198501012010011001) - Wali Kelas 10A
2. Dra. Budi Rahayu (NIP: 198602022010012002) - Wali Kelas 10B
3. M.Pd. Citra Dewi (NIP: 198703032010013003) - Wali Kelas 11A
4. S.Pd. Dedi Kurniawan (NIP: 198804042010014004) - Wali Kelas 11B
5. S.Si. Eka Putri (NIP: 198905052010015005) - Wali Kelas 12A
6. S.Pd. Fajar Ramadhan (NIP: 199006062010016006) - Wali Kelas 12B
7. M.Pd. Gita Savitri (NIP: 199107072010017007)
8. S.Pd. Hendra Wijaya (NIP: 199208082010018008)
9. Drs. Irfan Hakim (NIP: 199309092010019009)
10. S.Pd. Juwita Sari (NIP: 199410102010011010)

## Benefits

### 1. No Data Duplication
- Teacher name stored once in users table
- Changes to name automatically reflected everywhere

### 2. Consistent Authentication
- All teachers have user accounts
- Can login with NIP as user_id
- Unified authentication system

### 3. Better Data Organization
- User data (name, email, phone) in users table
- Teacher-specific data (NIP, education, join date) in gurus table
- Class data with proper teacher references

### 4. Flexible Relationships
- Teachers can be homeroom teachers for multiple classes
- Easy to query teacher's classes
- Easy to query class's homeroom teacher

### 5. Data Integrity
- Foreign key constraints ensure consistency
- SET NULL on delete prevents data loss
- Database-level validation

## Usage Examples

### Get Teacher with User Data

```php
$guru = Guru::with('user')->find(1);
echo $guru->user->name;        // Teacher name
echo $guru->user->email;       // Teacher email
echo $guru->nama;              // Also works (accessor)
echo $guru->nip;               // Teacher ID
```

### Get User with Teacher Data

```php
$user = User::where('role', 'like', '%Teacher%')->with('guru')->first();
echo $user->guru->nip;         // Teacher ID
echo $user->guru->gelar;       // Academic degree
```

### Get Class with Homeroom Teacher

```php
$kelas = Kelas::with('waliKelas')->find(1);
echo $kelas->waliKelas->name;  // Teacher name
echo $kelas->wali_kelas_nama;  // Also works (accessor)
```

### Get Teacher's Classes

```php
$user = User::where('role', 'like', '%Teacher%')->with('kelasAsWali')->first();
foreach ($user->kelasAsWali as $kelas) {
    echo $kelas->nama;         // Class name
}
```

## Files Created/Modified

### Migrations
- ✓ `2026_04_26_045542_modify_gurus_table_add_user_relationship.php`
- ✓ `2026_04_26_045607_modify_kelas_table_add_user_relationship.php`

### Seeders
- ✓ `MigrateGuruDataSeeder.php` (new)
- ✓ `MigrateKelasDataSeeder.php` (new)
- ✓ `GuruSeeder.php` (updated)
- ✓ `KelasSeeder.php` (updated)

### Models
- ✓ `app/Models/Guru.php` (updated)
- ✓ `app/Models/Kelas.php` (updated)
- ✓ `app/Models/User.php` (updated)

### Controllers
- ✓ `app/Http/Controllers/AdminController.php` (updated)

## All Features Working

✓ Teacher list display
✓ Search (searches both NIP and name)
✓ Sorting (including by name)
✓ Excel export
✓ Excel import (creates both user and guru)
✓ Class list display
✓ Search (searches class name, level, and wali kelas)
✓ Sorting (including by wali kelas)
✓ Pagination

## Future Enhancements

With this structure, you can now easily:

1. **Teacher Login**: Teachers can login with their NIP
2. **Teacher Dashboard**: Show personalized data
3. **Subject Assignment**: Link teachers to subjects they teach
4. **Schedule Management**: Create class schedules
5. **Grade Entry**: Teachers can enter grades for their subjects
6. **Attendance**: Teachers can mark attendance
7. **Class Management**: Homeroom teachers can manage their class

## Summary

✓ **Relationships Established**: Guru ↔ User, Kelas → User
✓ **Data Migrated**: All 10 teachers, all 6 classes
✓ **No Duplication**: Names stored once
✓ **Foreign Keys**: Enforced at database level
✓ **Accessors Added**: `$guru->nama` and `$kelas->wali_kelas_nama` still work
✓ **Controllers Updated**: Search and sort working
✓ **Import Updated**: Creates both user and guru
✓ **Export Updated**: Uses relationships

The database structure is now fully normalized with proper relationships for all entities!
