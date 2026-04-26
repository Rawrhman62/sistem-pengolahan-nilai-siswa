<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateStudentDataSeeder extends Seeder
{
    public function run(): void
    {
        // Check if user_id column exists in siswas table
        if (!Schema::hasColumn('siswas', 'user_id')) {
            echo "Error: user_id column doesn't exist yet. Run migration first.\n";
            return;
        }
        
        // Get all existing siswa records that don't have a user_id yet
        $siswas = DB::table('siswas')->whereNull('user_id')->get();
        
        if ($siswas->isEmpty()) {
            echo "No students need migration.\n";
            return;
        }
        
        echo "Migrating {$siswas->count()} students to user accounts...\n";
        
        foreach ($siswas as $siswa) {
            // Check if user already exists with this NIS
            $existingUser = User::where('user_id', $siswa->nis)->first();
            
            if ($existingUser) {
                // Link existing user
                DB::table('siswas')
                    ->where('id', $siswa->id)
                    ->update(['user_id' => $existingUser->id]);
                echo "Linked student {$siswa->nis} to existing user.\n";
            } else {
                // Create a new user account for this student
                $user = User::create([
                    'name' => $siswa->nama ?? 'Student ' . $siswa->nis,
                    'user_name' => 'student_' . $siswa->nis,
                    'user_id' => $siswa->nis, // Use NIS as user_id
                    'email' => 'student' . $siswa->nis . '@school.id',
                    'phone_number' => null,
                    'role' => 'student',
                    'password_set' => false,
                    'password' => null,
                ]);
                
                // Update siswa record with user_id
                DB::table('siswas')
                    ->where('id', $siswa->id)
                    ->update(['user_id' => $user->id]);
                    
                echo "Created user for student {$siswa->nis}.\n";
            }
        }
        
        echo "Migration complete!\n";
    }
}
