# Quick Start Guide

## Database is Ready! ✓

All tables have been created and populated with sample data.

## Start Using the Application

### 1. Start the Server
```bash
php artisan serve
```

### 2. Login
- URL: http://localhost:8000/login
- User ID: `admin`
- Password: (set during first login)

### 3. Access Admin Pages

**Students:** http://localhost:8000/admin/siswa
- 20 sample students loaded
- Try sorting by clicking column headers
- Try searching for "Ahmad"
- Try exporting to Excel

**Teachers:** http://localhost:8000/admin/guru
- 10 sample teachers loaded
- All features working

**Subjects:** http://localhost:8000/admin/mapel
- 15 sample subjects loaded
- All features working

**Classes:** http://localhost:8000/admin/kelas
- 6 sample classes loaded
- All features working

## Quick Commands

### View Data
```bash
php artisan tinker
>>> App\Models\Siswa::count()  # Should show 20
>>> App\Models\Guru::count()   # Should show 10
>>> App\Models\Mapel::count()  # Should show 15
>>> App\Models\Kelas::count()  # Should show 6
```

### Reset Database (if needed)
```bash
php artisan migrate:fresh --seed
```

### Add More Data
```bash
php artisan db:seed --class=SiswaSeeder
```

## Features Working

✓ Sorting (click column headers)
✓ Searching (use search box)
✓ Pagination (20 per page)
✓ Excel Export (download current view)
✓ Excel Import (upload .xlsx/.xls files)
✓ Template Download (get empty template)

## Sample Data Loaded

- **20 Students** in classes 10A, 10B, 11A, 11B, 12A, 12B
- **10 Teachers** teaching various subjects
- **15 Subjects** (Math, English, Physics, etc.)
- **6 Classes** with homeroom teachers assigned

## Everything is Connected!

All database tables are created, models are configured, controllers are connected, routes are set up, and views are ready. You can start using the application immediately!

For more details, see `DATABASE_SETUP_COMPLETE.md`
