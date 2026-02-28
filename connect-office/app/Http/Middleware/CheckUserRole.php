<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check() || trim(Auth::user()->role) !== $role) {
            abort(403);
        }

        return $next($request);
    }
}
