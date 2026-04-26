<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentUserSeeder extends Seeder
{
    public function run(): void
    {
        // Get all existing siswa records
        $siswas = DB::table('siswas')->get();
        
        foreach ($siswas as $siswa) {
            // Create a user account for each student
            $user = User::create([
                'name' => $siswa->nama,
                'user_name' => strtolower(str_replace(' ', '', $siswa->nama)), // e.g., ahmadfauzi
                'user_id' => $siswa->nis, // Use NIS as user_id
                'email' => strtolower(str_replace(' ', '', $siswa->nama)) . '@student.school.id',
                'phone_number' => null,
                'role' => 'student',
                'password_set' => false,
                'password' => null,
            ]);
            
            // Update siswa record with user_id
            DB::table('siswas')
                ->where('id', $siswa->id)
                ->update(['user_id' => $user->id]);
        }
    }
}
