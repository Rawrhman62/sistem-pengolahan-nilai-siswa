<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['nama' => 'Ahmad Fauzi', 'nis' => '1001', 'kelas' => '10A'],
            ['nama' => 'Siti Nurhaliza', 'nis' => '1002', 'kelas' => '10A'],
            ['nama' => 'Budi Santoso', 'nis' => '1003', 'kelas' => '10A'],
            ['nama' => 'Dewi Lestari', 'nis' => '1004', 'kelas' => '10B'],
            ['nama' => 'Eko Prasetyo', 'nis' => '1005', 'kelas' => '10B'],
            ['nama' => 'Fitri Handayani', 'nis' => '1006', 'kelas' => '10B'],
            ['nama' => 'Gunawan Wijaya', 'nis' => '1007', 'kelas' => '11A'],
            ['nama' => 'Hani Rahmawati', 'nis' => '1008', 'kelas' => '11A'],
            ['nama' => 'Indra Kusuma', 'nis' => '1009', 'kelas' => '11A'],
            ['nama' => 'Joko Widodo', 'nis' => '1010', 'kelas' => '11B'],
            ['nama' => 'Kartika Sari', 'nis' => '1011', 'kelas' => '11B'],
            ['nama' => 'Lukman Hakim', 'nis' => '1012', 'kelas' => '11B'],
            ['nama' => 'Maya Sari', 'nis' => '1013', 'kelas' => '12A'],
            ['nama' => 'Nanda Pratama', 'nis' => '1014', 'kelas' => '12A'],
            ['nama' => 'Oki Setiawan', 'nis' => '1015', 'kelas' => '12A'],
            ['nama' => 'Putri Ayu', 'nis' => '1016', 'kelas' => '12B'],
            ['nama' => 'Qori Amalia', 'nis' => '1017', 'kelas' => '12B'],
            ['nama' => 'Rudi Hartono', 'nis' => '1018', 'kelas' => '12B'],
            ['nama' => 'Sari Wulandari', 'nis' => '1019', 'kelas' => '10A'],
            ['nama' => 'Tono Sugiarto', 'nis' => '1020', 'kelas' => '10B'],
        ];

        foreach ($students as $student) {
            // Check if user already exists
            $user = User::where('user_id', $student['nis'])->first();
            
            if (!$user) {
                // Create user account for student
                $user = User::create([
                    'name' => $student['nama'],
                    'user_name' => 'student_' . $student['nis'],
                    'user_id' => $student['nis'],
                    'email' => 'student' . $student['nis'] . '@school.id',
                    'phone_number' => null,
                    'role' => 'student',
                    'password_set' => false,
                    'password' => null,
                ]);
            }
            
            // Create or update siswa record
            Siswa::updateOrCreate(
                ['nis' => $student['nis']],
                [
                    'user_id' => $user->id,
                    'kelas' => $student['kelas'],
                ]
            );
        }
    }
}
