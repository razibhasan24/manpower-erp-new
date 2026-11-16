<?php

namespace App\Http\Requests\Auth;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Login validation + throttling + login-history recording
 * (Phase 01 section 13 items 12-14).
 */
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            LoginHistory::create([
                'user_id' => \App\Models\User::where('email', $this->input('email'))->value('id'),
                'ip_address' => $this->ip(),
                'user_agent' => $this->userAgent(),
                'login_at' => now(),
                'status' => 'failed',
                'failed_reason' => 'Invalid credentials.',
            ]);

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // A suspended or inactive account must not keep a session, even when
        // the password is correct. The message stays generic so the login
        // form cannot be used to enumerate account states.
        $user = Auth::user();

        if ($user !== null && $user->status !== 'active') {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());

            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $this->ip(),
                'user_agent' => $this->userAgent(),
                'login_at' => now(),
                'status' => 'failed',
                'failed_reason' => 'Account is '.$user->status.'.',
            ]);

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', ['seconds' => $seconds]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
