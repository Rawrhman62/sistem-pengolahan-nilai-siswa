<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateGuruDataSeeder extends Seeder
{
    public function run(): void
    {
        // Check if user_id column exists in gurus table
        if (!Schema::hasColumn('gurus', 'user_id')) {
            echo "Error: user_id column doesn't exist yet. Run migration first.\n";
            return;
        }
        
        // Get all existing guru records that don't have a user_id yet
        $gurus = DB::table('gurus')->whereNull('user_id')->get();
        
        if ($gurus->isEmpty()) {
            echo "No teachers need migration.\n";
            return;
        }
        
        echo "Migrating {$gurus->count()} teachers to user accounts...\n";
        
        foreach ($gurus as $guru) {
            // Check if user already exists with this NIP
            $existingUser = User::where('user_id', $guru->nip)->first();
            
            if ($existingUser) {
                // Link existing user
                DB::table('gurus')
                    ->where('id', $guru->id)
                    ->update(['user_id' => $existingUser->id]);
                echo "Linked teacher {$guru->nip} to existing user.\n";
            } else {
                // Determine role based on mapel (if exists)
                $role = 'lectureTeacher'; // Default to lecture teacher
                
                // Create a new user account for this teacher
                $user = User::create([
                    'name' => $guru->nama ?? 'Teacher ' . $guru->nip,
                    'user_name' => 'teacher_' . $guru->nip,
                    'user_id' => $guru->nip, // Use NIP as user_id
                    'email' => 'teacher' . str_replace(' ', '', $guru->nip) . '@school.id',
                    'phone_number' => null,
                    'role' => $role,
                    'password_set' => false,
                    'password' => null,
                ]);
                
                // Update guru record with user_id
                DB::table('gurus')
                    ->where('id', $guru->id)
                    ->update(['user_id' => $user->id]);
                    
                echo "Created user for teacher {$guru->nip}.\n";
            }
        }
        
        echo "Migration complete!\n";
    }
}
