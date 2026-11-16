<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=AuthTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * Authentication (web + API). The API endpoints are Group A's
 * ApiTokenController: POST /api/v1/login (device_name is required),
 * POST /api/v1/logout, GET /api/v1/me.
 */
class AuthTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_guest_cannot_access_protected_web_routes(): void
    {
        $this->get('/reports')->assertRedirect('/login');
        $this->get('/backups')->assertRedirect('/login');
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_user_can_log_in_with_valid_credentials(): void
    {
        $user = $this->makeUser('General Staff', ['password' => Hash::make('secret123')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_log_in_with_wrong_password(): void
    {
        $user = $this->makeUser('General Staff', ['password' => Hash::make('secret123')]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_password_is_stored_hashed_not_plain_text(): void
    {
        $user = $this->makeUser('General Staff', ['password' => Hash::make('secret123')]);

        $this->assertNotSame('secret123', $user->password);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_login_is_throttled_after_repeated_failures(): void
    {
        $user = $this->makeUser('General Staff', ['password' => Hash::make('secret123')]);

        for ($i = 0; $i < 6; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'wrong']);
        }

        $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertStatus(429);
    }

    public function test_api_login_returns_sanctum_token(): void
    {
        $user = $this->makeUser('General Staff', ['password' => Hash::make('secret123')]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'secret123',
            'device_name' => 'test-device',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['data' => ['token', 'user' => ['id', 'email']]]);
    }

    public function test_api_login_rejects_wrong_credentials(): void
    {
        $user = $this->makeUser('General Staff', ['password' => Hash::make('secret123')]);

        $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrong',
            'device_name' => 'test-device',
        ])->assertStatus(422);
    }

    public function test_api_login_requires_device_name(): void
    {
        $user = $this->makeUser('General Staff', ['password' => Hash::make('secret123')]);

        $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ])->assertStatus(422)
            ->assertJsonValidationErrors('device_name');
    }

    public function test_api_logout_revokes_current_token(): void
    {
        $user = $this->makeSuperAdmin();

        $login = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'test-device',
        ])->assertOk();

        $token = $login->json('data.token');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/logout')
            ->assertOk();

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/me')
            ->assertUnauthorized();
    }
}
