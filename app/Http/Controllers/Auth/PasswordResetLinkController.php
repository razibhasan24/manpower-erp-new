<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

/**
 * Forgot-password request.
 *
 * Uses Laravel's native password broker. The response is deliberately the
 * same whether the email exists or not (no account enumeration), and mail
 * failures are logged without leaking account existence to the browser.
 */
class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $genericMessage = __('If an account exists for that email address, a password reset link has been sent. Please check your inbox and spam folder.');

        try {
            $status = Password::sendResetLink($request->only('email'));
        } catch (\Throwable $exception) {
            Log::error('Password reset email could not be sent.', [
                'email' => $request->input('email'),
                'error' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('password.request')
                ->withInput($request->only('email'))
                ->with('status', $genericMessage);
        }

        if ($status === Password::RESET_THROTTLED) {
            return redirect()
                ->route('password.request')
                ->withInput($request->only('email'))
                ->withErrors(['email' => __('Please wait a moment before requesting another reset link.')]);
        }

        // RESET_LINK_SENT and INVALID_USER intentionally produce the same
        // response. The failure reason is only written to the application log.
        if ($status !== Password::RESET_LINK_SENT) {
            Log::notice('Password reset requested for an unknown email address.', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
            ]);
        }

        return redirect()
            ->route('password.request')
            ->withInput($request->only('email'))
            ->with('status', $genericMessage);
    }
}
