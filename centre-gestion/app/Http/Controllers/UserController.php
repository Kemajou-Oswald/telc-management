<?php

namespace App\Http\Controllers;

use App\Models\Centre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected function availableRolesForUser($currentUser)
    {
        $roles = [
            'super_admin' => 'Super Admin (DG)',
            'directeur' => 'Directeur',
            'secretaire' => 'Secrétaire',
            'professeur' => 'Professeur',
        ];

        if ($currentUser->role === 'super_admin') {
            return $roles;
        }

        // Un directeur ne peut pas attribuer le rôle super_admin
        unset($roles['super_admin']);

        return $roles;
    }

    public function index()
    {
        $currentUser = auth()->user();

        $query = User::with('centre');

        if ($currentUser->role !== 'super_admin') {
            $query->where('role', '!=', 'super_admin');
        }

        $users = $query->orderBy('name')->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Formulaire de création d'un membre du personnel.
     */
    public function create()
    {
        $centres = Centre::orderBy('name')->get();
        $roles = $this->availableRolesForUser(auth()->user());

        return view('users.create', compact('centres', 'roles'));
    }

    /**
     * Enregistrer un nouveau membre du personnel.
     */
    public function store(Request $request)
    {
        $availableRoles = array_keys($this->availableRolesForUser(auth()->user()));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', Rule::in($availableRoles)],
            'centre_id' => ['nullable', 'exists:centres,id'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'centre_id' => $validated['centre_id'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Membre du personnel créé avec succès.');
    }

    public function edit(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->role !== 'super_admin' && $user->role === 'super_admin') {
            abort(403);
        }

        $centres = Centre::orderBy('name')->get();
        $roles = $this->availableRolesForUser($currentUser);

        return view('users.edit', compact('user', 'centres', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->role !== 'super_admin' && $user->role === 'super_admin') {
            abort(403);
        }

        $availableRoles = array_keys($this->availableRolesForUser($currentUser));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in($availableRoles)],
            'centre_id' => ['nullable', 'exists:centres,id'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'centre_id' => $validated['centre_id'] ?? null,
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->role !== 'super_admin' && $user->role === 'super_admin') {
            abort(403);
        }

        // Empêcher un utilisateur de se supprimer lui-même
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé.');
    }
}
