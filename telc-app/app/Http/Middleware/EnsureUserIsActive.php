<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Defense in depth: a blocked (or soft-deleted) user should not keep an active session.
            $isBlocked = (bool) ($user->is_blocked ?? false);
            $isTrashed = $user && method_exists($user, 'trashed') ? (bool) $user->trashed() : false;

            if ($isBlocked || $isTrashed) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Votre compte est desactive. Contactez l administration.',
                ]);
            }
        }

        return $next($request);
    }
}
