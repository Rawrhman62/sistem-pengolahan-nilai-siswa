<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Lecturer;
use App\Models\Homeroom;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Kkm;
use App\Models\RasioBobotNilai;
use App\Models\Predikat;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed data from databas.csv
        $this->call([
            DatabaseCsvSeeder::class,
        ]);

        // Configuration Data
        Kkm::create(['grade' => 10, 'kkm' => 78]);
        Kkm::create(['grade' => 11, 'kkm' => 80]);
        Kkm::create(['grade' => 12, 'kkm' => 82]);

        RasioBobotNilai::create([
            'nilai_harian' => '25',
            'nilai_keterampilan' => '25',
            'nilai_ulangan' => '25',
            'nilai_ujian' => '25',
            'void' => '0',
        ]);

        Predikat::create([
            'grade' => 10,
            'a_range' => '100-93',
            'b_range' => '92-83',
            'c_range' => '83-78',
            'd_range' => '>78',
        ]);

        Predikat::create([
            'grade' => 11,
            'a_range' => '100-93',
            'b_range' => '92-83',
            'c_range' => '83-80',
            'd_range' => '>80',
        ]);

        Predikat::create([
            'grade' => 12,
            'a_range' => '100-93',
            'b_range' => '92-83',
            'c_range' => '83-82',
            'd_range' => '>82',
        ]);
    }
}
