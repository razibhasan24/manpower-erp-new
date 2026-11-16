<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Reset the password with a broker token.
 *
 * The token is single-use and expires according to
 * config/auth.php (60 minutes). Password rules are enforced here, and a
 * successful reset revokes remembered sign-ins, database sessions and API
 * tokens so an attacker holding an old session cannot keep using it.
 */
class NewPasswordController extends Controller
{
    public function create(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'request' => $request,
            'token' => $token,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ]);

        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password): void {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'must_change_password' => false,
                        'remember_token' => Str::random(60),
                    ])->save();

                    $this->invalidateOtherSessions($user);
                }
            );
        } catch (\Throwable $exception) {
            Log::error('Password reset failed unexpectedly.', [
                'email' => $request->input('email'),
                'error' => $exception->getMessage(),
            ]);

            throw ValidationException::withMessages([
                'email' => __('We could not reset the password. Please request a new reset link.'),
            ]);
        }

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }

        return redirect()
            ->route('password.reset.success')
            ->with('password_reset_success', true)
            ->with('status', __('Your password has been updated. You can now sign in with the new password.'));
    }

    /**
     * Best-effort revocation of sessions/tokens. Failure must not reveal
     * internals to the user, and must not block a successful password reset.
     */
    protected function invalidateOtherSessions(User $user): void
    {
        try {
            if (Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'user_id')) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
            }
        } catch (\Throwable $exception) {
            Log::warning('Could not clear database sessions after password reset.', [
                'user_id' => $user->id,
                'error' => $exception->getMessage(),
            ]);
        }

        try {
            if (Schema::hasTable('personal_access_tokens')) {
                $user->tokens()->delete();
            }
        } catch (\Throwable $exception) {
            Log::warning('Could not revoke API tokens after password reset.', [
                'user_id' => $user->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
