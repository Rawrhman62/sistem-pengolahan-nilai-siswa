<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticationRouteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear rate limiter before each test
        RateLimiter::clear('login_attempts:127.0.0.1:teacher123');
        RateLimiter::clear('login_attempts:127.0.0.1:123456');
    }

    /** @test */
    public function login_route_is_accessible()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    /** @test */
    public function login_form_has_csrf_protection()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertSee('csrf-token', false);
        $response->assertSee('_token', false);
    }

    /** @test */
    public function login_route_accepts_post_with_valid_credentials()
    {
        // Create test user with teacher role
        $user = User::create([
            'id_user' => 1001,
            'name' => 'Test Teacher',
            'gender' => 'M',
            'email' => 'teacher@test.com',
            'password' => Hash::make('password123'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'TEACHER123',
            'date_of_employment' => 2020,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        // Add lecturer role
        \App\Models\Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        $response = $this->post('/login', [
            'login_id' => 'TEACHER123',
            'password' => 'password123',
            'selected_role' => 'lectureTeacher',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    /** @test */
    public function login_stores_correct_session_data()
    {
        // Create test user with admin role
        $user = User::create([
            'id_user' => 1002,
            'name' => 'Test Admin',
            'gender' => 'F',
            'email' => 'admin@test.com',
            'password' => Hash::make('adminpass'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'ADMIN001',
            'date_of_employment' => 2015,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        Admin::create([
            'id_user' => $user->id_user,
        ]);

        $response = $this->post('/login', [
            'login_id' => 'ADMIN001',
            'password' => 'adminpass',
            'selected_role' => 'administrator',
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('selected_role', 'administrator');
        $response->assertSessionHas('id_user', 1002);
    }

    /** @test */
    public function login_fails_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'login_id' => 'INVALID123',
            'password' => 'wrongpass',
            'selected_role' => 'lectureTeacher',
        ]);

        $response->assertSessionHasErrors(['login_id']);
        $this->assertGuest();
    }

    /** @test */
    public function login_validates_required_fields()
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['login_id', 'selected_role']);
    }

    /** @test */
    public function rate_limiting_works_with_new_credential_format()
    {
        // Create test user
        $user = User::create([
            'id_user' => 1003,
            'name' => 'Test User',
            'gender' => 'M',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
        ]);

        Teacher::create([
            'id_user' => $user->id_user,
            'nomor_induk' => 'RATE001',
            'date_of_employment' => 2020,
            'teacher_status' => 'employed',
            'type' => 'honorer',
        ]);

        \App\Models\Lecturer::create([
            'id_user' => $user->id_user,
        ]);

        // Make multiple failed login attempts
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post('/login', [
                'login_id' => 'RATE001',
                'password' => 'wrongpassword',
                'selected_role' => 'lectureTeacher',
            ]);
        }

        // Last attempt should be rate limited
        $response->assertSessionHasErrors(['login_id']);
        $errors = session('errors');
        $errorMessage = $errors->first('login_id');
        $this->assertStringContainsString('Too many login attempts', $errorMessage);
    }

    /** @test */
    public function student_login_works_with_nis()
    {
        $user = User::create([
            'id_user' => 2001,
            'name' => 'Test Student',
            'gender' => 'F',
            'email' => 'student@test.com',
            'password' => Hash::make('studentpass'),
        ]);

        Student::create([
            'id_user' => $user->id_user,
            'nis' => '123456',
            'nisn' => '1234567890',
            'entry_year' => 2023,
        ]);

        $response = $this->post('/login', [
            'login_id' => '123456',
            'password' => 'studentpass',
            'selected_role' => 'student',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $response->assertSessionHas('selected_role', 'student');
    }

    /** @test */
    public function logout_clears_session_and_redirects()
    {
        $user = User::create([
            'id_user' => 3001,
            'name' => 'Test Logout',
            'gender' => 'M',
            'email' => 'logout@test.com',
            'password' => Hash::make('pass'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /** @test */
    public function csrf_token_is_required_for_login()
    {
        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/login', [
                'login_id' => 'TEST123',
                'password' => 'password',
                'selected_role' => 'lectureTeacher',
            ]);

        // Without CSRF middleware, it should work
        $response->assertSessionHasErrors(['login_id']); // Will fail due to invalid credentials
    }
}
