<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\TeacherAttendance;
use App\Models\User;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    public function stats()
    {
        $user = auth()->user();
        Carbon::setLocale('fr');

        // 1. Déterminer le jour actuel pour le planning (ex: "Ven")
        $today = ucfirst(substr(Carbon::now()->translatedFormat('D'), 0, 3));
        if (str_ends_with($today, '.')) $today = substr($today, 0, -1);

        // 2. Récupérer les groupes du prof connecté (pour son planning)
        $myGroups = Group::where('teacher_id', $user->id)->with('level')->get();

        // 3. Logique des statistiques du tableau
        $stats = [];
        if (in_array($user->role, ['super_admin', 'directeur', 'secretaire'])) {
            // L'admin voit tous les profs
            $teachers = User::whereIn('role', ['teacher', 'professeur'])->get();
            foreach ($teachers as $t) {
                $stats[] = [
                    'teacher' => $t,
                    'totalDays' => TeacherAttendance::where('teacher_id', $t->id)->count(),
                    'validatedDays' => TeacherAttendance::where('teacher_id', $t->id)->where('validated', true)->count(),
                    'rate' => TeacherAttendance::teacherRate($t->id)
                ];
            }
        } else {
            // Le prof ne voit que ses propres statistiques
            $stats[] = [
                'teacher' => $user,
                'totalDays' => TeacherAttendance::where('teacher_id', $user->id)->count(),
                'validatedDays' => TeacherAttendance::where('teacher_id', $user->id)->where('validated', true)->count(),
                'rate' => TeacherAttendance::teacherRate($user->id)
            ];
        }

        return view('teacher.stats', compact('myGroups', 'today', 'stats'));
    }

    public function checkin(Group $group)
    {
        $user = auth()->user();
        $todayDate = Carbon::now()->toDateString(); // Format YYYY-MM-DD

        // Vérifier si déjà pointé aujourd'hui pour ce groupe
        $exists = TeacherAttendance::where('teacher_id', $user->id)
            ->where('group_id', $group->id)
            ->where('date', $todayDate)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Vous avez déjà signalé votre présence pour ce cours aujourd\'hui.');
        }

        // Création selon ton modèle
        TeacherAttendance::create([
            'teacher_id'   => $user->id,
            'group_id'     => $group->id,
            'date'         => $todayDate,
            'arrival_time' => Carbon::now()->format('H:i:s'),
            'validated'    => false,
        ]);

        return back()->with('success', 'Présence signalée avec succès !');
    }
    public function validationList()
    {
        $user = auth()->user();

        // On récupère les pointages non validés du centre de la secrétaire
        $pending = TeacherAttendance::where('validated', false)
            ->whereHas('group', function ($q) use ($user) {
                $q->where('centre_id', $user->centre_id);
            })
            ->with(['teacher', 'group'])
            ->latest()
            ->get();

        return view('teacher.validation', compact('pending'));
    }

    // Action de validation
    public function validateAttendance(TeacherAttendance $attendance)
    {
        $attendance->update([
            'validated' => true,
            'validated_by' => auth()->id() // On enregistre qui a validé
        ]);

        return back()->with('success', 'La présence du professeur a été validée avec succès.');
    }
}
