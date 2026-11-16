<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class PasswordResetTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_forgot_password_sends_a_notification_for_an_existing_user(): void
    {
        Notification::fake();

        $user = $this->makeUser('General Staff');

        $this->post('/forgot-password', ['email' => $user->email])
            ->assertRedirect()
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_forgot_password_does_not_reveal_whether_an_email_exists(): void
    {
        Notification::fake();

        $response = $this->post('/forgot-password', ['email' => 'nobody@example.test']);

        $response->assertRedirect()->assertSessionHas('status');
        Notification::assertNothingSent();
    }

    public function test_a_valid_token_resets_the_password_once(): void
    {
        $user = $this->makeUser('General Staff');
        $token = Password::broker()->createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPass!234',
            'password_confirmation' => 'NewPass!234',
        ]);

        $response->assertRedirect(route('password.reset.success'));
        $this->assertTrue(Hash::check('NewPass!234', $user->fresh()->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);

        // Reusing the same token must fail.
        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Another!234',
            'password_confirmation' => 'Another!234',
        ])->assertSessionHasErrors('email');
    }

    public function test_an_invalid_token_is_rejected_without_changing_the_password(): void
    {
        $user = $this->makeUser('General Staff');
        $original = $user->password;

        $this->post('/reset-password', [
            'token' => 'not-a-real-token',
            'email' => $user->email,
            'password' => 'NewPass!234',
            'password_confirmation' => 'NewPass!234',
        ])->assertSessionHasErrors('email');

        $this->assertSame($original, $user->fresh()->password);
    }

    public function test_password_confirmation_and_security_rules_are_enforced(): void
    {
        $user = $this->makeUser('General Staff');
        $token = Password::broker()->createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'weakpass',
            'password_confirmation' => 'different',
        ])->assertSessionHasErrors('password');
    }

    public function test_success_page_requires_a_completed_reset(): void
    {
        $this->get('/reset-password/success')->assertRedirect(route('login'));
    }

    public function test_success_page_renders_after_a_reset(): void
    {
        $this->withSession([
            'password_reset_success' => true,
            'status' => 'Your password has been updated.',
        ])
            ->get('/reset-password/success')
            ->assertOk()
            ->assertSee('Password updated');
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        $user = $this->makeUser('General Staff', ['status' => 'inactive']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
