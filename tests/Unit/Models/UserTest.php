<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Homeroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_roles_returns_correct_roles_for_admin_only()
    {
        // Create user
        $user = User::create([
            'id_user' => 1001,
            'name' => 'Admin User',
            'gender' => 'M',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);

        // Create teacher (required for admin login)
        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'ADMIN001',
            'date_of_employment' => 2020,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        // Create admin record
        Admin::create([
            'id_user' => $user->id_user,
        ]);

        $roles = $user->getRoles();

        $this->assertContains('administrator', $roles);
        $this->assertCount(1, $roles);
    }

    /** @test */
    public function get_roles_returns_correct_roles_for_lecturer_only()
    {
        // Create user
        $user = User::create([
            'id_user' => 1002,
            'name' => 'Lecturer User',
            'gender' => 'F',
            'email' => 'lecturer@test.com',
            'password' => Hash::make('password'),
        ]);

        // Create teacher (required for lecturer)
        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'LECTURER001',
            'date_of_employment' => 2018,
            'teacher_status' => 'employed',
            'type' => 'honorer',
        ]);

        // Create lecturer record
        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        $roles = $user->getRoles();

        $this->assertContains('lectureTeacher', $roles);
        $this->assertCount(1, $roles);
    }

    /** @test */
    public function get_roles_returns_correct_roles_for_homeroom_teacher_only()
    {
        // Create a class
        \Illuminate\Support\Facades\DB::table('tb_classes')->insert([
            'id_class' => 1,
            'name' => '10A',
            'grade' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create user
        $user = User::create([
            'id_user' => 1003,
            'name' => 'Homeroom Teacher',
            'gender' => 'M',
            'email' => 'homeroom@test.com',
            'password' => Hash::make('password'),
        ]);

        // Create teacher (required for homeroom)
        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'HOMEROOM001',
            'date_of_employment' => 2019,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        // Create homeroom record
        Homeroom::create([
            'id_user' => $user->id_user,
            'id_class' => 1,
            'school_year' => '2023/2024',
        ]);

        $roles = $user->getRoles();

        $this->assertContains('homeroomTeacher', $roles);
        $this->assertCount(1, $roles);
    }

    /** @test */
    public function get_roles_returns_correct_roles_for_student_only()
    {
        // Create user
        $user = User::create([
            'id_user' => 2001,
            'name' => 'Student User',
            'gender' => 'F',
            'email' => 'student@test.com',
            'password' => Hash::make('password'),
        ]);

        // Create student record
        Student::create([
            'id_user' => $user->id_user,
            'nis' => '123456',
            'nisn' => '1234567890',
            'entry_year' => 2023,
        ]);

        $roles = $user->getRoles();

        $this->assertContains('student', $roles);
        $this->assertCount(1, $roles);
    }

    /** @test */
    public function get_roles_returns_multiple_roles_for_teacher_and_homeroom()
    {
        // Create a class
        \Illuminate\Support\Facades\DB::table('tb_classes')->insert([
            'id_class' => 2,
            'name' => '11B',
            'grade' => 11,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create user
        $user = User::create([
            'id_user' => 1004,
            'name' => 'Teacher and Homeroom',
            'gender' => 'M',
            'email' => 'teacherhomeroom@test.com',
            'password' => Hash::make('password'),
        ]);

        // Create teacher
        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'TEACHER001',
            'date_of_employment' => 2017,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        // Create lecturer record
        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Create homeroom record
        Homeroom::create([
            'id_user' => $user->id_user,
            'id_class' => 2,
            'school_year' => '2023/2024',
        ]);

        $roles = $user->getRoles();

        $this->assertContains('lectureTeacher', $roles);
        $this->assertContains('homeroomTeacher', $roles);
        $this->assertCount(2, $roles);
    }

    /** @test */
    public function get_roles_returns_multiple_roles_for_teacher_and_admin()
    {
        // Create user
        $user = User::create([
            'id_user' => 1005,
            'name' => 'Teacher and Admin',
            'gender' => 'F',
            'email' => 'teacheradmin@test.com',
            'password' => Hash::make('password'),
        ]);

        // Create teacher
        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'ADMIN002',
            'date_of_employment' => 2015,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        // Create lecturer record
        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Create admin record
        Admin::create([
            'id_user' => $user->id_user,
        ]);

        $roles = $user->getRoles();

        $this->assertContains('administrator', $roles);
        $this->assertContains('lectureTeacher', $roles);
        $this->assertCount(2, $roles);
    }

    /** @test */
    public function has_role_returns_true_for_existing_role()
    {
        // Create user with admin role
        $user = User::create([
            'id_user' => 1006,
            'name' => 'Admin User',
            'gender' => 'M',
            'email' => 'admin2@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'ADMIN003',
            'date_of_employment' => 2020,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        Admin::create([
            'id_user' => $user->id_user,
        ]);

        $this->assertTrue($user->hasRole('administrator'));
    }

    /** @test */
    public function has_role_returns_false_for_non_existing_role()
    {
        // Create user with only lecturer role
        $user = User::create([
            'id_user' => 1007,
            'name' => 'Lecturer User',
            'gender' => 'F',
            'email' => 'lecturer2@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'LECTURER002',
            'date_of_employment' => 2021,
            'teacher_status' => 'employed',
            'type' => 'honorer',
        ]);

        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        $this->assertTrue($user->hasRole('lectureTeacher'));
        $this->assertFalse($user->hasRole('administrator'));
        $this->assertFalse($user->hasRole('student'));
    }

    /** @test */
    public function is_admin_returns_true_when_user_has_admin_role()
    {
        // Create user with admin
        $user = User::create([
            'id_user' => 1008,
            'name' => 'Admin User',
            'gender' => 'M',
            'email' => 'admin3@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'ADMIN004',
            'date_of_employment' => 2020,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        Admin::create([
            'id_user' => $user->id_user,
        ]);

        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function is_student_returns_true_when_user_has_student_role()
    {
        // Create user with student
        $user = User::create([
            'id_user' => 2002,
            'name' => 'Student User',
            'gender' => 'F',
            'email' => 'student2@test.com',
            'password' => Hash::make('password'),
        ]);

        Student::create([
            'id_user' => $user->id_user,
            'nis' => '234567',
            'nisn' => '2345678901',
            'entry_year' => 2024,
        ]);

        $this->assertTrue($user->isStudent());
    }

    /** @test */
    public function is_lecturer_returns_true_when_user_has_lecturer_role()
    {
        // Create user with lecturer
        $user = User::create([
            'id_user' => 1009,
            'name' => 'Lecturer User',
            'gender' => 'M',
            'email' => 'lecturer3@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'LECTURER003',
            'date_of_employment' => 2022,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        $this->assertTrue($user->isLecturer());
    }

    /** @test */
    public function is_homeroom_teacher_returns_true_when_user_has_homeroom_role()
    {
        // Create a class
        \Illuminate\Support\Facades\DB::table('tb_classes')->insert([
            'id_class' => 3,
            'name' => '12C',
            'grade' => 12,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create user with homeroom
        $user = User::create([
            'id_user' => 1010,
            'name' => 'Homeroom Teacher',
            'gender' => 'F',
            'email' => 'homeroom2@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'HOMEROOM002',
            'date_of_employment' => 2018,
            'teacher_status' => 'employed',
            'type' => 'honorer',
        ]);

        Homeroom::create([
            'id_user' => $user->id_user,
            'id_class' => 3,
            'school_year' => '2024/2025',
        ]);

        $this->assertTrue($user->isHomeroomTeacher());
    }
}
