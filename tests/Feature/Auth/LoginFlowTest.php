<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Admin;
use App\Models\Lecturer;
use App\Models\Homeroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class LoginFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear rate limiter before each test
        RateLimiter::clear('login_attempts:127.0.0.1:admin001');
        RateLimiter::clear('login_attempts:127.0.0.1:teacher001');
        RateLimiter::clear('login_attempts:127.0.0.1:lecturer001');
        RateLimiter::clear('login_attempts:127.0.0.1:homeroom001');
        RateLimiter::clear('login_attempts:127.0.0.1:ratelimit001');
        RateLimiter::clear('login_attempts:127.0.0.1:123456');
        RateLimiter::clear('login_attempts:127.0.0.1:valid001');
        RateLimiter::clear('login_attempts:127.0.0.1:teacher002');
        RateLimiter::clear('login_attempts:127.0.0.1:nonexistent999');
    }

    /** @test */
    public function admin_can_login_with_nomor_induk()
    {
        // Create user
        $user = User::create([
            'id_user' => 1001,
            'name' => 'Admin User',
            'gender' => 'M',
            'email' => 'admin@test.com',
            'password' => Hash::make('adminpass'),
        ]);

        // Create teacher record
        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'ADMIN001',
            'date_of_employment' => 2015,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        // Create admin record
        Admin::create([
            'id_user' => $user->id_user,
        ]);

        // Attempt login as admin
        $response = $this->post('/login', [
            'login_id' => 'ADMIN001',
            'password' => 'adminpass',
            'selected_role' => 'administrator',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $response->assertSessionHas('selected_role', 'administrator');
        $response->assertSessionHas('id_user', 1001);
    }

    /** @test */
    public function teacher_can_login_with_nomor_induk()
    {
        // Create user
        $user = User::create([
            'id_user' => 1002,
            'name' => 'Teacher User',
            'gender' => 'F',
            'email' => 'teacher@test.com',
            'password' => Hash::make('teacherpass'),
        ]);

        // Create teacher record
        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'TEACHER001',
            'date_of_employment' => 2018,
            'teacher_status' => 'employed',
            'type' => 'honorer',
        ]);

        // Create lecturer record (required for lectureTeacher role)
        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Attempt login as teacher (note: teacher role is identified through lectureTeacher)
        $response = $this->post('/login', [
            'login_id' => 'TEACHER001',
            'password' => 'teacherpass',
            'selected_role' => 'lectureTeacher',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $response->assertSessionHas('selected_role', 'lectureTeacher');
        $response->assertSessionHas('id_user', 1002);
    }

    /** @test */
    public function lecturer_can_login_with_nomor_induk()
    {
        // Create user
        $user = User::create([
            'id_user' => 1003,
            'name' => 'Lecturer User',
            'gender' => 'M',
            'email' => 'lecturer@test.com',
            'password' => Hash::make('lecturerpass'),
        ]);

        // Create teacher record
        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'LECTURER001',
            'date_of_employment' => 2019,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        // Create lecturer record
        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Attempt login as lecturer
        $response = $this->post('/login', [
            'login_id' => 'LECTURER001',
            'password' => 'lecturerpass',
            'selected_role' => 'lectureTeacher',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $response->assertSessionHas('selected_role', 'lectureTeacher');
        $response->assertSessionHas('id_user', 1003);
    }

    /** @test */
    public function homeroom_teacher_can_login_with_nomor_induk()
    {
        // Create a class first using raw query
        \Illuminate\Support\Facades\DB::table('tb_classes')->insert([
            'id_class' => 1,
            'name' => '10A',
            'grade' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create user
        $user = User::create([
            'id_user' => 1004,
            'name' => 'Homeroom Teacher',
            'gender' => 'F',
            'email' => 'homeroom@test.com',
            'password' => Hash::make('homeroompass'),
        ]);

        // Create teacher record
        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'HOMEROOM001',
            'date_of_employment' => 2017,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        // Create homeroom record
        Homeroom::create([
            'id_user' => $user->id_user,
            'id_class' => 1,
            'school_year' => '2023/2024',
        ]);

        // Attempt login as homeroom teacher
        $response = $this->post('/login', [
            'login_id' => 'HOMEROOM001',
            'password' => 'homeroompass',
            'selected_role' => 'homeroomTeacher',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $response->assertSessionHas('selected_role', 'homeroomTeacher');
        $response->assertSessionHas('id_user', 1004);
    }

    /** @test */
    public function student_can_login_with_nis()
    {
        // Create user
        $user = User::create([
            'id_user' => 2001,
            'name' => 'Student User',
            'gender' => 'M',
            'email' => 'student@test.com',
            'password' => Hash::make('studentpass'),
        ]);

        // Create student record
        Student::create([
            'id_user' => $user->id_user,
            'nis' => '123456',
            'nisn' => '1234567890',
            'entry_year' => 2023,
        ]);

        // Attempt login as student
        $response = $this->post('/login', [
            'login_id' => '123456',
            'password' => 'studentpass',
            'selected_role' => 'student',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $response->assertSessionHas('selected_role', 'student');
        $response->assertSessionHas('id_user', 2001);
    }

    /** @test */
    public function login_fails_with_invalid_credentials()
    {
        // Create user with teacher role
        $user = User::create([
            'id_user' => 1005,
            'name' => 'Test Teacher',
            'gender' => 'M',
            'email' => 'test@test.com',
            'password' => Hash::make('correctpassword'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'VALID001',
            'date_of_employment' => 2020,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Attempt login with wrong password
        $response = $this->post('/login', [
            'login_id' => 'VALID001',
            'password' => 'wrongpassword',
            'selected_role' => 'lectureTeacher',
        ]);

        $response->assertSessionHasErrors(['login_id']);
        $this->assertGuest();
    }

    /** @test */
    public function login_fails_with_non_existent_login_id()
    {
        // Attempt login with non-existent nomor_induk
        $response = $this->post('/login', [
            'login_id' => 'NONEXISTENT999',
            'password' => 'anypassword',
            'selected_role' => 'lectureTeacher',
        ]);

        $response->assertSessionHasErrors(['login_id']);
        $this->assertGuest();
    }

    /** @test */
    public function login_fails_with_mismatched_role()
    {
        // Create user with only teacher role (no admin)
        $user = User::create([
            'id_user' => 1006,
            'name' => 'Regular Teacher',
            'gender' => 'F',
            'email' => 'regularteacher@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'TEACHER002',
            'date_of_employment' => 2020,
            'teacher_status' => 'employed',
            'type' => 'honorer',
        ]);

        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Try to login as administrator (but user is not admin)
        $response = $this->post('/login', [
            'login_id' => 'TEACHER002',
            'password' => 'password',
            'selected_role' => 'administrator',
        ]);

        $response->assertSessionHasErrors(['selected_role']);
        $this->assertGuest();
    }

    /** @test */
    public function rate_limiting_blocks_excessive_login_attempts()
    {
        // Create valid user
        $user = User::create([
            'id_user' => 1007,
            'name' => 'Rate Limited User',
            'gender' => 'M',
            'email' => 'ratelimit@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'RATELIMIT001',
            'date_of_employment' => 2020,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Make 5 failed login attempts (the threshold)
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'login_id' => 'RATELIMIT001',
                'password' => 'wrongpassword',
                'selected_role' => 'lectureTeacher',
            ]);
        }

        // 6th attempt should be rate limited
        $response = $this->post('/login', [
            'login_id' => 'RATELIMIT001',
            'password' => 'wrongpassword',
            'selected_role' => 'lectureTeacher',
        ]);

        $response->assertSessionHasErrors(['login_id']);
        
        // Check if rate limiting error is present
        $errors = session('errors');
        $errorMessage = $errors->first('login_id');
        $this->assertStringContainsString('Too many login attempts', $errorMessage);
    }
}
