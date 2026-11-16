<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordResetSuccessController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        if (! $request->session()->pull('password_reset_success', false)) {
            return redirect()->route('login');
        }

        return view('auth.password-reset-success');
    }
}
