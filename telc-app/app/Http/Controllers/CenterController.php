<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CenterController extends Controller
{
    public function index(Request $request)
    {
        if ($this->authUser()->isSuperAdmin() && $request->boolean('create')) {
            return redirect()->route('centers.create');
        }

        $archived = $request->boolean('archived');

        if ($archived && ! $this->authUser()->isSuperAdmin()) {
            abort(403);
        }

        $query = Center::query()
            ->withCount(['sessions', 'students', 'registrations'])
            ->orderBy('name');

        if ($archived) {
            $query->onlyTrashed();
        }

        if ($this->isCenterScoped()) {
            $query->whereIn('id', $this->accessibleCenterIds());
        }

        return view('centers-index-managed', [
            'centers' => $query->get(),
            'archived' => $archived,
            'totalCenters' => Center::withTrashed()->count(),
            'activeCenters' => Center::where('is_active', true)->count(),
            'archivedCenters' => Center::onlyTrashed()->count(),
            'user' => $this->authUser(),
        ]);
    }

    public function create()
    {
        $this->onlySuperAdmin();

        return view('centers.create', [
            'user' => $this->authUser(),
        ]);
    }

    public function store(Request $request)
    {
        $this->onlySuperAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:centers,name'],
            'code' => ['required', 'string', 'max:50', 'unique:centers,code'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'is_active' => ['required', Rule::in(['0', '1'])],
        ]);

        $data['is_active'] = (bool) $data['is_active'];

        $center = Center::create($data);

        ActivityLog::create([
            'action' => 'center_created',
            'details' => 'Centre cree: ' . $center->name . ' (' . $center->city . ')',
            'entity_id' => $center->id,
            'entity_type' => 'center',
            'ip_address' => $request->ip(),
            'user_id' => $this->authUser()->id,
        ]);

        return redirect()->route('centers.index')->with('success', 'Centre cree avec succes.');
    }

    public function edit(Center $center)
    {
        if ($this->isCenterAdmin()) {
            abort_unless(in_array((int) $center->id, $this->accessibleCenterIds(), true), 403);
        } else {
            $this->onlySuperAdmin();
        }

        return view('centers.edit', [
            'center' => $center,
            'user' => $this->authUser(),
        ]);
    }

    public function update(Request $request, Center $center)
    {
        if ($this->isCenterAdmin()) {
            abort_unless(in_array((int) $center->id, $this->accessibleCenterIds(), true), 403);
        } else {
            $this->onlySuperAdmin();
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('centers', 'name')->ignore($center->id)],
            'code' => ['required', 'string', 'max:50', Rule::unique('centers', 'code')->ignore($center->id)],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'is_active' => ['required', Rule::in(['0', '1'])],
        ]);

        $data['is_active'] = (bool) $data['is_active'];

        $oldData = $center->toArray();
        $center->update($data);

        $changes = [];
        foreach ($data as $key => $newValue) {
            if (($oldData[$key] ?? null) != $newValue) {
                $changes[] = $key . ': ' . ($oldData[$key] ?? 'vide') . ' -> ' . ($newValue ?? 'vide');
            }
        }

        if (! empty($changes)) {
            ActivityLog::create([
                'action' => 'center_updated',
                'details' => 'Centre modifie: ' . $center->name . ' | ' . implode(', ', $changes),
                'entity_id' => $center->id,
                'entity_type' => 'center',
                'ip_address' => $request->ip(),
                'user_id' => $this->authUser()->id,
            ]);
        }

        return redirect()->route('centers.index')->with('success', 'Centre modifie avec succes.');
    }

    public function confirmArchive($centerId)
    {
        $this->onlySuperAdmin();

        $center = Center::withTrashed()->findOrFail($centerId);

        if ($center->trashed()) {
            return redirect()->route('centers.index', ['archived' => 1])->with('error', 'Ce centre est deja archive.');
        }

        return view('centers-confirm-archive', [
            'center' => $center,
            'user' => $this->authUser(),
        ]);
    }

    public function archive(Request $request, $centerId)
    {
        $this->onlySuperAdmin();

        $center = Center::withTrashed()->findOrFail($centerId);
        $action = (string) $request->input('action', 'archive');

        if ($action === 'restore') {
            if ($center->trashed()) {
                $center->restore();
            }

            ActivityLog::create([
                'action' => 'center_restored',
                'details' => 'Centre restaure: ' . $center->name,
                'entity_id' => $center->id,
                'entity_type' => 'center',
                'ip_address' => $request->ip(),
                'user_id' => $this->authUser()->id,
            ]);

            return redirect()->route('centers.index')->with('success', 'Centre restaure.');
        }

        $data = $request->validate([
            'confirm_name' => ['required', 'string'],
        ]);

        if (strcasecmp(trim($data['confirm_name']), $center->name) !== 0) {
            return back()->withErrors([
                'confirm_name' => 'Le nom saisi ne correspond pas au centre.',
            ]);
        }

        $center->delete();

        ActivityLog::create([
            'action' => 'center_archived',
            'details' => 'Centre archive: ' . $center->name,
            'entity_id' => $center->id,
            'entity_type' => 'center',
            'ip_address' => $request->ip(),
            'user_id' => $this->authUser()->id,
        ]);

        return redirect()->route('centers.index')->with('success', 'Centre archive.');
    }

    public function switchCenter(Request $request)
    {
        $user = $this->authUser();
        $accessibleCenterIds = $this->accessibleCenterIds();

        $data = $request->validate([
            'center_id' => ['required', 'integer', Rule::in($accessibleCenterIds)],
        ]);

        $newCenterId = (int) $data['center_id'];
        $oldCenterId = $user->center_id;

        $oldCenter = Center::find($oldCenterId);
        $newCenter = Center::find($newCenterId);

        $user->update(['center_id' => $newCenterId]);

        ActivityLog::create([
            'action' => 'center_switched',
            'details' => 'Centre change: ' . ($oldCenter ? $oldCenter->name : 'Aucun') . ' -> ' . ($newCenter ? $newCenter->name : 'N/A'),
            'entity_id' => $newCenterId,
            'entity_type' => 'center',
            'ip_address' => $request->ip(),
            'user_id' => $user->id,
        ]);

        $message = $newCenter
            ? 'Centre actif change vers: ' . $newCenter->name . ($newCenter->city ? ' (' . $newCenter->city . ')' : '')
            : 'Centre actif modifie';

        return back()->with('success', $message);
    }
}
