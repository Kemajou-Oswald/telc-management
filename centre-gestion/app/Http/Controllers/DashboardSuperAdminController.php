<?php

namespace App\Http\Controllers;

use App\Models\Centre;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Product;
use App\Models\SupportRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardSuperAdminController extends Controller
{
    public function index(Request $request)
    {
        // 1. PÉRIODES
        $period = $request->get('period', 'month');
        $startDate = now()->startOfMonth();
        if($period == 'quarter') $startDate = now()->startOfQuarter();
        if($period == 'year') $startDate = now()->startOfYear();
        if($period == 'all') $startDate = Carbon::parse('2020-01-01');

        // 2. REQUÊTES FILTRÉES
        $stQuery = Student::query();
        $payQuery = Payment::query()->where('payment_date', '>=', $startDate);
        $expQuery = Expense::query()->where('created_at', '>=', $startDate);

        if ($request->filled('centre')) {
            $stQuery->where('centre_id', $request->centre);
            $payQuery->where('centre_id', $request->centre);
            $expQuery->where('centre_id', $request->centre);
        }

        if ($request->filled('level')) {
            $stQuery->where('level_id', $request->level);
            $payQuery->whereHas('tuitionFee', fn($q) => $q->where('level_id', $request->level));
        }

        // 3. STATS DE HAUT NIVEAU
        $totalStudents = $stQuery->count();
        $activeStudents = (clone $stQuery)->where('status', 'actif')->count();
        $totalRevenue = (float) $payQuery->sum('amount');
        $totalExpenses = (float) $expQuery->sum('amount');
        $benefit = $totalRevenue - $totalExpenses;
        
        // Ratio de solvabilité
        $totalDebt = $stQuery->get()->sum(fn($s) => $s->getTuitionBalance());
        $solvabilityRate = ($totalRevenue + $totalDebt) > 0 ? round(($totalRevenue / ($totalRevenue + $totalDebt)) * 100) : 0;

        // 4. RÉPARTITION PAR LANGUE (Statistique Clé)
        $languageStats = Payment::where('payment_date', '>=', $startDate)
            ->join('tuition_fees', 'payments.tuition_fee_id', '=', 'tuition_fees.id')
            ->select('tuition_fees.language', DB::raw('SUM(amount) as total'))
            ->groupBy('tuition_fees.language')
            ->get();

        // 5. ALERTES CRITIQUES
        $insolventCount = $stQuery->get()->filter(fn($s) => !$s->isFinanciallyCleared())->count();
        $expiredCycles = $stQuery->get()->filter(fn($s) => $s->isCycleExpired())->count();
        $lowStock = Product::whereRaw('min_stock > 0 AND (SELECT SUM(quantity) FROM stock_movements WHERE product_id = products.id) <= min_stock')->count();
        $pendingSupport = SupportRequest::where('status', 'pending')->count();

        // 6. DONNÉES DES CENTRES (Pour la carte et les barres)
        $centres = Centre::withCount(['students' => function($q) use ($request) {
            if ($request->filled('level')) $q->where('level_id', $request->level);
        }])->get();

        // 7. DERNIÈRES TRANSACTIONS
        $recentPayments = Payment::with(['student', 'centre'])->latest()->limit(5)->get();

        return view('dashboards.super_admin', compact(
            'totalStudents', 'activeStudents', 'totalRevenue', 'totalExpenses', 'benefit',
            'solvabilityRate', 'languageStats', 'insolventCount', 'expiredCycles',
            'lowStock', 'pendingSupport', 'centres', 'recentPayments'
        ));
    }
}