<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\ExamSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function authUser()
    {
        return Auth::user();
    }

    protected function isSuperAdmin()
    {
        return $this->authUser() && $this->authUser()->role === User::ROLE_SUPER_ADMIN;
    }

    protected function isCenterAdmin()
    {
        return $this->authUser() && $this->authUser()->role === User::ROLE_CENTER_ADMIN;
    }

    protected function isSecretary()
    {
        return $this->authUser() && $this->authUser()->role === User::ROLE_SECRETARY;
    }

    protected function isCenterScoped()
    {
        return $this->isCenterAdmin() || $this->isSecretary();
    }

    protected function accessibleCenters()
    {
        $user = $this->authUser();

        return $user ? $user->accessibleCenters() : collect();
    }

    protected function accessibleCenterIds()
    {
        $user = $this->authUser();

        return $user ? $user->accessibleCenterIds() : [];
    }

    protected function selectedCenterFilter(Request $request)
    {
        $centerId = (int) $request->input('center_id', 0);

        if (! $centerId) {
            return null;
        }

        if ($this->isCenterScoped()) {
            return in_array($centerId, $this->accessibleCenterIds(), true) ? $centerId : null;
        }

        return $centerId;
    }

    protected function currentCenterId(Request $request = null)
    {
        $request = $request ?: request();
        $user = $this->authUser();

        if (! $user) {
            return null;
        }

        $selectedCenterId = $this->selectedCenterFilter($request);
        if ($selectedCenterId) {
            return $selectedCenterId;
        }

        if ($this->isSuperAdmin()) {
            return null;
        }

        $accessibleCenterIds = $this->accessibleCenterIds();

        if ($user->center_id && in_array((int) $user->center_id, $accessibleCenterIds, true)) {
            return (int) $user->center_id;
        }

        return $accessibleCenterIds[0] ?? null;
    }

    protected function currentCenter(Request $request = null)
    {
        $centerId = $this->currentCenterId($request);

        if (! $centerId) {
            return null;
        }

        return $this->accessibleCenters()->firstWhere('id', $centerId) ?: Center::find($centerId);
    }

    protected function resolveOperationalCenterId(Request $request, $field = 'center_id')
    {
        $requestedCenterId = (int) $request->input($field, 0);
        $accessibleCenterIds = $this->accessibleCenterIds();

        if ($requestedCenterId && in_array($requestedCenterId, $accessibleCenterIds, true)) {
            return $requestedCenterId;
        }

        if (count($accessibleCenterIds) === 1) {
            return $accessibleCenterIds[0];
        }

        if ($this->authUser() && $this->authUser()->center_id && in_array((int) $this->authUser()->center_id, $accessibleCenterIds, true)) {
            return (int) $this->authUser()->center_id;
        }

        return null;
    }

    protected function ensureCenterContext()
    {
        if ($this->isCenterScoped() && empty($this->accessibleCenterIds())) {
            abort(403, 'Utilisateur sans centre rattache.');
        }
    }

    protected function applyCenterScope($query, $column = 'center_id', Request $request = null)
    {
        $this->ensureCenterContext();
        $request = $request ?: request();
        $selectedCenterId = $this->selectedCenterFilter($request);

        if ($this->isCenterScoped()) {
            if ($selectedCenterId) {
                $query->where($column, $selectedCenterId);
            } else {
                $query->whereIn($column, $this->accessibleCenterIds());
            }
        } elseif ($selectedCenterId) {
            $query->where($column, $selectedCenterId);
        }

        return $query;
    }

    protected function availableSessionsQuery($onlyOpen = false, Request $request = null)
    {
        $relations = ['center'];

        if (ExamSession::supportsMultiCenters()) {
            $relations[] = 'centers';
        }

        $query = ExamSession::query()
            ->with($relations)
            ->orderBy('exam_date')
            ->orderBy('opens_at');

        if ($onlyOpen) {
            $query->where('status', ExamSession::STATUS_OPEN);
        }

        $request = $request ?: request();

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

    protected function applyExamSessionCenterScope(Builder $query, array $centerIds): Builder
    {
        $centerIds = array_values(array_unique(array_filter($centerIds)));

        if (empty($centerIds)) {
            return $query;
        }

        if (! ExamSession::supportsMultiCenters()) {
            return $query->whereIn('exam_sessions.center_id', $centerIds);
        }

        return $query->where(function (Builder $builder) use ($centerIds) {
            $builder->whereHas('centers', function (Builder $relationQuery) use ($centerIds) {
                $relationQuery->whereIn('centers.id', $centerIds);
            })->orWhereIn('exam_sessions.center_id', $centerIds);
        });
    }


    protected function onlySuperAdmin()
    {
        abort_unless($this->isSuperAdmin(), 403);
    }

    protected function onlyCenterOperations()
    {
        abort_unless($this->isCenterAdmin() || $this->isSecretary(), 403);
    }
}
