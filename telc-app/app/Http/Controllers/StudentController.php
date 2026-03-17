<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['center', 'registrations.examSession'])
            ->withCount(['registrations as active_registrations_count' => function ($builder) {
                $builder->where('status', 'active');
            }])
            ->latest();

        $this->applyCenterScope($query, 'students.center_id', $request);

        if ($request->filled('q')) {
            $search = trim((string) $request->input('q'));

            $query->where(function ($builder) use ($search) {
                $builder->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orWhere('country', 'like', '%' . $search . '%')
                    ->orWhereHas('center', function ($centerQuery) use ($search) {
                        $centerQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('session_id')) {
            $sessionId = (int) $request->input('session_id');
            $query->whereHas('registrations', function ($builder) use ($sessionId) {
                $builder->where('exam_session_id', $sessionId);
            });
        }

        if ($request->filled('level')) {
            $level = (string) $request->input('level');
            $query->whereHas('registrations.examSession', function ($builder) use ($level) {
                $builder->where('level', $level);
            });
        }

        if ($request->filled('payment_status')) {
            $status = (string) $request->input('payment_status');
            $query->whereHas('registrations', function ($builder) use ($status) {
                $builder->where('payment_status', $status);
            });
        }

        return view('students.index', [
            'centerOptions' => $this->isSuperAdmin() ? Center::where('is_active', true)->orderBy('name')->get() : $this->accessibleCenters(),
            'sessionOptions' => $this->availableSessionsQuery(false, $request)->get(),
            'students' => $query->paginate(15)->withQueryString(),
            'user' => $this->authUser(),
        ]);
    }
}
