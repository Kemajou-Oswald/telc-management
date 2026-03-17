<?php

namespace App\Http\Controllers;

use App\Models\BankDeposit;
use App\Models\Center;
use App\Models\ExamSession;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mode = $this->dashboardMode();
        $selectedCenterId = $this->selectedCenterFilter($request);
        $cacheKey = 'dashboard:' . $this->authUser()->id . ':' . md5(json_encode($request->query()));
        $cachedPayload = Cache::get($cacheKey);
        if (is_array($cachedPayload)) {
            return view('dashboard-optimized', $cachedPayload);
        }

        $registrationsQuery = Registration::query();
        $studentsQuery = Student::query();
        $sessionsQuery = ExamSession::query()->active();
        $depositsQuery = BankDeposit::query();

        $this->applyDashboardCenterScope($registrationsQuery, $request, 'registrations.center_id');
        $this->applyDashboardCenterScope($studentsQuery, $request, 'students.center_id');
        $this->applyDashboardSessionScope($sessionsQuery, $request);
        $this->applyDashboardCenterScope($depositsQuery, $request, 'bank_deposits.center_id');

        $this->applyDashboardRegistrationFilters($registrationsQuery, $request);
        $this->applyDashboardStudentFilters($studentsQuery, $request);
        $this->applyDashboardDepositFilters($depositsQuery, $request);
        $this->applyDashboardSessionFilters($sessionsQuery, $request);

        $recordedAmount = (float) (clone $registrationsQuery)->sum('amount');
        $collectedAmount = (float) (clone $registrationsQuery)
            ->where('payment_status', Registration::PAYMENT_PAID)
            ->sum('amount');
        $depositedAmount = (float) (clone $depositsQuery)
            ->where('status', BankDeposit::STATUS_VALIDATED)
            ->sum('amount');

        $sessionRelations = ['center'];
        if (ExamSession::supportsMultiCenters()) {
            $sessionRelations[] = 'centers';
        }

        $recentSessions = (clone $sessionsQuery)
            ->with($sessionRelations)
            ->withCount([
                'registrations',
                'registrations as active_registrations_count' => function (Builder $builder) {
                    $builder->where('status', Registration::STATUS_ACTIVE);
                },
                'registrations as paid_registrations_count' => function (Builder $builder) {
                    $builder->where('status', Registration::STATUS_ACTIVE)
                        ->where('payment_status', Registration::PAYMENT_PAID);
                },
            ])
            ->withSum([
                'registrations as paid_registrations_amount' => function (Builder $builder) {
                    $builder->where('status', Registration::STATUS_ACTIVE)
                        ->where('payment_status', Registration::PAYMENT_PAID);
                },
            ], 'amount')
            ->orderByDesc('exam_date')
            ->orderByDesc('opens_at')
            ->take(5)
            ->get();

        if (! ExamSession::supportsMultiCenters()) {
            $recentSessions->each(function (ExamSession $session) {
                $session->setRelation('centers', collect());
            });
        }

        $recentRegistrations = (clone $registrationsQuery)
            ->with(['student', 'center', 'examSession', 'registrar'])
            ->orderByDesc('registered_at')
            ->take(6)
            ->get();

        $stats = [
            'registrations' => (clone $registrationsQuery)->count(),
            'paid_count' => (clone $registrationsQuery)
                ->where('payment_status', Registration::PAYMENT_PAID)
                ->count(),
            'paid' => (clone $registrationsQuery)
                ->where('payment_status', Registration::PAYMENT_PAID)
                ->count(),
            'students' => (clone $studentsQuery)->count(),
            'sessions' => (clone $sessionsQuery)->count(),
            'centers' => $selectedCenterId
                ? 1
                : ($mode === 'super_admin'
                    ? Center::where('is_active', true)->count()
                    : $this->accessibleCenters()->count()),
            'users' => User::count(),
            'recorded_amount' => $recordedAmount,
            'collected_amount' => $collectedAmount,
            'deposited_amount' => $depositedAmount,
            'current_session' => optional(
                (clone $sessionsQuery)
                    ->where('status', ExamSession::STATUS_OPEN)
                    ->orderBy('exam_date')
                    ->orderBy('opens_at')
                    ->first()
            )->title,
        ];

        $sessionStatusSummary = [
            'open' => (clone $sessionsQuery)->where('status', ExamSession::STATUS_OPEN)->count(),
            'closed' => (clone $sessionsQuery)->where('status', ExamSession::STATUS_CLOSED)->count(),
            'draft' => (clone $sessionsQuery)->where('status', ExamSession::STATUS_DRAFT)->count(),
        ];

        $health = [
            'pending_registrations' => max($stats['registrations'] - $stats['paid_count'], 0),
            'pending_amount' => max($recordedAmount - $collectedAmount, 0),
            'deposit_gap' => max($collectedAmount - $depositedAmount, 0),
            'payment_rate' => $stats['registrations'] > 0
                ? (int) round(($stats['paid_count'] / $stats['registrations']) * 100)
                : 0,
            'deposit_coverage' => $collectedAmount > 0
                ? (int) round(min(($depositedAmount / $collectedAmount) * 100, 100))
                : 0,
            'pending_deposits' => (clone $depositsQuery)
                ->where('status', BankDeposit::STATUS_PENDING)
                ->count(),
            'rejected_deposits' => (clone $depositsQuery)
                ->where('status', BankDeposit::STATUS_REJECTED)
                ->count(),
            'validated_deposits' => (clone $depositsQuery)
                ->where('status', BankDeposit::STATUS_VALIDATED)
                ->count(),
            'blocked_users' => User::where('is_blocked', true)->count(),
            'active_users' => User::where('is_blocked', false)->count(),
        ];

        $roleSummary = collect([
            [
                'label' => 'Super admins',
                'count' => User::where('role', User::ROLE_SUPER_ADMIN)->count(),
                'tone' => 'violet',
                'caption' => 'Pilotage global',
            ],
            [
                'label' => 'Directeurs',
                'count' => User::where('role', User::ROLE_CENTER_ADMIN)->count(),
                'tone' => 'sky',
                'caption' => 'Responsables de centre',
            ],
            [
                'label' => 'Secretaires',
                'count' => User::where('role', User::ROLE_SECRETARY)->count(),
                'tone' => 'amber',
                'caption' => 'Operations courantes',
            ],
        ]);

        $activityTrend = collect(range(6, 0))->map(function (int $offset) use ($request, $selectedCenterId) {
            $date = now()->subDays($offset);
            $count = Registration::query()
                ->when($selectedCenterId, function (Builder $builder) use ($selectedCenterId) {
                    $builder->where('center_id', $selectedCenterId);
                }, function (Builder $builder) {
                    if ($this->isCenterScoped()) {
                        $centerIds = $this->accessibleCenterIds();

                        if (! empty($centerIds)) {
                            $builder->whereIn('center_id', $centerIds);
                        }
                    }
                })
                ->where('status', Registration::STATUS_ACTIVE)
                ->whereDate('registered_at', $date->toDateString())
                ->when($request->filled('level'), function (Builder $builder) use ($request) {
                    $level = (string) $request->input('level');

                    $builder->whereHas('examSession', function (Builder $sessionQuery) use ($level) {
                        $sessionQuery->where('level', $level);
                    });
                })
                ->count();

            return [
                'label' => $date->translatedFormat('D'),
                'full_label' => $date->format('d/m'),
                'count' => $count,
            ];
        });

        $sessionLevelSummary = collect(['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])
            ->map(function (string $level) use ($sessionsQuery) {
                return [
                    'level' => $level,
                    'count' => (clone $sessionsQuery)->where('level', $level)->count(),
                ];
            })
            ->filter(function (array $row) {
                return $row['count'] > 0;
            })
            ->values();

        $centerPerformance = $this->buildCenterPerformance($request, $selectedCenterId, $mode);
        $advanced = $this->buildAdvancedDashboardMetrics($request, $selectedCenterId);
        $selectedCenter = $selectedCenterId
            ? $this->dashboardCenterOptions()->firstWhere('id', $selectedCenterId)
            : null;

        $payload = [
            'advanced' => $advanced,
            'activityTrend' => $activityTrend,
            'centerOptions' => $this->dashboardCenterOptions(),
            'centerPerformance' => $centerPerformance,
            'health' => $health,
            'levelOptions' => ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'],
            'mode' => $mode,
            'recentRegistrations' => $recentRegistrations,
            'recentSessions' => $recentSessions,
            'roleSummary' => $roleSummary,
            'selectedCenter' => $selectedCenter,
            'sessionLevelSummary' => $sessionLevelSummary,
            'sessionStatusSummary' => $sessionStatusSummary,
            'stats' => $stats,
            'user' => $this->authUser(),
        ];

        Cache::put($cacheKey, $payload, now()->addSeconds(45));

        return view('dashboard-optimized', $payload);
    }

    protected function dashboardMode(): string
    {
        if ($this->isSuperAdmin()) {
            return 'super_admin';
        }

        if ($this->isCenterAdmin()) {
            return 'center_admin';
        }

        if ($this->isSecretary()) {
            return 'secretary';
        }

        return 'user';
    }

    protected function dashboardCenterOptions()
    {
        return $this->isSuperAdmin()
            ? Center::where('is_active', true)->orderBy('name')->get()
            : $this->accessibleCenters();
    }

    protected function applyDashboardCenterScope($query, Request $request, string $column): void
    {
        $selectedCenterId = $this->selectedCenterFilter($request);

        if ($this->isCenterScoped()) {
            $centerIds = $selectedCenterId ? [$selectedCenterId] : $this->accessibleCenterIds();

            if (! empty($centerIds)) {
                $query->whereIn($column, $centerIds);
            }

            return;
        }

        if ($selectedCenterId) {
            $query->where($column, $selectedCenterId);
        }
    }

    protected function applyDashboardSessionScope($query, Request $request): void
    {
        $selectedCenterId = $this->selectedCenterFilter($request);

        if (! ExamSession::supportsMultiCenters()) {
            $this->applyDashboardCenterScope($query, $request, 'exam_sessions.center_id');

            return;
        }

        if ($this->isCenterScoped()) {
            $centerIds = $selectedCenterId ? [$selectedCenterId] : $this->accessibleCenterIds();

            $this->applyExamSessionCenterScope($query, $centerIds);

            return;
        }

        if ($selectedCenterId) {
            $this->applyExamSessionCenterScope($query, [$selectedCenterId]);
        }
    }

    protected function applyDashboardRegistrationFilters(Builder $query, Request $request): void
    {
        $query->where('registrations.status', Registration::STATUS_ACTIVE);

        if ($request->filled('level')) {
            $level = (string) $request->input('level');

            $query->whereHas('examSession', function (Builder $builder) use ($level) {
                $builder->where('level', $level);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('registrations.registered_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('registrations.registered_at', '<=', $request->input('date_to'));
        }
    }

    protected function applyDashboardStudentFilters(Builder $query, Request $request): void
    {
        if (! $request->filled('level') && ! $request->filled('date_from') && ! $request->filled('date_to')) {
            return;
        }

        $query->whereHas('registrations', function (Builder $builder) use ($request) {
            $this->applyDashboardCenterScope($builder, $request, 'registrations.center_id');
            $this->applyDashboardRegistrationFilters($builder, $request);
        });
    }

    protected function applyDashboardDepositFilters(Builder $query, Request $request): void
    {
        if ($request->filled('level')) {
            $level = (string) $request->input('level');

            $query->whereHas('examSession', function (Builder $builder) use ($level) {
                $builder->where('level', $level);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('bank_deposits.deposit_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('bank_deposits.deposit_date', '<=', $request->input('date_to'));
        }
    }

    protected function applyDashboardSessionFilters(Builder $query, Request $request): void
    {
        if ($request->filled('level')) {
            $query->where('exam_sessions.level', (string) $request->input('level'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('exam_sessions.exam_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('exam_sessions.exam_date', '<=', $request->input('date_to'));
        }
    }

    protected function buildCenterPerformance(Request $request, ?int $selectedCenterId, string $mode)
    {
        $query = Center::query()->where('is_active', true);

        if ($selectedCenterId) {
            $query->whereKey($selectedCenterId);
        } elseif ($this->isCenterScoped()) {
            $centerIds = $this->accessibleCenterIds();

            if (! empty($centerIds)) {
                $query->whereIn('id', $centerIds);
            }
        }

        return $query
            ->withCount([
                'registrations as active_registrations_count' => function (Builder $builder) use ($request) {
                    $this->applyDashboardRegistrationFilters($builder, $request);
                },
                'registrations as paid_registrations_count' => function (Builder $builder) use ($request) {
                    $this->applyDashboardRegistrationFilters($builder, $request);
                    $builder->where('payment_status', Registration::PAYMENT_PAID);
                },
                'bankDeposits as pending_deposits_count' => function (Builder $builder) use ($request) {
                    $this->applyDashboardDepositFilters($builder, $request);
                    $builder->where('status', BankDeposit::STATUS_PENDING);
                },
                'bankDeposits as validated_deposits_count' => function (Builder $builder) use ($request) {
                    $this->applyDashboardDepositFilters($builder, $request);
                    $builder->where('status', BankDeposit::STATUS_VALIDATED);
                },
            ])
            ->orderByDesc('active_registrations_count')
            ->orderBy('name')
            ->take($mode === 'super_admin' ? 6 : 4)
            ->get()
            ->map(function (Center $center) {
                $paymentRate = $center->active_registrations_count > 0
                    ? (int) round(($center->paid_registrations_count / $center->active_registrations_count) * 100)
                    : 0;

                return [
                    'id' => $center->id,
                    'name' => $center->name,
                    'city' => $center->city,
                    'active_registrations_count' => $center->active_registrations_count,
                    'paid_registrations_count' => $center->paid_registrations_count,
                    'pending_deposits_count' => $center->pending_deposits_count,
                    'validated_deposits_count' => $center->validated_deposits_count,
                    'payment_rate' => $paymentRate,
                ];
            });
    }

    protected function buildAdvancedDashboardMetrics(Request $request, ?int $selectedCenterId): array
    {
        $growthBaseQuery = Registration::query();
        $this->applyDashboardCenterScope($growthBaseQuery, $request, 'registrations.center_id');
        $growthBaseQuery->where('registrations.status', Registration::STATUS_ACTIVE);

        if ($request->filled('level')) {
            $level = (string) $request->input('level');

            $growthBaseQuery->whereHas('examSession', function (Builder $builder) use ($level) {
                $builder->where('level', $level);
            });
        }

        $currentPeriodCount = (clone $growthBaseQuery)
            ->whereDate('registrations.registered_at', '>=', now()->subDays(30)->toDateString())
            ->count();

        $previousPeriodCount = (clone $growthBaseQuery)
            ->whereBetween('registrations.registered_at', [
                now()->subDays(60)->startOfDay(),
                now()->subDays(30)->endOfDay(),
            ])
            ->count();

        $growth = null;
        if ($currentPeriodCount > 0 || $previousPeriodCount > 0) {
            $growth = $previousPeriodCount > 0
                ? (int) round((($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100)
                : 100;
        }

        $byCenter = Registration::query()
            ->selectRaw('centers.name as name, COUNT(*) as total')
            ->join('centers', 'centers.id', '=', 'registrations.center_id');
        $this->applyDashboardCenterScope($byCenter, $request, 'registrations.center_id');
        $this->applyDashboardRegistrationFilters($byCenter, $request);
        $byCenter = $byCenter
            ->groupBy('centers.id', 'centers.name')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        $bySession = Registration::query()
            ->selectRaw('exam_sessions.title as title, COUNT(*) as total')
            ->join('exam_sessions', 'exam_sessions.id', '=', 'registrations.exam_session_id');
        $this->applyDashboardCenterScope($bySession, $request, 'registrations.center_id');
        $this->applyDashboardRegistrationFilters($bySession, $request);
        $bySession = $bySession
            ->groupBy('exam_sessions.id', 'exam_sessions.title')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        $monthlyRevenue = Registration::query()
            ->selectRaw("DATE_FORMAT(COALESCE(registered_at, created_at), '%Y-%m') as period, SUM(amount) as total");
        $this->applyDashboardCenterScope($monthlyRevenue, $request, 'registrations.center_id');
        $this->applyDashboardRegistrationFilters($monthlyRevenue, $request);
        $monthlyRevenue = $monthlyRevenue
            ->groupBy('period')
            ->orderByDesc('period')
            ->take(6)
            ->get()
            ->reverse()
            ->values();

        return [
            'blocked_users' => User::where('is_blocked', true)->count(),
            'by_center' => $byCenter,
            'by_session' => $bySession,
            'growth' => $growth,
            'monthly_revenue' => $monthlyRevenue,
        ];
    }
}
