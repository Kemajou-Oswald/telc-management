<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    /**
     * Afficher la liste des groupes du professeur
     */
    public function index()
    {
        $groups = Group::where('teacher_id', auth()->id())->get();

        return view('student_attendances.index', compact('groups'));
    }

    /**
     * Formulaire de prise de présence
     */
    public function create(Group $group)
    {
        // 🔐 Vérifier que le groupe appartient au prof connecté
        if ($group->teacher_id !== auth()->id()) {
            abort(403);
        }

        // 🔒 Vérifier validation secrétaire
        $teacherAttendance = TeacherAttendance::where('teacher_id', auth()->id())
            ->where('group_id', $group->id)
            ->whereDate('date', today())
            ->where('validated', true)
            ->first();

        if (!$teacherAttendance) {
            abort(403, 'Votre présence n’est pas encore validée par la secrétaire.');
        }

        // Filtrer les étudiants solvables (au moins tranche 1 réglée)
        $students = $group->students->filter(function ($student) {
            // Si aucun tarif associé, on considère qu'il n'est pas solvable
            $fee = $student->getActiveTuitionFee();
            if (!$fee) {
                return false;
            }

            // Tranche 1 = 50% du montant total (peut être payée en plusieurs versements)
            $requiredFirstTranche = $fee->total_amount / 2;
            return $student->totalPaid() >= $requiredFirstTranche;
        });

        return view('student_attendances.create', compact('group', 'students'));
    }

    /**
     * Enregistrer les présences
     */
    public function store(Request $request, Group $group)
    {
        if ($group->teacher_id !== auth()->id()) {
            abort(403);
        }

        // 🔒 Double sécurité validation
        $teacherAttendance = TeacherAttendance::where('teacher_id', auth()->id())
            ->where('group_id', $group->id)
            ->whereDate('date', today())
            ->where('validated', true)
            ->first();

        if (!$teacherAttendance) {
            abort(403);
        }

        foreach ($group->students as $student) {

            $present = isset($request->students[$student->id]);

            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'date' => today(),
                ],
                [
                    'group_id' => $group->id,
                    'teacher_id' => auth()->id(),
                    'present' => $present,
                ]
            );
        }

        return redirect()
            ->route('student_attendances.index')
            ->with('success', 'Présence enregistrée avec succès.');
    }

    /**
     * Historique des présences d’un groupe
     */
    public function history(Group $group)
    {
        if ($group->teacher_id !== auth()->id()) {
            abort(403);
        }

        $attendances = StudentAttendance::where('group_id', $group->id)
            ->with('student')
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('date');

        return view('student_attendances.history', compact('group', 'attendances'));
    }

    /**
     * Statistiques d’assiduité du groupe
     */
    public function stats(Group $group)
    {
        if ($group->teacher_id !== auth()->id()) {
            abort(403);
        }

        $students = $group->students;

        $stats = [];

        foreach ($students as $student) {

            $total = $student->attendances()
                ->where('group_id', $group->id)
                ->count();

            $present = $student->attendances()
                ->where('group_id', $group->id)
                ->where('present', true)
                ->count();

            $rate = $total > 0 ? round(($present / $total) * 100, 2) : 0;

            $stats[] = [
                'student' => $student,
                'rate' => $rate,
            ];
        }

        return view('student_attendances.stats', compact('group', 'stats'));
    }
}