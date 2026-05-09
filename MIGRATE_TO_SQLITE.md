# Migration from MySQL to SQLite Guide

## Current Status

✓ Data has been exported from MySQL to JSON files in `database/` directory:
- `backup_users.json` (37 records)
- `backup_siswas.json` (21 records)
- `backup_nilai.json` (8 records)
- `backup_gurus.json` (10 records)
- `backup_mapels.json` (15 records)
- `backup_kelas.json` (6 records)

## Issue

The SQLite PDO extension is not enabled in your PHP installation.

## Solution: Enable SQLite Extension

### Step 1: Find your php.ini file

Run this command to find your php.ini location:
```bash
php --ini
```

### Step 2: Edit php.ini

Open the php.ini file and find these lines:
```ini
;extension=pdo_sqlite
;extension=sqlite3
```

Remove the semicolon (;) to uncomment them:
```ini
extension=pdo_sqlite
extension=sqlite3
```

### Step 3: Restart your web server

If using:
- **XAMPP**: Restart Apache from XAMPP Control Panel
- **Laragon**: Restart All Services
- **Built-in PHP server**: Just restart the `php artisan serve` command

### Step 4: Verify SQLite is enabled

```bash
php -m | findstr sqlite
```

You should see:
```
pdo_sqlite
sqlite3
```

## After Enabling SQLite

### Step 1: Update .env (Already Done)

The `.env` file has been updated to:
```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=db_sinilai
# DB_USERNAME=root
# DB_PASSWORD=
```

### Step 2: Run Migrations

```bash
php artisan migrate:fresh
```

### Step 3: Import Data

Run the import script:
```bash
php database/import_sqlite_data.php
```

## Alternative: Stay with MySQL but Use Better Workflow

If you prefer to stay with MySQL, here's a better workflow for team collaboration:

### Option 1: Use Seeders (Recommended)

1. Create seeders for your data:
```bash
php artisan make:seeder DatabaseSeeder
```

2. Commit seeders to Git
3. Team members run:
```bash
php artisan migrate:fresh --seed
```

### Option 2: Use SQL Dump

1. Export MySQL data:
```bash
mysqldump -u root db_sinilai > database/db_sinilai.sql
```

2. Commit the SQL file to Git
3. Team members import:
```bash
mysql -u root db_sinilai < database/db_sinilai.sql
```

### Option 3: Use Docker

Create a `docker-compose.yml` with MySQL container - everyone uses the same database setup.

## Benefits of SQLite for Development

1. **File-based**: Database is a single file that can be committed to Git
2. **No server**: No need to install/configure MySQL
3. **Portable**: Works the same on all team members' machines
4. **Fast**: Great for development and testing
5. **Zero configuration**: Just enable the extension and go

## When to Use MySQL

- Production environment
- Need advanced features (stored procedures, triggers)
- Large datasets (> 1GB)
- Multiple concurrent writers
- Need user permissions and roles at database level

## Current Backup Files

All your data is safely backed up in JSON format in the `database/` directory. These files can be used to:
- Import into SQLite once enabled
- Import back into MySQL if needed
- Share with team members
- Version control in Git

## Next Steps

1. Enable SQLite extension in PHP
2. Run migrations: `php artisan migrate:fresh`
3. Import data: `php database/import_sqlite_data.php`
4. Commit `database/database.sqlite` to Git
5. Team members just pull and run `php artisan migrate` (database file is already there!)

## Reverting to MySQL

If you need to go back to MySQL:

1. Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sinilai
DB_USERNAME=root
DB_PASSWORD=
```

2. Run migrations:
```bash
php artisan migrate:fresh
```

3. Import data:
```bash
php database/import_mysql_data.php
```

---

**Note**: The export script (`database/export_mysql_data.php`) and import script (to be created) are available for easy data migration between databases.
