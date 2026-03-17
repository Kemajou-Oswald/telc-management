<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $archived = $request->boolean('archived');

        if ($this->isCenterAdmin()) {
            if ($archived) {
                abort(403);
            }

            $centerIds = $this->accessibleCenterIds();
            if (empty($centerIds)) {
                abort(403);
            }

            $usersQuery = User::with(['center', 'audits.performer', 'audits.affectedUser'])
                ->where('role', User::ROLE_SECRETARY)
                ->latest();

            if (User::supportsCenterAssignments()) {
                $usersQuery->where(function ($builder) use ($centerIds) {
                    $builder->whereIn('center_id', $centerIds)
                        ->orWhereHas('centers', function ($centerQuery) use ($centerIds) {
                            $centerQuery->whereIn('centers.id', $centerIds);
                        });
                });
            } else {
                $usersQuery->whereIn('center_id', $centerIds);
            }

            $users = $usersQuery->get();
            $users->each->primeCentersRelation();

            return view('users-index-managed', [
                'centers' => $this->accessibleCenters(),
                'showCreate' => false,
                'user' => $this->authUser(),
                'archived' => false,
                'totalUsers' => $users->count(),
                'activeUsers' => $users->where('is_blocked', false)->count(),
                'blockedUsers' => $users->where('is_blocked', true)->count(),
                'archivedUsers' => 0,
                'users' => $users,
            ]);
        }

        $this->onlySuperAdmin();

        $usersQuery = User::with(['center', 'audits.performer', 'audits.affectedUser'])->latest();

        if ($archived) {
            $usersQuery->onlyTrashed();
        }

        $users = $usersQuery->get();
        $users->each->primeCentersRelation();

        return view('users-index-managed', [
            'centers' => Center::where('is_active', true)->orderBy('name')->get(),
            'showCreate' => false,
            'user' => $this->authUser(),
            'archived' => $archived,
            'totalUsers' => User::withTrashed()->count(),
            'activeUsers' => User::where('is_blocked', false)->count(),
            'blockedUsers' => User::where('is_blocked', true)->count(),
            'archivedUsers' => User::onlyTrashed()->count(),
            'users' => $users,
        ]);
    }

    public function create()
    {
        if ($this->isCenterAdmin()) {
            return view('users.create', [
                'centers' => $this->accessibleCenters(),
                'user' => $this->authUser(),
            ]);
        }

        $this->onlySuperAdmin();

        return view('users.create', [
            'centers' => Center::where('is_active', true)->orderBy('name')->get(),
            'user' => $this->authUser(),
        ]);
    }

    public function store(Request $request)
    {
        if ($this->isCenterAdmin()) {
            $centerIds = $this->accessibleCenterIds();
            if (empty($centerIds)) {
                abort(403);
            }

            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'phone' => ['nullable', 'string', 'max:50'],
                'center_id' => ['nullable', 'integer', Rule::in($centerIds)],
                'center_ids' => ['required', 'array', 'min:1'],
                'center_ids.*' => ['integer', Rule::in($centerIds)],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $data['role'] = User::ROLE_SECRETARY;
        } else {
            $this->onlySuperAdmin();

            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'phone' => ['nullable', 'string', 'max:50'],
                'role' => ['required', Rule::in([User::ROLE_SUPER_ADMIN, User::ROLE_CENTER_ADMIN, User::ROLE_SECRETARY])],
                'center_id' => ['nullable', 'exists:centers,id'],
                'center_ids' => ['nullable', 'array'],
                'center_ids.*' => ['integer', 'exists:centers,id'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        }

        $assignedCenterIds = collect($data['center_ids'] ?? [])->map(function ($value) {
            return (int) $value;
        })->filter()->unique()->values();

        if ($data['role'] === User::ROLE_SUPER_ADMIN) {
            $data['center_id'] = null;
            $assignedCenterIds = collect();
        } else {
            if ($assignedCenterIds->isEmpty()) {
                throw ValidationException::withMessages([
                    'center_ids' => 'Selectionnez au moins un centre pour ce compte.',
                ]);
            }

            $primaryCenterId = ! empty($data['center_id']) ? (int) $data['center_id'] : (int) $assignedCenterIds->first();
            if (! $assignedCenterIds->contains($primaryCenterId)) {
                $assignedCenterIds->prepend($primaryCenterId);
                $assignedCenterIds = $assignedCenterIds->unique()->values();
            }

            $data['center_id'] = $primaryCenterId;
        }

        $data['password'] = Hash::make($data['password']);
        unset($data['center_ids']);

        $managedUser = User::create($data);

        if ($data['role'] !== User::ROLE_SUPER_ADMIN && User::supportsCenterAssignments()) {
            $managedUser->centers()->sync($assignedCenterIds->all());
        }

        $managedUser->audits()->create([
            'performed_by' => $this->authUser()->id,
            'action' => 'created',
            'details' => 'Compte cree par ' . $this->authUser()->name,
        ]);

        return redirect()->route('users.index')->with('success', 'Compte utilisateur cree avec succes.');
    }

    public function edit(User $managedUser)
    {
        if ($this->isCenterAdmin()) {
            $this->authorizeManagedSecretary($managedUser);

            $managedUser->load('center');
            $managedUser->primeCentersRelation();

            return view('users.edit', [
                'managedUser' => $managedUser,
                'centers' => $this->accessibleCenters(),
                'user' => $this->authUser(),
            ]);
        }

        $this->onlySuperAdmin();

        $managedUser->load('center');
        $managedUser->primeCentersRelation();

        return view('users.edit', [
            'managedUser' => $managedUser,
            'centers' => Center::where('is_active', true)->orderBy('name')->get(),
            'user' => $this->authUser(),
        ]);
    }

    public function update(Request $request, User $managedUser)
    {
        if ($this->isCenterAdmin()) {
            $this->authorizeManagedSecretary($managedUser);

            $centerIds = $this->accessibleCenterIds();
            if (empty($centerIds)) {
                abort(403);
            }

            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($managedUser->id)],
                'phone' => ['nullable', 'string', 'max:50'],
                'center_id' => ['nullable', 'integer', Rule::in($centerIds)],
                'center_ids' => ['required', 'array', 'min:1'],
                'center_ids.*' => ['integer', Rule::in($centerIds)],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ]);

            $data['role'] = User::ROLE_SECRETARY;
        } else {
            $this->onlySuperAdmin();

            // Empêcher la modification d'un autre super admin
            if ($managedUser->role === User::ROLE_SUPER_ADMIN && $managedUser->id !== $this->authUser()->id) {
                return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas modifier un autre super administrateur.');
            }

            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($managedUser->id)],
                'phone' => ['nullable', 'string', 'max:50'],
                'role' => ['required', Rule::in([User::ROLE_SUPER_ADMIN, User::ROLE_CENTER_ADMIN, User::ROLE_SECRETARY])],
                'center_id' => ['nullable', 'exists:centers,id'],
                'center_ids' => ['nullable', 'array'],
                'center_ids.*' => ['integer', 'exists:centers,id'],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ]);

            // Empêcher le changement de rôle d'un super admin
            if ($managedUser->role === User::ROLE_SUPER_ADMIN && $data['role'] !== User::ROLE_SUPER_ADMIN) {
                return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas changer le rôle d\'un super administrateur.');
            }
        }

        $assignedCenterIds = collect($data['center_ids'] ?? [])->map(function ($value) {
            return (int) $value;
        })->filter()->unique()->values();

        if ($data['role'] === User::ROLE_SUPER_ADMIN) {
            $data['center_id'] = null;
            $assignedCenterIds = collect();
        } else {
            if ($assignedCenterIds->isEmpty()) {
                throw ValidationException::withMessages([
                    'center_ids' => 'Selectionnez au moins un centre pour ce compte.',
                ]);
            }

            $primaryCenterId = ! empty($data['center_id']) ? (int) $data['center_id'] : (int) $assignedCenterIds->first();
            if (! $assignedCenterIds->contains($primaryCenterId)) {
                $assignedCenterIds->prepend($primaryCenterId);
                $assignedCenterIds = $assignedCenterIds->unique()->values();
            }

            $data['center_id'] = $primaryCenterId;
        }

        // Mise a jour du mot de passe seulement si fourni
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['center_ids']);

        $managedUser->update($data);

        if ($data['role'] !== User::ROLE_SUPER_ADMIN && User::supportsCenterAssignments()) {
            $managedUser->centers()->sync($assignedCenterIds->all());
        } elseif (User::supportsCenterAssignments()) {
            $managedUser->centers()->detach();
        }

        $managedUser->audits()->create([
            'performed_by' => $this->authUser()->id,
            'action' => 'updated',
            'details' => 'Compte modifie par ' . $this->authUser()->name,
        ]);

        return redirect()->route('users.index')->with('success', 'Compte utilisateur modifie avec succes.');
    }

    public function toggleBlock(User $managedUser)
    {
        if ($this->isCenterAdmin()) {
            $this->authorizeManagedSecretary($managedUser);
        } else {
            $this->onlySuperAdmin();
        }

        if ($managedUser->trashed()) {
            return redirect()->route('users.index')->with('error', 'Impossible de bloquer un compte archive.');
        }

        if ($managedUser->id === $this->authUser()->id) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas bloquer votre propre compte.');
        }

        // Empêcher le blocage d'un autre super admin
        if ($managedUser->role === User::ROLE_SUPER_ADMIN) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas bloquer un super administrateur.');
        }

        $shouldBlock = ! $managedUser->is_blocked;

        $managedUser->update([
            'is_blocked' => $shouldBlock,
            'blocked_at' => $shouldBlock ? now() : null,
        ]);

        $managedUser->audits()->create([
            'performed_by' => $this->authUser()->id,
            'action' => $shouldBlock ? 'blocked' : 'unblocked',
            'details' => ($shouldBlock ? 'Blocage' : 'Deblocage') . ' par ' . $this->authUser()->name,
        ]);

        return redirect()->route('users.index')->with('success', 'Etat du compte mis a jour.');
    }

    public function archive(Request $request, $managedUserId)
    {
        $this->onlySuperAdmin();

        $managedUser = User::withTrashed()->findOrFail($managedUserId);
        $action = (string) $request->input('action', 'archive');

        if ($action === 'restore') {
            if ($managedUser->trashed()) {
                $managedUser->restore();
            }

            $managedUser->audits()->create([
                'performed_by' => $this->authUser()->id,
                'action' => 'restored',
                'details' => 'Compte restaure par ' . $this->authUser()->name,
            ]);

            return redirect()->route('users.index', ['archived' => 1])->with('success', 'Compte restaure.');
        }

        if ($managedUser->id === $this->authUser()->id) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas archiver votre propre compte.');
        }

        if ($managedUser->role === User::ROLE_SUPER_ADMIN) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas archiver un super administrateur.');
        }

        $managedUser->delete();

        $managedUser->audits()->create([
            'performed_by' => $this->authUser()->id,
            'action' => 'archived',
            'details' => 'Compte archive par ' . $this->authUser()->name,
        ]);

        return redirect()->route('users.index')->with('success', 'Compte archive.');
    }
    protected function authorizeManagedSecretary(User $managedUser): void
    {
        if (! $this->isCenterAdmin()) {
            return;
        }

        if ($managedUser->role !== User::ROLE_SECRETARY) {
            abort(403);
        }

        $centerIds = $this->accessibleCenterIds();
        if (empty($centerIds)) {
            abort(403);
        }

        $managedUser->primeCentersRelation();
        $userCenterIds = $managedUser->centers->pluck('id')->all();

        if ($managedUser->center_id) {
            $userCenterIds[] = (int) $managedUser->center_id;
        }

        if (empty(array_intersect($centerIds, array_unique($userCenterIds)))) {
            abort(403);
        }
    }
}




