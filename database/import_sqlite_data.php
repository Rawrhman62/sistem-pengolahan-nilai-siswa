<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Starting data import to SQLite...\n\n";

// Import users
$users = json_decode(file_get_contents(__DIR__.'/backup_users.json'), true);
foreach ($users as $user) {
    DB::table('users')->insert((array)$user);
}
echo "Users imported: " . count($users) . "\n";

// Import siswas
$siswas = json_decode(file_get_contents(__DIR__.'/backup_siswas.json'), true);
foreach ($siswas as $siswa) {
    DB::table('siswas')->insert((array)$siswa);
}
echo "Siswas imported: " . count($siswas) . "\n";

// Import gurus
$gurus = json_decode(file_get_contents(__DIR__.'/backup_gurus.json'), true);
foreach ($gurus as $guru) {
    DB::table('gurus')->insert((array)$guru);
}
echo "Gurus imported: " . count($gurus) . "\n";

// Import mapels
$mapels = json_decode(file_get_contents(__DIR__.'/backup_mapels.json'), true);
foreach ($mapels as $mapel) {
    DB::table('mapels')->insert((array)$mapel);
}
echo "Mapels imported: " . count($mapels) . "\n";

// Import kelas
$kelas = json_decode(file_get_contents(__DIR__.'/backup_kelas.json'), true);
foreach ($kelas as $k) {
    DB::table('kelas')->insert((array)$k);
}
echo "Kelas imported: " . count($kelas) . "\n";

// Import nilai
$nilai = json_decode(file_get_contents(__DIR__.'/backup_nilai.json'), true);
foreach ($nilai as $n) {
    DB::table('nilai')->insert((array)$n);
}
echo "Nilai imported: " . count($nilai) . "\n";

echo "\nAll data imported successfully to SQLite!\n";
echo "Database file: database/database.sqlite\n";
echo "You can now commit this file to Git for team collaboration.\n";
