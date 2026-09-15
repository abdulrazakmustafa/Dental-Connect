<?php

namespace App\Modules\Identity\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Separate, unadvertised route for platform staff (PRD §10). Only accounts
 * holding admin/super_admin are accepted here — a correct password for a
 * non-admin account is still rejected with the same generic message so the
 * endpoint does not leak which accounts exist or are privileged.
 *
 * TODO(security, pre-launch): wire MFA challenge here before production
 * launch — required for super_admin/admin per PRD §64/IAM-004.
 */
class AdminLoginController extends Controller
{
    public function create(): View
    {
        return view('auth.admin-login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = 'admin|'.strtolower($credentials['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Too many attempts. Try again in {$seconds} seconds.",
            ]);
        }

        $ok = Auth::attempt($credentials);
        $user = Auth::user();

        if (! $ok || ! $user?->hasAnyRole(['admin', 'super_admin']) || ! $user->isActive()) {
            if ($ok) {
                Auth::logout();
            }
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        $user->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(route('admin.dashboard'));
    }
}
