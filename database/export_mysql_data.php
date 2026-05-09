<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Export users
$users = DB::table('users')->get();
file_put_contents(__DIR__.'/backup_users.json', json_encode($users, JSON_PRETTY_PRINT));
echo "Users exported: " . count($users) . "\n";

// Export siswas
$siswas = DB::table('siswas')->get();
file_put_contents(__DIR__.'/backup_siswas.json', json_encode($siswas, JSON_PRETTY_PRINT));
echo "Siswas exported: " . count($siswas) . "\n";

// Export nilai
$nilai = DB::table('nilai')->get();
file_put_contents(__DIR__.'/backup_nilai.json', json_encode($nilai, JSON_PRETTY_PRINT));
echo "Nilai exported: " . count($nilai) . "\n";

// Export gurus
$gurus = DB::table('gurus')->get();
file_put_contents(__DIR__.'/backup_gurus.json', json_encode($gurus, JSON_PRETTY_PRINT));
echo "Gurus exported: " . count($gurus) . "\n";

// Export mapels
$mapels = DB::table('mapels')->get();
file_put_contents(__DIR__.'/backup_mapels.json', json_encode($mapels, JSON_PRETTY_PRINT));
echo "Mapels exported: " . count($mapels) . "\n";

// Export kelas
$kelas = DB::table('kelas')->get();
file_put_contents(__DIR__.'/backup_kelas.json', json_encode($kelas, JSON_PRETTY_PRINT));
echo "Kelas exported: " . count($kelas) . "\n";

echo "\nAll data exported successfully!\n";
