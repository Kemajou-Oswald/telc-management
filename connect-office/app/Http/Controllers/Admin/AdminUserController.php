<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // 🔎 Recherche
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 🎭 Filtre rôle
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Si pas super admin → il ne voit que lui-même
        if (!auth()->user()->isSuperAdmin()) {
            $query->where('id', auth()->id());
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Administrateur créé.');
    }

    public function edit(User $user)
    {
        if (!auth()->user()->isSuperAdmin() && auth()->id() !== $user->id) {
            abort(403);
        }

        return view('admin.users.edit', compact('user'));
    }
    public function profile()
    {
        $user = auth()->user();
        return view('admin.users.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'nullable|min:6|confirmed';
        }

        if ($request->hasFile('profile_photo')) {
            $rules['profile_photo'] = 'image|max:2048';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès.');
    }


    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isSuperAdmin() && auth()->id() !== $user->id) {
            abort(403);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ];

        // Mot de passe optionnel
        if ($request->filled('password')) {
            $rules['password'] = 'nullable|min:6|confirmed';
        }

        // Rôle uniquement si super admin
        if (auth()->user()->isSuperAdmin()) {
            $rules['role'] = 'required|in:admin,super_admin';
        }

        $request->validate($rules);

        // Update des données
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if (auth()->user()->isSuperAdmin()) {
            $user->role = $request->role;
            $user->is_active = $request->has('is_active');
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Administrateur mis à jour avec succès.');
    }

    public function create()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        return view('admin.users.create');
    }


    public function destroy(User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Impossible de supprimer votre propre compte.');
        }

        $user->delete();

        return back()->with('success', 'Administrateur supprimé.');
    }

    public function __construct()
    {
        $this->middleware('role:super_admin')->only([
            'create',
            'store',
            'destroy'
        ]);
    }
}
