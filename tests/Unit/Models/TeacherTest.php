<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class TeacherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_find_teacher_by_nomor_induk()
    {
        // Create a user
        $user = User::create([
            'id_user' => 1001,
            'name' => 'Test Teacher',
            'gender' => 'M',
            'email' => 'teacher@test.com',
            'password' => Hash::make('password'),
        ]);

        // Create a teacher with nomor_induk
        $teacher = Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'TEACHER001',
            'date_of_employment' => 2020,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        // Test findByNomorInduk method
        $foundTeacher = Teacher::findByNomorInduk('TEACHER001');

        $this->assertNotNull($foundTeacher);
        $this->assertEquals($teacher->id, $foundTeacher->id);
        $this->assertEquals('TEACHER001', $foundTeacher->nomor_induk);
        $this->assertEquals($user->id_user, $foundTeacher->id_user);
    }

    /** @test */
    public function returns_null_for_invalid_nomor_induk()
    {
        // Create a user and teacher
        $user = User::create([
            'id_user' => 1002,
            'name' => 'Another Teacher',
            'gender' => 'F',
            'email' => 'teacher2@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'TEACHER002',
            'date_of_employment' => 2021,
            'teacher_status' => 'employed',
            'type' => 'honorer',
        ]);

        // Test with invalid nomor_induk
        $foundTeacher = Teacher::findByNomorInduk('INVALID999');

        $this->assertNull($foundTeacher);
    }
}
