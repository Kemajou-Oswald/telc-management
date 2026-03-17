<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BankDepositAudit;
use App\Models\LoginAudit;
use App\Models\RegistrationAudit;
use App\Models\User;
use App\Models\UserAudit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        if (! $this->isSuperAdmin() && ! $this->isCenterAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        $isDirector = $this->isCenterAdmin();
        $centerIds = $isDirector ? $this->accessibleCenterIds() : [];
        if ($isDirector && empty($centerIds)) {
            abort(403);
        }

        $cacheKey = 'audits:' . $this->authUser()->id . ':' . md5(json_encode($request->query()));
        $cachedPayload = Cache::get($cacheKey);
        if (is_array($cachedPayload)) {
            return view('audits.index', $cachedPayload);
        }

        $usersQuery = User::query()->orderBy('name');
        $allowedUserIds = null;

        if ($isDirector) {
            $usersQuery->where('role', User::ROLE_SECRETARY);

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
        }

        $users = $usersQuery->get();
        $allowedUserIds = $isDirector ? $users->pluck('id')->all() : null;

        $selectedUserId = isset($validated['user_id']) ? (int) $validated['user_id'] : null;
        if ($selectedUserId && ! $users->pluck('id')->contains($selectedUserId)) {
            $selectedUserId = null;
        }

        $dateFromInput = $validated['date_from'] ?? null;
        $dateToInput = $validated['date_to'] ?? null;

        $dateFrom = $dateFromInput ? Carbon::parse($dateFromInput)->startOfDay() : null;
        $dateTo = $dateToInput ? Carbon::parse($dateToInput)->endOfDay() : null;

        $limitPerSource = 300;

        $registrationAudits = RegistrationAudit::with(['user', 'registration'])
            ->when($isDirector, function ($query) use ($allowedUserIds, $centerIds) {
                $query->whereIn('user_id', $allowedUserIds)
                    ->whereHas('registration', function ($registrationQuery) use ($centerIds) {
                        $registrationQuery->whereIn('center_id', $centerIds);
                    });
            })
            ->when($selectedUserId, function ($query) use ($selectedUserId) {
                $query->where('user_id', $selectedUserId);
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->where('created_at', '<=', $dateTo);
            })
            ->latest()
            ->take($limitPerSource)
            ->get();

        $depositAudits = BankDepositAudit::with(['user', 'deposit.center'])
            ->when($isDirector, function ($query) use ($allowedUserIds, $centerIds) {
                $query->whereIn('user_id', $allowedUserIds)
                    ->whereHas('deposit', function ($depositQuery) use ($centerIds) {
                        $depositQuery->whereIn('center_id', $centerIds);
                    });
            })
            ->when($selectedUserId, function ($query) use ($selectedUserId) {
                $query->where('user_id', $selectedUserId);
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->where('created_at', '<=', $dateTo);
            })
            ->latest()
            ->take($limitPerSource)
            ->get();

        $loginAudits = LoginAudit::with('user')
            ->when($isDirector, function ($query) use ($allowedUserIds) {
                $query->whereIn('user_id', $allowedUserIds);
            })
            ->when($selectedUserId, function ($query) use ($selectedUserId) {
                $query->where('user_id', $selectedUserId);
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->where('created_at', '<=', $dateTo);
            })
            ->latest()
            ->take($limitPerSource)
            ->get();

        $userAudits = UserAudit::with(['performer', 'affectedUser'])
            ->when($isDirector, function ($query) use ($allowedUserIds) {
                $query->whereIn('performed_by', $allowedUserIds);
            })
            ->when($selectedUserId, function ($query) use ($selectedUserId) {
                $query->where('performed_by', $selectedUserId);
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->where('created_at', '<=', $dateTo);
            })
            ->latest()
            ->take($limitPerSource)
            ->get();

        $activityLogs = ActivityLog::with('user')
            ->when($isDirector, function ($query) use ($allowedUserIds) {
                $query->whereIn('user_id', $allowedUserIds);
            })
            ->when($selectedUserId, function ($query) use ($selectedUserId) {
                $query->where('user_id', $selectedUserId);
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->where('created_at', '<=', $dateTo);
            })
            ->latest()
            ->take($limitPerSource)
            ->get();

        $entries = collect()
            ->merge($registrationAudits->map(function ($audit) {
                return [
                    'type' => 'Inscription',
                    'action' => $audit->action,
                    'user_name' => optional($audit->user)->name ?: 'Systeme',
                    'user' => $audit->user,
                    'user_id' => $audit->user_id,
                    'created_at' => $audit->created_at,
                    'details' => $audit->details,
                    'ip_address' => null,
                ];
            }))
            ->merge($depositAudits->map(function ($audit) {
                $centerName = optional(optional($audit->deposit)->center)->name;
                $details = $audit->details;
                if ($centerName) {
                    $details = trim($details . ' (Centre: ' . $centerName . ')');
                }

                return [
                    'type' => 'Depot bancaire',
                    'action' => $audit->action,
                    'user_name' => optional($audit->user)->name ?: 'Systeme',
                    'user' => $audit->user,
                    'user_id' => $audit->user_id,
                    'created_at' => $audit->created_at,
                    'details' => $details,
                    'ip_address' => null,
                ];
            }))
            ->merge($loginAudits->map(function ($audit) {
                $isLogout = $audit->action === 'logout';

                return [
                    'type' => 'Connexion',
                    'action' => $isLogout ? 'Deconnexion' : 'Connexion',
                    'user_name' => optional($audit->user)->name ?: 'Systeme',
                    'user' => $audit->user,
                    'user_id' => $audit->user_id,
                    'created_at' => $audit->created_at,
                    'details' => $isLogout ? 'Deconnexion' : 'Connexion',
                    'ip_address' => $audit->ip_address,
                ];
            }))
            ->merge($userAudits->map(function ($audit) {
                $affectedName = optional($audit->affectedUser)->name;
                $details = $audit->details;
                if ($affectedName) {
                    $details = trim($details . ' (Compte: ' . $affectedName . ')');
                }

                return [
                    'type' => 'Utilisateur',
                    'action' => $audit->action,
                    'user_name' => optional($audit->performer)->name ?: 'Systeme',
                    'user' => $audit->performer,
                    'user_id' => $audit->performed_by,
                    'created_at' => $audit->created_at,
                    'details' => $details,
                    'ip_address' => null,
                ];
            }))
            ->merge($activityLogs->map(function ($log) {
                $type = $log->entity_type === 'center' ? 'Centre' : ($log->entity_type === 'session' ? 'Session' : 'Activite');
                return [
                    'type' => $type,
                    'action' => $log->action,
                    'user_name' => optional($log->user)->name ?: 'Systeme',
                    'user' => $log->user,
                    'user_id' => $log->user_id,
                    'created_at' => $log->created_at,
                    'details' => $log->details,
                    'ip_address' => $log->ip_address,
                ];
            }))
            ->sortByDesc('created_at')
            ->values();

        $perPage = 20;
        $page = max((int) $request->input('page', 1), 1);
        $total = $entries->count();
        $pageItems = $entries->slice(($page - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $pageItems,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        $stats = [
            'total' => $total,
            'logins' => $loginAudits->count(),
            'registrations' => $registrationAudits->count(),
            'deposits' => $depositAudits->count(),
            'users' => $userAudits->count(),
            'centers' => $activityLogs->where('entity_type', 'center')->count(),
            'sessions' => $activityLogs->where('entity_type', 'session')->count(),
        ];

        $payload = [
            'entries' => $paginator,
            'users' => $users,
            'selectedUserId' => $selectedUserId,
            'dateFrom' => $dateFromInput,
            'dateTo' => $dateToInput,
            'stats' => $stats,
            'user' => $this->authUser(),
        ];

        Cache::put($cacheKey, $payload, now()->addSeconds(30));

        return view('audits.index', $payload);
    }
}
