<?php

namespace Database\Seeders;

use App\Models\Mapel;
use Illuminate\Database\Seeder;

class MapelSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['kode' => 'MAT', 'nama' => 'Matematika', 'kelompok' => 'Wajib'],
            ['kode' => 'IND', 'nama' => 'Bahasa Indonesia', 'kelompok' => 'Wajib'],
            ['kode' => 'ENG', 'nama' => 'Bahasa Inggris', 'kelompok' => 'Wajib'],
            ['kode' => 'FIS', 'nama' => 'Fisika', 'kelompok' => 'MIPA'],
            ['kode' => 'KIM', 'nama' => 'Kimia', 'kelompok' => 'MIPA'],
            ['kode' => 'BIO', 'nama' => 'Biologi', 'kelompok' => 'MIPA'],
            ['kode' => 'SEJ', 'nama' => 'Sejarah', 'kelompok' => 'IPS'],
            ['kode' => 'GEO', 'nama' => 'Geografi', 'kelompok' => 'IPS'],
            ['kode' => 'EKO', 'nama' => 'Ekonomi', 'kelompok' => 'IPS'],
            ['kode' => 'SOS', 'nama' => 'Sosiologi', 'kelompok' => 'IPS'],
            ['kode' => 'PAI', 'nama' => 'Pendidikan Agama Islam', 'kelompok' => 'Wajib'],
            ['kode' => 'PKN', 'nama' => 'Pendidikan Kewarganegaraan', 'kelompok' => 'Wajib'],
            ['kode' => 'PJK', 'nama' => 'Pendidikan Jasmani', 'kelompok' => 'Wajib'],
            ['kode' => 'SEN', 'nama' => 'Seni Budaya', 'kelompok' => 'Wajib'],
            ['kode' => 'TIK', 'nama' => 'Teknologi Informasi', 'kelompok' => 'Pilihan'],
        ];

        foreach ($subjects as $subject) {
            Mapel::create($subject);
        }
    }
}
