<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\TuitionFee;
use App\Models\Level;
use App\Models\Group;
use App\Models\Centre;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class StudentController extends Controller
{
    /**
     * Liste des étudiants (filtrée par centre, recherche, niveau, groupe et dates)
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Student::with(['level', 'group', 'centre']);

        // 1. Sécurité et Périmètre par Rôle
        if ($user->role === 'professeur') {
            $query->whereHas('group', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });
        }
        elseif ($user->role === 'super_admin' || $user->role === 'directeur') {
            if ($request->filled('centre_id')) {
                $query->where('centre_id', $request->centre_id);
            }
        }
        else {
            $query->where('centre_id', $user->centre_id);
        }

        // 2. Filtre de recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 3. Filtres par Niveau et Groupe
        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        // 4. Filtre par Période (Date d'inscription)
        if ($request->filled('start_date')) {
            $query->whereDate('registration_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('registration_date', '<=', $request->end_date);
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        $levels = Level::all();
        $centers = Centre::all();
        $groups = Group::when($user->role !== 'super_admin' && $user->role !== 'directeur', function ($q) use ($user) {
            return $q->where('centre_id', $user->centre_id);
        })->get();

        return view('students.index', compact('students', 'levels', 'groups', 'centers'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $user = auth()->user();
        $levels = Level::all();
        $groups = Group::where('centre_id', $user->centre_id)->get();

        $tuitionFees = TuitionFee::where('is_active', true)
            ->where(function($q) use ($user) {
                $q->whereNull('centre_id')->orWhere('centre_id', $user->centre_id);
            })->get();

        return view('students.create', compact('tuitionFees', 'levels', 'groups'));
    }

    /**
     * Enregistrement d'un nouvel étudiant
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string',
            'email' => 'required|email|unique:students,email',
            'level_id' => 'required|exists:levels,id',
            'group_id' => 'nullable|exists:groups,id',
            'tuition_fee_id' => 'required|exists:tuition_fees,id', // Programme rendu obligatoire
        ]);

        $student = Student::create([
            'centre_id' => auth()->user()->centre_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'level_id' => $request->level_id,
            'group_id' => $request->group_id,
            'tuition_fee_id' => $request->tuition_fee_id,
            'registration_date' => now(), // Initialisation du chrono temporel
            'status' => 'actif',
        ]);

        return redirect()->route('students.index')->with('success', 'Étudiant créé et cycle de formation initialisé.');
    }

    /**
     * Fiche détaillée de l'étudiant
     */
    public function show(Student $student)
    {
        $this->authorizeView($student);

        $student->load(['level', 'group', 'tuitionFee', 'centre', 'payments' => function ($q) {
            $q->orderByDesc('payment_date');
        }]);

        return view('students.show', compact('student'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Student $student)
    {
        $this->authorizeView($student);

        $levels = Level::all();
        $groups = Group::where('centre_id', $student->centre_id)->get();

        return view('students.edit', compact('student', 'levels', 'groups'));
    }

    /**
     * Mise à jour des informations
     */
    public function update(Request $request, Student $student)
    {
        $this->authorizeView($student);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'level_id' => 'required|exists:levels,id',
            'group_id' => 'nullable|exists:groups,id',
            'phone' => 'required|string',
            'email' => 'required|email|unique:students,email,' . $student->id,
        ]);

        $student->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'level_id' => $request->level_id,
            'group_id' => $request->group_id,
        ]);

        return redirect()->route('students.index')->with('success', 'Dossier étudiant mis à jour.');
    }

    /**
     * Suppression
     */
    public function destroy(Student $student)
    {
        $this->authorizeView($student);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Étudiant supprimé.');
    }

    /**
     * Liste des étudiants en retard de paiement
     */
    public function insolvent()
    {
        $user = auth()->user();

        $students = Student::where('centre_id', $user->centre_id)
            ->with(['level', 'tuitionFee'])
            ->get()
            ->filter(function ($student) {
                if (!$student->tuitionFee) return false;
                return !$student->isFinanciallyCleared();
            });

        return view('students.insolvent', compact('students'));
    }

    /**
     * Formulaire de transfert / promotion
     */
    public function showTransferForm(Student $student)
    {
        $this->authorizeView($student);

        $groups = Group::where('centre_id', $student->centre_id)->get();
        $levels = Level::all();
        $tuitionFees = TuitionFee::where('is_active', true)
            ->where(function ($q) use ($student) {
                $q->whereNull('centre_id')->orWhere('centre_id', $student->centre_id);
            })->get();

        return view('students.transfer', compact('student', 'groups', 'levels', 'tuitionFees'));
    }

    /**
     * ACTION DE TRANSFERT INTELLIGENTE (Règles 1, 2, 3, 4)
     */
    public function transfer(Request $request, Student $student)
    {
        $this->authorizeView($student);

        $request->validate([
            'new_level_id' => 'required|exists:levels,id',
            'new_group_id' => 'required|exists:groups,id',
            'new_tuition_fee_id' => 'required|exists:tuition_fees,id',
            'reason' => 'required|string|min:5',
        ]);

        // --- RÈGLE 1 : Vérifier si l'inscription actuelle est payée ---
        if (!$student->hasPaidRegistration()) {
            return back()->with('error', "Transfert refusé : L'élève doit d'abord régler ses frais d'inscription du cycle actuel.");
        }

        // --- DÉTERMINATION DU TYPE DE MOUVEMENT ---
        $isPromotion = ($request->new_level_id != $student->level_id);

        if ($isPromotion) {
            // --- RÈGLE 2 : Vérifier si le solde du niveau actuel est à zéro ---
            if ($student->getTuitionBalance() > 0) {
                $reste = number_format($student->getTuitionBalance(), 0, ',', ' ');
                return back()->with('error', "Promotion bloquée : L'élève doit solder sa pension actuelle ({$reste} FCFA) avant de passer au niveau supérieur.");
            }

            // --- RÈGLE 4 : Promotion (Nouveau Niveau) -> Réinitialisation complète ---
            $student->update([
                'level_id' => $request->new_level_id,
                'group_id' => $request->new_group_id,
                'tuition_fee_id' => $request->new_tuition_fee_id,
                'registration_date' => now(), // RÈGLE 5 : Le chrono repart à zéro pour le nouveau cycle
                'last_transfer_reason' => $request->reason,
            ]);
            $msg = "Promotion effectuée ! Un nouveau cycle académique et financier a été ouvert pour l'élève.";
        } else {
            // --- RÈGLE 3 : Transfert Simple (Même Niveau) ---
            $student->update([
                'group_id' => $request->new_group_id,
                'last_transfer_reason' => $request->reason,
                // On ne touche pas au tarif ni à la date, le cycle financier continue
            ]);
            $msg = "L'élève a été transféré de groupe avec succès.";
        }

        return redirect()->route('students.show', $student)->with('success', $msg);
    }

    /**
     * Exportation CSV filtrée
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $query = Student::with(['level', 'group', 'centre']);

        if ($user->role === 'professeur') {
            $query->whereHas('group', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });
        } elseif ($user->role === 'super_admin' || $user->role === 'directeur') {
            if ($request->filled('centre_id')) {
                $query->where('centre_id', $request->centre_id);
            }
        } else {
            $query->where('centre_id', $user->centre_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%");
            });
        }
        if ($request->filled('level_id')) $query->where('level_id', $request->level_id);
        if ($request->filled('group_id')) $query->where('group_id', $request->group_id);

        $students = $query->latest()->get();

        $fileName = 'export_etudiants_' . now()->format('d_m_Y') . '.csv';
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($students) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['ID', 'Prénom', 'Nom', 'Email', 'Téléphone', 'Centre', 'Niveau', 'Groupe'], ';');

            foreach ($students as $student) {
                fputcsv($file, [
                    $student->id,
                    $student->first_name,
                    $student->last_name,
                    $student->email,
                    $student->phone,
                    $student->centre->name ?? 'N/A',
                    $student->level->name ?? 'N/A',
                    $student->group->name ?? 'Sans groupe',
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function authorizeView(Student $student)
    {
        $user = auth()->user();

        if ($user->role === 'super_admin' || $user->role === 'directeur') return;

        if ($user->role === 'professeur') {
            if ($student->group && $student->group->teacher_id == $user->id) return;
            abort(403, "Accès refusé.");
        }

        if ($student->centre_id != $user->centre_id) {
            abort(403, "Cet étudiant n'appartient pas à votre centre.");
        }
    }
}