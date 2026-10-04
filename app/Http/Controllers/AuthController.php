<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Jumlah kegagalan login yang diperbolehkan untuk satu kombinasi email + IP
     * sebelum akun tersebut dikunci sementara.
     */
    private const MAX_LOGIN_ATTEMPTS = 5;

    /**
     * Durasi penguncian login dalam detik.
     */
    private const LOCKOUT_SECONDS = 300;

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = $this->throttleKey($request, $credentials['email']);

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
            event(new Lockout($request));

            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, self::LOCKOUT_SECONDS);

            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi yang dimasukkan tidak sesuai.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Kunci throttle digabung dengan IP agar percobaan dari banyak IP tetap
     * terhitung sebagai satu akun yang sama.
     */
    private function throttleKey(Request $request, string $email): string
    {
        return 'login|'.Str::transliterate(Str::lower($email)).'|'.$request->ip();
    }
}
