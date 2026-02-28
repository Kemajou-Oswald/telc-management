<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {

            if (!Auth::user()->is_active) {

                Auth::logout();

                return redirect('/')
                    ->with('error', 'Votre compte a été désactivé.');
            }
        }

        return $next($request);
    }
}
