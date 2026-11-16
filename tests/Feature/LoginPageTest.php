<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class LoginPageTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_login_page_has_show_hide_password_remember_and_forgot_link(): void
    {
        $response = $this->get('/login');

        $response->assertOk()
            ->assertSee('data-password-toggle="loginPassword"', false)
            ->assertSee('name="remember"', false)
            ->assertSee(route('password.request'), false)
            ->assertSee('data-loading-form', false)
            ->assertSee('Forgot password?');
    }

    public function test_branding_logo_is_public_and_does_not_redirect_to_login(): void
    {
        // No company profile exists in the test database, so the controller
        // must return 404 — importantly NOT a redirect to /login.
        $this->get('/branding/logo')->assertNotFound();
    }

    public function test_active_user_can_log_in_and_is_redirected_to_the_dashboard(): void
    {
        $user = $this->makeUser('Super Admin');

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }
}
