<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Level;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * Liste des groupes avec filtrage par rôle/centre
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Group::with(['teacher', 'level', 'students']);

        // Sécurité Multi-centres
        if ($user->role !== 'super_admin' && $user->role !== 'directeur') {
            $query->where('centre_id', $user->centre_id);
        }

        // Filtres de recherche
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        $groups = $query->latest()->get();
        return view('groups.index', compact('groups'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $user = auth()->user();
        $levels = Level::orderBy('id', 'asc')->get();

        // Profs du centre uniquement
        $teachers = User::whereIn('role', ['teacher', 'professeur'])
            ->where('centre_id', $user->centre_id)
            ->get();

        /** 
         * LOGIQUE FINANCIÈRE : 
         * On ne récupère que les étudiants du centre qui :
         * 1. N'ont pas encore de groupe (group_id NULL)
         * 2. ONT PAYÉ leur inscription (10 000 FCFA)
         */
        $students = Student::where('centre_id', $user->centre_id)
            ->whereNull('group_id')
            ->whereHas('payments', function($q) {
                $q->where('type', 'inscription');
            })
            ->get();

        return view('groups.create', compact('levels', 'teachers', 'students'));
    }

    /**
     * Enregistrement d'un nouveau groupe
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'language' => 'required|string',
            'level_id' => 'required|exists:levels,id',
            'teacher_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'capacity' => 'nullable|integer',
            'days' => 'nullable|array',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'student_ids' => 'nullable|array'
        ]);

        DB::transaction(function () use ($request) {
            $group = Group::create([
                'name'       => $request->name,
                'language'   => $request->language,
                'type'       => $request->type,
                'centre_id'  => auth()->user()->centre_id,
                'level_id'   => $request->level_id,
                'teacher_id' => $request->teacher_id,
                'capacity'   => $request->capacity ?? 20,
                'days'       => $request->days, // Casté en array dans le modèle
                'start_time' => $request->start_time,
                'end_time'   => $request->end_time,
                'status'     => 'active',
            ]);

            if ($request->has('student_ids')) {
                Student::whereIn('id', $request->student_ids)
                    ->update(['group_id' => $group->id]);
            }
        });

        return redirect()->route('groups.index')->with('success', 'Groupe créé avec succès.');
    }

    /**
     * Détails du groupe
     */
    public function show(Group $group)
    {
        // On charge les relations nécessaires
        $group->load(['level', 'teacher', 'students']);

        return view('groups.show', compact('group'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Group $group)
    {
        $user = auth()->user();
        $levels = Level::all();
        
        $teachers = User::whereIn('role', ['teacher', 'professeur'])
            ->where('centre_id', $group->centre_id)
            ->get();

        /**
         * LOGIQUE FINANCIÈRE ÉDITION :
         * On prend les étudiants du centre qui :
         * - Sont déjà dans ce groupe (pour pouvoir les gérer)
         * - OU (n'ont pas de groupe ET ont payé l'inscription)
         */
        $students = Student::where('centre_id', $group->centre_id)
            ->where(function ($query) use ($group) {
                $query->where('group_id', $group->id)
                    ->orWhere(function($sub) {
                        $sub->whereNull('group_id')
                            ->whereHas('payments', function($q) {
                                $q->where('type', 'inscription');
                            });
                    });
            })->get();

        return view('groups.edit', compact('group', 'teachers', 'levels', 'students'));
    }

    /**
     * Mise à jour du groupe
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'language' => 'required|string',
            'type' => 'required|string',
            'level_id' => 'required|exists:levels,id',
            'teacher_id' => 'required|exists:users,id',
            'status' => 'required|in:active,finished',
            'capacity' => 'nullable|integer',
            'days' => 'nullable|array',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'student_ids' => 'nullable|array'
        ]);

        DB::transaction(function () use ($request, $group) {
            // 1. Mise à jour des infos du groupe
            $group->update([
                'name'       => $request->name,
                'language'   => $request->language,
                'type'       => $request->type,
                'level_id'   => $request->level_id,
                'teacher_id' => $request->teacher_id,
                'capacity'   => $request->capacity ?? 20,
                'status'     => $request->status,
                'days'       => $request->days,
                'start_time' => $request->start_time,
                'end_time'   => $request->end_time,
            ]);

            // 2. Synchronisation des étudiants
            // On retire d'abord tout le monde de ce groupe
            Student::where('group_id', $group->id)->update(['group_id' => null]);

            // On ajoute les étudiants sélectionnés (cochés)
            if ($request->has('student_ids')) {
                Student::whereIn('id', $request->student_ids)
                    ->update(['group_id' => $group->id]);
            }
        });

        return redirect()->route('groups.show', $group)->with('success', 'Groupe mis à jour avec succès.');
    }

    /**
     * Suppression
     */
    public function destroy(Group $group)
    {
        Student::where('group_id', $group->id)->update(['group_id' => null]);
        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Groupe supprimé.');
    }

    /**
     * Exportation CSV de la liste de classe
     */
    public function exportGroup(Group $group)
    {
        $group->load(['level', 'teacher', 'students']);
        $fileName = 'liste_' . Str::slug($group->name) . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($group) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, ['FEUILLE D\'APPEL / CLASSE'], ';');
            fputcsv($file, ['Groupe', $group->name], ';');
            fputcsv($file, ['Langue', $group->language], ';');
            fputcsv($file, ['Professeur', $group->teacher->name ?? 'N/A'], ';');
            fputcsv($file, ['Horaire', $group->start_time . ' - ' . $group->end_time], ';');
            fputcsv($file, [], ';');

            fputcsv($file, ['ID', 'Prénom', 'Nom', 'Téléphone', 'Statut'], ';');

            foreach ($group->students as $student) {
                fputcsv($file, [
                    $student->id,
                    $student->first_name,
                    $student->last_name,
                    $student->phone,
                    $student->isFinanciallyCleared() ? 'En règle' : 'Insolvable'
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}