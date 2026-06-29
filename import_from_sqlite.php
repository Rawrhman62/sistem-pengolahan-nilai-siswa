<?php
// Import data from SQLite to MySQL
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Starting data import from SQLite to MySQL...\n\n";

// Connect to SQLite
$sqlite = new PDO('sqlite:database/database.sqlite');
$sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Import Users
echo "Importing users...\n";
$users = $sqlite->query('SELECT * FROM tb_users')->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $user) {
    DB::table('tb_users')->insert($user);
}
echo "  ✓ Imported " . count($users) . " users\n";

// Import Admins
echo "Importing admins...\n";
$admins = $sqlite->query('SELECT * FROM tb_admins')->fetchAll(PDO::FETCH_ASSOC);
foreach ($admins as $admin) {
    DB::table('tb_admins')->insert($admin);
}
echo "  ✓ Imported " . count($admins) . " admins\n";

// Import Teachers
echo "Importing teachers...\n";
$teachers = $sqlite->query('SELECT * FROM tb_teachers')->fetchAll(PDO::FETCH_ASSOC);
foreach ($teachers as $teacher) {
    DB::table('tb_teachers')->insert($teacher);
}
echo "  ✓ Imported " . count($teachers) . " teachers\n";

// Import Lecturers
echo "Importing lecturers...\n";
$lecturers = $sqlite->query('SELECT * FROM tb_lecturers')->fetchAll(PDO::FETCH_ASSOC);
foreach ($lecturers as $lecturer) {
    DB::table('tb_lecturers')->insert($lecturer);
}
echo "  ✓ Imported " . count($lecturers) . " lecturers\n";

// Import Classes
echo "Importing classes...\n";
$classes = $sqlite->query('SELECT * FROM tb_classes')->fetchAll(PDO::FETCH_ASSOC);
foreach ($classes as $class) {
    DB::table('tb_classes')->insert($class);
}
echo "  ✓ Imported " . count($classes) . " classes\n";

// Import Homerooms
echo "Importing homerooms...\n";
$homerooms = $sqlite->query('SELECT * FROM tb_homerooms')->fetchAll(PDO::FETCH_ASSOC);
foreach ($homerooms as $homeroom) {
    DB::table('tb_homerooms')->insert($homeroom);
}
echo "  ✓ Imported " . count($homerooms) . " homerooms\n";

// Import Students
echo "Importing students...\n";
$students = $sqlite->query('SELECT * FROM tb_students')->fetchAll(PDO::FETCH_ASSOC);
foreach ($students as $student) {
    DB::table('tb_students')->insert($student);
}
echo "  ✓ Imported " . count($students) . " students\n";

// Import Subjects
echo "Importing subjects...\n";
$subjects = $sqlite->query('SELECT * FROM tb_subjects')->fetchAll(PDO::FETCH_ASSOC);
foreach ($subjects as $subject) {
    DB::table('tb_subjects')->insert($subject);
}
echo "  ✓ Imported " . count($subjects) . " subjects\n";

// Import Teached Subjects
echo "Importing teached subjects...\n";
$teached = $sqlite->query('SELECT * FROM tb_teached_subjects')->fetchAll(PDO::FETCH_ASSOC);
foreach ($teached as $t) {
    DB::table('tb_teached_subjects')->insert($t);
}
echo "  ✓ Imported " . count($teached) . " teached subjects\n";

// Import Tahun Ajaran Dates (skip if already exists from migration)
echo "Importing tahun ajaran dates...\n";
try {
    $tahunAjaran = $sqlite->query('SELECT * FROM tb_tanggal_tahun_ajaran')->fetchAll(PDO::FETCH_ASSOC);
    $imported = 0;
    foreach ($tahunAjaran as $ta) {
        $exists = DB::table('tb_tanggal_tahun_ajaran')->where('id', $ta['id'])->exists();
        if (!$exists) {
            DB::table('tb_tanggal_tahun_ajaran')->insert($ta);
            $imported++;
        }
    }
    echo "  ✓ Imported " . $imported . " new tahun ajaran dates (skipped " . (count($tahunAjaran) - $imported) . " existing)\n";
} catch (Exception $e) {
    echo "  ⚠ Skipped tahun ajaran (already exists from migration)\n";
}

// Import Nilai Harian FIRST (before tb_nilai)
echo "Importing nilai harian...\n";
$nilaiHarian = $sqlite->query('SELECT * FROM tb_nilai_harian')->fetchAll(PDO::FETCH_ASSOC);
foreach ($nilaiHarian as $nh) {
    DB::table('tb_nilai_harian')->insert($nh);
}
echo "  ✓ Imported " . count($nilaiHarian) . " nilai harian records\n";

// Import Nilai Keterampilan
echo "Importing nilai keterampilan...\n";
$nilaiKeterampilan = $sqlite->query('SELECT * FROM tb_nilai_keterampilan')->fetchAll(PDO::FETCH_ASSOC);
foreach ($nilaiKeterampilan as $nk) {
    DB::table('tb_nilai_keterampilan')->insert($nk);
}
echo "  ✓ Imported " . count($nilaiKeterampilan) . " nilai keterampilan records\n";

// Import Nilai Ulangan
echo "Importing nilai ulangan...\n";
$nilaiUlangan = $sqlite->query('SELECT * FROM tb_nilai_ulangan')->fetchAll(PDO::FETCH_ASSOC);
foreach ($nilaiUlangan as $nu) {
    DB::table('tb_nilai_ulangan')->insert($nu);
}
echo "  ✓ Imported " . count($nilaiUlangan) . " nilai ulangan records\n";

// Import Nilai Ujian
echo "Importing nilai ujian...\n";
$nilaiUjian = $sqlite->query('SELECT * FROM tb_nilai_ujian')->fetchAll(PDO::FETCH_ASSOC);
foreach ($nilaiUjian as $nuj) {
    DB::table('tb_nilai_ujian')->insert($nuj);
}
echo "  ✓ Imported " . count($nilaiUjian) . " nilai ujian records\n";

// Import Nilai (main grades table) - NOW AFTER child tables
echo "Importing nilai...\n";
$nilai = $sqlite->query('SELECT * FROM tb_nilai')->fetchAll(PDO::FETCH_ASSOC);
foreach ($nilai as $n) {
    DB::table('tb_nilai')->insert($n);
}
echo "  ✓ Imported " . count($nilai) . " nilai records\n";

echo "\n✅ Data import completed successfully!\n";
echo "\nSummary:\n";
echo "  Users: " . count($users) . "\n";
echo "  Students: " . count($students) . "\n";
echo "  Teachers: " . count($teachers) . "\n";
echo "  Classes: " . count($classes) . "\n";
echo "  Subjects: " . count($subjects) . "\n";
echo "  Nilai Records: " . count($nilai) . "\n";
