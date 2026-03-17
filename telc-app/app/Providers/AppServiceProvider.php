<?php

namespace App\Providers;

use App\Models\BankDeposit;
use App\Models\User;
use App\Models\ExamSession;
use App\Observers\BankDepositObserver;
use App\Observers\ExamSessionObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        @ini_set('default_charset', 'UTF-8');

        BankDeposit::observe(BankDepositObserver::class);
        ExamSession::observe(ExamSessionObserver::class);

        Paginator::defaultView('vendor.pagination.telc');
        Paginator::defaultSimpleView('vendor.pagination.telc-simple');

        View::composer(['layouts.app', 'dashboard'], function ($view) {
            $user = Auth::user();
            $pendingDepositsCount = 0;
            $accessibleCenters = collect();
            $selectedCenter = null;

            if ($user) {
                $centerFilterId = (int) request()->input('center_id', 0);

                $accessibleCenters = Cache::remember(
                    'layout:accessibleCenters:' . $user->id,
                    now()->addSeconds(60),
                    fn () => $user->accessibleCenters()
                );

                $query = BankDeposit::query()->where('status', BankDeposit::STATUS_PENDING);

                if ($user->role === User::ROLE_CENTER_ADMIN || $user->role === User::ROLE_SECRETARY) {
                    $centerIds = $user->accessibleCenterIds();

                    if (! empty($centerIds)) {
                        $query->whereIn('center_id', $centerIds);

                        if ($centerFilterId && in_array($centerFilterId, $centerIds, true)) {
                            $query->where('center_id', $centerFilterId);
                            $selectedCenter = $accessibleCenters->firstWhere('id', $centerFilterId);
                        } else {
                            $selectedCenter = $accessibleCenters->firstWhere('id', $user->center_id) ?: $accessibleCenters->first();
                        }
                    }
                } else {
                    if ($centerFilterId) {
                        $selectedCenter = $accessibleCenters->firstWhere('id', $centerFilterId);
                    }
                }

                $pendingDepositsCount = Cache::remember(
                    'layout:pendingDeposits:' . $user->id . ':' . $centerFilterId,
                    now()->addSeconds(30),
                    fn () => $query->count()
                );
            }

            $view->with('pendingDepositsCount', $pendingDepositsCount)
                ->with('layoutAccessibleCenters', $accessibleCenters)
                ->with('layoutSelectedCenter', $selectedCenter);
        });
    }
}






