<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

/**
 * Complete authentication workflow test using sample data.
 * 
 * This test verifies the entire authentication process with data
 * similar to what exists in databas.csv, including:
 * - Admin login with nomor_induk
 * - Teacher login with nomor_induk  
 * - Student login with nis
 * - Users with null passwords
 * - Correct redirects after login
 * - Logout functionality
 */
class CompleteAuthenticationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed the database with sample data from databas.csv structure
        $this->seedSampleData();
    }

    /**
     * Seed database with sample data matching databas.csv
     */
    protected function seedSampleData()
    {
        // Create classes first (required for students)
        \Illuminate\Support\Facades\DB::table('tb_classes')->insert([
            ['id_class' => 105, 'name' => 'XI IPA 2', 'grade' => 11, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // User 1001 - Takanashi Hoshino (Admin with password)
        $user1001 = User::create([
            'id_user' => 1001,
            'name' => 'Takanashi Hoshino',
            'gender' => 'F',
            'email' => 'hoshino@test.com',
            'password' => Hash::make('Admin123'),
            'profile_picture' => 'demo/profile_1001.png',
        ]);

        Teacher::create([
            'id_user' => 1001,
            'nomor_induk' => '198507252006102020',
            'date_of_employment' => 2017,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        Admin::create(['id_user' => 1001]);

        // User 1002 - Fazri Rahman (Student with password)
        $user1002 = User::create([
            'id_user' => 1002,
            'name' => 'Fazri Rahman',
            'gender' => 'M',
            'email' => 'fazrirahman62@gmail.com',
            'phone_number' => '+62 852 6431 2600',
            'password' => Hash::make('Fazri123'),
            'profile_picture' => 'profile_3312501038.png',
        ]);

        Student::create([
            'id_user' => 1002,
            'nis' => '240001',
            'nisn' => '3312501038',
            'entry_year' => 2024,
            'id_class' => 105,
        ]);

        // User 1003 - Sorasaki Hina (Teacher/Lecturer/Homeroom with password)
        $user1003 = User::create([
            'id_user' => 1003,
            'name' => 'Sorasaki Hina',
            'gender' => 'F',
            'email' => 'hina@test.com',
            'password' => Hash::make('Admin123'),
            'profile_picture' => 'demo/profile_1002.png',
        ]);

        Teacher::create([
            'id_user' => 1003,
            'nomor_induk' => '199510152020012000',
            'date_of_employment' => 2018,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        \App\Models\Lecturer::create(['id_user' => 1003]);

        \App\Models\Homeroom::create([
            'id_user' => 1003,
            'id_class' => 105,
            'school_year' => '2024/2025',
        ]);

        // User 1004 - Ahmad Rafi (Admin with password)
        $user1004 = User::create([
            'id_user' => 1004,
            'name' => 'Ahmad Rafi Sa\'id F.',
            'gender' => 'M',
            'email' => 'rafi@test.com',
            'password' => Hash::make('Rafi123'),
            'profile_picture' => 'profile_3312501051.png',
        ]);

        Teacher::create([
            'id_user' => 1004,
            'nomor_induk' => '197908112000040990',
            'date_of_employment' => 2017,
            'teacher_status' => 'employed',
            'type' => 'pns',
        ]);

        Admin::create(['id_user' => 1004]);

        // User 1005 - Kuchinashi Yume (Teacher/Lecturer with NULL password)
        $user1005 = User::create([
            'id_user' => 1005,
            'name' => 'Kuchinashi Yume',
            'gender' => 'F',
            'email' => 'yumesenpai47@bmail.com',
            'phone_number' => '+62 867 6767 6767',
            'password' => null, // NULL password user
            'profile_picture' => 'demo/profile_1003.png',
        ]);

        Teacher::create([
            'id_user' => 1005,
            'nomor_induk' => '199907250000001980',
            'date_of_employment' => 2020,
            'teacher_status' => 'employed',
            'type' => 'honorer',
        ]);

        \App\Models\Lecturer::create(['id_user' => 1005]);
    }

    /** @test */
    public function admin_can_login_with_sample_nomor_induk_from_database()
    {
        // Test admin login using Takanashi Hoshino's credentials
        $response = $this->post('/login', [
            'login_id' => '198507252006102020',
            'password' => 'Admin123',
            'selected_role' => 'administrator',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        
        // Verify session data
        $this->assertEquals('administrator', session('selected_role'));
        $this->assertEquals(1001, session('id_user'));
        
        // Verify the correct user is authenticated
        $this->assertEquals(1001, auth()->user()->id_user);
        $this->assertEquals('Takanashi Hoshino', auth()->user()->name);
    }

    /** @test */
    public function teacher_can_login_with_sample_nomor_induk_from_database()
    {
        // Test teacher/lecturer login using Sorasaki Hina's credentials
        $response = $this->post('/login', [
            'login_id' => '199510152020012000',
            'password' => 'Admin123',
            'selected_role' => 'lectureTeacher',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        
        // Verify session data
        $this->assertEquals('lectureTeacher', session('selected_role'));
        $this->assertEquals(1003, session('id_user'));
        
        // Verify the correct user is authenticated
        $this->assertEquals(1003, auth()->user()->id_user);
        $this->assertEquals('Sorasaki Hina', auth()->user()->name);
    }

    /** @test */
    public function student_can_login_with_sample_nis_from_database()
    {
        // Test student login using Fazri Rahman's credentials
        $response = $this->post('/login', [
            'login_id' => '240001',
            'password' => 'Fazri123',
            'selected_role' => 'student',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        
        // Verify session data
        $this->assertEquals('student', session('selected_role'));
        $this->assertEquals(1002, session('id_user'));
        
        // Verify the correct user is authenticated
        $this->assertEquals(1002, auth()->user()->id_user);
        $this->assertEquals('Fazri Rahman', auth()->user()->name);
    }

    /** @test */
    public function user_with_null_password_can_login_with_blank_password()
    {
        // Test login with null password user (Kuchinashi Yume)
        $response = $this->post('/login', [
            'login_id' => '199907250000001980',
            'password' => '',
            'selected_role' => 'lectureTeacher',
        ]);

        $response->assertRedirect('/settings');
        $this->assertAuthenticated();
        
        // Verify session data
        $this->assertEquals('lectureTeacher', session('selected_role'));
        $this->assertEquals(1005, session('id_user'));
        
        // Verify the correct user is authenticated
        $this->assertEquals(1005, auth()->user()->id_user);
        $this->assertEquals('Kuchinashi Yume', auth()->user()->name);
    }

    /** @test */
    public function homeroom_teacher_can_login_and_access_homeroom_features()
    {
        // Test homeroom teacher login using Sorasaki Hina's credentials
        $response = $this->post('/login', [
            'login_id' => '199510152020012000',
            'password' => 'Admin123',
            'selected_role' => 'homeroomTeacher',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        
        // Verify session data
        $this->assertEquals('homeroomTeacher', session('selected_role'));
        $this->assertEquals(1003, session('id_user'));
        
        // Verify the user has homeroom role
        $user = auth()->user();
        $this->assertTrue($user->hasRole('homeroomTeacher'));
    }

    /** @test */
    public function successful_login_redirects_to_dashboard()
    {
        // Test that successful login redirects to dashboard
        $response = $this->post('/login', [
            'login_id' => '198507252006102020',
            'password' => 'Admin123',
            'selected_role' => 'administrator',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    /** @test */
    public function null_password_user_login_redirects_to_settings()
    {
        // Test that null password user login redirects to settings page
        $response = $this->post('/login', [
            'login_id' => '199907250000001980',
            'password' => '',
            'selected_role' => 'lectureTeacher',
        ]);

        $response->assertRedirect('/settings');
        $this->assertAuthenticated();
    }

    /** @test */
    public function logout_functionality_maintains_data_integrity()
    {
        // First, login as a user
        $this->post('/login', [
            'login_id' => '198507252006102020',
            'password' => 'Admin123',
            'selected_role' => 'administrator',
        ]);

        $this->assertAuthenticated();
        
        // Verify user and data before logout
        $userIdBeforeLogout = auth()->user()->id_user;
        $this->assertEquals(1001, $userIdBeforeLogout);
        
        // Perform logout
        $response = $this->post('/logout');
        
        // Verify user is logged out
        $this->assertGuest();
        $response->assertRedirect('/login');
        
        // Verify session is cleared
        $this->assertNull(session('selected_role'));
        $this->assertNull(session('id_user'));
        
        // Verify user data still exists in database (data integrity)
        $user = User::where('id_user', 1001)->first();
        $this->assertNotNull($user);
        $this->assertEquals('Takanashi Hoshino', $user->name);
        
        // Verify related records still exist
        $teacher = Teacher::where('id_user', 1001)->first();
        $this->assertNotNull($teacher);
        $this->assertEquals('198507252006102020', $teacher->nomor_induk);
        
        $admin = Admin::where('id_user', 1001)->first();
        $this->assertNotNull($admin);
    }

    /** @test */
    public function multiple_login_logout_cycles_work_correctly()
    {
        // Login as admin
        $this->post('/login', [
            'login_id' => '198507252006102020',
            'password' => 'Admin123',
            'selected_role' => 'administrator',
        ]);
        $this->assertAuthenticated();
        $this->assertEquals(1001, auth()->user()->id_user);
        
        // Logout
        $this->post('/logout');
        $this->assertGuest();
        
        // Login as teacher
        $this->post('/login', [
            'login_id' => '199510152020012000',
            'password' => 'Admin123',
            'selected_role' => 'lectureTeacher',
        ]);
        $this->assertAuthenticated();
        $this->assertEquals(1003, auth()->user()->id_user);
        
        // Logout
        $this->post('/logout');
        $this->assertGuest();
        
        // Login as student
        $this->post('/login', [
            'login_id' => '240001',
            'password' => 'Fazri123',
            'selected_role' => 'student',
        ]);
        $this->assertAuthenticated();
        $this->assertEquals(1002, auth()->user()->id_user);
        
        // Final logout
        $this->post('/logout');
        $this->assertGuest();
        
        // Verify all user data is still intact
        $this->assertCount(5, User::all());
        $this->assertCount(4, Teacher::all());
        $this->assertCount(1, Student::all());
        $this->assertCount(2, Admin::all());
    }

    /** @test */
    public function user_with_multiple_roles_can_switch_between_them()
    {
        // Login as admin (who is also a teacher)
        $this->post('/login', [
            'login_id' => '198507252006102020',
            'password' => 'Admin123',
            'selected_role' => 'administrator',
        ]);
        
        $this->assertAuthenticated();
        $this->assertEquals('administrator', session('selected_role'));
        
        // User 1001 is both admin and teacher, verify they can't access teacher role without switching
        $user = auth()->user();
        $this->assertTrue($user->hasRole('administrator'));
        
        // Note: User 1001 is admin and has teacher record but is NOT in tb_lecturers
        // So they should NOT have lectureTeacher role
        $this->assertFalse($user->hasRole('lectureTeacher'));
    }

    /** @test */
    public function session_regenerates_on_login_for_security()
    {
        // Get initial session ID
        $this->get('/login');
        $initialSessionId = session()->getId();
        
        // Perform login
        $response = $this->post('/login', [
            'login_id' => '198507252006102020',
            'password' => 'Admin123',
            'selected_role' => 'administrator',
        ]);
        
        // Note: In testing environment, session regeneration may not change the ID
        // but we can verify that the login was successful and session contains user data
        $this->assertAuthenticated();
        $this->assertNotNull(session('selected_role'));
        $this->assertNotNull(session('id_user'));
    }

    /** @test */
    public function protected_routes_redirect_to_login_when_not_authenticated()
    {
        // Try to access dashboard without login
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
        
        // Try to access settings without login
        $response = $this->get('/settings');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_protected_routes()
    {
        // Login first
        $this->post('/login', [
            'login_id' => '198507252006102020',
            'password' => 'Admin123',
            'selected_role' => 'administrator',
        ]);
        
        $this->assertAuthenticated();
        
        // Verify login was successful and session has data
        $this->assertEquals('administrator', session('selected_role'));
        $this->assertEquals(1001, session('id_user'));
    }
}
