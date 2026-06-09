<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Admin;
use App\Models\Lecturer;
use App\Models\Homeroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class RoleDeterminationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear rate limiter before each test
        RateLimiter::clear('login_attempts:127.0.0.1:multi001');
        RateLimiter::clear('login_attempts:127.0.0.1:single001');
    }

    /** @test */
    public function session_stores_correct_role_for_user_with_single_role()
    {
        // Create user with only lecturer role
        $user = User::create([
            'id_user' => 1001,
            'name' => 'Single Role User',
            'gender' => 'M',
            'email' => 'single@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'SINGLE001',
            'date_of_employment' => 2020,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Login as lecturer
        $response = $this->post('/login', [
            'login_id' => 'SINGLE001',
            'password' => 'password',
            'selected_role' => 'lectureTeacher',
        ]);

        $response->assertRedirect('/dashboard');
        
        // Verify session has correct role
        $this->assertEquals('lectureTeacher', session('selected_role'));
        $this->assertEquals(1001, session('id_user'));
    }

    /** @test */
    public function session_stores_correct_role_for_user_with_multiple_roles_as_admin()
    {
        // Create user with both admin and lecturer roles
        $user = User::create([
            'id_user' => 1002,
            'name' => 'Multi Role User',
            'gender' => 'F',
            'email' => 'multi@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'MULTI001',
            'date_of_employment' => 2018,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        Admin::create([
            'id_user' => $user->id_user,
        ]);

        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Login as admin (even though user has lecturer role too)
        $response = $this->post('/login', [
            'login_id' => 'MULTI001',
            'password' => 'password',
            'selected_role' => 'administrator',
        ]);

        $response->assertRedirect('/dashboard');
        
        // Verify session has admin role, not lecturer
        $this->assertEquals('administrator', session('selected_role'));
        $this->assertEquals(1002, session('id_user'));
    }

    /** @test */
    public function session_stores_correct_role_for_user_with_multiple_roles_as_lecturer()
    {
        // Create user with both admin and lecturer roles
        $user = User::create([
            'id_user' => 1003,
            'name' => 'Multi Role User 2',
            'gender' => 'M',
            'email' => 'multi2@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'MULTI002',
            'date_of_employment' => 2019,
            'teacher_status' => 'employed',
            'type' => 'honorer',
        ]);

        Admin::create([
            'id_user' => $user->id_user,
        ]);

        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Login as lecturer (even though user has admin role too)
        $response = $this->post('/login', [
            'login_id' => 'MULTI002',
            'password' => 'password',
            'selected_role' => 'lectureTeacher',
        ]);

        $response->assertRedirect('/dashboard');
        
        // Verify session has lecturer role, not admin
        $this->assertEquals('lectureTeacher', session('selected_role'));
        $this->assertEquals(1003, session('id_user'));
    }

    /** @test */
    public function session_stores_correct_role_for_teacher_with_homeroom()
    {
        // Create a class
        \Illuminate\Support\Facades\DB::table('tb_classes')->insert([
            'id_class' => 1,
            'name' => '10A',
            'grade' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create user with both lecturer and homeroom roles
        $user = User::create([
            'id_user' => 1004,
            'name' => 'Teacher Homeroom User',
            'gender' => 'F',
            'email' => 'teacherhomeroom@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'MULTI003',
            'date_of_employment' => 2017,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        Homeroom::create([
            'id_user' => $user->id_user,
            'id_class' => 1,
            'school_year' => '2023/2024',
        ]);

        // Login as homeroom teacher
        $response = $this->post('/login', [
            'login_id' => 'MULTI003',
            'password' => 'password',
            'selected_role' => 'homeroomTeacher',
        ]);

        $response->assertRedirect('/dashboard');
        
        // Verify session has homeroom role
        $this->assertEquals('homeroomTeacher', session('selected_role'));
        $this->assertEquals(1004, session('id_user'));
    }

    /** @test */
    public function login_controller_verifies_role_correctly_for_valid_role()
    {
        // Create user with lecturer role
        $user = User::create([
            'id_user' => 1005,
            'name' => 'Valid Role User',
            'gender' => 'M',
            'email' => 'validrole@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'VALID001',
            'date_of_employment' => 2021,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Try to login with valid role
        $response = $this->post('/login', [
            'login_id' => 'VALID001',
            'password' => 'password',
            'selected_role' => 'lectureTeacher',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    /** @test */
    public function login_controller_rejects_invalid_role_for_user()
    {
        // Create user with only lecturer role (no admin)
        $user = User::create([
            'id_user' => 1006,
            'name' => 'Invalid Role User',
            'gender' => 'F',
            'email' => 'invalidrole@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'INVALID001',
            'date_of_employment' => 2022,
            'teacher_status' => 'employed',
            'type' => 'honorer',
        ]);

        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Try to login with invalid role (admin when user is not admin)
        $response = $this->post('/login', [
            'login_id' => 'INVALID001',
            'password' => 'password',
            'selected_role' => 'administrator',
        ]);

        $response->assertSessionHasErrors(['selected_role']);
        $this->assertGuest();
    }

    /** @test */
    public function user_with_multiple_roles_can_login_with_any_valid_role()
    {
        // Create user with admin, lecturer, and homeroom roles
        $user = User::create([
            'id_user' => 1007,
            'name' => 'Triple Role User',
            'gender' => 'M',
            'email' => 'triple@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'TRIPLE001',
            'date_of_employment' => 2015,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        Admin::create([
            'id_user' => $user->id_user,
        ]);

        Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Create a class
        \Illuminate\Support\Facades\DB::table('tb_classes')->insert([
            'id_class' => 2,
            'name' => '11B',
            'grade' => 11,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Homeroom::create([
            'id_user' => $user->id_user,
            'id_class' => 2,
            'school_year' => '2024/2025',
        ]);

        // Test login as admin
        $response = $this->post('/login', [
            'login_id' => 'TRIPLE001',
            'password' => 'password',
            'selected_role' => 'administrator',
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertEquals('administrator', session('selected_role'));

        // Logout
        $this->post('/logout');

        // Clear rate limiter
        RateLimiter::clear('login_attempts:127.0.0.1:triple001');

        // Test login as lecturer
        $response = $this->post('/login', [
            'login_id' => 'TRIPLE001',
            'password' => 'password',
            'selected_role' => 'lectureTeacher',
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertEquals('lectureTeacher', session('selected_role'));

        // Logout
        $this->post('/logout');

        // Clear rate limiter
        RateLimiter::clear('login_attempts:127.0.0.1:triple001');

        // Test login as homeroom
        $response = $this->post('/login', [
            'login_id' => 'TRIPLE001',
            'password' => 'password',
            'selected_role' => 'homeroomTeacher',
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertEquals('homeroomTeacher', session('selected_role'));
    }
}
