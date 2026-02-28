<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\TeacherAttendance;

class DashboardProfesseurController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $groups = Group::query()
            ->where('teacher_id', $user->id)
            ->with(['level'])
            ->orderBy('name')
            ->get();

        $todayAttendances = TeacherAttendance::query()
            ->where('teacher_id', $user->id)
            ->whereDate('date', today())
            ->get()
            ->keyBy('group_id');

        return view('dashboards.professeur', compact('groups', 'todayAttendances'));
    }
}

