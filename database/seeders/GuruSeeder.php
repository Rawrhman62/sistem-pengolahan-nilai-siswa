<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            ['nama' => 'Dr. Agus Salim', 'nip' => '198501012010011001'],
            ['nama' => 'Dra. Budi Rahayu', 'nip' => '198602022010012002'],
            ['nama' => 'M.Pd. Citra Dewi', 'nip' => '198703032010013003'],
            ['nama' => 'S.Pd. Dedi Kurniawan', 'nip' => '198804042010014004'],
            ['nama' => 'S.Si. Eka Putri', 'nip' => '198905052010015005'],
            ['nama' => 'S.Pd. Fajar Ramadhan', 'nip' => '199006062010016006'],
            ['nama' => 'M.Pd. Gita Savitri', 'nip' => '199107072010017007'],
            ['nama' => 'S.Pd. Hendra Wijaya', 'nip' => '199208082010018008'],
            ['nama' => 'Drs. Irfan Hakim', 'nip' => '199309092010019009'],
            ['nama' => 'S.Pd. Juwita Sari', 'nip' => '199410102010011010'],
        ];

        foreach ($teachers as $teacher) {
            // Check if user already exists
            $user = User::where('user_id', $teacher['nip'])->first();
            
            if (!$user) {
                // Create user account for teacher
                $user = User::create([
                    'name' => $teacher['nama'],
                    'user_name' => 'teacher_' . $teacher['nip'],
                    'user_id' => $teacher['nip'],
                    'email' => 'teacher' . str_replace(' ', '', $teacher['nip']) . '@school.id',
                    'phone_number' => null,
                    'role' => 'lectureTeacher',
                    'password_set' => false,
                    'password' => null,
                ]);
            }
            
            // Create or update guru record
            Guru::updateOrCreate(
                ['nip' => $teacher['nip']],
                ['user_id' => $user->id]
            );
        }
    }
}
