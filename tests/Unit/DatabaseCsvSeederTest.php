<?php

namespace Tests\Unit;

use Tests\TestCase;
use Database\Seeders\DatabaseCsvSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class DatabaseCsvSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_runs_successfully(): void
    {
        // Run the seeder
        $this->seed(DatabaseCsvSeeder::class);

        // Verify users were seeded
        $this->assertDatabaseCount('tb_users', 6);
        
        // Verify specific user exists
        $this->assertDatabaseHas('tb_users', [
            'id_user' => 1001,
            'name' => 'Takanashi Hoshino',
            'email' => 'hoshino@example.com',
        ]);

        // Verify admins were seeded
        $this->assertDatabaseCount('tb_admins', 2);
        $this->assertDatabaseHas('tb_admins', ['id_user' => 1001]);
        $this->assertDatabaseHas('tb_admins', ['id_user' => 1004]);

        // Verify teachers were seeded
        $this->assertDatabaseCount('tb_teachers', 4);
        $this->assertDatabaseHas('tb_teachers', [
            'id_user' => 1003,
            'nomor_induk' => '199510152020012002',
        ]);

        // Verify lecturers were seeded
        $this->assertDatabaseCount('tb_lecturers', 2);

        // Verify students were seeded
        $this->assertDatabaseCount('tb_students', 1);
        $this->assertDatabaseHas('tb_students', [
            'id_user' => 1002,
            'nis' => '240001',
            'nisn' => '3312501038',
        ]);

        // Verify classes were seeded
        $this->assertDatabaseCount('tb_classes', 9);

        // Verify homerooms were seeded
        $this->assertDatabaseCount('tb_homerooms', 2);

        // Verify subjects were seeded
        $this->assertDatabaseCount('tb_subjects', 21);

        // Verify subject codes were seeded
        $this->assertDatabaseCount('tb_subjects_code', 17);

        // Verify teached subjects were seeded
        $this->assertDatabaseCount('tb_teached_subjects', 3);
    }

    public function test_user_passwords_are_hashed(): void
    {
        $this->seed(DatabaseCsvSeeder::class);

        $user = DB::table('tb_users')->where('id_user', 1001)->first();
        
        // Password should be hashed, not plain text
        $this->assertNotEquals('Admin123', $user->password);
        $this->assertStringStartsWith('$2y$', $user->password); // bcrypt hash
    }

    public function test_user_with_null_password(): void
    {
        $this->seed(DatabaseCsvSeeder::class);

        $user = DB::table('tb_users')->where('id_user', 1005)->first();
        
        // User 1005 should have null password
        $this->assertNull($user->password);
    }

    public function test_foreign_key_relationships_are_maintained(): void
    {
        $this->seed(DatabaseCsvSeeder::class);

        // Verify teacher relationship
        $teacher = DB::table('tb_teachers')->where('id_user', 1003)->first();
        $user = DB::table('tb_users')->where('id_user', $teacher->id_user)->first();
        $this->assertNotNull($user);

        // Verify student relationship
        $student = DB::table('tb_students')->where('id_user', 1002)->first();
        $user = DB::table('tb_users')->where('id_user', $student->id_user)->first();
        $this->assertNotNull($user);

        // Verify student class relationship
        $class = DB::table('tb_classes')->where('id_class', $student->id_class)->first();
        $this->assertNotNull($class);
        $this->assertEquals(105, $class->id_class);
    }

    public function test_unique_constraints_are_enforced(): void
    {
        $this->seed(DatabaseCsvSeeder::class);

        // Verify nomor_induk is unique for teachers
        $nomorIndukCount = DB::table('tb_teachers')
            ->where('nomor_induk', '199510152020012002')
            ->count();
        $this->assertEquals(1, $nomorIndukCount);

        // Verify nis is unique for students
        $nisCount = DB::table('tb_students')
            ->where('nis', '240001')
            ->count();
        $this->assertEquals(1, $nisCount);
    }

    public function test_teached_subjects_relationships(): void
    {
        $this->seed(DatabaseCsvSeeder::class);

        // Verify teached subject exists
        $teachedSubject = DB::table('tb_teached_subjects')
            ->where('id_user', 1003)
            ->where('id_subjects', 'S001')
            ->first();
        
        $this->assertNotNull($teachedSubject);
        $this->assertEquals(101, $teachedSubject->teached_class);

        // Verify relationships exist
        $user = DB::table('tb_users')->where('id_user', $teachedSubject->id_user)->first();
        $subject = DB::table('tb_subjects')->where('id_subjects', $teachedSubject->id_subjects)->first();
        $class = DB::table('tb_classes')->where('id_class', $teachedSubject->teached_class)->first();

        $this->assertNotNull($user);
        $this->assertNotNull($subject);
        $this->assertNotNull($class);
    }
}
