<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingPageTest extends TestCase
{
    /**
     * Test that unauthenticated users see landing page at root URL.
     */
    public function test_unauthenticated_users_see_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('landing');
        $response->assertSee('E-RAPOR');
        $response->assertSee('Sistem Informasi Raport Elektronik');
        $response->assertSee('Electronic Report Card System');
        $response->assertSee('Login to E-RAPOR');
    }

    /**
     * Test that landing page contains login button with correct link.
     */
    public function test_landing_page_contains_login_button(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('href="/login"', false);
        $response->assertSee('Login to E-RAPOR');
    }

    /**
     * Test that login route is accessible and shows login form.
     */
    public function test_login_route_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    /**
     * Test that dashboard route requires authentication.
     */
    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Test that admin routes require authentication.
     */
    public function test_admin_routes_require_authentication(): void
    {
        $response = $this->get('/admin');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Test that all main public routes are accessible as expected.
     */
    public function test_public_route_accessibility(): void
    {
        // Test public routes
        $publicRoutes = [
            '/' => 200,
            '/login' => 200,
        ];

        foreach ($publicRoutes as $route => $expectedStatus) {
            $response = $this->get($route);
            $response->assertStatus($expectedStatus);
        }
    }

    /**
     * Test that protected routes redirect to login when unauthenticated.
     */
    public function test_protected_routes_redirect_to_login(): void
    {
        $protectedRoutes = [
            '/dashboard',
            '/settings',
            '/admin',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(302);
        }
    }
}