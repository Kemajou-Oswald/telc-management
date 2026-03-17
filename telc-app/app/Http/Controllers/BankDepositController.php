<?php

namespace App\Http\Controllers;

use App\Models\BankDeposit;
use App\Models\BankDepositAudit;
use App\Models\Center;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BankDepositController extends Controller
{
    public function index(Request $request)
    {
        if ($request->boolean('create') && ! $this->isSuperAdmin()) {
            return redirect()->route('deposits.create', $request->except(['create', 'page']));
        }

        $depositsQuery = BankDeposit::with(['center', 'examSession.center', 'submitter', 'validator', 'audits.user'])
            ->latest();
        $this->applyCenterScope($depositsQuery, 'bank_deposits.center_id', $request);

        $auditsQuery = BankDepositAudit::with(['deposit.center', 'user'])->latest();

        if ($this->isCenterScoped()) {
            $centerIds = $this->accessibleCenterIds();
            $auditsQuery->whereHas('deposit', function ($builder) use ($centerIds) {
                $builder->whereIn('center_id', $centerIds);
            });
        } elseif ($request->filled('center_id')) {
            $centerId = (int) $request->input('center_id');
            $auditsQuery->whereHas('deposit', function ($builder) use ($centerId) {
                $builder->where('center_id', $centerId);
            });
        }

        if ($request->filled('q')) {
            $search = trim((string) $request->input('q'));

            $depositsQuery->where(function ($builder) use ($search) {
                $builder->where('amount', 'like', '%' . $search . '%')
                    ->orWhereHas('center', function ($centerQuery) use ($search) {
                        $centerQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('examSession', function ($sessionQuery) use ($search) {
                        $sessionQuery->where('title', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('submitter', function ($submitterQuery) use ($search) {
                        $submitterQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('session_id')) {
            $depositsQuery->where('exam_session_id', (int) $request->input('session_id'));
        }

        if ($request->filled('status')) {
            $depositsQuery->where('status', (string) $request->input('status'));
        }

        if ($request->filled('submitted_by')) {
            $depositsQuery->where('submitted_by', (int) $request->input('submitted_by'));
        }

        if ($request->filled('date_from')) {
            $depositsQuery->whereDate('deposit_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $depositsQuery->whereDate('deposit_date', '<=', $request->input('date_to'));
        }

        return view('deposits.index', [
            'accessibleCenters' => $this->accessibleCenters(),
            'centerOptions' => $this->isSuperAdmin() ? Center::where('is_active', true)->orderBy('name')->get() : $this->accessibleCenters(),
            'deposits' => $depositsQuery->paginate(15)->withQueryString(),
            'depositAudits' => $auditsQuery->take(12)->get(),
            'sessions' => $this->availableSessionsQuery(true, $request)->get(),
            'submitterOptions' => $this->depositSubmitterOptions($request),
            'user' => $this->authUser(),
        ]);
    }

    public function create(Request $request)
    {
        $this->onlyCenterOperations();

        return view('deposits.create', [
            'accessibleCenters' => $this->accessibleCenters(),
            'selectedCenterId' => $this->currentCenterId($request),
            'sessions' => $this->availableSessionsQuery(true, $request)->get(),
            'user' => $this->authUser(),
        ]);
    }

    public function store(Request $request)
    {
        $this->onlyCenterOperations();

        $data = $request->validate([
            'center_id' => ['nullable', 'exists:centers,id'],
            'exam_session_id' => ['nullable', 'exists:exam_sessions,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'deposit_date' => ['required', 'date'],
            'slip' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $centerId = $this->resolveOperationalCenterId($request);
        if (! $centerId) {
            throw ValidationException::withMessages([
                'center_id' => 'Selectionnez le centre qui effectue ce depot.',
            ]);
        }

        if (! empty($data['exam_session_id'])) {
            $session = $this->availableSessionsQuery(true, $request)->whereKey($data['exam_session_id'])->first();
            if (! $session) {
                throw ValidationException::withMessages([
                    'exam_session_id' => 'Cette session ne fait pas partie du perimetre autorise.',
                ]);
            }

            if (\App\Models\ExamSession::supportsMultiCenters()) {
                $allowedCenterIds = $session->centers()->pluck('centers.id')->all();
                if ($session->center_id) {
                    $allowedCenterIds[] = (int) $session->center_id;
                }

                if (! in_array((int) $centerId, array_unique($allowedCenterIds), true)) {
                    throw ValidationException::withMessages([
                        'center_id' => 'Le centre selectionne ne correspond pas a la session choisie.',
                    ]);
                }
            } elseif ($session->center_id && (int) $session->center_id !== (int) $centerId) {
                throw ValidationException::withMessages([
                    'center_id' => 'Le centre selectionne ne correspond pas a la session choisie.',
                ]);
            }
        }

        $path = $request->file('slip')->store('bank-slips', 'public');

        $deposit = BankDeposit::create([
            'center_id' => $centerId,
            'exam_session_id' => $data['exam_session_id'] ?? null,
            'submitted_by' => $this->authUser()->id,
            'amount' => $data['amount'],
            'deposit_date' => $data['deposit_date'],
            'slip_path' => $path,
            'status' => BankDeposit::STATUS_PENDING,
        ]);

        $deposit->audits()->create([
            'user_id' => $this->authUser()->id,
            'action' => 'submitted',
            'details' => 'Depot soumis par ' . $this->authUser()->name,
        ]);

        return redirect()->route('deposits.index')->with('success', 'Depot bancaire enregistre et en attente de validation.');
    }

    public function review(Request $request, BankDeposit $deposit)
    {
        $this->onlySuperAdmin();

        if ($deposit->status !== BankDeposit::STATUS_PENDING) {
            return redirect()
                ->route('deposits.index', $request->only('center_id', 'status', 'submitted_by', 'session_id', 'date_from', 'date_to'))
                ->with('error', 'Ce depot a deja ete traite.');
        }

        $data = $request->validate([
            'status' => ['required', Rule::in([BankDeposit::STATUS_VALIDATED, BankDeposit::STATUS_REJECTED])],
            'admin_note' => ['required_if:status,rejected', 'nullable', 'string', 'min:3'],
        ]);

        $deposit->update([
            'status' => $data['status'],
            'admin_note' => $data['admin_note'] ?? null,
            'validated_by' => $this->authUser()->id,
            'validated_at' => now(),
        ]);

        $deposit->audits()->create([
            'user_id' => $this->authUser()->id,
            'action' => $data['status'],
            'details' => trim(($data['admin_note'] ?? '') . ' | traite par ' . $this->authUser()->name, ' |'),
        ]);

        return redirect()->route('deposits.index', $request->only('center_id', 'status', 'submitted_by', 'session_id', 'date_from', 'date_to'))->with('success', 'Depot traite avec succes.');
    }

    public function previewSlip(BankDeposit $deposit)
    {
        $this->authorizeDeposit($deposit);

        $disk = Storage::disk('public');
        abort_unless($deposit->slip_path && $disk->exists($deposit->slip_path), 404);

        return response()->file($disk->path($deposit->slip_path));
    }

    public function downloadSlip(BankDeposit $deposit)
    {
        $this->authorizeDeposit($deposit);

        $disk = Storage::disk('public');
        abort_unless($deposit->slip_path && $disk->exists($deposit->slip_path), 404);

        return $disk->download($deposit->slip_path);
    }

    protected function authorizeDeposit(BankDeposit $deposit)
    {
        if ($this->isCenterScoped() && ! in_array((int) $deposit->center_id, $this->accessibleCenterIds(), true)) {
            abort(403);
        }
    }

    protected function depositSubmitterOptions(Request $request)
    {
        $query = User::query()
            ->whereIn('role', [User::ROLE_CENTER_ADMIN, User::ROLE_SECRETARY])
            ->orderBy('name');

        if ($this->isCenterScoped()) {
            if (User::supportsCenterAssignments()) {
                $query->where(function ($builder) {
                    $builder->whereIn('center_id', $this->accessibleCenterIds())
                        ->orWhereHas('centers', function ($centerQuery) {
                            $centerQuery->whereIn('centers.id', $this->accessibleCenterIds());
                        });
                });
            } else {
                $query->whereIn('center_id', $this->accessibleCenterIds());
            }
        } elseif ($request->filled('center_id')) {
            $centerId = (int) $request->input('center_id');
            if (User::supportsCenterAssignments()) {
                $query->where(function ($builder) use ($centerId) {
                    $builder->where('center_id', $centerId)
                        ->orWhereHas('centers', function ($centerQuery) use ($centerId) {
                            $centerQuery->where('centers.id', $centerId);
                        });
                });
            } else {
                $query->where('center_id', $centerId);
            }
        }

        $users = $query->get();
        $users->each->primeCentersRelation();

        return $users;
    }
}





