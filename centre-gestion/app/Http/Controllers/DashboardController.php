<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'super_admin') {
            return redirect()->route('dashboards.super_admin');
        }

        if ($user->role === 'directeur') {
            return redirect()->route('dashboards.directeur');
        }

        if ($user->role === 'secretaire') {
            return redirect()->route('dashboards.secretaire');
        }

        if ($user->role === 'professeur') {
            return redirect()->route('dashboards.professeur');
        }

        abort(403, 'Rôle non reconnu.');
    }
}