<?php

namespace App\Http\Controllers;

use App\Events\ExamSessionCreated;
use App\Models\ActivityLog;
use App\Models\Center;
use App\Models\ExamSession;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExamSessionController extends Controller
{
    public function index(Request $request)
    {
        if ($this->authUser()->isSuperAdmin() && $request->boolean('create')) {
            return redirect()->route('sessions.create', $request->only('center_id'));
        }

        if ($request->boolean('archived')) {
            return $this->archivedSessions($request);
        }

        $query = ExamSession::query()
            ->with($this->sessionRelations())
            ->withCount(['registrations'])
            ->orderByDesc('exam_date')
            ->orderByDesc('opens_at')
            ->active();

        $this->applySessionCenterFilter($query, $request);

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        $statsQuery = clone $query;
        $counts = [
            'total' => (clone $statsQuery)->count(),
            'open' => (clone $statsQuery)->where('status', ExamSession::STATUS_OPEN)->count(),
            'draft' => (clone $statsQuery)->where('status', ExamSession::STATUS_DRAFT)->count(),
            'closed' => (clone $statsQuery)->where('status', ExamSession::STATUS_CLOSED)->count(),
        ];

        return view('sessions.index', [
            'centerOptions' => $this->accessibleCenters(),
            'counts' => $counts,
            'sessions' => $query->paginate(12)->withQueryString(),
            'showArchived' => false,
            'user' => $this->authUser(),
        ]);
    }

    public function archivedSessions(Request $request)
    {
        $query = ExamSession::query()
            ->with($this->sessionRelations())
            ->withCount(['registrations'])
            ->orderByDesc('exam_date')
            ->archived();

        $this->applySessionCenterFilter($query, $request);

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        $statsQuery = clone $query;
        $counts = [
            'total' => (clone $statsQuery)->count(),
            'open' => (clone $statsQuery)->where('status', ExamSession::STATUS_OPEN)->count(),
            'draft' => (clone $statsQuery)->where('status', ExamSession::STATUS_DRAFT)->count(),
            'closed' => (clone $statsQuery)->where('status', ExamSession::STATUS_CLOSED)->count(),
        ];

        return view('sessions.index', [
            'centerOptions' => $this->accessibleCenters(),
            'counts' => $counts,
            'sessions' => $query->paginate(12)->withQueryString(),
            'showArchived' => true,
            'user' => $this->authUser(),
        ]);
    }

    public function create(Request $request)
    {
        $this->onlySuperAdmin();

        return view('sessions-create-managed', [
            'centerOptions' => $this->accessibleCenters(),
            'selectedCenterId' => $request->input('center_id'),
            'user' => $this->authUser(),
        ]);
    }

    public function show($sessionId)
    {
        $session = $this->findAccessibleSessionOrFail((int) $sessionId);

        $registrations = $session->registrations
            ->sortByDesc(function ($registration) {
                return optional($registration->registered_at)->timestamp ?: 0;
            })
            ->values();

        $activeRegistrations = $registrations->where('status', Registration::STATUS_ACTIVE);
        $paidRegistrations = $activeRegistrations->where('payment_status', Registration::PAYMENT_PAID);
        $pendingRegistrations = $activeRegistrations->where('payment_status', Registration::PAYMENT_PENDING);
        $cancelledRegistrations = $registrations->where('status', Registration::STATUS_CANCELLED);

        return view('sessions.show', [
            'registrations' => $registrations,
            'session' => $session,
            'statusLabel' => $this->statusLabel($session->status),
            'summary' => [
                'total' => $registrations->count(),
                'active' => $activeRegistrations->count(),
                'paid' => $paidRegistrations->count(),
                'pending' => $pendingRegistrations->count(),
                'cancelled' => $cancelledRegistrations->count(),
                'recorded_amount' => (float) $activeRegistrations->sum('amount'),
                'paid_amount' => (float) $paidRegistrations->sum('amount'),
                'pending_amount' => (float) $pendingRegistrations->sum('amount'),
            ],
            'user' => $this->authUser(),
        ]);
    }

    public function store(Request $request)
    {
        $this->onlySuperAdmin();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'center_ids' => ['required', 'array', 'min:1'],
            'center_ids.*' => ['required', 'exists:centers,id'],
            'level' => ['required', Rule::in(ExamSession::LEVELS)],
            'city' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'exam_date' => ['nullable', 'date'],
            'opens_at' => ['required', 'date'],
            'closes_at' => ['nullable', 'date', 'after_or_equal:opens_at'],
            'status' => ['required', Rule::in([ExamSession::STATUS_DRAFT, ExamSession::STATUS_OPEN, ExamSession::STATUS_CLOSED])],
            'notes' => ['nullable', 'string'],
        ]);

        $allowedCities = Center::whereIn('id', $data['center_ids'])
            ->pluck('city')
            ->filter()
            ->unique()
            ->values()
            ->all();
        if (! empty($allowedCities) && ! in_array($data['city'], $allowedCities, true)) {
            return back()
                ->withErrors(['city' => 'La ville selectionnee ne correspond pas aux centres choisis.'])
                ->withInput();
        }

        $firstCenter = Center::findOrFail($data['center_ids'][0]);
        $sessionData = [
            'title' => $data['title'],
            'center_id' => $firstCenter->id,
            'level' => $data['level'],
            'city' => $data['city'],
            'price' => $data['price'],
            'exam_date' => $data['exam_date'],
            'opens_at' => $data['opens_at'],
            'closes_at' => $data['closes_at'],
            'status' => $data['status'],
            'notes' => $data['notes'],
        ];

        $session = ExamSession::create($sessionData);

        if (ExamSession::supportsMultiCenters()) {
            $session->centers()->attach($data['center_ids']);
            event(new ExamSessionCreated($session->fresh(['centers', 'center'])));
        } else {
            $session->setRelation('centers', collect());
        }

        $centersNames = Center::whereIn('id', $data['center_ids'])->pluck('name')->implode(', ');
        ActivityLog::create([
            'action' => 'session_created',
            'details' => 'Session creee: ' . $data['title'] . ' (' . $data['level'] . ') - Centres: ' . $centersNames,
            'entity_id' => $session->id,
            'entity_type' => 'session',
            'ip_address' => $request->ip(),
            'user_id' => $this->authUser()->id,
        ]);

        return redirect()->route('sessions.index')->with('success', 'Session creee avec succes.');
    }

    public function edit(ExamSession $session)
    {
        $this->onlySuperAdmin();

        if (! ExamSession::supportsMultiCenters()) {
            $session->setRelation('centers', collect());
        }

        return view('sessions.edit', [
            'centerOptions' => $this->accessibleCenters(),
            'session' => $session,
            'user' => $this->authUser(),
        ]);
    }

    public function update(Request $request, ExamSession $session)
    {
        $this->onlySuperAdmin();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'center_ids' => ['required', 'array', 'min:1'],
            'center_ids.*' => ['required', 'exists:centers,id'],
            'level' => ['required', Rule::in(ExamSession::LEVELS)],
            'city' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'exam_date' => ['nullable', 'date'],
            'opens_at' => ['required', 'date'],
            'closes_at' => ['nullable', 'date', 'after_or_equal:opens_at'],
            'status' => ['required', Rule::in([ExamSession::STATUS_DRAFT, ExamSession::STATUS_OPEN, ExamSession::STATUS_CLOSED])],
            'notes' => ['nullable', 'string'],
        ]);

        $allowedCities = Center::whereIn('id', $data['center_ids'])
            ->pluck('city')
            ->filter()
            ->unique()
            ->values()
            ->all();
        if (! empty($allowedCities) && ! in_array($data['city'], $allowedCities, true)) {
            return back()
                ->withErrors(['city' => 'La ville selectionnee ne correspond pas aux centres choisis.'])
                ->withInput();
        }

        $firstCenter = Center::findOrFail($data['center_ids'][0]);
        $sessionData = [
            'title' => $data['title'],
            'center_id' => $firstCenter->id,
            'level' => $data['level'],
            'city' => $data['city'],
            'price' => $data['price'],
            'exam_date' => $data['exam_date'],
            'opens_at' => $data['opens_at'],
            'closes_at' => $data['closes_at'],
            'status' => $data['status'],
            'notes' => $data['notes'],
        ];

        $oldData = $session->toArray();
        $session->update($sessionData);

        if (ExamSession::supportsMultiCenters()) {
            $session->centers()->sync($data['center_ids']);
        } else {
            $session->setRelation('centers', collect());
        }

        $changes = [];
        foreach ($sessionData as $key => $newValue) {
            if (array_key_exists($key, $oldData) && $oldData[$key] != $newValue) {
                $changes[] = $key . ': ' . ($oldData[$key] ?? 'vide') . ' -> ' . ($newValue ?? 'vide');
            }
        }

        $centersNames = Center::whereIn('id', $data['center_ids'])->pluck('name')->implode(', ');
        $details = 'Session modifiee: ' . $session->title . ' (' . $session->level . ') - Centres: ' . $centersNames;
        if (! empty($changes)) {
            $details .= ' | ' . implode(', ', $changes);
        }

        ActivityLog::create([
            'action' => 'session_updated',
            'details' => $details,
            'entity_id' => $session->id,
            'entity_type' => 'session',
            'ip_address' => $request->ip(),
            'user_id' => $this->authUser()->id,
        ]);

        return redirect()->route('sessions.index')->with('success', 'Session modifiee avec succes.');
    }

    public function archive(Request $request, ExamSession $session)
    {
        $this->onlySuperAdmin();

        if (! ExamSession::supportsArchiving()) {
            abort(404);
        }

        $action = (string) $request->input('action', 'archive');
        if ($action === 'restore') {
            $session->update(['archived_at' => null]);
            $message = 'Session restauree.';
        } else {
            $session->update(['archived_at' => now()]);
            $message = 'Session archivee.';
        }

        $logAction = $action === 'restore' ? 'session_restored' : 'session_archived';
        $logDetails = ($action === 'restore' ? 'Session restauree: ' : 'Session archivee: ') . $session->title . ' (' . $session->level . ')';
        ActivityLog::create([
            'action' => $logAction,
            'details' => $logDetails,
            'entity_id' => $session->id,
            'entity_type' => 'session',
            'ip_address' => $request->ip(),
            'user_id' => $this->authUser()->id,
        ]);

        $filters = $request->only('center_id', 'status');
        if ($request->boolean('archived')) {
            $filters['archived'] = 1;
        }

        return redirect()->route('sessions.index', $filters)->with('success', $message);
    }

    public function toggle(Request $request, ExamSession $session)
    {
        $this->onlySuperAdmin();

        $data = $request->validate([
            'status' => ['required', Rule::in([ExamSession::STATUS_OPEN, ExamSession::STATUS_CLOSED, ExamSession::STATUS_DRAFT])],
        ]);

        $previousStatus = $session->status;
        $session->update(['status' => $data['status']]);

        ActivityLog::create([
            'action' => 'session_status_changed',
            'details' => 'Statut modifie: ' . $session->title . ' (' . $previousStatus . ' -> ' . $data['status'] . ')',
            'entity_id' => $session->id,
            'entity_type' => 'session',
            'ip_address' => $request->ip(),
            'user_id' => $this->authUser()->id,
        ]);

        $filters = $request->only('center_id');
        if ($request->filled('status_filter')) {
            $filters['status'] = (string) $request->input('status_filter');
        }

        return redirect()->route('sessions.index', $filters)->with('success', 'Statut de la session mis a jour.');
    }

    protected function findAccessibleSessionOrFail(int $sessionId)
    {
        $relations = [
            'registrations.student',
            'registrations.center',
            'registrations.registrar',
        ];

        if (ExamSession::supportsMultiCenters()) {
            array_unshift($relations, 'centers');
        } else {
            array_unshift($relations, 'center');
        }

        $query = ExamSession::query()->with($relations);

        if ($this->isCenterScoped()) {
            if (ExamSession::supportsMultiCenters()) {
                $this->applyExamSessionCenterScope($query, $this->accessibleCenterIds());
            } else {
                $query->whereIn('center_id', $this->accessibleCenterIds());
            }
        }

        $session = $query->findOrFail($sessionId);

        if (! ExamSession::supportsMultiCenters()) {
            $session->setRelation('centers', collect());
        }

        return $session;
    }

    protected function applySessionCenterFilter($query, Request $request)
    {
        if (! ExamSession::supportsMultiCenters()) {
            return $this->applyCenterScope($query, 'center_id', $request);
        }

        $selectedCenterId = $this->selectedCenterFilter($request);

        if ($this->isCenterScoped()) {
            $centerIds = $selectedCenterId ? [$selectedCenterId] : $this->accessibleCenterIds();

            $this->applyExamSessionCenterScope($query, $centerIds);

            return $query;
        }

        if ($selectedCenterId) {
            $this->applyExamSessionCenterScope($query, [$selectedCenterId]);
        }

        return $query;
    }

    protected function sessionRelations(): array
    {
        if (ExamSession::supportsMultiCenters()) {
            return ['center', 'centers'];
        }

        return ['center'];
    }

    protected function statusLabel(string $status): string
    {
        if ($status === ExamSession::STATUS_OPEN) {
            return 'Ouverte';
        }

        if ($status === ExamSession::STATUS_CLOSED) {
            return 'Fermee';
        }

        return 'Brouillon';
    }
}
