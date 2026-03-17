<?php

namespace App\Http\Controllers;

use App\Models\LoginAudit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $maxAttempts = (int) env('LOGIN_MAX_ATTEMPTS', 5);
        $decaySeconds = (int) env('LOGIN_DECAY_SECONDS', 60);
        $throttleKey = Str::lower($credentials['email']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->logLoginEvent('login_throttled', null, $request);

            return back()->withInput($request->except('password'))->withErrors([
                'email' => 'Trop de tentatives. Reessayez dans ' . $seconds . ' secondes.',
            ]);
        }

        // Méthode simplifiée pour contourner le problème
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($throttleKey, $decaySeconds);
            $this->logLoginEvent('login_failed', $user, $request);

            return back()->withInput($request->except('password'))->withErrors([
                'email' => 'Identifiants invalides.',
            ]);
        }

        if ($user->is_blocked) {
            RateLimiter::hit($throttleKey, $decaySeconds);
            $this->logLoginEvent('login_blocked', $user, $request);

            return back()->withInput($request->except('password'))->withErrors([
                'email' => 'Ce compte est actuellement bloque par l\'administration.',
            ]);
        }

        // Connexion manuelle
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        RateLimiter::clear($throttleKey);
        $this->logLoginEvent('login', $user, $request);

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $this->logLoginEvent('logout', $user, $request);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function logLoginEvent(string $action, ?User $user, Request $request): void
    {
        LoginAudit::create([
            'user_id' => optional($user)->id,
            'action' => $action,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);
    }
}
