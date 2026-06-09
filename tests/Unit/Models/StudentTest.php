<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_find_student_by_nis()
    {
        // Create a user
        $user = User::create([
            'id_user' => 2001,
            'name' => 'Test Student',
            'gender' => 'M',
            'email' => 'student@test.com',
            'password' => Hash::make('password'),
        ]);

        // Create a student with nis
        $student = Student::create([
            'id_user' => $user->id_user,
            'nis' => 'STU001',
            'nisn' => '1234567890',
            'entry_year' => 2023,
        ]);

        // Test findByNis method
        $foundStudent = Student::findByNis('STU001');

        $this->assertNotNull($foundStudent);
        $this->assertEquals($student->id, $foundStudent->id);
        $this->assertEquals('STU001', $foundStudent->nis);
        $this->assertEquals($user->id_user, $foundStudent->id_user);
    }

    /** @test */
    public function returns_null_for_invalid_nis()
    {
        // Create a user and student
        $user = User::create([
            'id_user' => 2002,
            'name' => 'Another Student',
            'gender' => 'F',
            'email' => 'student2@test.com',
            'password' => Hash::make('password'),
        ]);

        Student::create([
            'id_user' => $user->id_user,
            'nis' => 'STU002',
            'nisn' => '0987654321',
            'entry_year' => 2024,
        ]);

        // Test with invalid nis
        $foundStudent = Student::findByNis('INVALID999');

        $this->assertNull($foundStudent);
    }
}
