<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Guru;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateKelasDataSeeder extends Seeder
{
    public function run(): void
    {
        // Check if wali_kelas_id column exists in kelas table
        if (!Schema::hasColumn('kelas', 'wali_kelas_id')) {
            echo "Error: wali_kelas_id column doesn't exist yet. Run migration first.\n";
            return;
        }
        
        // Get all existing kelas records that don't have a wali_kelas_id yet
        $kelasList = DB::table('kelas')->whereNull('wali_kelas_id')->get();
        
        if ($kelasList->isEmpty()) {
            echo "No classes need migration.\n";
            return;
        }
        
        echo "Migrating {$kelasList->count()} classes to link homeroom teachers...\n";
        
        foreach ($kelasList as $kelas) {
            if (empty($kelas->wali_kelas)) {
                echo "Class {$kelas->nama} has no homeroom teacher assigned.\n";
                continue;
            }
            
            // Try to find the teacher by name in users table (search for partial match)
            $user = User::where('role', 'like', '%Teacher%')
                ->where('name', 'like', '%' . $kelas->wali_kelas . '%')
                ->first();
            
            if (!$user) {
                // Try exact match
                $user = User::where('role', 'like', '%Teacher%')
                    ->where('name', $kelas->wali_kelas)
                    ->first();
            }
            
            if ($user) {
                // Update teacher role to include homeroomTeacher
                $currentRole = $user->role;
                if (strpos($currentRole, 'homeroomTeacher') === false) {
                    // Add homeroomTeacher role
                    if ($currentRole === 'lectureTeacher') {
                        $user->update(['role' => 'lectureTeacher,homeroomTeacher']);
                    } else {
                        $user->update(['role' => $currentRole . ',homeroomTeacher']);
                    }
                    echo "Updated {$user->name} role to include homeroomTeacher.\n";
                }
                
                // Link the homeroom teacher
                DB::table('kelas')
                    ->where('id', $kelas->id)
                    ->update(['wali_kelas_id' => $user->id]);
                echo "Linked class {$kelas->nama} to homeroom teacher {$user->name}.\n";
            } else {
                echo "Could not find teacher {$kelas->wali_kelas} for class {$kelas->nama}.\n";
            }
        }
        
        echo "Migration complete!\n";
    }
}
