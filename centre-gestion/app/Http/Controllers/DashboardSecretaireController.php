<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\TeacherAttendance;
use App\Models\Group;
use App\Models\SupportRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardSecretaireController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $centreId = $user->centre_id;

        // 1. STATS FINANCIÈRES (Mois en cours)
        $startOfMonth = now()->startOfMonth();
        $totalRevenue = (float) Payment::where('centre_id', $centreId)->where('payment_date', '>=', $startOfMonth)->sum('amount');
        $totalExpenses = (float) Expense::where('centre_id', $centreId)->where('created_at', '>=', $startOfMonth)->sum('amount');
        $benefit = $totalRevenue - $totalExpenses;

        // 2. EFFECTIFS
        $totalStudents = Student::where('centre_id', $centreId)->count();
        $activeStudents = Student::where('centre_id', $centreId)->where('status', 'actif')->count();

        // 3. ALERTES OPÉRATIONNELLES
        $allStudents = Student::where('centre_id', $centreId)->with(['tuitionFee', 'payments'])->get();
        
        // Règle 5 : Qui a fini son temps ?
        $expiredCycles = $allStudents->filter(fn($s) => $s->isCycleExpired())->count();
        
        // Qui doit de l'argent ?
        $insolventCount = $allStudents->filter(fn($s) => !$s->isFinanciallyCleared())->count();

        // Présences profs à valider (on cherche ceux où is_validated est false)
        $pendingTeacherValidations = TeacherAttendance::whereHas('group', function($q) use ($centreId) {
            $q->where('centre_id', $centreId);
        })->where('is_validated', false)->count();

        // Support
        $pendingSupport = SupportRequest::where('centre_id', $centreId)->where('status', 'pending')->count();

        // Stock faible
        $lowStock = Product::all()->filter(function ($product) {
            return $product->min_stock > 0 && $product->currentStock() <= $product->min_stock;
        })->count();

        // 4. ACTIVITÉ RÉCENTE
        $recentPayments = Payment::where('centre_id', $centreId)->with('student')->latest()->limit(5)->get();

        return view('dashboards.secretaire', compact(
            'totalRevenue', 'totalExpenses', 'benefit', 'totalStudents', 'activeStudents',
            'expiredCycles', 'insolventCount', 'pendingTeacherValidations', 'pendingSupport', 'recentPayments', 'lowStock'
        ));
    }
}