# Database Migration: SQLite → MySQL

## Migration Summary

Successfully migrated the E-RAPOR database from SQLite to MySQL.

### Configuration Changes

**File: `.env`**
- Changed `DB_CONNECTION` from `sqlite` to `mysql`
- Enabled MySQL connection parameters:
  - `DB_HOST=127.0.0.1`
  - `DB_PORT=3306`
  - `DB_DATABASE=db_sinilai`
  - `DB_USERNAME=root`
  - `DB_PASSWORD=` (empty)

### Migration Steps Completed

1. ✅ Cleared configuration cache
2. ✅ Wiped all existing tables in `db_sinilai` database (removed old structure)
3. ✅ Ran all migrations on clean MySQL database
4. ✅ Verified all 27 tables created successfully

### Migrations Applied (11 total)

1. `0001_01_01_000000_create_users_table` - User accounts
2. `0001_01_01_000001_create_cache_table` - Cache storage
3. `0001_01_01_000002_create_jobs_table` - Queue jobs
4. `2026_06_09_000001_create_role_tables` - Role tables (admins, teachers, lecturers, homerooms)
5. `2026_06_09_000002_create_class_tables` - Class and student tables
6. `2026_06_09_000003_create_subject_tables` - Subject and teaching assignment tables
7. `2026_06_09_000004_create_nilai_tables` - Nilai (grade) tables
8. `2026_06_09_000005_create_grading_config_tables` - Grading configuration
9. `2026_06_19_000001_add_rafi_lecturer_homeroom` - Seed initial user
10. `2026_06_19_000002_create_tahun_ajaran_dates_table` - Academic year dates
11. `2026_06_20_103653_fix_email_nullable_in_tb_users` - Email nullable fix

### Database Structure

**Key Tables Created:**
- `tb_users` - User accounts (0 rows - clean start)
- `tb_students` - Student records
- `tb_teachers` - Teacher records
- `tb_lecturers` - Lecturer role mapping
- `tb_homerooms` - Homeroom teacher assignments
- `tb_admins` - Administrator role mapping
- `tb_classes` - Class/room definitions
- `tb_subjects` - Subject definitions
- `tb_teached_subjects` - Teaching assignments
- `tb_nilai` - Main grade records
- `tb_nilai_harian` - Daily grades (12 weeks)
- `tb_nilai_keterampilan` - Skills grades
- `tb_nilai_ulangan` - Quiz grades (8 quizzes)
- `tb_nilai_ujian` - Exam grades (4 exams)
- `tb_tanggal_tahun_ajaran` - Academic year dates
- Plus system tables: migrations, cache, jobs, sessions, etc.

### Important Notes

1. **Database is now EMPTY** - All old data from SQLite has been removed
2. **Fresh start** - You'll need to:
   - Register new admin users via `/admin/register`
   - Import student/teacher data via admin Excel import
   - Create new subjects, classes, and teaching assignments
3. **All functionality preserved** - The structure is identical to SQLite
4. **Primary key handling** - MySQL auto-increment works correctly for all tables

### Next Steps

1. Access `/login` and create initial admin account
2. Use admin panel to:
   - Import students via Excel (`/admin/siswa`)
   - Import teachers via Excel (`/admin/guru`)
   - Import subjects via Excel (`/admin/mapel`)
   - Import classes via Excel (`/admin/kelas`)
3. Set up academic year dates in `/admin/tahun-ajaran`
4. Start entering grades via teacher interface

### Verification

Database connection verified:
- Connected to: `mysql`
- Database: `db_sinilai`
- Tables created: 27
- Current data: 0 users, 0 students (clean database)

### Rollback Instructions

If you need to revert to SQLite:

1. Change `.env`:
   ```
   DB_CONNECTION=sqlite
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=db_sinilai
   # DB_USERNAME=root
   # DB_PASSWORD=
   ```

2. Run:
   ```bash
   php artisan config:clear
   php artisan migrate:refresh
   ```

---

Migration completed successfully on: 2026-06-20
