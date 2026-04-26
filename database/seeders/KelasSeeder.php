<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            ['nama' => '10A', 'tingkat' => '10', 'wali_kelas' => 'Dr. Agus Salim'],
            ['nama' => '10B', 'tingkat' => '10', 'wali_kelas' => 'Dra. Budi Rahayu'],
            ['nama' => '11A', 'tingkat' => '11', 'wali_kelas' => 'M.Pd. Citra Dewi'],
            ['nama' => '11B', 'tingkat' => '11', 'wali_kelas' => 'S.Pd. Dedi Kurniawan'],
            ['nama' => '12A', 'tingkat' => '12', 'wali_kelas' => 'S.Si. Eka Putri'],
            ['nama' => '12B', 'tingkat' => '12', 'wali_kelas' => 'S.Pd. Fajar Ramadhan'],
        ];

        foreach ($classes as $class) {
            // Find the teacher user
            $teacher = User::where('name', $class['wali_kelas'])->first();
            
            if ($teacher) {
                // Update teacher role to include homeroomTeacher
                $currentRole = $teacher->role;
                if (strpos($currentRole, 'homeroomTeacher') === false) {
                    // Add homeroomTeacher role
                    if ($currentRole === 'lectureTeacher') {
                        $teacher->update(['role' => 'lectureTeacher,homeroomTeacher']);
                    } else {
                        $teacher->update(['role' => $currentRole . ',homeroomTeacher']);
                    }
                }
            }
            
            // Create or update kelas record
            Kelas::updateOrCreate(
                ['nama' => $class['nama']],
                [
                    'tingkat' => $class['tingkat'],
                    'wali_kelas' => $class['wali_kelas'],
                    'wali_kelas_id' => $teacher ? $teacher->id : null,
                ]
            );
        }
    }
}
