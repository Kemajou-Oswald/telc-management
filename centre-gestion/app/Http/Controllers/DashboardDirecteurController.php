<?php

namespace App\Http\Controllers;

use App\Models\Centre;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Student;
use App\Models\SupportRequest;
use App\Models\Group;
use App\Models\Level;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardDirecteurController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $centreId = $user->centre_id;

        $period = $request->get('period', 'month');
        $startDate = now()->startOfMonth();
        if ($period == 'quarter') $startDate = now()->startOfQuarter();
        if ($period == 'year') $startDate = now()->startOfYear();
        if ($period == 'all') $startDate = Carbon::parse('2020-01-01');

        $stQuery = Student::where('centre_id', $centreId);
        $payQuery = Payment::where('centre_id', $centreId)->where('payment_date', '>=', $startDate);
        $expQuery = Expense::where('centre_id', $centreId)->where('created_at', '>=', $startDate);

        if ($request->filled('level')) {
            $stQuery->where('level_id', $request->level);
            $payQuery->whereHas('tuitionFee', fn($q) => $q->where('level_id', $request->level));
        }

        $totalStudents = $stQuery->count();
        $activeStudents = (clone $stQuery)->where('status', 'actif')->count();
        $totalRevenue = (float) $payQuery->sum('amount');
        $totalExpenses = (float) $expQuery->sum('amount');
        $benefit = $totalRevenue - $totalExpenses;

        $allStudents = $stQuery->with(['tuitionFee', 'payments'])->get();
        $totalDebt = $allStudents->sum(fn($s) => $s->getTuitionBalance());
        $solvabilityRate = ($totalRevenue + $totalDebt) > 0 ? round(($totalRevenue / ($totalRevenue + $totalDebt)) * 100) : 0;

        // FIX AMBIGUITY CENTRE_ID
        $languageStats = Payment::where('payments.centre_id', $centreId)
            ->where('payment_date', '>=', $startDate)
            ->join('tuition_fees', 'payments.tuition_fee_id', '=', 'tuition_fees.id')
            ->select('tuition_fees.language', DB::raw('SUM(amount) as total'), DB::raw('count(DISTINCT student_id) as count'))
            ->groupBy('tuition_fees.language')
            ->get();

        $insolventCount = $allStudents->filter(fn($s) => !$s->isFinanciallyCleared())->count();
        $expiredCycles = $allStudents->filter(fn($s) => $s->isCycleExpired())->count();
        $pendingSupport = SupportRequest::where('centre_id', $centreId)->where('status', 'pending')->count();
        
        $lowStock = Product::all()->filter(function ($p) {
            return $p->min_stock > 0 && $p->currentStock() <= $p->min_stock;
        })->count();

        $totalGroups = Group::where('centre_id', $centreId)->count();
        $recentPayments = Payment::where('centre_id', $centreId)->with(['student'])->latest()->limit(5)->get();
        $levels = Level::all();

        return view('dashboards.directeur', compact(
            'totalStudents', 'activeStudents', 'totalRevenue', 'totalExpenses', 'benefit',
            'solvabilityRate', 'languageStats', 'insolventCount', 'expiredCycles',
            'pendingSupport', 'recentPayments', 'totalGroups', 'levels', 'lowStock'
        ));
    }
}